<?PHP
/*
    Registration/Login script from HTML Form Guide
    V1.0

    This program is free software published under the
    terms of the GNU Lesser General Public License.
    http://www.gnu.org/copyleft/lesser.html
    

This program is distributed in the hope that it will
be useful - WITHOUT ANY WARRANTY; without even the
implied warranty of MERCHANTABILITY or FITNESS FOR A
PARTICULAR PURPOSE.

For updates, please visit:
http://www.html-form-guide.com/php-form/php-registration-form.html
http://www.html-form-guide.com/php-form/php-login-form.html

*/
require_once("class.phpmailer.php");
require_once("formvalidator.php");

class FGMembersite
{
    var $admin_email;
    var $from_address;
    
    var $connectName;
    var $pwd;
    var $database;
    var $tablename;
    var $connection;
    var $rand_key;
    
    var $error_message;
		
    //-----Initialization -------
    function FGMembersite()
    {
        $this->sitename = 'leyff.com';
        $this->rand_key = '0iQx5oBk66oVZep';
    }
    
    function InitDB($host,$cname,$pwd,$database,$tablename)
    {
        $this->db_host  = $host;
        $this->connectName = $cname;
        $this->pwd  = $pwd;
        $this->database  = $database;
        $this->tablename = $tablename;
    }
	
    function SetAdminEmail($email)
    {
        $this->admin_email = $email;
    }
    
    function SetWebsiteName($sitename)
    {
        $this->sitename = $sitename;
    }
    
    function SetRandomKey($key)
    {
        $this->rand_key = $key;
    }
    
    //-------Main Operations ----------------------
    function RegisterUser()
    {
        if(!isset($_POST['submitted']))
        {
           return false;
        }
        
        $formvars = array();
        
        if(!$this->ValidateRegistrationSubmission())
        {
            return false;
        }
        
        $this->CollectRegistrationSubmission($formvars);
        
        if(!$this->SaveToDatabase($formvars))
        {
            return false;
        }
        
        if(!$this->SendUserConfirmationEmail($formvars))
        {
            return false;
        }

        $this->SendAdminIntimationEmail($formvars);
        
        return true;
    }
	
	
    function ConfirmUser()
    {
        if(empty($_GET['code'])||strlen($_GET['code'])<=10)
        {
            $this->HandleError("Please provide the confirm code");
            return false;
        }
        $user_rec = array();
        if(!$this->UpdateDBRecForConfirmation($user_rec))
        {
            return false;
        }
        
        $this->SendUserWelcomeEmail($user_rec);
        
        $this->SendAdminIntimationOnRegComplete($user_rec);
        
        return true;
    }    
    
    function Login()
    {
        if(empty($_POST['connectName']))
        {
            $this->HandleError("Connect Name is empty!");
            return false;
        }
        
        if(empty($_POST['password']))
        {
            $this->HandleError("Password is empty!");
            return false;
        }
        
        $connectName = trim($_POST['connectName']);
        $password = trim($_POST['password']);
        
        if(!isset($_SESSION)){ session_start(); }
        if(!$this->CheckLoginInDB($connectName,$password))
        {
            return false;
        }
        
        $_SESSION[$this->GetLoginSessionVar()] = $connectName;
        
        return true;
    }
    
    function CheckLogin()
    {
         if(!isset($_SESSION)){ session_start(); }

         $sessionvar = $this->GetLoginSessionVar();
         
         if(empty($_SESSION[$sessionvar]))
         {
            return false;
         }
         return true;
    }
    
    function UserFullName()
    {
        return isset($_SESSION['name_of_user'])?$_SESSION['name_of_user']:'';
    }
    
    function UserEmail()
    {
        return isset($_SESSION['email_of_user'])?$_SESSION['email_of_user']:'';
    }
	
	function ConnectName()
    {
        $user_rec = array();
        if(!$this->GetUserFromEmail($this->UserEmail(),$user_rec))
        {
            return false;
        }  
        
        $result = mysql_query("Select connectName from person where id_user=".$user_rec['id_user']." ",$this->connection);  

        if(!$result || mysql_num_rows($result) <= 0)
        {
            return false;
        }
        $row = mysql_fetch_assoc($result);
        
        return $row['connectName'];
    }
	
	function GetUserFromConnectName($thisName) {
		$user_rec = array();
        if(!$this->GetUserFromEmail($this->UserEmail(),$user_rec))
        {
        }
        $result = mysql_query("Select id_user from person where connectName='".$thisName."' ",$this->connection);  

        if(!$result || mysql_num_rows($result) <= 0)
        {
            return false;
        }
        $row = mysql_fetch_assoc($result);
        
        return $row['id_user'];
	}
    
	function GetNameFromConnectName($thisName) {
		$user_rec = array();
        if(!$this->GetUserFromEmail($this->UserEmail(),$user_rec))
        {
        }
        $result = mysql_query("Select name from person where connectName='".$thisName."' ",$this->connection);  

        if(!$result || mysql_num_rows($result) <= 0)
        {
            return false;
        }
        $row = mysql_fetch_assoc($result);
        
        return $row['name'];
	}
	
	function IsFollowing($user, $follower) {
		$user_rec = array();
        if(!$this->GetUserFromEmail($this->UserEmail(),$user_rec))
        {
        }
		
        $result = mysql_query("Select * from follower where user_id ='".$user."' and follower_user_id ='" .$follower. "' ",$this->connection);  

        if(!$result || mysql_num_rows($result) <= 0)
        {
            return 0;
        }
        $row = mysql_fetch_assoc($result);
        
        return 1;
	}
	
	function GetNotifications($connectName) {
		
		$user_rec = array();
        if(!$this->GetUserFromEmail($this->UserEmail(),$user_rec))
        {			
        }
		
		$user = mysql_query("Select id_user from person where connectName = '".$connectName."' ", $this->connection);		
		
		if (!$user ||  mysql_num_rows($user) <= 0) {
			return false;
		}
		
		$userRow = mysql_fetch_assoc($user);
		
		$myQuery = mysql_query("SELECT * FROM notification where user_id = '". $userRow['id_user'] ."'ORDER BY time DESC");
		
		$notificationArr = array();
		
		while ($row = mysql_fetch_assoc($myQuery))
		{
			array_push($notificationArr, $row);
		}
		
		
		return $notificationArr;
		
	}
	
	function NotifyUser($user, $type, $id = NULL, $text = NULL, $sender=NULL) {	
		
		$text = addslashes($text);
        $user_rec = array();
        if(!$this->GetUserFromEmail($this->UserEmail(),$user_rec))
        {			
        }   
		
		//Make sure we didn't receive an override
		if ($sender == NULL) {
			$sender = $user_rec['id_user'];
		}
		
		//We don't need to notify ourself
		if($sender == $user) {
			return false;
		}
		
		//What kind of notification is this
		$fullType = $type;
		$typeParts = explode("||", $type);
		$type = $typeParts[0];
		$typeSpecific = $typeParts[1];
			
		switch ($type) {
			case "post":{
				
					//Get Post Info
					$result = mysql_query("Select post_text from post where post_id = '$id'",$this->connection);   
					if(!$result || mysql_num_rows($result) <= 0)
					{
						$this->HandleError("No post found.");
						return false;
					}
					
					$row = mysql_fetch_assoc($result);
					$postTitle = $row['post_text'];
					$link = "post.php?pid=".$id;
				
					//Get the specific type and create the message
					switch ($typeSpecific) {
						case "comment": {
								$notification_text = "<strong>".$this->GetNameFromConnectName($this->GetConnectName($sender)) . "</strong> has commented on your <strong>" .$postTitle. "</strong> post: <br/> <i>\"".$text."\"</i>";								
							break;
						}
						case "favorite": {
								$notification_text = "<strong>".$this->GetNameFromConnectName($this->GetConnectName($sender)) . "</strong> has favorited your post:  <br/><i>" .$postTitle."</i>";									
							break;
						}
						case "upvote": {
								$notification_text = "<strong>".$this->GetNameFromConnectName($this->GetConnectName($sender)) . "</strong> has upvoted your post:  <br/><i>" .$postTitle."</i>";									
							break;
						}
						case "downvote": {
								$notification_text = "<strong>".$this->GetNameFromConnectName($this->GetConnectName($sender)) . "</strong> has downvoted your post:  <br/><i>" .$postTitle."</i>";									
							break;
						}
					}										
				break; 	
			}
				
			case "follow": {
					switch ($typeSpecific) {
						 case "followed":  {				
								$notification_text = "<strong>".$this->GetConnectName($sender) . "</strong> has followed you. ";
								$link = "profile.php?user='".$this->GetConnectName($sender)."'";
							break;
						 }
						 case "unfollowed": {
								$notification_text =  "<strong>".$this->GetConnectName($sender) . "</strong> has unfollowed you. ";
								$link = "profile.php?user='".$this->GetConnectName($sender)."'";
							 break;
						}
					 }					
				break;
			}					
			
			case "comment": {
					switch ($typeSpecific) {
						case "like": {				
								$notification_text = "<strong>".$this->GetConnectName($sender) . "</strong> has liked your comment: <br/> <i>\"" .$text."\"</i>";
								$link = 'viewmessage.php?id='.$id;
							break;
						}
					}					
				break; 
			}
			
		}
					
		$sql = "Insert into notification
					(user_id, 
					 type,
					 notification_text, 
					 link, 
					 sender)
								 
				Values (
					".$user.",
					'".$fullType."',
					'".$notification_text."',
					'".$link."',
					'".$sender."'
					)";
														
				if(!mysql_query( $sql ,$this->connection))
				{					
					return false;
				}
			
			$this->AddAlert('notification', $user);
				
			return true;
		
	}
	
function humanTiming ($time){
	
	$time = (time() - 25200) - $time; // to get the time since that moment (adjusted for mysql time)
	$time = ($time<1)? 1 : $time;
	$tokens = array (
		31536000 => 'year',
		2592000 => 'month',
		604800 => 'week',
		86400 => 'day',
		3600 => 'hour',
		60 => 'minute',
		1 => 'second'
	);

	foreach ($tokens as $unit => $text) {
		if ($time < $unit) continue;
		$numberOfUnits = floor($time / $unit);
		return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s ago':' ago');
	}
}

function humanNumbers($num) {
  $x = round($num);
  $x_number_format = number_format($x);
  $x_array = explode(',', $x_number_format);
  $x_parts = array('k', 'm', 'b', 't');
  $x_count_parts = count($x_array) - 1;
  $x_display = $x;
  $x_display = $x_array[0] . ((int) $x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');
  $x_display .= $x_parts[$x_count_parts - 1];
  return $x_display;
}
	
    function LogOut()
    {
        session_start();
        
        $sessionvar = $this->GetLoginSessionVar();
        
        $_SESSION[$sessionvar]=NULL;	
        
        unset($_SESSION[$sessionvar]);
		session_destroy();
    }
    
    function EmailResetPasswordLink()
    {
        if(empty($_POST['email']))
        {
            $this->HandleError("Email is empty!");
            return false;
        }
        $user_rec = array();
        if(false === $this->GetUserFromEmail($_POST['email'], $user_rec))
        {
            return false;
        }
        if(false === $this->SendResetPasswordLink($user_rec))
        {
            return false;
        }
        return true;
    }
    
    function ResetPassword()
    {
        if(empty($_GET['email']))
        {
            $this->HandleError("There was no e-mail provided. Please verify your link is correct.");
            return false;
        }
        if(empty($_GET['code']))
        {
            $this->HandleError("Please verify the link is correct.");
            return false;
        }
        $email = trim($_GET['email']);
        $code = trim($_GET['code']);
        
        if($this->GetResetPasswordCode($email) != $code)
        {
            $this->HandleError("Bad reset code. Please make sure your link is correct.");
            return false;
        }
        
        $user_rec = array();
        if(!$this->GetUserFromEmail($email,$user_rec))
        {
            return false;
        }
        
        $new_password = $this->ResetUserPasswordInDB($user_rec);
        if(false === $new_password || empty($new_password))
        {
            $this->HandleError("Error updating new password");
            return false;
        }
        
        if(false == $this->SendNewPassword($user_rec,$new_password))
        {
            $this->HandleError("Error sending new password");
            return false;
        }
        return true;
    }
    
    function ChangePassword()
    {
        if(!$this->CheckLogin())
        {
            $this->HandleError("Not logged in!");
            return false;
        }
        
        if(empty($_POST['oldpwd']))
        {
            $this->HandleError("Old password is empty!");
            return false;
        }
        if(empty($_POST['newpwd']))
        {
            $this->HandleError("New password is empty!");
            return false;
        }
        
        $user_rec = array();
        if(!$this->GetUserFromEmail($this->UserEmail(),$user_rec))
        {
            return false;
        }
        
        $pwd = trim($_POST['oldpwd']);

    	$salt = $user_rec['salt'];
        $hash = $this->checkhashSSHA($salt, $pwd);
        
        if($user_rec['password'] != $hash)
        {
            $this->HandleError("The old password is not correct.");
            return false;
        }
        $newpwd = trim($_POST['newpwd']);
        
        if(!$this->ChangePasswordInDB($user_rec, $newpwd))
        {
            return false;
        }
        return true;
    }
	
	//Find by E-mail of logged in user
	function GetProfilePicture() {
		
		$user_rec = array();
        if(!$this->GetUserFromEmail($this->UserEmail(),$user_rec))
        {
        }  
        
        $result = mysql_query("Select photo_location from photo where user_id=".$user_rec['id_user']." and photo_type='1';",$this->connection);  

        if(!$result || mysql_num_rows($result) <= 0)
        {
            return 'images/myPicture.jpg';
        }
        $row = mysql_fetch_assoc($result);

        
        return $row['photo_location'];
	}
	
	function GetBackgroundImage($user) {
		
		$user_rec = array();
        if(!$this->GetUserFromEmail($this->UserEmail(),$user_rec))
        {
        }  
        
        $result = mysql_query("Select photo_location from photo where user_id=".$user." and photo_type='2';",$this->connection);  

        if(!$result || mysql_num_rows($result) <= 0)
        {
            return 'images/BackgroundDefault.jpg';
        }
        $row = mysql_fetch_assoc($result);

        
        return $row['photo_location'];
	}
	
	
	function imageResizing ( $uploadDir, $file, $maxWidth = 2000, $maxHeight = 2000){


	$ext = substr($file,-3,3);

		$src_img = imagecreatefromjpeg($uploadDir.$file);

		$maxSize = $maxWidth;
		
		// This will resize either width or height depending on which is wrong
		// If the image is smaller it won't resize at all.
		
		  $src_size = getimagesize($uploadDir.$file);
		   $width = $src_size[0];
		   $height = $src_size[1];
		
		   if($width > $maxSize || $height > $maxHeight) {
		
			  if($width > $height) {
				$z = $width;
				$i = 0;
				while($z > $maxSize) {
				  --$z; ++$i;
				}
				$dest_width = $z;
				$dest_height = $height - ($height * ($i / $width));
			  }
			  
			  else {
		
				$z = $height;
				$i = 0;
				while($z > $maxHeight) {
				  --$z; ++$i;
				}
				$dest_width = $width - ($width * ($i / $height));
				$dest_height = $z;
			  }
		
		  }
		  
		  else {
		
			 $dest_width = $width;
			 $dest_height = $height;
		  }
	
		$dest_img = imagecreatetruecolor($dest_width, $dest_height);
		imagecopyresampled($dest_img, $src_img, 0, 0, 0, 0, $dest_width, $dest_height,$src_size[0],$src_size[1]);
		$medImg = imagejpeg($dest_img, $uploadDir.$file,100);	
		
		imagedestroy($src_img);
	}
	
	function UpdateQuote($text) {
		$user_rec = array();
        if(!$this->GetUserFromEmail($this->UserEmail(),$user_rec))
        {
        } 
		
		$text = addslashes($text);
		
		$sql = "Update person set user_quote = '".$text."' where id_user = ".$user_rec['id_user']."";
													
		if(!mysql_query( $sql ,$this->connection))
		{
			return 'There was a problem updating the quote. Please retry.';
		}
					
         return true;		
	}
	
	function UpdateConnectName($text){
		$user_rec = array();
        if(!$this->GetUserFromEmail($this->UserEmail(),$user_rec))
        {
        } 
		
		$text = addslashes($text);
		
		//Check if this is in use
		$sql = mysql_query("Select connectName from person where connectName = '".$text."'", $this->connection);		
		
		if (!$sql ||  mysql_num_rows($sql) <= 0) {		
			$sql = "Update person set connectName = '".$text."' where id_user = ".$user_rec['id_user']."";
												
			if(!mysql_query( $sql ,$this->connection))
			{
				return false;
			}			
			return true;
		}
		
		else {	
			return false;
		}	
	}  	
		
	function UpdateSubject($name) {
		$user_rec = array();
        if(!$this->GetUserFromEmail($this->UserEmail(),$user_rec))
        {
        } 
		$name = addslashes($name);
		
		//Check if this subject exists
		$sql = mysql_query("Select subject_id from subject where subject_name = '".$name."'", $this->connection);		
		
		//IF not, create one
		if (!$sql ||  mysql_num_rows($sql) <= 0) 
		{		
			$sql = "Insert into subject (subject_name) Values ('".$name."')";
												
			if(!mysql_query( $sql ,$this->connection))
			{
				return false;
			}

			//Make sure this was inserted and get the id
			$sql = mysql_query("Select subject_id from subject where subject_name = '".$name."'", $this->connection);	
			if (!$sql ||  mysql_num_rows($sql) <= 0) 
					{	
						return false;
					}
			$subjectRow = mysql_fetch_assoc($sql);	
			$sid = $subjectRow['subject_id'];
		}
		
		$sql = "Update person set subject = '".$sid."' where id_user = ".$user_rec['id_user']."";
													
		if(!mysql_query( $sql ,$this->connection))
		{
			return 'There was a problem updating the quote. Please retry.';
		}
					
         return true;		
	}
	
	function UpdateBCAddress($name) {

		$user_rec = array();
        if(!$this->GetUserFromEmail($this->UserEmail(),$user_rec))
        {
			return false;
        } 
		$name = addslashes($name);
		
		//Check if this subject exists
		$sql = mysql_query("Select bitcoin_address from person where bitcoin_address = '".$name."'", $this->connection);		
		
		//IF not, create one
		if (!$sql ||  mysql_num_rows($sql) <= 0) 
		{		
			$sql = "Update person set bitcoin_address = '".$name."' where id_user = ".$user_rec['id_user']."";
										
			if(!mysql_query( $sql ,$this->connection))
			{
				return false;
			}
		}
         return "Select bitcoin_address from person where bitcoin_address = '".$name."'";		
	}
	
	function InsertFavoriteSubject($userEmail, $id) {
		$user_rec = array();
        if(!$this->GetUserFromEmail($this->UserEmail(),$user_rec))
        {
        } 
		
		$sql = "Update person set subject = '".$id."' where email = '".$userEmail."'";
													
		if(!mysql_query( $sql ,$this->connection))
		{
			return 'There was a problem updating the quote. Please retry.' .$sql;
		}
					
         return true;		
	}
	
	function GetConnectName($userid) {
		 if(!$this->GetUserFromEmail($this->UserEmail(),$user_rec))
        {
        }  
		
		$user = mysql_query("Select connectName from person where id_user = '".$userid."' ", $this->connection);		
		
		if (!$user ||  mysql_num_rows($user) <= 0) {		
			return false;
		}
			
		$userRow = mysql_fetch_assoc($user);
	
        return $userRow['connectName'];
	}
	
	function CurrentUser() {
		 if(!$this->GetUserFromEmail($this->UserEmail(),$user_rec))
        {
            return false;
        }  
		
		return $user_rec['id_user'];
		
	}
	
	//Find by UserID
	function GetProfilePictureByConnectName($thisUser) {
		 if(!$this->GetUserFromEmail($this->UserEmail(),$user_rec))
        {
        }  		
		
		$user = mysql_query("Select id_user from person where connectName = '".$thisUser."' ", $this->connection);		
		
		if (!$user ||  mysql_num_rows($user) <= 0) {
			return false;
		}
		
		$userRow = mysql_fetch_assoc($user);
		
        $result = mysql_query("Select photo_location from photo where user_id=".$userRow['id_user']." and photo_type='1';",$this->connection);  

        if(!$result || mysql_num_rows($result) <= 0)
        {
			return 'images/myPicture.jpg';
        }
        $row = mysql_fetch_assoc($result);

        
        return $row['photo_location'];
	}
	
	function GetPoints($connectName) {
        if(!$this->GetUserFromEmail($this->UserEmail(),$user_rec))
        {
        }  
		
		$user = mysql_query("Select id_user from person where connectName = '".$connectName."' ", $this->connection);		
		
		if (!$user ||  mysql_num_rows($user) <= 0) {
			return false;
		}
		
		$userRow = mysql_fetch_assoc($user);
        
        $result = mysql_query("Select points from person where id_user=".$userRow['id_user']." ",$this->connection);  

        if(!$result || mysql_num_rows($result) <= 0)
        {
            return 0;
        }
        $row = mysql_fetch_assoc($result);

        
        return $row['points'];
	}
	
	function GetFollowerCount($connectName) {
		 if(!$this->GetUserFromEmail($this->UserEmail(),$user_rec))
        {
        }  
		
		
		$user = mysql_query("Select id_user from person where connectName = '".$connectName."' ", $this->connection);		
		
		if (!$user ||  mysql_num_rows($user) <= 0) {
			return false;
		}
		
		$userRow = mysql_fetch_assoc($user);
      
     $result = mysql_query("Select count(follower_id) as total_followers from follower where user_id=".$userRow['id_user']." ",$this->connection); 

        if(!$result || mysql_num_rows($result) <= 0)
        {
            return 0;
        }
        $row = mysql_fetch_assoc($result);

        
        return $row['total_followers'];
	}
	
	//Check if they are a friend or friend of a friend
	function ValidToMessage($toUser, $fromUser) {

			//First check if they are friends
			$result = mysql_query("SELECT follower_id as total_friends FROM follower WHERE user_id IN (SELECT follower_user_id FROM follower WHERE user_id =  ".$fromUser." )  AND follower_user_id = ".$fromUser." AND user_id = ".$toUser." ",$this->connection);   
							
			if(!$result || mysql_num_rows($result) <= 0)
			{
				//Then check if they are a friend of a friend
				$result = mysql_query("SELECT DISTINCT pf.user_id
											FROM follower pf
											JOIN follower f ON pf.follower_user_id = f.user_id
											JOIN follower me ON me.user_id = f.follower_user_id
												AND me.user_id =".$fromUser."
													WHERE me.user_id <> pf.follower_user_id
														  AND me.follower_user_id <> pf.user_id
														  AND pf.user_id = ".$toUser."",$this->connection);  

				if(!$result || mysql_num_rows($result) <= 0)
				{
					return false;
				}
				//They are a friend of a friend
				else 
				{
					return true;
				}
			}
			
			//They are friends
			else 
			{
				return true;
			}       
	}
	
	function GetFollowingCount($connectName) {
		 if(!$this->GetUserFromEmail($this->UserEmail(),$user_rec))
        {
        }  
		
		
		$user = mysql_query("Select id_user from person where connectName = '".$connectName."' ", $this->connection);		
		
		if (!$user ||  mysql_num_rows($user) <= 0) {
			return false;
		}
		
		$userRow = mysql_fetch_assoc($user);
        
        $result = mysql_query("Select count(follower_id) as total_following from follower where follower_user_id=".$userRow['id_user']." ",$this->connection);  

        if(!$result || mysql_num_rows($result) <= 0)
        {
            return 0;
        }
        $row = mysql_fetch_assoc($result);

        
        return $row['total_following'];
	}
	
	function GetFriendsCount($connectName) {
		 if(!$this->GetUserFromEmail($this->UserEmail(),$user_rec))
        {
        }  
				
		$user = mysql_query("Select id_user from person where connectName = '".$connectName."' ", $this->connection);		
		
		if (!$user ||  mysql_num_rows($user) <= 0) {
			return 0;
		}
		
		$userRow = mysql_fetch_assoc($user);
        
        $result = mysql_query("SELECT COUNT( follower_id ) as total_friends FROM follower WHERE user_id IN (SELECT follower_user_id FROM follower WHERE user_id =  ".$userRow['id_user']." )  AND follower_user_id = ".$userRow['id_user']." ",$this->connection);   

        if(!$result || mysql_num_rows($result) <= 0)
        {
            return 0;
        }
        $row = mysql_fetch_assoc($result);

        
        return $row['total_friends'];
	}
    
	function GetPostCount($connectName) {
		 if(!$this->GetUserFromEmail($this->UserEmail(),$user_rec))
        {
        }  
		
		
		$user = mysql_query("Select id_user from person where connectName = '".$connectName."' ", $this->connection);		
		
		if (!$user ||  mysql_num_rows($user) <= 0) {
			return 0;
		}
		
		$userRow = mysql_fetch_assoc($user);
      
     $result = mysql_query("Select count(post_id) as total_posts from post where author_id=".$userRow['id_user']." ",$this->connection); 

        if(!$result || mysql_num_rows($result) <= 0)
        {
            return 0;
        }
        $row = mysql_fetch_assoc($result);

        
        return $row['total_posts'];
	}
	
	function GetAllPosts() {
		$user_rec = array();
        $this->GetUserFromEmail($this->UserEmail(),$user_rec);
		
		$myQuery = mysql_query("SELECT * FROM post 
								INNER JOIN (person) 
									ON (person.id_user = post.author_id) 
								GROUP BY post_id
								ORDER BY post.time DESC
								LIMIT 50");
		
		$postArr = array();
		
		while ($row = mysql_fetch_assoc($myQuery))
		{			
			array_push($postArr, $row);
		}
		
		return $postArr;
	}
	
	function GetPostsByCollection($cid) {
		$user_rec = array();
        $this->GetUserFromEmail($this->UserEmail(),$user_rec);
		
		$myQuery = mysql_query("SELECT post.post_id, post_text, time, views, post_image, post_points, post_link, connectName, name, collection
								FROM post 
								INNER JOIN (person) 
									ON (person.id_user = post.author_id)
								Right Join favorite
									ON post.post_id = favorite.post_id	
								WHERE favorite.collection = ".$cid."
								GROUP BY post.post_id
								ORDER BY post.time DESC
								LIMIT 50");
				
		$postArr = array();
		
		while ($row = mysql_fetch_assoc($myQuery))
		{			
			array_push($postArr, $row);
		}
		
		return $postArr;
	}
	
	function GetAllTopPosts() {
				$user_rec = array();
        $this->GetUserFromEmail($this->UserEmail(),$user_rec);
		$myQuery = mysql_query("SELECT * FROM post 
								INNER JOIN (person) 
									ON (person.id_user = post.author_id) 
								GROUP BY post_id
								ORDER BY post.post_points DESC
								LIMIT 50");
		
		$postArr = array();
		
		while ($row = mysql_fetch_assoc($myQuery))
		{			
			array_push($postArr, $row);
		}
		
		return $postArr;
	}
	
	function AddAlert($alert, $user) {
		$user_rec = array();
        if(!$this->GetUserFromEmail($this->UserEmail(),$user_rec))
        {
        } 
		
		if (!file_exists('alerts/'.$user)) {
            mkdir
			('alerts/'.$user, 0777, false);
        }
		//Set content
		if ($alert == 'notification') {
			$fp = fopen("alerts/".$user."/notification.txt","a");
		}
		else if ($alert == 'message') {
			$fp = fopen("alerts/".$user."/message.txt","a");		
		}
				
		fwrite($fp,'1');
		fclose($fp);	
		return true;		
	}
 	
	function GetNotificationAlerts() {
		$user_rec = array();
        if(!$this->GetUserFromEmail($this->UserEmail(),$user_rec))
        {
			return false;
        } 
		
		//Open the file
		$fp = fopen("alerts/".$user_rec['id_user']."/notification.txt","r");
		if( $fp == false )
		{
			//there are no alerts			
			return false;
		}
		else
		{
			//Count the notifications
			while(!feof($fp)){
				$fr = fread($fp, 8192);
				$count += substr_count($fr,'1');
			}
		}	
		
		return $count;
	}
	
	function GetMessageAlerts() {
		$user_rec = array();
        if(!$this->GetUserFromEmail($this->UserEmail(),$user_rec))
        {
			return false;
        } 
		
		//Open the file
		$fp = fopen("alerts/".$user_rec['id_user']."/message.txt","r");
		if( $fp == false )
		{
			//there are no alerts			
			return false;
		}
		else
		{
			//Count the messages
			while(!feof($fp)){
				$fr = fread($fp, 8192);
				$count += substr_count($fr,'1');
			}
		}	
		
		return $count;
	}

	function RemoveNotificationAlerts() {
		$user_rec = array();
        if(!$this->GetUserFromEmail($this->UserEmail(),$user_rec))
        {
			return false;
        } 
		
		unlink("alerts/".$user_rec['id_user']."/notification.txt");
	}
	
	function RemoveMessageAlerts() {
		$user_rec = array();
        if(!$this->GetUserFromEmail($this->UserEmail(),$user_rec))
        {
			return false;
        } 
		
		unlink("alerts/".$user_rec['id_user']."/message.txt");
	}
	
	function GetTopPosts() {		
		$user_rec = array();
        if(!$this->GetUserFromEmail($this->UserEmail(),$user_rec))
        {
        } 
		$myQuery = mysql_query("SELECT * FROM post 
								INNER JOIN (person) 
									ON (person.id_user = post.author_id)
								WHERE post.time >= now() - INTERVAL 1 DAY								
								GROUP BY post_id
								ORDER BY post.post_points DESC
								LIMIT 50");
		
		$postArr = array();
		
		while ($row = mysql_fetch_assoc($myQuery))
		{			
			array_push($postArr, $row);
		}
		
		return $postArr;
	}
	
	
	function GetFollowedPosts() {
		
		$user_rec = array();
        if(!$this->GetUserFromEmail($this->UserEmail(),$user_rec))
        {
			return false;
        }  
        
		
		$myQuery = mysql_query("SELECT * FROM post 
								INNER JOIN(follower) 
									ON	((post.author_id = follower.follower_user_id OR 
										  post.author_id = follower.user_id) 
												AND 
										  follower.follower_user_id = '". $user_rec['id_user'] ."') OR post.author_id = '". $user_rec['id_user'] ."'
								INNER JOIN (person) 
									ON (person.id_user = post.author_id) 
								GROUP BY post_id
								ORDER BY post.time DESC
								LIMIT 50");
		
		$postArr = array();
		
		while ($row = mysql_fetch_assoc($myQuery))
		{			
			array_push($postArr, $row);
		}
		
		return $postArr;
	}
	
	function GetFollowedTopPosts() {
		
		$user_rec = array();
        if(!$this->GetUserFromEmail($this->UserEmail(),$user_rec))
        {
        }  
        
		
		$myQuery = mysql_query("SELECT * FROM post 
								INNER JOIN(follower) 
									ON	((post.author_id = follower.follower_user_id OR 
										  post.author_id = follower.user_id) 
												AND 
										  follower.follower_user_id = '". $user_rec['id_user'] ."') 
								INNER JOIN (person) 
									ON (person.id_user = post.author_id) 
								GROUP BY post_id
								ORDER BY post.post_points DESC
								LIMIT 50");
		
		$postArr = array();
		
		while ($row = mysql_fetch_assoc($myQuery))
		{			
			array_push($postArr, $row);
		}
		
		return $postArr;
	}
	
	function GetFollowedRandomPosts() {
		
		$user_rec = array();
        if(!$this->GetUserFromEmail($this->UserEmail(),$user_rec))
        {
        }  
        
		
		$myQuery = mysql_query("SELECT * FROM post 
								INNER JOIN(follower) 
									ON	((post.author_id = follower.follower_user_id OR 
										  post.author_id = follower.user_id) 
												AND 
										  follower.follower_user_id = '". $user_rec['id_user'] ."') 
								INNER JOIN (person) 
									ON (person.id_user = post.author_id) 
								GROUP BY post_id
								ORDER BY post.post_points DESC
								LIMIT 50");
		
		$postArr = array();
		
		while ($row = mysql_fetch_assoc($myQuery))
		{			
			array_push($postArr, $row);
		}
		
		return $postArr;
	}
	
	function GetFollowerLikedPosts() {
		
		$user_rec = array();
        if(!$this->GetUserFromEmail($this->UserEmail(),$user_rec))
        {
			return false;
        }  
        
		
		$myQuery = mysql_query("SELECT * FROM post 
								INNER JOIN(point)
									ON  ((post.post_id = point.post_id)
										AND
										point.upvote = 1)
								INNER JOIN(follower) 
									ON	((post.author_id = follower.follower_user_id OR 
										   post.author_id = follower.user_id) 
										AND 
										follower.follower_user_id = '". $user_rec['id_user'] ."') 
								INNER JOIN (person) 
									ON (person.id_user = post.author_id) 
								WHERE NOT point.sender_id = '". $user_rec['id_user'] ."'
								GROUP BY post.post_id
								ORDER BY post.time DESC
								LIMIT 50");
		
		$postArr = array();
		
		while ($row = mysql_fetch_assoc($myQuery))
		{		
			//Dont retrieve our posts
			if($row['author_id'] != $user_rec['id_user']) {
				array_push($postArr, $row);
			}
		}
		
		return $postArr;
	}
	
	function SearchLivePosts($qry) {
		 if(!$this->GetUserFromEmail($this->UserEmail(),$user_rec))
        {
          
        }  
		if ($qry == '') {
			return false;
		}
		$myQuery = mysql_query("SELECT * FROM post 
								INNER JOIN (person) 
									ON (person.id_user = post.author_id) 
								WHERE post.post_text LIKE '%".$qry."%'
								GROUP BY post_id
								ORDER BY post.time DESC
								LIMIT 50");
		
		$postArr = array();
		
		while ($row = mysql_fetch_assoc($myQuery))
		{			
			array_push($postArr, $row);
		}
		
		return $postArr;
	}
	
	function SearchRandomPosts($qry) {
		 if(!$this->GetUserFromEmail($this->UserEmail(),$user_rec))
        {
          
        }  
		
		if ($qry == '') {
			return false;
		}
		
		$myQuery = mysql_query("SELECT * FROM post 
								INNER JOIN (person) 
									ON (person.id_user = post.author_id) 
								WHERE post.post_text LIKE '%".$qry."%'
									  AND post.time >= now() - INTERVAL 1 DAY		
								GROUP BY post_id
								ORDER BY post.post_points DESC
								LIMIT 50");
		
		$postArr = array();
		
		while ($row = mysql_fetch_assoc($myQuery))
		{			
			array_push($postArr, $row);
		}
		
		return $postArr;
	}
	
	function SearchUsers($key) {
		 if(!$this->GetUserFromEmail($this->UserEmail(),$user_rec))
        {          
        }  
			
		if ($key == '') {
			return false;
		}
			
			$result = mysql_query("Select subject_id from subject where subject_name = '".$key."'",$this->connection);  
			
			if(!$result || mysql_num_rows($result) <= 0)
			{
					//Find all people (by name and subject) related to this search ordered by followers
						$myQuery = mysql_query("Select name,connectName, count(follower_id) as followers
													from person 
														left join follower ON person.id_user = follower.user_id
															where name LIKE '%".$key."%'
																GROUP BY person.id_user
																	ORDER BY followers DESC");				
			}
			else 
			{
				$row = mysql_fetch_assoc($result);					
				$sid = $row['subject_id'];
			
				//Find all people (by name and subject) related to this search ordered by followers
				$myQuery = mysql_query("Select name,connectName,  count(follower_id) as followers
											from person 
												left join follower ON person.id_user = follower.user_id
													where name LIKE '%".$key."%' OR subject = ".$sid."
														GROUP BY person.id_user
															ORDER BY followers DESC");
			}
			
			$personArr = array();
			
			while ($row = mysql_fetch_assoc($myQuery))
			{			
				array_push($personArr, $row);
			}
			
			//Sort by followers
			for($j=$i+1;$j<count($personArr);$j++){

				if($personArr[$i]['followers']>$personArr[$j]['followers']){

					$tmp = $personArr[$i];
					$personArr[$i] = $personArr[$j];
					$personArr[$j] = $tmp;
				}
			}
			
		return $personArr;
	}
	
	function GetPosts($connectName) {
		 if(!$this->GetUserFromEmail($this->UserEmail(),$user_rec))
        {
          
        }  
		
		
		$user = mysql_query("Select id_user from person where connectName = '".$connectName."' ", $this->connection);		
		
		if (!$user ||  mysql_num_rows($user) <= 0) {
			return false;
		}
		
		$userRow = mysql_fetch_assoc($user);
		
		$myQuery = mysql_query("SELECT * FROM post where author_id = '". $userRow['id_user'] ."'ORDER BY post.time DESC");
		
		$postArr = array();
		
		while ($row = mysql_fetch_assoc($myQuery))
		{
			array_push($postArr, $row);
		}
		
		return $postArr;
	}	
	
	function GetSubjects() {
		 if(!$this->GetUserFromEmail($this->UserEmail(),$user_rec))
        {
        } 
		$myQuery = mysql_query("SELECT subject_id, subject_name FROM subject ORDER BY subject_name ASC");
		
		$subjectArr = array();
		
		while ($row = mysql_fetch_assoc($myQuery))
		{
			array_push($subjectArr, $row);
		}
		
		return $subjectArr;
	}
	
	function GetFavorites($thisUser) {
		 if(!$this->GetUserFromEmail($this->UserEmail(),$user_rec))
        {
        }  
		
		$favorite = mysql_query("Select * from favorite inner join post on (favorite.post_id = post.post_id ) where favorite.sender_id = '".$thisUser."' ", $this->connection);		
				
		$postArr = array();
		
		while ($row = mysql_fetch_assoc($favorite))
		{
			array_push($postArr, $row);
		}
		
		return $postArr;
	}	
	
	function GetComments($post_id) {
		if(!$this->GetUserFromEmail($this->UserEmail(),$user_rec))
        {
        }  
				
		$myQuery = mysql_query("SELECT * FROM comment where post_id = '". $post_id ."'ORDER BY comment_time DESC");
		
		$commentArr = array();
		
		while ($row = mysql_fetch_assoc($myQuery))
		{
			array_push($commentArr, $row);
		}
		
		return $commentArr;
	}
	
	
	function GetTopScores() {
		
		$user_rec = array();
        if(!$this->GetUserFromEmail($this->UserEmail(),$user_rec))
        {
        }  
		
		$myQuery = mysql_query("SELECT id_user, connectName, name, points FROM person ORDER BY points DESC LIMIT 10");
		
		$scoresArr = array();
		
		while ($row = mysql_fetch_assoc($myQuery))
		{			
			array_push($scoresArr, $row);
		}
		
		return $scoresArr;
	}
	
	
	function GetAllMessages($user_id) {		
		$user_rec = array();
		 if(!$this->GetUserFromEmail($this->UserEmail(),$user_rec))
        {
        }  
		
		
		$myQuery = mysql_query("SELECT * FROM user_message_string INNER JOIN message ON (user_message_string.user_message_string_id = message.message_string_id) where userA_id = '" .$user_id."' OR userB_id = '".$user_id."' GROUP BY user_message_string_id ORDER BY sent_time DESC");
		
		$conversations = array();
		
		while ($row = mysql_fetch_assoc($myQuery))
		{
			array_push($conversations, $row);
		}
		
		return $conversations;
	}
	
	function GetLastMessage($conversation_id) {		
		$user_rec = array();
		 if(!$this->GetUserFromEmail($this->UserEmail(),$user_rec))
        {
	    }  
				
		$myQuery = mysql_query("SELECT message_text, sender_id FROM message join user_message_string on (message.message_string_id = user_message_string.user_message_string_id) where message_string_id = '" .$conversation_id."' ORDER BY message.sent_time DESC LIMIT 1");
		
		$messageArr = array();
		
		while ($row = mysql_fetch_assoc($myQuery))
		{
			array_push($messageArr, $row);
		}
		
		return $messageArr;
	}
	
	function myCollections() {
		
		$user_rec = array();
         if(!$this->GetUserFromEmail($this->UserEmail(),$user_rec))
        {			
            return false;
        }  
        
        $result = mysql_query("Select collection_id, collection_name from collection where collection_owner=".$user_rec['id_user']." ", $this->connection);  
		
		$collectionsArr = array();
		
        while ($row = mysql_fetch_assoc($result))
		{
			
			array_push($collectionsArr, $row);
		}
		
		return $collectionsArr;
	}
	
	function getCollections($user) {
		
		$user_rec = array();
         if(!$this->GetUserFromEmail($this->UserEmail(),$user_rec))
        {	
        }  
        
        $result = mysql_query("Select collection_id, collection_name from collection where collection_owner=".$user." ORDER BY collection_id DESC", $this->connection);  
		
		$collectionsArr = array();
		
        while ($row = mysql_fetch_assoc($result))
		{
			
			array_push($collectionsArr, $row);
		}
		
		return $collectionsArr;
	}
	
	function GetCollectionInfo($cid) {
		$user_rec = array();
         if(!$this->GetUserFromEmail($this->UserEmail(),$user_rec))
        {	
        }  
        
        $result = mysql_query("SELECT collection_name, collection_owner	FROM collection	 WHERE collection_id = ".$cid." ", $this->connection);  
		
        $row = mysql_fetch_assoc($result);
		
		return $row;
	}
	
	function GetCollectionBackground($cid) {
		$user_rec = array();
         if(!$this->GetUserFromEmail($this->UserEmail(),$user_rec))
        {	
        }  
        
        $result = mysql_query("SELECT collection_background	FROM collection WHERE collection_id = ".$cid." ", $this->connection);  
		
        $row = mysql_fetch_assoc($result);
		
		//Check if there is one otherwise default it
		if ($row['collection_background'] == '') {
			$bgImage = 'images/BackgroundDefault.jpg';
		}
		else {
			$bgImage = $row['collection_background'];
		}
		
		return $bgImage;
	}
	
	function CreateCollection($text){
		$user_rec = array();
		 if(!$this->GetUserFromEmail($this->UserEmail(),$user_rec))
        {
			return false;
	    }
		
		$text = addslashes($text);
		
		$sql = "Insert into collection
					(collection_name,
					 collection_owner
					 )
								 
				Values 
					('".$text."',
					  ".$user_rec['id_user'].")";
														
		if(!mysql_query( $sql ,$this->connection))
		{
			return false;
		}
				
		//Get the new collection id
		$cid = mysql_query("Select collection_id from collection where collection_owner = '".$user_rec['id_user']."' and collection_name = '".$text."' ", $this->connection);		
		
		if (!$cid ||  mysql_num_rows($cid) <= 0) {
			return false;
		}
		
		$newCID = mysql_fetch_assoc($cid);
		$cid = $newCID['collection_id'];	
		
		return $cid;		
	}
	
	function RenameCollection($text, $cid){
		$user_rec = array();
		 if(!$this->GetUserFromEmail($this->UserEmail(),$user_rec))
        {
			return false;
	    }
		
		$text = addslashes($text);
		
		//Is this our collection?
		$sql = mysql_query("Select collection_owner from collection where collection_id = ".$cid." ", $this->connection);		
	
		if (!$sql ||  mysql_num_rows($sql) <= 0) {
			return false;
		}
		
		$cOwner= mysql_fetch_assoc($sql);
		if ($cOwner['collection_owner'] != $user_rec['id_user'])  {
			
		}
		
		$sql = "Update collection set collection_name = '".$text."' where collection_id = ".$cid."";
													
		if(!mysql_query( $sql ,$this->connection))
		{
			return false;
		}
			
		//Return the new collection id
		$cid = mysql_query("Select collection_id from collection where collection_owner = '".$user_rec['id_user']."' and collection_name = '".$text."' ", $this->connection);		
		
		if (!$cid ||  mysql_num_rows($cid) <= 0) {
			return false;
		}
		
		$newCID = mysql_fetch_assoc($cid);
			
		return $newCID['collection_id'];		
	}
	
	function SetCollectionImage($cid) {
		$user_rec = array();
         if(!$this->GetUserFromEmail($this->UserEmail(),$user_rec))
        {		
        }  
        
        $result = mysql_query("SELECT post_image
								FROM post
									LEFT JOIN favorite 
											  ON post.post_id = favorite.post_id
									WHERE favorite.collection = ".$cid."
										ORDER BY RAND( ) 
											LIMIT 1", $this->connection);  
		
		
		
        $row = mysql_fetch_assoc($result);
		
		return $row['post_image'];
	}
	
	function GetCollectionImage($cid) {
		$user_rec = array();
         if(!$this->GetUserFromEmail($this->UserEmail(),$user_rec))
        {		
        } 
		
		//Get the new collection id
		$cImage = mysql_query("Select collection_image from collection where collection_id = '".$cid."' ", $this->connection);		
		
		if (!$cImage ||  mysql_num_rows($cImage) <= 0) {
			return false;
		}
		
		$img = mysql_fetch_assoc($cImage);
		return $img['collection_image'];
	}
	
	function GetAllFollowers($connectName) {	
		 if(!$this->GetUserFromEmail($this->UserEmail(),$user_rec))
        {            
			$user_rec = array();
			 if(!$this->GetUserFromEmail($this->UserEmail(),$user_rec))
			{
				return false;
			}
        }  
				
		$user = mysql_query("Select id_user from person where connectName = '".$connectName."' ", $this->connection);		
		
		if (!$user ||  mysql_num_rows($user) <= 0) {
			return false;
		}
		
		$userRow = mysql_fetch_assoc($user);
      
		$myQuery = mysql_query("Select follower_user_id from follower where user_id=".$userRow['id_user']." ",$this->connection); 
	
		
        $followerArr = array();
		
		while ($row = mysql_fetch_assoc($myQuery))
		{	
			array_push($followerArr, $row);
		}
		
		return $followerArr;
	
	}
	
	function GetAllFollowings($connectName) {	
		 if(!$this->GetUserFromEmail($this->UserEmail(),$user_rec))
        {            
        }  
				
		$user = mysql_query("Select id_user from person where connectName = '".$connectName."' ", $this->connection);		
		
		if (!$user ||  mysql_num_rows($user) <= 0) {
			return false;
		}
		
		$userRow = mysql_fetch_assoc($user);
      
		$myQuery = mysql_query("Select user_id from follower where follower_user_id=".$userRow['id_user']." ",$this->connection); 
	
		
        $followingArr = array();
		
		while ($row = mysql_fetch_assoc($myQuery))
		{	
			array_push($followingArr, $row);
		}
		
		return $followingArr;
	
	}
	
	function UpdatePoint($postID, $upvote) {
	
		$user_rec = array();
	    if(!$this->GetUserFromEmail($this->UserEmail(),$user_rec))
        {
            return false;
        }   
		
		//Get the author
			$result = mysql_query("Select author_id from post where post_id=".$postID." ",$this->connection); 
			$resultArr = mysql_fetch_array($result);
			$author = $resultArr['author_id'];
				
		//Check if we already added a point to this post
		$result = mysql_query("Select point_id from point where post_id=".$postID." and sender_id=".$user_rec['id_user']."",$this->connection); 
				
        if(!$result || mysql_num_rows($result) <= 0)
        { 
            //Add a record of their point
			$sql = "Insert into `point`	(sender_id,  post_id, upvote)	Values (".$user_rec['id_user'].",".$postID.", ".$upvote.")";
										
			if(!mysql_query( $sql ,$this->connection))
			{
				return false;
			}
			
			
			if ($upvote == 1) {				
				$sql = "Update post set post_points = post_points + 1 where post_id = ".$postID."";
				
				if ($author != $user_rec['id_user']) {
					$this->NotifyUser($author, "post||upvote", $postID, null, $user_rec['id_user']);
				}
			}
			else {
				$sql = "Update post set post_points = post_points - 1 where post_id = ".$postID."";
				if ($author != $user_rec['id_user']) {
					$this->NotifyUser($author, "post||downvote", $postID, null, $user_rec['id_user']);
				}
			}		
			
			if(!mysql_query( $sql ,$this->connection))
			{
				return false;
			}
        }
		
		else {
			
			//Get the current value
			$result = mysql_query("Select upvote from point where post_id=".$postID." and sender_id=".$user_rec['id_user']."",$this->connection); 
			$currValues = mysql_fetch_array($result);
			$upvoteCurr = $currValues['upvote'];
			
			//If it's different, update it
			if($upvote != $upvoteCurr)
			{
				$sql = "Update point set upvote = ".$upvote." where post_id = ".$postID." and sender_id = ".$user_rec['id_user']."";
													
					if(!mysql_query( $sql ,$this->connection))
					{
						return false;
					}
				
					//Is this an upvote or downvote
					if ($upvote == 1) {				
						$sql = "Update post set post_points = post_points + 2 where post_id = ".$postID."";
						if ($author != $user_rec['id_user']) {
							$this->NotifyUser($author, "post||upvote", $postID, null, $user_rec['id_user']);
						}
					}
						
					else {
						$sql = "Update post set post_points = post_points - 2 where post_id = ".$postID."";
						if ($author != $user_rec['id_user']) {
							$this->NotifyUser($author, "post||downvote", $postID, null, $user_rec['id_user']);
						}
					}	
					
					if(!mysql_query( $sql ,$this->connection))
					{
						return false;
					}
			}
		}
		
		return true;
	}
	
	function DeletePoint($postID) {
		$user_rec = array();
	    if(!$this->GetUserFromEmail($this->UserEmail(),$user_rec))
        {
            return false;
        }   
		
		//Get the author
			$result = mysql_query("Select author_id from post where post_id=".$postID." ",$this->connection); 
			$resultArr = mysql_fetch_array($result);
			$author = $resultArr['author_id'];
				
		//Check our current point
		$result = mysql_query("Select upvote from point where post_id=".$postID." and sender_id = ".$user_rec['id_user']."",$this->connection); 
		$resultArr = mysql_fetch_array($result);
		$upvote = $resultArr['upvote'];
		
            //Delete the record of their point
			$sql = "Delete from `point`	where sender_id = ".$user_rec['id_user']." and post_id = ".$postID."";
															
			if(!mysql_query( $sql ,$this->connection))
			{
				return 'Something went wrong. Please try again.';
			}
			
			//Add/remove their point
			if ($upvote == '1') {				
				$sql = "Update post set post_points = post_points - 1 where post_id = ".$postID."";
				if(!mysql_query( $sql ,$this->connection))
				{
					return 'Something went wrong. Please try again.';
				}
			}
			else {
				$sql = "Update post set post_points = post_points + 1 where post_id = ".$postID."";
				if(!mysql_query( $sql ,$this->connection))
				{
					return 'Something went wrong. Please try again.';
				}
			}		
			
			return true;
	}
	
	function GetConversation($conversation_id) {
		 if(!$this->GetUserFromEmail($this->UserEmail(),$user_rec))
        {
			return false;
        }  
		
		
		$myQuery = mysql_query("SELECT * FROM message join user_message_string on (message.message_string_id = user_message_string.user_message_string_id) where message_string_id = '" .$conversation_id."' ORDER BY message.sent_time DESC");
		
		$conversationArr = array();
		
		while ($row = mysql_fetch_assoc($myQuery))
		{
			array_push($conversationArr, $row);
		}
		
		return $conversationArr;
	}
	
    //-------Public Helper functions -------------
    function GetSelfScript()
    {
        return htmlentities($_SERVER['PHP_SELF']);
    }    
    
    function SafeDisplay($value_name)
    {
        if(empty($_POST[$value_name]))
        {
            return'';
        }
        return htmlentities($_POST[$value_name]);
    }
    
    function RedirectToURL($url)
    {
        header("Location: $url");
        exit;
    }
    
    function GetSpamTrapInputName()
    {
        return 'sp'.md5('KHGdnbvsgst'.$this->rand_key);
    }
    
    function GetErrorMessage()
    {
        if(empty($this->error_message))
        {
            return '';
        }
        $errormsg = nl2br(htmlentities($this->error_message));
        return $errormsg;
    }    
    //-------Private Helper functions-----------
    
    function HandleError($err)
    {
        $this->error_message .= $err."\r\n";
    }
    
    function HandleDBError($err)
    {
        $this->HandleError($err."\r\n mysqlerror:".mysql_error());
    }
    
    function GetFromAddress()
    {
        if(!empty($this->from_address))
        {
            return $this->from_address;
        }

        $host = $_SERVER['SERVER_NAME'];

        $from ="nobody@$host";
        return $from;
    } 
    
    function GetLoginSessionVar()
    {
        $retvar = md5($this->rand_key);
        $retvar = 'usr_'.substr($retvar,0,10);
        return $retvar;
    }
    
    function CheckLoginInDB($connectName,$password)
    {
        if(!$this->DBLogin())
        {
            $this->HandleError("Database login failed!");
            return false;
        }          
        $connectName = $this->SanitizeForSQL($connectName);

  	$nresult = mysql_query("SELECT * FROM $this->tablename WHERE connectName = '$connectName'", $this->connection) or die(mysql_error());
        // check for result 
        $no_of_rows = mysql_num_rows($nresult);
        if ($no_of_rows > 0) {
            $nresult = mysql_fetch_array($nresult);
            $salt = $nresult['salt'];
            $encrypted_password = $nresult['password'];
            $hash = $this->checkhashSSHA($salt, $password);
         
           
        }        

        $qry = "Select name, email from $this->tablename where connectName='$connectName' and password='$hash' and confirmcode='y'";
        
        $result = mysql_query($qry,$this->connection);
        
        if(!$result || mysql_num_rows($result) <= 0)
        {
            $this->HandleError("Error logging in. The connectName or password does not match");
            return false;
        }
        
        $row = mysql_fetch_assoc($result);
        
        
        $_SESSION['name_of_user']  = $row['name'];
        $_SESSION['email_of_user'] = $row['email'];
		$_SESSION['connectName'] = $row['connectName'];
		
		
		date_default_timezone_set('America/Los_Angeles');
		
        return true;
    }

 public function checkhashSSHA($salt, $password) {
 
        $hash = base64_encode(sha1($password . $salt, true) . $salt);
 
        return $hash;
    }
    
    function UpdateDBRecForConfirmation(&$user_rec)
    {
        if(!$this->DBLogin())
        {
            $this->HandleError("Database login failed!");
            return false;
        }   
        $confirmcode = $this->SanitizeForSQL($_GET['code']);
        
        $result = mysql_query("Select name, email from $this->tablename where confirmcode='$confirmcode'",$this->connection);   
        if(!$result || mysql_num_rows($result) <= 0)
        {
            $this->HandleError("Wrong confirm code.");
            return false;
        }
        $row = mysql_fetch_assoc($result);
        $user_rec['name'] = $row['name'];
        $user_rec['email']= $row['email'];
        
        $qry = "Update $this->tablename Set confirmcode='y' Where  confirmcode='$confirmcode'";
        
        if(!mysql_query( $qry ,$this->connection))
        {
            $this->HandleDBError("Error inserting data to the table\nquery:$qry");
            return false;
        }      
        return true;
    }
    
    function ResetUserPasswordInDB($user_rec)
    {
        $new_password = substr(md5(uniqid()),0,10);
        
        if(false == $this->ChangePasswordInDB($user_rec,$new_password))
        {
            return false;
        }
        return $new_password;
    }
    
    function ChangePasswordInDB($user_rec, $newpwd)
    {
        $newpwd = $this->SanitizeForSQL($newpwd);

        $hash = $this->hashSSHA($newpwd);

	$new_password = $hash["encrypted"];

	$salt = $hash["salt"];
        
        $qry = "Update $this->tablename Set password='".$new_password."', salt='".$salt."' Where  id_user=".$user_rec['id_user']."";
        
        if(!mysql_query( $qry ,$this->connection))
        {
            $this->HandleDBError("Error updating the password \nquery:$qry");
            return false;
        }     
        return true;
    }
    
    function GetUserFromEmail($email,&$user_rec)
    {
        if(!$this->DBLogin())
        {
            $this->HandleError("Database login failed!");
            return false;
        }   
        $email = $this->SanitizeForSQL($email);
        
        $result = mysql_query("Select * from $this->tablename where email='$email'",$this->connection);  

        if(!$result || mysql_num_rows($result) <= 0)
        {
            $this->HandleError("There is no user with email: $email");
            return false;
        }
        $user_rec = mysql_fetch_assoc($result);

        
        return true;
    }
    
    function SendUserWelcomeEmail(&$user_rec)
    {
        $mailer = new PHPMailer();
        
        $mailer->CharSet = 'utf-8';
        
        $mailer->AddAddress($user_rec['email'],$user_rec['name']);
        
        $mailer->Subject = "Welcome to ".$this->sitename;

        $mailer->From = $this->GetFromAddress();        
        
        $mailer->Body ="Hello ".$user_rec['name']."\r\n\r\n".
        "Welcome! Your registration  with ".$this->sitename." is completed.\r\n".
        "\r\n".
        "Regards,\r\n".
        "Webmaster\r\n".
        $this->sitename;

        if(!$mailer->Send())
        {
            $this->HandleError("Failed sending user welcome email.");
            return false;
        }
        return true;
    }
    
    function SendAdminIntimationOnRegComplete(&$user_rec)
    {
        if(empty($this->admin_email))
        {
            return false;
        }
        $mailer = new PHPMailer();
        
        $mailer->CharSet = 'utf-8';
        
        $mailer->AddAddress($this->admin_email);
        
        $mailer->Subject = "Registration Completed: ".$user_rec['name'];

        $mailer->From = $this->GetFromAddress();         
        
        $mailer->Body ="A new user registered at ".$this->sitename."\r\n".
        "Name: ".$user_rec['name']."\r\n".
        "Email address: ".$user_rec['email']."\r\n";
        
        if(!$mailer->Send())
        {
            return false;
        }
        return true;
    }
    
    function GetResetPasswordCode($email)
    {
		return substr(md5($email.$this->sitename.$this->rand_key),0,10);
    }
    
    function SendResetPasswordLink($user_rec)
    {
        $email = $user_rec['email'];
        
        $mailer = new PHPMailer();
        
        $mailer->CharSet = 'utf-8';
        
        $mailer->AddAddress($email,$user_rec['name']);
        
        $mailer->Subject = "Your reset password request at ".$this->sitename;

        $mailer->From = $this->GetFromAddress();
        
        $link = $this->GetAbsoluteURLFolder().
                '/resetpwd.php?email='.
                urlencode($email).'&code='.
                urlencode($this->GetResetPasswordCode($email));
				
        $mailer->Body ="Hello ".$user_rec['name'].",\r\n\r\n".
        "There was a request to reset your password at ".$this->sitename."\r\n".
        "Please click the link below to complete the request: \r\n".$link."\r\n".
        "\nRegards,\r\n".
        "Webmaster\r\n".
        $this->sitename;
        
        if(!$mailer->Send())
        {
            return false;
        }
        return true;
    }
    
    function SendNewPassword($user_rec, $new_password)
    {
        $email = $user_rec['email'];
        
        $mailer = new PHPMailer();
        
        $mailer->CharSet = 'utf-8';
        
        $mailer->AddAddress($email,$user_rec['name']);
        
        $mailer->Subject = "Your new password for ".$this->sitename;

        $mailer->From = $this->GetFromAddress();
        
        $mailer->Body ="Hello ".$user_rec['name']."\r\n\r\n".
        "Your password is reset successfully. ".
        "Here is your updated login:\r\n".
        "Connect Name:".$user_rec['connectName']."\r\n".
        "password:$new_password\r\n".
        "\r\n".
        "Login here: ".$this->GetAbsoluteURLFolder()."/login.php\r\n".
        "\r\n".
        "Regards,\r\n".
        "Webmaster\r\n".
        $this->sitename;
        
        if(!$mailer->Send())
        {
            return false;
        }
        return true;
    }    
    
    function ValidateRegistrationSubmission()
    {
        //This is a hidden input field. Humans won't fill this field.
        if(!empty($_POST[$this->GetSpamTrapInputName()]) )
        {
            //The proper error is not given intentionally
            $this->HandleError("Automated submission prevention: case 2 failed");
            return false;
        }
        
        $validator = new FormValidator();
        $validator->addValidation("name","req","Please fill in your name");
        $validator->addValidation("email","email","The input for Email should be a valid email value");
        $validator->addValidation("email","req","Please fill in Email");
		$validator->addValidation("connectName","req","Please fill in Connect Name");
        $validator->addValidation("password","req","Please fill in Password");

        
        if(!$validator->ValidateForm())
        {
            $error='';
            $error_hash = $validator->GetErrors();
            foreach($error_hash as $inpname => $inp_err)
            {
                $error .= $inpname.':'.$inp_err."\n";
            }
            $this->HandleError($error);
            return false;
        }        
        return true;
    }
    
    function CollectRegistrationSubmission(&$formvars)
    {
        $formvars['name'] = $this->Sanitize($_POST['name']);
		$formvars['connectName'] = $this->Sanitize($_POST['connectName']);
        $formvars['email'] = $this->Sanitize($_POST['email']);
        $formvars['password'] = $this->Sanitize($_POST['password']);
		$formvars['photo'] = $this->Sanitize($_POST['photo']);   
    }
    
    function SendUserConfirmationEmail(&$formvars)
    {
        $mailer = new PHPMailer();
        
        $mailer->CharSet = 'utf-8';
        
        $mailer->AddAddress($formvars['email'],$formvars['name']);
        
        $mailer->Subject = "Your registration with ".$this->sitename;

        $mailer->From = $this->GetFromAddress();        
        
        $confirmcode = $formvars['confirmcode'];
        
        $confirm_url = $this->GetAbsoluteURLFolder().'/confirmreg.php?code='.$confirmcode;
        
        $mailer->Body ="Hello ".$formvars['name']."\r\n\r\n".
        "Thanks for your registration with ".$this->sitename."\r\n".
        "Please click the link below to confirm your registration.\r\n".
        "$confirm_url\r\n".
        "\r\n".
        "Regards,\r\n".
        "The Leyff Team\r\n".
        $this->sitename;

        if(!$mailer->Send())
        {
            $this->HandleError("Failed sending registration confirmation email.");
            return false;
        }
		
		
		if(!isset($_SESSION)){ session_start(); }
        
        $_SESSION[$this->GetLoginSessionVar()] = $connectName;
		$_SESSION['email_of_user'] = $formvars['email'];
		
        return true;
    }
	
	function ResendEmail() {
		
		$user_rec = array();
        if(!$this->GetUserFromEmail($this->UserEmail(),$user_rec))
        {
            return false;
        }
		
		$mailer = new PHPMailer();
        
        $mailer->CharSet = 'utf-8';
        
        $mailer->AddAddress($user_rec['email'], $user_rec['name']);
        
        $mailer->Subject = "Your registration with ".$this->sitename;

        $mailer->From = $this->GetFromAddress();        
        
        $confirmcode = $user_rec['confirmcode'];
        
        $confirm_url = $this->GetAbsoluteURLFolder().'/confirmreg.php?code='.$confirmcode;
        
        $mailer->Body ="Hello ".$user_rec['name']."\r\n\r\n".
        "Thanks for your registration with ".$this->sitename."\r\n".
        "Please click the link below to confirm your registration.\r\n".
        "$confirm_url\r\n".
        "\r\n".
        "Regards,\r\n".
        "The Leyff Team\r\n".
        $this->sitename;

        if(!$mailer->Send())
        {
            $this->HandleError("Failed sending registration confirmation email.");
            return false;
        }
		
		
		if(!isset($_SESSION)){ session_start(); }
        
        $_SESSION[$this->GetLoginSessionVar()] = $connectName;
		$_SESSION['email_of_user'] = $user_rec['email'];
		
        return true;
	}
	
	
    function GetAbsoluteURLFolder()
    {
        $scriptFolder = (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on')) ? 'https://' : 'http://';

        $urldir ='';
        $pos = strrpos($_SERVER['REQUEST_URI'],'/');
        if(false !==$pos)
        {
            $urldir = substr($_SERVER['REQUEST_URI'],0,$pos);
        }

        $scriptFolder .= $_SERVER['HTTP_HOST'].$urldir;

        return $scriptFolder;
    }
    
    function SendAdminIntimationEmail(&$formvars)
    {
        if(empty($this->admin_email))
        {
            return false;
        }
        $mailer = new PHPMailer();
        
        $mailer->CharSet = 'utf-8';
        
        $mailer->AddAddress($this->admin_email);
        
        $mailer->Subject = "New registration: ".$formvars['name'];

        $mailer->From = $this->GetFromAddress();         
        
        $mailer->Body ="A new user registered at ".$this->sitename."\r\n".
        "Name: ".$formvars['name']."\r\n".
        "Email address: ".$formvars['email']."\r\n".
        "Connect Name: ".$formvars['connectName'];
        
        if(!$mailer->Send())
        {
            return false;
        }
        return true;
    }
    
    function SaveToDatabase(&$formvars)
    {
        if(!$this->DBLogin())
        {
            $this->HandleError("Database login failed!");
            return false;
        }
        if(!$this->Ensuretable())
        {
            return false;
        }
        if(!$this->IsFieldUnique($formvars,'email'))
        {
            $this->HandleError("This email is already registered");
            return false;
        }
        
	if(!$this->IsFieldUnique($formvars,'connectName'))
        {
            $this->HandleError("This Connect Name is already used. Please try another.");
            return false;
        } 
              
        if(!$this->InsertIntoDB($formvars))
        {
            $this->HandleError("Inserting to Database failed!");
            return false;
        }
        return true;
    }
    
    function IsFieldUnique($formvars,$fieldname)
    {
        $field_val = $this->SanitizeForSQL($formvars[$fieldname]);
        $qry = "select connectName from $this->tablename where $fieldname='".$field_val."'";
        $result = mysql_query($qry,$this->connection);   
        if($result && mysql_num_rows($result) > 0)
        {
            return false;
        }
        return true;
    }
    
    function DBLogin()
    {

        $this->connection = mysql_connect($this->db_host,$this->connectName,$this->pwd);

        if(!$this->connection)
        {   
            $this->HandleDBError("Database Login failed! Please make sure that the DB login credentials provided are correct");
            return false;
        }
        if(!mysql_select_db($this->database, $this->connection))
        {
            $this->HandleDBError('Failed to select database: '.$this->database.' Please make sure that the database name provided is correct');
            return false;
        }
        if(!mysql_query("SET NAMES 'UTF8'",$this->connection))
        {
            $this->HandleDBError('Error setting utf8 encoding');
            return false;
        }
        return true;
    }    
    
    function Ensuretable()
    {
        $result = mysql_query("SHOW COLUMNS FROM $this->tablename");   
        if(!$result || mysql_num_rows($result) <= 0)
        {
            return $this->CreateTable();
        }
        return true;
    }
    
    function CreateTable()
    {
       
    	$qry = "Create Table $this->tablename (".
                "id_user INT NOT NULL AUTO_INCREMENT ,".
                "name VARCHAR( 128 ) NOT NULL ,".
                "email VARCHAR( 64 ) NOT NULL ,".
                "phone_number VARCHAR( 16 ) NOT NULL ,".
                "connectName VARCHAR( 16 ) NOT NULL ,".
				"salt VARCHAR( 50 ) NOT NULL ,".
                "password VARCHAR( 80 ) NOT NULL ,".
                "confirmcode VARCHAR(32) ,".
				"points BIGINT(20) ,".
                "PRIMARY KEY ( id_user )".
                ")";
	
                
        if(!mysql_query($qry,$this->connection))
        {
            $this->HandleDBError("Error creating the table \nquery was\n $qry");
            return false;
        }
        return true;
    }
	
	function  CreatePost($text, $link, $image) {
		$text = addslashes($text);
		
		$user_rec = array();
        if(!$this->GetUserFromEmail($this->UserEmail(),$user_rec))
        {
            return false;
        }  
		
		$sql = "Insert into post
					(author_id, 
					 post_text,
					 views, 
					 post_image,
					 post_points, 
					 post_link)
								 
				Values (
					".$user_rec['id_user'].",
					'".$text."',
					0,
					'".$image."',
					0,
					'".$link."'
				)";
														
				if(!mysql_query( $sql ,$this->connection))
				{
					return false;
				}
				
			return true;
	}
	
	function  Comment($text, $post_id) {
		
		$text = addslashes($text);
		$user_rec = array();
        
		if(!$this->GetUserFromEmail($this->UserEmail(),$user_rec))
        {
            return false;
        }  
		
		$sql = "Insert into comment
					(comment_text, 
					 post_id,
					 sender_id)
								 
				Values (
					'".$text."',
					'".$post_id."',
					'".$user_rec['id_user']."'							
				)";
														
		if(!mysql_query( $sql ,$this->connection))
		{
			return false;
		}
		

		//Notify the author
		$result = mysql_query("Select author_id from post where post_id=".$post_id." ",$this->connection);  

        if(!$result || mysql_num_rows($result) <= 0)
        {
            return false;
        }
        $row = mysql_fetch_assoc($result);
        
        $user = $row['author_id'];
		$type = "post||comment";
		
		if ($user != $sender) {
			$this->NotifyUser($user, $type, $post_id, $text, $sender);
		}
		
		return true;
	}
	
	function  SendMessage($text, $toUser) 
	{
		
		$text = addslashes($text);
		$user_rec = array();
        if(!$this->GetUserFromEmail($this->UserEmail(),$user_rec))
        {
            return false;
        }  
		
		//Find or Create the message string
		$result = mysql_query("Select * from user_message_string where ((userA_id =".$user_rec['id_user']." AND userB_id = ".$toUser.") OR (userB_id =".$user_rec['id_user']." AND userA_id = ".$toUser."))", $this->connection);
				
				if(!$result || mysql_num_rows($result) <= 0)
				{
					//Create a message string
					$insert_query = "Insert into user_message_string (user_message_string_id, userA_id, userB_id) Values (NULL, '".$user_rec['id_user']."', '".$toUser."')";
					if(!mysql_query( $insert_query ,$this->connection))
					{
						$this->HandleDBError("Error inserting data to the table\nquery:$insert_query");
						return false;
					}
					
					//Find the id of the message string we just created
					$result = mysql_query("Select * from user_message_string where ((userA_id =".$user_rec['id_user']." AND userB_id = ".$toUser.") OR (userB_id =".$user_rec['id_user']." AND userA_id = ".$toUser."))", $this->connection);
				
					if(!$result || mysql_num_rows($result) <= 0)
					{
						return false;
					}
					
					$row = mysql_fetch_assoc($result);
					$conversation_id = $row['user_message_string_id'];
					
					//Add the message to the string
					$insert_query = "Insert into message (message_id, sender_id, sent_time, message_text, message_string_id) Values (NULL, '".$user_rec['id_user']."', CURRENT_TIMESTAMP, '".$text."', '".$conversation_id."')";
					if(!mysql_query( $insert_query ,$this->connection))
					{
						$this->HandleDBError("Error inserting data to the table\nquery:$insert_query");
						return false;
					}
				}
				
				else {
					$row = mysql_fetch_assoc($result);
					$conversation_id = $row['user_message_string_id'];
					
					//Add the message to the string
					$insert_query = "Insert into message (message_id, sender_id, sent_time, message_text, message_string_id) Values (NULL, '".$user_rec['id_user']."', CURRENT_TIMESTAMP, '".$text."', '".$conversation_id."')";
					if(!mysql_query( $insert_query ,$this->connection))
					{
						$this->HandleDBError("Error inserting data to the table\nquery:$insert_query");
						return false;
					}
				}
				
			$this->AddAlert('message', $toUser);
				
			return true;
	}
	
	 function Redirect($url, $permanent = false)
	{
		if (headers_sent() === false)
		{
			header('Location: ' . $url, true, ($permanent === true) ? 301 : 302);
		}

		exit();
	}
    
    function InsertIntoDB(&$formvars)
    {
    
        $confirmcode = $this->MakeConfirmationMd5($formvars['email']);

        $formvars['confirmcode'] = $confirmcode;

		$hash = $this->hashSSHA($formvars['password']);

		$encrypted_password = $hash["encrypted"];
        
 

		$salt = $hash["salt"];
 
        $insert_query = 'insert into '.$this->tablename.'(
		name,
		email,
		connectName,	
		password,
		salt,
		confirmcode,
		points
		)
		values
		(
		"' . $this->SanitizeForSQL($formvars['name']) . '",
		"' . $this->SanitizeForSQL($formvars['email']) . '",
		"' . $this->SanitizeForSQL($formvars['connectName']) . '",
		"' . $encrypted_password . '",
		"' . $salt . '",
		"' . $confirmcode . '",
		"    0  "
		)';  

 
        if(!mysql_query( $insert_query ,$this->connection))
        {
            //$this->HandleDBError("Error inserting data to the table\nquery:$insert_query");
			$this->HandleDBError("There was a problem, please try again.");
            return false;
        }        
        return true;
    }
    function hashSSHA($password) {
 
        $salt = sha1(rand());
        $salt = substr($salt, 0, 10);
        $encrypted = base64_encode(sha1($password . $salt, true) . $salt);
        $hash = array("salt" => $salt, "encrypted" => $encrypted);
        return $hash;
    }
    function MakeConfirmationMd5($email)
    {
        $randno1 = rand();
        $randno2 = rand();
        return md5($email.$this->rand_key.$randno1.''.$randno2);
    }
	function Alert($message) {
		echo '<script language="javascript">';
		echo 'alert("'.$message.'")';
		echo '</script>';
	}
	
    function SanitizeForSQL($str)
    {
        if( function_exists( "mysql_real_escape_string" ) )
        {
              $ret_str = mysql_real_escape_string( $str );
        }
        else
        {
              $ret_str = addslashes( $str );
        }
        return $ret_str;
    }
    
 /*
    Sanitize() function removes any potential threat from the
    data submitted. Prevents email injections or any other hacker attempts.
    if $remove_nl is true, newline chracters are removed from the input.
    */
    function Sanitize($str,$remove_nl=true)
    {
        $str = $this->StripSlashes($str);

        if($remove_nl)
        {
            $injections = array('/(\n+)/i',
                '/(\r+)/i',
                '/(\t+)/i',
                '/(%0A+)/i',
                '/(%0D+)/i',
                '/(%08+)/i',
                '/(%09+)/i'
                );
            $str = preg_replace($injections,'',$str);
        }

        return $str;
    }    
    function StripSlashes($str)
    {
        if(get_magic_quotes_gpc())
        {
            $str = stripslashes($str);
        }
        return $str;
    }    
}
?>

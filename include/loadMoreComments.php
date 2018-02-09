<?PHP
require_once("./include/membersite_config.php");

$loggedIn = true;

if(!($fgmembersite->CheckLogin()))
{
	$loggedIn = false;
	$fgmembersite->RedirectToURL("index.php");
}	


if ($loggedIn) {				
	$myID = $fgmembersite->CurrentUser();
}

else {
	$myID = NULL;
}


$offset = '';

if (isset($_POST['sort'])) {
	$offset = $_POST['sort'];
}

$servername = "localhost";
$username = "tylre";
$password = "tylrePass";
$db = "YellowstoneDB1";


// Create connection
$conn = new mysqli($servername, $username, $password, $db);	

		if (isset($_POST['post_id'])) 
		{
			$post_id = $_POST['post_id'];
			
			if (isset($_POST['sort']) && $_POST['sort'] != '' && $_POST['sort'] != undefined) {
					
				$qry = "SELECT * 
							FROM comment 
								where post_id = '". $post_id ."'
									ORDER BY comment_time DESC
											LIMIT 5 OFFSET ".$offset."";
			}	
			
			else {
				$qry = "SELECT * 
							FROM comment 
								where post_id = '". $post_id ."'
									ORDER BY comment_time DESC
											LIMIT 5";
			}
		}
		else 
		{
			return false;
		}
			
	
				
		$result = $conn->query($qry);
		
		$allComments = array();
		
		while ($row = $result->fetch_assoc())
		{	
				array_push($allComments, $row);
		}
		
			
		for ($i = 0; $i < sizeOf($allComments); $i++) {
					$offset = $offset + 1;
					
					if ($allComments[$i]['sender_id'] == $myID) {
						$myComment = true;
					}
					
					else {
						$myComment = false;
					}
					
					$commentID = $allComments[$i]['comment_id'];
					
				//Add an HTML row
				echo ' 
						<br/>
							<div class="row" id="'.$commentID.'" style="text-align: left; font-weight: normal;">
								<div class="col-xs-4">
									<a href="profile.php?user='. $fgmembersite->GetConnectName($allComments[$i]['sender_id']) .'" style="color: black;">
										<img class="img img-responsive" style="max-height: 60px; margin-left: 25%;" src="'. $fgmembersite->GetProfilePictureByConnectName($fgmembersite->GetConnectName($allComments[$i]['sender_id'])).'"> </img>
									</a>
								</div>
								<div class="col-xs-8">
									<p style="margin-bottom: 0;"><b>'.$fgmembersite->GetNameFromConnectName($fgmembersite->GetConnectName($allComments[$i]['sender_id'])).'</b></p>
									<p>	<b>' .$fgmembersite->humanTiming(strtotime($allComments[$i]['comment_time'])). '</b></p>	
									<p> ' .$allComments[$i]['comment_text']. '</p>';
								 if ($myComment) { ?> <div class="glyphicon glyphicon-trash" onclick="removeComment(<?php echo $commentID ?>)" style = "z-index: 50; color: #cccccc; border-radius: 50%; float: right; width: 40px; height: 40px; display: inline;"></div><?php } 	
								echo ' 
								</div>	
								<span class="commentSort" id="'.$offset.'"></span>	
							</div>';	

		}//for

?>
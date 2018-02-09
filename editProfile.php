<?php 
require_once("./include/membersite_config.php");

$servername = "localhost";
$username = "tylre";
$password = "tylrePass";
$db = "YellowstoneDB1";
$redirect = 'editProfile.php';
	

if ($fgmembersite->CheckLogin()) {
	$userID = $fgmembersite->CurrentUser();
}

else {
	$fgmembersite->RedirectToURL("login.php");
}

// Create connection
$conn = new mysqli($servername, $username, $password, $db);

$updated=0;
if(isset($_GET['u']))
{
   $updated=1;
}

//Check notifications & messages	
$notificationCount = $fgmembersite->GetNotificationAlerts();
$messageCount = $fgmembersite->GetMessageAlerts();


   if(isset($_FILES['image'])){
      $errors= array();
      $file_name = $_FILES['image']['name'];
      $file_size = $_FILES['image']['size'];
      $file_tmp = $_FILES['image']['tmp_name'];
      $file_type = $_FILES['image']['type'];
      $file_ext=strtolower(end(explode('.',$_FILES['image']['name'])));
      
      $expensions= array("jpeg","jpg","png");
      
      if(in_array($file_ext,$expensions)=== false){
         $errors[]="extension not allowed, please choose a JPEG or PNG file.";
      }
      
      if(empty($errors)==true) {						
							
							//Is this a new profile picture or updating current one
							$photoQry = "Select * from photo where user_id= '" . $userID. "' and photo_type = '1'";  
												
							if(!$result2 = $conn->query($photoQry)){
								die('There was an error running the query [' . $conn->error . ']');
							}
								
							$row2 = mysqli_fetch_assoc($result2);	
							
							if (mysqli_num_rows($result2) > 0) {
								
								// Generate a random 12 character name for the image
								// Generate up to 10 times to find a unique name
								$nameLength = 12;
								$tryCount = 10;
								$name = "";
								$tries = 0;
								$validChars = "abcdefghijklmnopqrstuvwxyz0123456789";
								while ($tryCount > 0 && strlen($name) == 0) {
									$tmpName = "";
									for ($i = 0; $i < $nameLength; $i++) {
										$tmpName = $tmpName . substr($validChars, rand(0, strlen($validChars)), 1);
									}
									if (!file_exists('images/'.$userID.'/' . $tmpName)) {
										// Found a name
										$name = $tmpName;
									}
								}
								
								if (strlen($name) > 0) {
									$imageName = $name;
								}
								else {
									$imageName = time(). preg_replace('/[\/\&%#\$\s]/','',$file_name) ;
								}
																 
								$insert_query = 'Update photo SET photo_location = "images/'.$userID.'/'. $imageName . '" WHERE user_id = "' . $userID . '" AND photo_type = "1" ';
							 
									if(!$insert = $conn->query($insert_query))
									{			
										Redirect($redirect, false);
										return false;
									}  
									
									//Remove the old photo
									unlink($row2['photo_location']);
									
									$path = "images/".$userID;
									
									if ( ! is_dir($path)) {
										mkdir($path);
									}
									move_uploaded_file($file_tmp, $path.'/'.$imageName);	
									
									Redirect($redirect, false);
									return true;
								
								Redirect($redirect, false);
								return true;
							}
							
							else {	

								// Generate a random 12 character name for the image
								// Generate up to 10 times to find a unique name
								$nameLength = 12;
								$tryCount = 10;
								$name = "";
								$tries = 0;
								$validChars = "abcdefghijklmnopqrstuvwxyz0123456789";
								while ($tryCount > 0 && strlen($name) == 0) {
									$tmpName = "";
									for ($i = 0; $i < $nameLength; $i++) {
										$tmpName = $tmpName . substr($validChars, rand(0, strlen($validChars)), 1);
									}
									if (!file_exists('images/'.$userID.'/' . $tmpName)) {
										// Found a name
										$name = $tmpName;
									}
								}
								
								if (strlen($name) > 0) {
									$imageName = $name;
								}
								else {
									$imageName = time(). preg_replace('/[\/\&%#\$\s]/','',$file_name) ;
								}
															
								$insert_query = 'insert into photo(
									user_id,
									photo_type,
									photo_location
									)
									values
									(
									"' . $userID . '",
									" 	 1 ",
									"images/'.$userID.'/'. $imageName . '"
									)';  

							 
									if(!$insert = $conn->query($insert_query))
									{																
										echo "Error: " .mysqli_error($conn);
										return false;
									}   
									
									
									$path = "images/".$userID;
									
									if ( ! is_dir($path)) {
										mkdir($path);
									}
									move_uploaded_file($file_tmp, $path.'/'.$imageName);	
									$fgmembersite->imageResizing($path, $imageName, 500, 500);
							
									Redirect($redirect, false);
									return true;
							}								
      }
	  
	  else{
         print_r($errors);
      }
   }
   
      if(isset($_FILES['backgroundImage'])){
		
      $errors= array();
      $file_name = $_FILES['backgroundImage']['name'];
      $file_size = $_FILES['backgroundImage']['size'];
      $file_tmp = $_FILES['backgroundImage']['tmp_name'];
      $file_type = $_FILES['backgroundImage']['type'];
      $file_ext=strtolower(end(explode('.',$_FILES['backgroundImage']['name'])));
      
      $expensions= array("jpeg","jpg","png");
      
      if(in_array($file_ext,$expensions)=== false){
         $errors[]="extension not allowed, please choose a JPEG or PNG file.";
      }
      
      if(empty($errors)==true) {
		  
							//Is this a new profile picture or updating current one
							$photoQry = "Select user_id from photo where user_id= '" . $userID. "' and photo_type = '2'";  
												
							if(!$result2 = $conn->query($photoQry)){
								die('There was an error running the query [' . $conn->error . ']');
							}
							
							$row2 = mysqli_fetch_assoc($result2);	
							
							if (mysqli_num_rows($result2) > 0) {
								
								// Generate a random 12 character name for the image
								// Generate up to 10 times to find a unique name
								$nameLength = 12;
								$tryCount = 10;
								$name = "";
								$tries = 0;
								$validChars = "abcdefghijklmnopqrstuvwxyz0123456789";
								while ($tryCount > 0 && strlen($name) == 0) {
									$tmpName = "";
									for ($i = 0; $i < $nameLength; $i++) {
										$tmpName = $tmpName . substr($validChars, rand(0, strlen($validChars)), 1);
									}
									if (!file_exists('images/'.$userID.'/' . $tmpName)) {
										// Found a name
										$name = $tmpName;
									}
								}
								
								if (strlen($name) > 0) {
									$imageName = $name;
								}
								else {
									$imageName = time(). preg_replace('/[\/\&%#\$\s]/','',$file_name) ;
								}
								
								$insert_query = 'Update photo SET photo_location = "images/'.$userID.'/'. $imageName . '" WHERE user_id = "' . $userID . '" AND photo_type = "2" ';
							 
									if(!$insert = $conn->query($insert_query))
									{			
										echo mysqli_error($conn);
										Redirect($redirect, false);
										return false;
									}  
									
									//Remove the old photo
									unlink($row2['photo_location']);
									
									$path = "images/".$userID.'/';
									
									if ( ! is_dir($path)) {
										mkdir($path);
									}
									move_uploaded_file($file_tmp, $path.'/'.$imageName);	
									$fgmembersite->imageResizing($path, $imageName, 2000, 600);
									
									Redirect($redirect, false);
									return true;
								
								Redirect($redirect, false);
								return true;
							}
							
							else {
													 
								// Generate a random 12 character name for the image
								// Generate up to 10 times to find a unique name
								$nameLength = 12;
								$tryCount = 10;
								$name = "";
								$tries = 0;
								$validChars = "abcdefghijklmnopqrstuvwxyz0123456789";
								while ($tryCount > 0 && strlen($name) == 0) {
									$tmpName = "";
									for ($i = 0; $i < $nameLength; $i++) {
										$tmpName = $tmpName . substr($validChars, rand(0, strlen($validChars)), 1);
									}
									if (!file_exists('images/'.$userID.'/' . $tmpName)) {
										// Found a name
										$name = $tmpName;
									}
								}
								
								if (strlen($name) > 0) {
									$imageName = $name;
								}
								else {
									$imageName = time(). preg_replace('/[\/\&%#\$\s]/','',$file_name) ;
								}
								
								$insert_query = 'insert into photo(
									user_id,
									photo_type,
									photo_location
									)
									values
									(
									"' . $userID . '",
									" 	 2 ",
									"images/'.$userID.'/'. $imageName . '"
									)';  

							 
									if(!$insert = $conn->query($insert_query))
									{																
										echo "Error: " .mysqli_error($conn);
										return false;
									}   
									
									
									$path = "images/".$userID;
									
									if ( ! is_dir($path)) {
										mkdir($path);
									}	
									
									move_uploaded_file($file_tmp, $path.'/'.$imageName);	
							
									Redirect($redirect, false);
									return true;
							}									
      }
	  
	  else{
         print_r($errors);
      }
   }
   
   
   	
	if(isset($_POST['location'])){
		if ($fgmembersite->UpdateLocation($_POST['location'])){			
			Redirect($redirect, false);
			return true;
		}
	}
	
	if(isset($_POST['connectName'])){		
		
		if ($_POST['connectName'] != '') {
			if ($fgmembersite->UpdateConnectName($_POST['connectName'])){	
				Redirect($redirect, false);
				return true;
			}
			
			else {
				echo '<script language="javascript">'; echo 'alert("That connect name is already in use. Please select another."); </script>'; 
			}
		}
	}
	
	if(isset($_POST['password']))
	{
	   if($fgmembersite->ChangePassword())
	   {
			Redirect($redirect, false);
	   }
	}
	   
   function Redirect($url, $permanent = false, $message='')
	{
		
		if (headers_sent() === false)
		{			
			header('Location: ' . $url .'?u=1', true, ($permanent === true) ? 301 : 302);
		}
	
		exit();
	}

?>

<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
	<meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
    <script type='text/javascript' src='scripts/gen_validatorv31.js'></script>
    <link rel="icon" href="favicon.ico">
	<title>Leyff | Edit Profile</title>	
	
<link href="bootstrap-3.3.6-dist/css/fileinput.css" media="all" rel="stylesheet" type="text/css">
 <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>	
  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
  <!-- Optional theme -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">  
  <link rel="stylesheet" href="icomoon/style.css">
  <link rel="stylesheet" href="css/style.css">
</head>

	<nav class="navbar navbar-default navbar-fixed-top col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2"> 
	   <div class="container-fluid">
		  <!-- Brand and toggle get grouped for better mobile display -->
		  <div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="true">
			  <span class="sr-only">Toggle navigation</span>
			  <span class="icon-bar"></span>
			  <span class="icon-bar"></span>
			  <span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="home.php"><img src="./images/LeyffLogo.png" style="height: 50px; margin-top: -15px;"></a>
		  </div>

		  <!-- Collect the nav links, forms, and other content for toggling -->
		  <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav navbar-left">
			  <form class="navbar-form form-horizontal" method="GET" action="search.php" role="search">
				<div class="input-group" style="position: fixed; left: 50%; width: 300px; margin-left: -150px;">
				  <input type="text" class="form-control" name="qry" placeholder="Search" id="searchText" type="text">
				  <span class="input-group-btn">
					<button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"></span></button>
				</div>
			  </form>
			</ul>

			<ul class="nav navbar-nav navbar-right">
			  <li><a href= "profile.php"><div class="circle-avatar" style= "width: 24px; height: 24px; border-radius: 12px; background-image: url(<?php echo $fgmembersite->GetProfilePicture(); ?>)"> </div></a> 
				<ul>
					<a href="profile.php"><li><h5> View Profile </h5> </li></a>	
					<a href="editProfile.php"> <li><h5> Edit Profile </h5> </li></a>
					<a href="logout.php"><li><h5> Logout </h5></li></a>
				</ul>
			  </li>
			  <li><a href="notifications.php"><span class="glyphicon glyphicon-globe" 
							<?php if ($notificationCount > 0) 
									{echo 'style="color: lightblue;"';
								  } 
							?>></span></a></li>
			</ul>
		  </div><!-- /.navbar-collapse -->
		</div><!-- /.container-fluid -->
	</nav>
	
	<div id="alertUser" style="display: none; position: absolute; width: 100%; text-align: center;" class="alert alert-success">Successfully Updated!</div>
	
<div id="body-wrapper" style = "padding: 3%;">
	<!-- Signup Page --> 
	<div id="signup">

		
				
				 
		<div class="main">
			<div class="container" style="padding: 50px; background-color: rgba(0,0,0,.1);  border: solid 1px white; border-radius: 20px;">			    
				<div class="row">
					<div class ="col-md-4">
							<div class="col-md-12" style = "background-color: #FFFFFF; border: solid 1px white; border-radius: 20px;">
								<div style="width: 100%; text-align: center;">
									<h3>Profile Picture</h3>		<br/>		
								</div>							
								<div class="login" style = "text-align: center; ">
									   <form action = "" method = "POST" enctype = "multipart/form-data">	
										<label class="control-label"></label>
										<input type = "file" class="file" name = "image" />
										<br/>
										<input type="submit" name="submit" class = "btn btn-large" id="submit" value="Update" style = "background-color: #75C0C0; width: 100%;"></p>								 
									  </form>
								</div>
						</div>
					</div>
					<br class="visible-sm-block visible-xs-block"/>
					<div class="col-md-4">
							<div class="col-md-12" style = "background-color: #FFFFFF; border: solid 1px white; border-radius: 20px;">
								<div style="width: 100%; text-align: center;">
									<h3>Background Image:</h3>		<br/>		
								</div>							
								<div class="login" style = "text-align: center; ">
									   <form action = "" method = "POST" enctype = "multipart/form-data">	
										<label class="control-label"></label>
										<input type = "file" class="file" name = "backgroundImage" />
										<br/>
										<input type="submit" name="submit" class = "btn btn-large" id="submit" value="Update" style = "background-color: #75C0C0; width: 100%;"></p>								 
									  </form>
								</div>
						</div>
					</div>
					<br class="visible-sm-block visible-xs-block"/>
					<div class = "col-md-4">
						<div class="col-md-12" style = "background-color: #FFFFFF; border: solid 1px white; border-radius: 20px;">
							<div style="width: 100%; text-align: center;">
								<h3>Location:</h3>	<br/>			
							</div>							
							<div class="login" style = "text-align: center; ">
								   <form action = "" method = "POST" enctype = "multipart/form-data">	
									<label class="control-label"></label>
									<input type = "text" class="form-control" placeholder="Location" name = "location" />
									<br/>
									<input type="submit" name="submit" class = "btn btn-large" id="submit" value="Update" style = "background-color: #75C0C0; width: 100%;"></p>								 
								  </form>
							</div>
						</div>
					</div>
					<br class="visible-sm-block visible-xs-block"/>
				</div>
				<hr class="hidden-xs hidden-sm"/>
				<div class="row">
						<br class="visible-sm-block visible-xs-block"/>
						<div class = "col-md-4">
							<div class="col-md-12" style = "background-color: #FFFFFF; border: solid 1px white; border-radius: 20px;">
								<div style="width: 100%; text-align: center;">
									<h3>Store Name:</h3>	<br/>			
								</div>							
								<div class="login" style = "text-align: center; ">
									   <form action = "" method = "POST" enctype = "multipart/form-data">	
										<label class="control-label"></label>
										<input type = "text" class="form-control" placeholder="Store Name" name = "connectName" />
										<br/>
										<input type="submit" name="submit" class = "btn btn-large" id="submit" value="Update" style = "background-color: #75C0C0; width: 100%;"></p>								 
									  </form>
								</div>
							</div>
						</div>
						<br class="visible-sm-block visible-xs-block"/>
						<div class = "col-md-4" >
							<div class="col-md-12" style = "background-color: #FFFFFF; border: solid 1px white; border-radius: 20px;">
									<div style="width: 100%; text-align: center;">
										<h3>Password:</h3>				
									</div>							
									<div class="login" style = "text-align: center; ">
										   <form id='changepwd' action='<?php echo $fgmembersite->GetSelfScript(); ?>' method='post' accept-charset='UTF-8'>
												<fieldset >
													<input type='hidden' name='password' id='password' value='1'/>
													<div><span class='error'><?php echo $fgmembersite->GetErrorMessage(); ?></span></div>
													
													<div class="col-md-6">
														<label for='oldpwd' >Old Password:</label><br/>
																									
														<input type='password' class="form-control" name='oldpwd' id='oldpwd' maxlength="50" />												
														<span id='changepwd_oldpwd_errorloc' class='error'></span>
												</div>

													<div class="col-md-6">
														<label for='newpwd' >New Password:</label>
														
														<input type='password' class="form-control" name='newpwd' id='newpwd' maxlength="50" /><br/>
														<span id='changepwd_newpwd_errorloc' class='error'></span>													
												</div>
														<input type="submit" name="submit" class = "btn btn-large" id="submit" value="Update" style = "background-color: #75C0C0; width: 100%;"></p>				
												</fieldset>
											</form>
									</div>
							</div>
						</div>
						<br class="visible-sm-block visible-xs-block"/>
						<div class = "col-md-4">
							<div class="col-md-12" style = "background-color: #FFFFFF; border: solid 1px white; border-radius: 20px;">
								<div style="width: 100%; text-align: center;">
									<h3>Delete Account</h3>		<br/>		
								</div>							
								<div class="login" style = "text-align: center; ">
									   <form action = "" method = "POST" enctype = "multipart/form-data">	
										<label class="control-label"></label>
										<input type="text" class="form-control" placeholder="Confirm by typing DELETE" id = "confirmDelete"> </input>
										<br/>
										<input type="submit" name="submit" class = "btn btn-danger" id="submit" onclick="deleteAccount()" value="Delete" style = "width: 100%;"></p>								 
									  </form>
								</div>
							</div>
						</div>
					</div>
				</div>					
			</div>
		</div>		    
			
	</div>		
</div> <!-- Body Wrapper -->

	<script>
	$(document).ready(function(){

		var show=<?php echo $updated; ?>;

		 if(show==1)
		 {
		   $('#alertUser').show().delay(1500).fadeOut();
		 }
		 
		});
		
		function deleteAccount(){	
			
			var confirmCode = "DELETE";
			var confirmText = $('#confirmDelete').val();
			var uid = <?php echo $userID;?>;			
			
			if (confirmCode === confirmText) {
				var confirmed = confirm('Are you sure you want to delete this account?');	
				
					if (confirmed){					 
						 $.ajax
						({ 
							url: 'deleteAccount.php',
							 type: "POST",	
							 data: "uid=" + uid,						 
							  success: function(msg){																  
							  }
						}); 
					 } 
			}
		}
		
	</script>
	
	<script type='text/javascript'>
		// <![CDATA[
			
			var frmvalidator  = new Validator("changepwd");
			frmvalidator.EnableOnPageErrorDisplay();
			frmvalidator.EnableMsgsTogether();

			frmvalidator.addValidation("oldpwd","req","Please provide your old password");
			
			frmvalidator.addValidation("newpwd","req","Please provide your new password");

		// ]]>
	</script>
  <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="bootstrap-3.3.6-dist/js/bootstrap.min.js"></script>
    <script src="assets/js/docs.min.js"></script>
	</div>
  </body>
   <div id="footer"></div> 
   <script>
	   $(function() {
		  $("#footer").load("footer.html");		 
		 
		  $.ajaxSetup({
			async: false
		  });
		});
   </script>
</html>
<!----------------------------------->







</body>
</html>>
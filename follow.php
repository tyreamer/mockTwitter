<?php 
require_once("./include/membersite_config.php");

$servername = "localhost";
$username = "tylre";
$password = "tylrePass";
$db = "YellowstoneDB1";

// Create connection
$conn = new mysqli($servername, $username, $password, $db);

$user = $_GET['user']; 
$follower = $_GET['follower'];
$isFollowing = $_GET['following'];


$sql = "Select connectName from person where id_user ='".$user."'";  
																
								if(!$result = $conn->query($sql)){
									die('There was an error running the insert query [' . mysqli_error($conn) . ']');
								}
if ($row = $result->fetch_assoc()) {
        $connectName = $row['connectName'];
    }	


if(isset($_POST['submit'])){
      $errors= array();
      
      if(empty($errors)==true) {
			
			//Check if we're really following them
			$verify = "Select follower_id as total from follower where user_id ='".$user."' and follower_user_id ='" .$follower. "' ";
			if(!$result = $conn->query($verify))
			{
                                Redirect("profile.php?user=$connectName", false);
				return true;
			}			
			else  {
				
                                if (mysqli_num_rows($result) == 0) {
                                       $isFollowing = 0;
                                }
		                
                                else {

				  $isFollowing = 1;
                                }
			}
                       
			
			//If the GET is wrong, throw them home
			if ($isFollowing != $_GET['following']) {
				Redirect("profile.php?user=$connectName", false);
				return true;
			}

			
			//Do we want to follow or unfollow
			if ($isFollowing == 0) {
				

			      $sql = "INSERT INTO follower(user_id, follower_user_id) VALUES ('".$user."', '".$follower."')";  
																
								if(!$result = $conn->query($sql)){
									die('There was an error running the insert query [' . mysqli_error($conn) . ']');
								}	
								Redirect("profile.php?user=$connectName", false);
								return true;														
			}
			
			else {
				$sql = "DELETE FROM follower where user_id = '".$user."' and follower_user_id = '".$follower."' ";  
			 
													
								if(!$result = $conn->query($sql)){
									echo $sql;
									die('There was an error running the delete query [' . mysqli_error($conn) . ']');
								}		
								Redirect("profile.php?user=$connectName", false);
								return true;	
			}
		
	  }
	  
	  else{
         print_r($errors);
      }
   }
   
   function Redirect($url, $permanent = false)
	{
		if (headers_sent() === false)
		{
			header('Location: ' . $url, true, ($permanent === true) ? 301 : 302);
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
	<title>Leyff | Confirm</title>	
	
	<link href="bootstrap-3.3.6-dist/css/fileinput.css" media="all" rel="stylesheet" type="text/css">
 <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>	
  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
  <!-- Optional theme -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">  
  <link rel="stylesheet" href="icomoon/style.css">
		
		
		
</head>

<div id="body-wrapper" style = "background-color: rgba(0,0,0,.1); padding-bottom: 3%;">
	<!-- Signup Page --> 
	<div id="signup">

		 <nav class="navbar navbar-default">
						<div class="container">
							  <div class="navbar-header">
									<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
									  <span class="sr-only">Toggle navigation</span>
									  <span class="icon-bar"></span>
									  <span class="icon-bar"></span>
									  <span class="icon-bar"></span>
									</button>
									<a class="navbar-brand" href="home.php"><img src="./images/LeyffLogo.jpg" style="height: 50px; margin-top: -15px;"></a>
							  </div>
						</div>
		 </nav>
				
				 
		<div class="main">
			<div class="container" style="height: 800px; padding: 50px;" >
			    <div class = "col-md-4">				
			    </div>
				<div class="col-md-4" style = "background-color: #FFFFFF; border-radius: 20px;">
					<div class="col-md-12">
							<div style="width: 100%; text-align: center;">
								<h3> Are you sure you want to 
								<?php
								if ($isFollowing == 0) {
									echo ' follow this user?';
								}
								
								else {
									echo ' unfollow this user?';
								}
								?>
								
							
								
								</h3>				
							</div>								
							<div class="login" style = "text-align: center; ">
								   <form action = "" method = "POST" enctype = "multipart/form-data">	
									<label class="control-label"></label>
									<input type="submit" name="submit" class = "btn btn-large" id="submit" value="Confirm" style = "background-color: #75C0C0; width: 100%;">
									<button name="cancel" onclick="history.go(-1);" class = "btn btn-large" id="cancel" value="Cancel" style = "margin-top: 2%;background-color: #99C0C0; width: 100%;"> Cancel </button>
								  </form>
							</div>
					</div>
				</div>
				<div class = "col-md-4">
		        </div>
			</div>
		</div>		    
			
	</div>		
</div> <!-- Body Wrapper -->

	
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
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
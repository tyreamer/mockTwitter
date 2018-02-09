<?php 
require_once("./include/membersite_config.php");

$servername = "localhost";
$username = "tylre";
$password = "tylrePass";
$db = "YellowstoneDB1";

if ($fgmembersite->CheckLogin()) {
	
}

// Create connection
$conn = new mysqli($servername, $username, $password, $db);
$userEmail = $_GET['email'];
$redirect = 'thank-you.php';
$imageName = $_GET['img'];

   if(($imageName != '') && ($userEmail != '') ){     
				 		 
		 $sql = "Select id_user from person where email= '" . $userEmail . "'"; 
		 if(!$result = $conn->query($sql)){
			echo $sql;
			die('There was an error running the email query [' . mysqli_error($conn) . ']');
		 }
			$row = mysqli_fetch_assoc($result);			
		
			$userID =  $row['id_user'];
					
			$insert_query = 'insert into photo(
				user_id,
				photo_type,
				photo_location
				)
				values
				(
				"' . $userID . '",
				" 	 1 ",
				"'.$imageName.'"
				)';  
		 
				if(!$insert = $conn->query($insert_query))
				{
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
	  
	  //Assign Default Image
	  else{
         $sql = "Select * from person where email= '" . $userEmail . "'"; 
		 if(!$result = $conn->query($sql)){
			echo $sql;
			die('There was an error running the email query [' . mysqli_error($conn) . ']');
		 }
			$row = mysqli_fetch_assoc($result);			
		
			$userID =  $row['id_user'];					
													 
			$imageName = "myPicture.jpg" ;
			
			$insert_query = 'insert into photo(
				user_id,
				photo_type,
				photo_location
				)
				values
				(
				"' . $userID . '",
				" 	 1 ",
				"images/myPicture.jpg"
				)';  

		 
				if(!$insert = $conn->query($insert_query))
				{
					return false;
				}   
				
				Redirect($redirect, false);
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
	<title>Leyff | Upload</title>	
	
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
				<div class="col-md-4" style = "background-color: #FFFFFF;  border-radius: 20px;">
					<div class="col-md-12">
							<div style="width: 100%; text-align: center;">
								<h3>Select your new Profile Picture</h3>				
							</div>							
							<div class="login" style = "text-align: center; ">
								   <form action = "" method = "POST" enctype = "multipart/form-data">	
									<label class="control-label"></label>
									<input type = "file" class="file" name = "image" />
									<br/>
									<input type="submit" name="submit" class = "btn btn-large" id="submit" value="Submit" style = "background-color: #75C0C0; width: 100%;"></p>									 
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
<!----------------------------------->







</body>
</html>
<?PHP
require_once("./include/membersite_config.php");

$success = false;
if($fgmembersite->ResetPassword())
{
    $success=true;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
<head>
      <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
      <title>Reset Password Sucess</title>
     
	 <link href="bootstrap-3.3.6-dist/css/fileinput.css" media="all" rel="stylesheet" type="text/css">
 <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>	
  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
  <!-- Optional theme -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">  
  <link rel="stylesheet" href="icomoon/style.css">
	  	
		
</head>
<body>
<div id="body-wrapper" style = "height: 100%;background-color: #C6E48B;">
	<!-- Login Page --> 
	<div id="login">
		<div class="main">
			<div class="container" style="margin-top: 10%; height: 100%; padding: 50px;" >
			    <div class = "col-md-1">				
			    </div>
				<div class="col-md-10" style = "background-color: #fff;  padding: 2%; border-style: solid; border-color: #fff; border-radius: 20px;">
				
							<?php
							if($success){
							?>
								<div class="alert alert-success" style="text-align: center; margin-bottom: 0;">
									<h1>Your password has been updated.</h1>	
									<div class='short_explanation'>A new password has been sent to your e-mail address. <br/> To change this after successfully logging in, navigate to <b>Edit Profile</b>.</div>
									<br/>
									<a href="index.php"><button class="btn btn-primary"> Return Home</button></a>
								</div>
							<?php
							}
							
							else{
							?>
									<div class="alert alert-danger" style="text-align: center; margin-bottom: 0;">
									<h1>We've encountered an error:</h1>	
									<div class='short_explanation'><span class='error'><?php echo $fgmembersite->GetErrorMessage(); ?></span></div>
									<br/>
									<a href="index.php"><button class="btn btn-primary"> Return Home</button></a>
								</div>
							<?php
							}
							?>
							
				</div>
				<div class = "col-md-1">				
		        </div>
			</div>
		</div>		    
			
	</div>		

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	</div> <!-- Body Wrapper -->

</body>
</html>







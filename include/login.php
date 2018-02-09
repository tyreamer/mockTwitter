<?PHP
require_once("./include/membersite_config.php");

if(isset($_POST['submitted']))
{
   if($fgmembersite->Login())
   {
        $fgmembersite->RedirectToURL("home.php");
   }
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
<head>
      <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
      <title>Login</title>

	  <link href="bootstrap-3.3.6-dist/css/fileinput.css" media="all" rel="stylesheet" type="text/css">
 <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>	
  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
  <!-- Optional theme -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">  
  <link rel="stylesheet" href="icomoon/style.css">
	  	
</head>
<body>
<div id="body-wrapper" style = "height: 100%;background-color: rgba(0,0,0,.1);">
	<!-- Login Page --> 
	<div id="login">
		<div class="main">
			<div class="container" style="margin-top: 10%; height: 100%; padding: 50px;" >
			    <div class = "col-md-1">				
			    </div>
				<div class="col-md-10 col-xs-12" style = "background-color: #fff;  border-style: solid; border-color: #fff; border-radius: 20px;">
					<div class="col-md-8">
							<div style="text-align: center;">
								<h1>Login</h1>				
							</div>							
							<div class="login" style = "text-align: center; ">
								<!-- Form Code Start -->
								<div id='fg_membersite'>
								<form id='login' action='<?php echo $fgmembersite->GetSelfScript(); ?>' method='post' accept-charset='UTF-8'>
								<fieldset class="form-group">
								
								<input type='hidden' name='submitted' class="form-control"id='submitted' value='1'/>
									<div><span class='error'><?php echo $fgmembersite->GetErrorMessage(); ?></span></div>
									<div class="container" style="max-width: 100%;">
										<label for='connectName' style="max-width: 100%;">Connect Name:</label><br/>
										<input type='text' class="form-control" name='connectName' id='connectName' value='<?php echo $fgmembersite->SafeDisplay('connectName') ?>' maxlength="50" /><br/>
										<span id='login_connectName_errorloc' class='error'></span>
									</div>
									<div class='container'style="max-width: 100%;">
										<label for='password' >Password:</label><br/>
										<input type='password'  class="form-control" name='password' id='password' maxlength="50" /><br/>
										<span id='login_password_errorloc' class='error'></span>
									</div>
									<div class='container' style="max-width: 100%;"> 
										<input type='submit' class= "btn btn-default" name='Submit' value='Submit' />
									</div>
									<div class='short_explanation'><a href='reset-pwd-req.php'>Forgot Password?</a></div>
								</fieldset>
								</form>
								<!-- client-side Form Validations:
								Uses the excellent form validation script from JavaScript-coder.com-->

								<script type='text/javascript'>
								// <![CDATA[

									var frmvalidator  = new Validator("login");
									frmvalidator.EnableOnPageErrorDisplay();
									frmvalidator.EnableMsgsTogether();

									frmvalidator.addValidation("connectName","req","Please provide your connect name");
									
									frmvalidator.addValidation("password","req","Please provide the password");

								// ]]>
								</script>
								</div>	  
							</div>
					</div>
					<div class = "col-md-4">	
						<div style="text-align: center; margin-top: 25%; margin-bottom: 5%;">
							<h3> Not yet a member? </h3>
							<a href="register.php"><button class="btn btn-default"> Sign-Up Today! </button> </a></h1>				
						</div>
					</div>	
				</div>
				<div class = "col-md-1">				
		        </div>
			</div>
		</div>		    
			
	</div>		

 <!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>

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
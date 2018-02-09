<?PHP
require_once("./include/membersite_config.php");


$emailsent = false;
if(isset($_POST['submitted']))
{
   if($fgmembersite->EmailResetPasswordLink())
   {
        $fgmembersite->RedirectToURL("reset-pwd-link-sent.html");
        exit;
   }
}



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
<head>
      <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
      <title>Reset Password</title>
      <link rel="STYLESHEET" type="text/css" href="style/fg_membersite.css" />
      <script type='text/javascript' src='scripts/gen_validatorv31.js'></script>
	  	
		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

		<!-- Optional theme -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

		<link rel="stylesheet" href="bootstrap-switch-master/dist/css/bootstrap3/bootstrap-switch.css">
		<link rel="stylesheet" href="bootstrap-switch-master/dist/css/bootstrap3/bootstrap-switch.min.css">

		<!-- Latest compiled and minified JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
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
				
							<div style="text-align: center;">
								<h1>Reset Password</h1>	
								<div class='short_explanation'>A link to reset your password will be sent to the email address</div>
								<br/>								
							</div>							
							<div class="login" style = "text-align: center; ">
								<!-- Form Code Start -->
								<div id='fg_membersite'>
									<form id='resetreq' action='<?php echo $fgmembersite->GetSelfScript(); ?>' method='post' accept-charset='UTF-8'>
										<fieldset>
											<input type='hidden' name='submitted' id='submitted' value='1'/>
											<div><span class='error'><?php echo $fgmembersite->GetErrorMessage(); ?></span></div>
										
												<label for='username' >Your Email:</label><br/>
												<input type='text' name='email' id='email' value='<?php echo $fgmembersite->SafeDisplay('email') ?>' maxlength="50" /><br/>
												<span id='resetreq_email_errorloc' class='error'></span>
												<br/>										
												<input type='submit' name='Submit' class="btn btn-default" style="min-width: 30%;" value='Submit' /> 
										</fieldset>
									</form>
								</div>	
							</div>
				</div>
				<div class = "col-md-1">				
		        </div>
			</div>
		</div>		    
			
	</div>		

<!--
Form Code End (see html-form-guide.com for more info.)
-->


 <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="bootstrap-3.3.6-dist/js/bootstrap.min.js"></script>
    <script src="assets/js/docs.min.js"></script>
	<script src="bootstrap-switch-master/dist/js/bootstrap-switch.js"></script>
	<script src="bootstrap-switch-master/dist/js/bootstrap-switch.min.js"></script>
	</div> <!-- Body Wrapper -->

		<!-- client-side Form Validations:
								Uses the excellent form validation script from JavaScript-coder.com-->

								<script type='text/javascript'>
									// <![CDATA[

										var frmvalidator  = new Validator("resetreq");
										frmvalidator.EnableOnPageErrorDisplay();
										frmvalidator.EnableMsgsTogether();

										frmvalidator.addValidation("email","req","Please provide the email address used to sign-up");
										frmvalidator.addValidation("email","email","Please provide the email address used to sign-up");

									// ]]>

								</script>
	
</body>
</html>
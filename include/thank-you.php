<?php 
require_once("./include/membersite_config.php");

$servername = "localhost";
$username = "tylre";
$password = "tylrePass";
$db = "YellowstoneDB1";

if ($fgmembersite->CheckLogin()) {	
}

if (isset($_POST['submit'])) {
	$fgmembersite->ResendEmail();
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
	<title>Leyff | Thank You</title>	
	
		<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet">
        <link href="bootstrap-3.3.6-dist/css/fileinput.css" media="all" rel="stylesheet" type="text/css" />
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script src="bootstrap-3.3.6-dist/js/fileinput.js" type="text/javascript"></script>
        <script src="bootstrap-3.3.6-dist/js/fileinput_locale_fr.js" type="text/javascript"></script>
        <script src="bootstrap-3.3.6-dist/js/fileinput_locale_es.js" type="text/javascript"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js" type="text/javascript"></script>
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
									<a class="navbar-brand" href="home.php"><span class="orange">Leyff</span></a>
							  </div>
						</div>
		 </nav>
				
				 
		<div class="main">
			<div class="container" style="height: 800px; padding: 50px; " >
			    <div class = "col-md-3">				
			    </div>
				<div class="col-md-6">
					<div class="col-md-12" style="display: table; text-align: center; background-color: #fff; border-radius: 10px;">
							<div style="width: 100%; text-align: center;">
								<h1>Thank You!</h1>				
							</div>							
							<div class="alert alert-success" style = "text-align: center; ">
								  Your confirmation email is on its way. Please keep this page open until you receive the e-mail. 								  
							</div>
							<hr/>
							<form action ="" method="POST">
								<p class="alert alert-warning"> <b>NOTE:</b> <br/>If you do not receive your e-mail within 5 minutes, please click the button below. </p>
								<button class="btn btn-primary" type="submit" name="submit" id="submitBtn">Resend Email</button>
							</form>
					</div>
				</div>
				<div class = "col-md-3">
		        </div>
			</div>
		</div>		    
			
	</div>		
</div> <!-- Body Wrapper -->

  <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="bootstrap-3.3.6-dist/js/bootstrap.min.js"></script>
    <script src="assets/js/docs.min.js"></script>
	</div>
  </body>
   <footer style="display: block; text-align: center;">
    <div class = "col-md-12"> <a href= "home.html" style = "padding-right: 20px;"> About Us </a>  <a href= "home.html" style = "padding-right: 20px;"> Contact </a>  <a href= "home.html"> FAQ </a>  </div>
		&copy;<script type="text/javascript">  document.write(new Date().getFullYear());</script> All Rights Reserved. </center> 
  </footer>
</html>
<!----------------------------------->

</body>
</html>

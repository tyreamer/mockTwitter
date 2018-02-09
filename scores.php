<?php

require_once("./include/membersite_config.php");

if(!$fgmembersite->CheckLogin())
{
	echo '<script language="javascript">';
	echo 'alert("Please Login and Try Again.")';
	echo '</script>';
    $fgmembersite->RedirectToURL("index.php");
    exit;
}

$topScores = $fgmembersite->GetTopScores();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">
	<title>Leyff | Scores</title>
	
	
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

<style>
::-webkit-scrollbar {
    width: 12px;
}
 
::-webkit-scrollbar-track {
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3); 
    border-radius: 10px;
}
 
::-webkit-scrollbar-thumb {
    border-radius: 10px;
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.5); 
}

td .postInfo {
	padding: 7%;
	margin: 1%;
	background-color: #C6E48B;
	border-radius: 20px;
	text-align: center;
}

</style>

</head>


<div id="body-wrapper" style = "background-color: #C6E48B; padding-bottom: 3%;">
	<!-- Scores Page --> 
	<div id="scores">

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
				<div class="col-md-12" style = "background-color: #FFFFFF;">
					<div class = "col-md-12" style = "padding-top: 5%;">					
						<div id="navbar"></div>
						<div class ="row" style="text-align: center;">
							<h2> Scores </h2>
						</div>
						<div class ="row">
							<div class= "col-md-2">																				
							</div>
							<div class="col-md-8" style= "overflow-y: auto;">
									<ul class = "list-group" style = "width: 100%; padding: 5px; font-weight: bold; text-align: center; margin-right: auto; margin-left: auto;">
										<?php
										 for ($i = 0 ; $i < 10; $i++) { ?>
										 
										<div class = "row" style= "padding: 0px; margin: 5px; background-color: #C6E48B; border-width: 100%;">
													<div class = "col-md-2" style= "background-color: #C6E48B">
														<h3> <?php echo $i+1 ?> </h3>
													</div>
													<a href="profile.php?user=<?php echo $topScores[$i]['connectName'] ?>" style="color: black;">
														<div class = "col-md-8" style= "line-height: 50px;  background-color: #75C0C0;">
																<?php echo $topScores[$i]['connectName'] ?>
																<br/>																													
														
															<?php if ($fgmembersite->GetProfilePictureByConnectName($topScores[$i]['connectName']) != "") {
																echo '<img src = "' .$fgmembersite->GetProfilePictureByConnectName($topScores[$i]['connectName']).' " alt = "Profile Picture" style = "border-radius: 30%; margin-bottom: 2%; width: 100px; height: 100px;"/>';
															}?>
															
														</div>
													</a>
													<div class = "col-md-2" style= "line-height: 50px;">
														<h3> <?php echo $topScores[$i]['points']?> </h3>
													</div>
										</div>
										<?php
										} 
										?>										
									</ul>	
							</div>
							 <div class= "col-md-2">								
							 </div>
						</div>
						<hr style= "border-color: black">
						<div class ="row">
						</div>
					</div>
				</div>
		</div>		    
			
	</div>		
</div> <!-- Body Wrapper -->

	
	

	
  <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="dist/js/bootstrap.min.js"></script>
    <script src="assets/js/docs.min.js"></script>
	


	<script>
		$("#navbar").load("navbar.html");
	</script>
	
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="assets/js/ie10-viewport-bug-workaround.js"></script>
	</div>
  </body>
   <footer style="display: block; text-align: center;">
    <div class = "col-md-12"> <a href= "home.php" style = "padding-right: 20px;"> About Us </a>  <a href= "home.php" style = "padding-right: 20px;"> Contact </a>  <a href= "home.php"> FAQ </a>  </div>
		&copy;<script type="text/javascript">  document.write(new Date().getFullYear());</script> All Rights Reserved. </center> 
  </footer>
</html>
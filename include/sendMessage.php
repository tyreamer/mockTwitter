<?php

require_once("./include/membersite_config.php");

if(!$fgmembersite->CheckLogin())
{
    $fgmembersite->RedirectToURL("index.php");
    exit;
}

$valid = true;

if(isset($_POST['messageText']))
{				
	 $text = $_POST['messageText'];	
	if ($fgmembersite->SendMessage($text, $toUser)) {
		$fgmembersite->Redirect('profile.php?user='.$connectName, false);		
	}			
	else {
		echo 'Failed to send message: '. $text .' Please try again.';
	}
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
	<title>Leyff | Send Message</title>	
	
<link href="bootstrap-3.3.6-dist/css/fileinput.css" media="all" rel="stylesheet" type="text/css">
 <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>	
  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
  <!-- Optional theme -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">  
  <link rel="stylesheet" href="icomoon/style.css">
  
		<script>
		$(document).ready(function() {
			var text_max = 800;
			$('#textarea_feedback').html(text_max + ' characters remaining');

			$('#messageText').keyup(function() {
				var text_length = $('#messageText').val().length;
				var text_remaining = text_max - text_length;

				$('#textarea_feedback').html(text_remaining + ' characters remaining');
			});
		});
</script>
<style>
.nav .btn-default {
		height:34px;
	}
	
	  .nav li ul{
	display: none;			
  }

	.nav li:hover ul{
	display: block;
	position: absolute;
	padding: 10px;
	background-color: rgba(255,255,255,1);
	list-style-type: none;
	text-align: center;
	z-index: 15;
	}
		
	.nav li ul li {					
	padding: 5px 15px 5px 15px;
	}
		
	ul li ul li:hover {
	background-color: rgba(0,0,0,.2);
	}
	
	  div.circle-avatar{
		/* make it responsive */
		max-width: 100%;
		width:100%;
		height:auto;
		display:block;
		/* div height to be the same as width*/
		padding-top:100%;

		/* make it a cirkle */
		border-radius:50%;

		/* Centering on image`s center*/
		background-position-y: center;
		background-position-x: center;
		background-repeat: no-repeat;

		/* it makes the clue thing, takes smaller dimention to fill div */
		background-size: cover;

		/* it is optional, for making this div centered in parent*/
		margin: 0 auto;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
	}
  
</style>
</head>

<nav class="navbar navbar-default navbar-fixed-top"> 
	   <div class="container-fluid">
		  <!-- Brand and toggle get grouped for better mobile display -->
		  <div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="true">
			  <span class="sr-only">Toggle navigation</span>
			  <span class="icon-bar"></span>
			  <span class="icon-bar"></span>
			  <span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="home.php"><img src="./images/LeyffLogo.jpg" style="height: 50px; margin-top: -15px;"></a>
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
			  <li><a href="createPost.php"><span class="glyphicon glyphicon-leaf"></span></a></li>
			  <li><a href="notifications.php"><span class="glyphicon glyphicon-globe" 
							<?php if ($notificationCount > 0) 
									{echo 'style="color: lightblue;"';
								  } 
							?>></span></a></li>
			</ul>
		  </div><!-- /.navbar-collapse -->
		</div><!-- /.container-fluid -->
	</nav>
<div id="body-wrapper" style = "padding-bottom: 3%;">

	<div id="signup" style="background-color: rgba(0,0,0,.1)">
		 <nav class="navbar navbar-default">
						<div class="container">
							  <div class="navbar-header">
									<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
									  <span class="sr-only">Toggle navigation</span>
									  <span class="icon-bar"></span>
									  <span class="icon-bar"></span>
									  <span class="icon-bar"></span>
									</button>
									<a class="navbar-brand" href="home.html"><span class="orange">Leyff</span></a>
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
								<h3>New Message to <?php echo $connectName; ?></h3>				
							</div>							
							<div class="login" style = "text-align: center; ">
								   <form action = "" method = "POST" enctype = "multipart/form-data">	
									<label class="control-label"></label>
									<textarea class="form-control messageText" autofocus="autofocus" name ="messageText" rows="5" maxlength="800" id="messageText"></textarea>									
									<br/>
									<div id="textarea_feedback"></div>
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
   <footer style="display: block; text-align: center;">
    <div class = "col-md-12"> <a href= "home.html" style = "padding-right: 20px;"> About Us </a>  <a href= "home.html" style = "padding-right: 20px;"> Contact </a>  <a href= "home.html"> FAQ </a>  </div>
		&copy;<script type="text/javascript">  document.write(new Date().getFullYear());</script> All Rights Reserved. </center> 
  </footer>
</html>
<!----------------------------------->







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
<?php 
}

else {
	$fgmembersite->RedirectToURL("index.php");
}
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

//Check notifications & messages	
$notificationCount = $fgmembersite->GetNotificationAlerts();
$messageCount = $fgmembersite->GetMessageAlerts();


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
	<title>Leyff | About</title>	
	
	<link href="bootstrap-3.3.6-dist/css/fileinput.css" media="all" rel="stylesheet" type="text/css">
 <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>	
  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
  <!-- Optional theme -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">  
  <link rel="stylesheet" href="icomoon/style.css">
  <link rel="stylesheet" href="css/style.css">
		
</head>

		<div class="row" style="width: 100%; background-color: #fff">
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
	</div>
	
	<div id="alertUser" style="display: none; position: absolute; width: 100%; text-align: center;" class="alert alert-success">Successfully Updated!</div>
	
<div id="body-wrapper" style = "padding: 3%;">
	<!-- About Page --> 
	<div id="about">				 
		<div class="main">
			<div class="container col-md-6 col-md-offset-3" style="padding: 50px; margin-bottom: 5%; background-color: rgba(0,0,0,.1);  border: solid 1px white; border-radius: 20px;">	
				<div class="row">
					<center><h2> About </h2></center>
				</div>		
				<div class="row">
					<center><h5> Leyff is a directory of people's interests. </h5></center>
				</div>	
				<hr/>
				<div class="row">
					<center><h4> <u>Founders</u></h4></center>
				</div>
<br/>				
				<div class="row">
					<center><p> Mike Mortillaro</p></center>
					<center><p>Designer, Crazy</p></center>
				</div>	
				<br/>
				<div class="row">
					<center><p> Ty Reamer</p></center>
					<center><p>Code Wizard, Neo</p></center>
				</div>					
			</div>
			<div class="col-md-3">
			</div>
		</div>	
	</div>		
</div> <!-- Body Wrapper -->

  <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="bootstrap-3.3.6-dist/js/bootstrap.min.js"></script>

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
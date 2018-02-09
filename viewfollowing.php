<?php

require_once("./include/membersite_config.php");
if(!$fgmembersite->CheckLogin())
{	
 
}

$selectedUser = $_GET['cname'];

$currentUser = $fgmembersite->ConnectName();
$currentUserID = $fgmembersite->CurrentUser();

//Check this user exists
if (!$fgmembersite->GetUserFromConnectName($selectedUser)) {	
   $fgmembersite->RedirectToURL("index.php");
}

//Check notifications & messages	
$notificationCount = $fgmembersite->GetNotificationAlerts();
$messageCount = $fgmembersite->GetMessageAlerts();

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
	<title>Leyff | <?php echo $selectedUser ?>'s Followings </title>
	
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
	</div>

<body>
<div id="body-wrapper">	
	  <!-- Main Body Container  -->
	  <div class="col-sm-10 col-sm-offset-1" style="padding-top: 0%;">
		<div class="row" style="text-align: center; margin-top: 2%;"><h2><a style="color: black; font-weight: bold;" href="profile.php?user=<?php echo $selectedUser ?>"><?php echo $fgmembersite->GetNameFromConnectName($selectedUser) ?></a>'s Followings</h2></div>
		<hr/>
		<div class="col-sm-10 col-sm-offset-1">
		    <div class="row" id="followings">
			</div>
			<div class="more"></div>
		</div>
		<div class="col-sm-1"></div>
	  </div>	
</div> <!-- Body Wrapper -->

	
	

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>

	
	</div>
  </body>
  <div class="col-sm-12"></div><div id="footer"></div> </div>
   <script>
	   $(function() {
		   loadFollowings();
		  $("#footer").load("footer.html");		 
		 
		  $.ajaxSetup({
			async: false
		  });
		});
		
		function loadFollowings(){	

			var getLastID = '';			
			var user = '<?php echo $selectedUser; ?>';
			
			
			if (('.postSort') != undefined) {
				getLastID=$('.postSort:last').attr('id');
			}

			$.ajax({											
					type: "POST",
					url: "loadMoreFollowings.php",
					data: {user : user},
					cache: false,
					success: function(msg){
								$('#followings' ).append( msg );
								
								$('.more').text('Show more');
								
								if(!msg){
									
									$('.more').text('All followers have been loaded.').css({"cursor":"pointer"});
								}												
							}
				}); 
			 
						
		}
		
   </script>
   <script>
   $(function() {
		   jQuery.noConflict();
		   var benchmark = 0;
			var totalPostSort = 0;
			var i = 0;
			$(window).scroll(function() {
				
				var wh = $(window).height();
				var scrollheight = $(document).scrollTop();
				
				if (scrollheight > (wh * .50)) 
				{
					if (scrollheight >= benchmark) 
					{												
						
						//Make sure there is more to load
						if (($('.postSort').length > totalPostSort) || i==0) 
						{										
								//increment count of number of loads
								i++;
								
								//set a new high to test off
								benchmark = $(document).scrollTop();
								
								//loadFollowings
								setTimeout(loadFollowings, 500);	
								
								//Get the new count
								var count = $('.postSort').length - totalPostSort;
								totalPostSort = totalPostSort + count;
						}
					}								
				}	
		   
   });
   </script>
</html>
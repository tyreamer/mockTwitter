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

$user = $fgmembersite->CurrentUser();

$fgmembersite->RemoveNotificationAlerts();

//Check notifications & messages	
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
	<title>Leyff | Notifications</title>

	<link href="bootstrap-3.3.6-dist/css/fileinput.css" media="all" rel="stylesheet" type="text/css">
 <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>	
  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
  <!-- Optional theme -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">  
  <link rel="stylesheet" href="icomoon/style.css">


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

.more {
		margin-bottom: 15%;
	}

 	
	.more:hover {
		cursor: pointer;
		background-color: rgba(0,0,0,.2) !important;
		color: white;
	}

.nav .btn-default {
		height:34px;
	}

   .nav li ul
		{
			display: none;			
		}

	.nav li:hover ul
		{
			display: block;
			position: absolute;
			padding: 10px;
			background-color: rgba(0,0,0,.1);
			list-style-type: none;
			text-align: center;
		}
		
	.nav li ul li {					
			padding: 5px 15px 5px 15px;
	}
		
	ul li ul li:hover
	{
		background-color: rgba(0,0,0,.2);
	}
	
	.glyphicon-trash:hover{
		cursor: pointer;
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
	
	  @media(max-width:767px){
		  #notifications {
			  padding-top: 10%;
		  }
		  
		  .mobileBreak {
			  display: block !important;
		  }
		  
	  }
	
</style>

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
	
	
<body onload="loadNotifications()" style="padding-top:5%;">
<div id="body-wrapper" style = "padding-bottom: 3%;">
	<!-- Scores Page --> 
	<div id="notifications">
	
	<?php
		
		$notifications = $fgmembersite->GetNotifications($connectName);
	?>
					
		<div class="main">
				<div class="col-md-12" style = "background-color: #FFFFFF;">
					<div class = "col-md-12">					
						
						<div class ="row" style="text-align: center;">
							<h2> Your Notifications </h2>
						</div>
						<div class ="row">
							<div class= "col-md-4">																				
							</div>
							<div class="col-md-4" style= "overflow-y: auto;">
								<ul class = "list-group" style = "width: 100%; padding: 5px; text-align: center; margin-right: auto; margin-left: auto;">
									<div id="notificationsList">
									</div>
									<div class="col-md-12 more" onclick="loadNotifications()" id="loadMore" style="text-align: center; padding: 2%; background-color: rgba(0,0,0,.1)"><h4 style="width: 40%; margin-left: auto; margin-right: auto;"></h4></div>																			
								</ul>
							</div>
						</div>
					</div>
				</div>
		</div>		    
			
	</div>		
</div> <!-- Body Wrapper -->

	
	
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	


	<script>
		function loadNotifications(){	

				var getLastID = '';			
				var user = '<?php echo $user; ?>';
				
				if (('.postSort') != undefined) {
					getLastID=$('.postSort:last').attr('id');
				}
					
				 $.ajax({											
							type: "POST",
							url: "loadMoreNotifications.php",
							data: {sort : getLastID, user : user},
							cache: false,
							success: function(msg){
									
										$(' #notificationsList' ).append( msg );
										
										$('.more').text('Show more');
										
										if(!msg){
											
											$('.more').text('All notifications have been loaded.').css({"cursor":"pointer"});
										}												
									}
						}); 
			 }
	
	</script>
	<script>
				 
		 $(function () {							
					var benchmark = 0;
					var totalNotificationSort = 0;
					var i = 0;
					$(window).scroll(function() {
						
						var wh = $(window).height();
						var scrollheight = $(document).scrollTop();
						
						if (scrollheight > (wh * .70)) 
						{
							if (scrollheight >= benchmark) 
							{	
								//Make sure there is more to load
								if (($('.postSort').length > totalNotificationSort) || i==0) 
								{										
										//increment count of number of loads
										i++;
										
										//set a new high to test off
										benchmark = $(document).scrollTop();
										
										//loadNotifications
										setTimeout(loadNotifications, 500);	
										
										//Get the new count
										var count = $('.postSort').length - totalNotificationSort;
										totalNotificationSort = totalNotificationSort + count;
								}
							}								
						}	
					});
				});
	</script>
	
	
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
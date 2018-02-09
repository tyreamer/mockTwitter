<?php
require_once("./include/membersite_config.php");

if(!($fgmembersite->CheckLogin()))
{
	$loggedIn = false;
}	
else {
	$loggedIn = true;
}


//How are we  searching
if (isset($_GET['type'])) {
	
	if ($_GET['type'] == 3) {
		//Shelves
		$searchType = 3;
	}	
	else if ($_GET['type'] == 2) {
		//Profiles
		$searchType = 2;
	}
	else if ($_GET['type'] == 1) {
		//Random
		$searchType = 1;
	}
	else {
		//Live
		$searchType = 0;
	}
}
else {
	//Live
	$searchType = 0;
}

//make sure there is a search query
if ($_GET['qry'] == '') {	
	$fgmembersite->RedirectToURL('home.php');
}

//Check notifications & messages	
$notificationCount = $fgmembersite->GetNotificationAlerts();
$messageCount = $fgmembersite->GetMessageAlerts();

$servername = "localhost";
$username = "tylre";
$password = "tylrePass";
$db = "YellowstoneDB1";

// Create connection
$conn = new mysqli($servername, $username, $password, $db);


if (isset($_GET['qry'])) {	
	
		$key= addslashes($_GET['qry']);

		if ($key != "") {
			
			//Find all people (by name) related to this search
			$sql = "Select connectName, id_user from person where name LIKE '%".$key."%' ;";

			if(!$result = $conn->query($sql)){		

					die('There was an error running the query [' . mysqli_error($conn) . ']');
					return false;
			}
			
			while ($row = mysqli_fetch_assoc($result)) {	
					
				$names[] =  $row['connectName'];				
			}	
			
			//Find all people (by connect name) related to this search
			$sql = "Select connectName, id_user from person where connectName LIKE '%".$key."%' ;";

			if(!$result = $conn->query($sql)){		

					die('There was an error running the query [' . mysqli_error($conn) . ']');
					return false;
			}
			
			while ($row = mysqli_fetch_assoc($result)) {	
					
				$connectNames[] =  $row['connectName'];				
			}	

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
  <link rel="icon" href="favicon.ico">
  <title>Leyff | Results</title>

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

    .nav li ul
		{
			display: none;			
		}
		
		.nav .btn-default {
		height:34px;
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
	
	.more {
		margin-bottom: 15%;
		display: none;
	}
	
		.more:hover {
		cursor: pointer;
		background-color: rgba(0,0,0,.2) !important;
		color: white;
	}
	
	 .btn-group {
	 width: 100%;
	 padding-top: .5%;		 
 }
 
 .btn-group h4 {
	 display: inline;
	 padding: 1% 2% 1% 2%;
	 font-weight: bold;
 }
 
  
 .btn-group h4:hover {
	 background-color: rgba(0,0,0,.1);
	 cursor: pointer;
 }
  
  .square {
    float:left;
    position: relative;
    width: 132%;
    padding-bottom : 132%;
    margin:1.66%;
    display: inline;
    background-color: white;
    overflow:hidden;
  }


  .content {
    position:absolute;
    height:80%;
    width:80%;
    padding: 4% 10% 10% 10%;
  }
  
  .content a {
	  color: #fff;
  }
  
  .table{
    display:table;
    width:100%;
    height:100%;
  }
  .table-cell{
    display:table-cell;
    vertical-align:middle;
  }


  .content .rs{
    width:auto;
    height:auto;
    max-height:80%;
    max-width:100%;
    float: left;
  }

  .bg{
    width: 90%;
    height: 80%;
    float: left;
    border-radius: 1em;
    background-position: center center;
    background-repeat: no-repeat;
    border: solid;
    background-size:cover;
  }

  .links {
    position: absolute;
    left: 85%;
    bottom: 20%;
    float: right;
  }

  .squareLinkItem {
    padding-bottom: 4px;
    background-color: #fff;
  }

  .squareLinkItem p {
    padding-bottom: 0px;
    margin-bottom: 0px;
  }

  .postName {
    text-align: center;
    width: 75%;
    font-weight: bold;
  }

  .postDetails{
    text-align: center;
    width: 80%;
    height: auto;
    line-height: 15px;
    max-width:80%;
    position: absolute;
    bottom: 25%;
    background-color: #fff;
  }

.myPhoto {
      border:1px solid #fff;
    border-radius: 90px;
    resize: both;
    width:50px;
    height:50px;
    position: absolute;
    top: 55%;
    left: 1%;
    z-index: 5;
  }

  .glpyhLg {
    font-size: 2em;
  }

  .favoriteBtnColored {
    color: orange;
  }

  body { padding-top: 50px; }
  
  .upvote:hover {
	  cursor: pointer;
  }
  
  .downvote:hover {
	  cursor: pointer;
  }
  
   .modal-dialog {
	  position: absolute;
	  width: 450px;
	  height: 200px;
	  top: 50%;
	  left: 47%;
	  margin: -120px 0 0 -200px;
  }
  
  .modalItem {
	  display: inline-block;
	  text-align: center;
	  font-size: 2em;
	  padding: 2% 5% 2% 5%;
  }
  
  a:hover {
	  text-decoration: none;
  }
  
  .modalItem:hover {
	  background-color: rgba(0,0,0,.2);
	  border-radius: 10px;
  }

  @media(max-width:767px){

    body { padding-top: 50px; }

    .square {
      float:left;
      position: relative;
      right: 5%;
      width: 150%;
      padding-bottom : 132%;
      margin:1.66%;
      display: inline;
      background-color: white;
      overflow:hidden;
    }

    .postDetails{
      text-align: center;
      width: 60%;
      height: auto;
      line-height: 15px;
      max-width:80%;
      top: 75%;
      left: 7%;
      background-color: rgba(255, 255, 255, 1);;
      z-index: 4;
    }

    .content .rs{
      width:auto;
      height:auto;
      max-height:80%;
      max-width:100%;
      float: left;
    }

    .bg{
      width: 88%;
      padding-bottom: 88%;
      float: left;
      border-radius: 1em;
      background-position: center center;
      background-repeat: no-repeat;
      border: solid;
      background-size:cover;
    }

    .links {
      position: absolute;
      left: 85%;
      top: 5%;
      float: right;
    }

    .squareLinkItem {
      padding-bottom: 3px;
      background-color: #fff;
    }

    .squareLinkItem p {
      padding-bottom: 0px;
      margin-bottom: 0px;
    }

    .postName {
      position: relative;
      left: -8%;
      top: -10%;
      text-align: center;
      width: 90%;
      font-weight: bold;
      padding-top: 10px;
    }

    .myPhoto {
      border:1px solid #fff;
      border-radius: 90px;
      resize: both;
      width:30px;
      height:30px;
      margin-top: -15px;
      margin-left: -15px;
      top: 70%;
      left: 37%;
      z-index: 5;
    }
  }
  @media(min-width:768px){

    .square {
      float:left;
      position: relative;
      width: 132%;
      padding-bottom : 100%;
      margin:1.66%;
      display: inline;
      background-color: white;
      overflow:hidden;
    }

    .postDetails{
      text-align: center;
      width: 54%;
      height: auto;
      line-height: 15px;
      max-width:80%;
      top: 83%;
      left: 10%;
      background-color: rgba(255, 255, 255, 1);;
      z-index: 4;
    }

    .content .rs{
      width:auto;
      height:auto;
      max-height:80%;
      max-width:100%;
      float: left;
    }

    .bg{
      width: 88%;
      padding-bottom: 88%;
      float: left;
      border-radius: 1em;
      background-position: center center;
      background-repeat: no-repeat;
      border: solid;
      background-size:cover;
    }

    .links {
      position: absolute;
      left: 85%;
      top: 5%;
      float: right;
    }

    .squareLinkItem {
      padding-bottom: 4px;
      background-color: #fff;
    }

    .squareLinkItem p {
      padding-bottom: 0px;
      margin-bottom: 0px;
    }

    .postName {
      position: relative;
      left: -9%;
      text-align: center;
      width: 90%;
      font-weight: bold;
    }

    .myPhoto {
      border:1px solid #fff;
      border-radius: 90px;
      resize: both;
      width:40px;
      height:40px;
      margin-top: -20px;
      margin-left: -20px;
      top: 77%;
      left: 37%;
      z-index: 5;
    }

  }

  @media(min-width:992px){

    .square {
      float:left;
      position: relative;
      width: 132%;
      padding-bottom : 132%;
      margin:1.66%;
      display: inline;
      margin-top: 10px;
      background-color: white;
      overflow:hidden;
    }

    .postDetails{
      text-align: center;
      width: 54%;
      height: auto;
      line-height: 15px;
      max-width:100%;
      top: 71%;
      left: 10%;
      background-color: rgba(255, 255, 255, 1);;
      z-index: 4;
    }

    .content .rs{
      width:auto;
      height:auto;
      max-height:80%;
      max-width:100%;
      float: left;
    }

    .bg{
      width: 88%;
      padding-bottom: 88%;
      float: left;
      border-radius: 1em;
      background-position: center center;
      background-repeat: no-repeat;
      border: solid;
      background-size:cover;
    }

    .links {
      position: absolute;
      left: 85%;
      top: 5%;
      float: right;
    }

    .squareLinkItem {
      padding-bottom: 4px;
      background-color: #fff;
    }

    .squareLinkItem p {
      padding-bottom: 0px;
      margin-bottom: 0px;
    }

    .postName {
      position: relative;
      left: -9%;
      text-align: center;
      width: 90%;
      font-weight: bold;
    }

   .myPhoto {
      border:1px solid #fff;
      border-radius: 90px;
      resize: both;
      width:40px;
      height:40px;
      margin-left: -20px;
      margin-top: -20px;
      top: 64%;
      left: 37%;
      z-index: 5;
    }

  }

  @media(min-width:1200px){

    .square {
      float:left;
      position: relative;
      width: 132%;
      padding-bottom : 132%;
      margin:1.66%;
      display: inline;
      background-color: white;
      overflow:hidden;
    }

    .postDetails{
      text-align: center;
      width: 54%;
      height: auto;
      line-height: 15px;
      max-width:100%;
      top: 70%;
      left: 10%;
      background-color: rgba(255, 255, 255, 1);;
      z-index: 4;
    }

    .content .rs{
      width:auto;
      height:auto;
      max-height:80%;
      max-width:100%;
      float: left;
    }

    .bg{
      width: 88%;
      padding-bottom: 88%;
      float: left;
      border-radius: 1em;
      background-position: center center;
      background-repeat: no-repeat;
      border: solid;
      background-size:cover;
    }

    .links {
      position: absolute;
      left: 85%;
      top: 5%;
      float: right;
    }

    .squareLinkItem {
      padding-bottom: 4px;
      background-color: #fff;
    }

    .squareLinkItem p {
      padding-bottom: 0px;
      margin-bottom: 0px;
    }

    .postName {
      position: relative;
      left: -9%;
      text-align: center;
      width: 90%;
      font-weight: bold;
    }

   .myPhoto {
      border:1px solid #fff;
      border-radius: 90px;
      resize: both;
      width:40px;
      height:40px;
      margin-top: -20px;
      margin-left: -20px;
      top: 63%;
      left: 37%;
      z-index: 5;
    }

  }

  @media(min-width:1440px){

    .square {
      float:left;
      position: relative;
      width: 132%;
      padding-bottom : 132%;
      margin:1.66%;
      display: inline;
      background-color: white;
      overflow:hidden;
    }

    .postDetails{
      text-align: center;
      width: 54%;
      height: auto;
      line-height: 15px;
      max-width:100%;
      top: 70%;
      left: 10%;
      background-color: rgba(255, 255, 255, 1);;
      z-index: 4;
    }

    .content .rs{
      width:auto;
      height:auto;
      max-height:80%;
      max-width:100%;
      float: left;
    }

    .bg{
      width: 88%;
      padding-bottom: 88%;
      float: left;
      border-radius: 1em;
      background-position: center center;
      background-repeat: no-repeat;
      border: solid;
      background-size:cover;
    }

    .links {
      position: absolute;
      left: 85%;
      top: 5%;
      float: right;
    }

    .squareLinkItem {
      padding-bottom: 4px;
      background-color: #fff;
    }

    .squareLinkItem p {
      padding-bottom: 0px;
      margin-bottom: 0px;
    }

    .postName {
      position: relative;
      left: -9%;
      text-align: center;
      width: 90%;
      font-weight: bold;
    }

  .myPhoto {
      border:1px solid #fff;
      border-radius: 90px;
      resize: both;
      width:40px;
      height:40px;
      margin-top: -20px;
      margin-left: -20px;
      top: 62%;
      left: 37%;
      z-index: 5;
    }

  }

  @media(min-width:1920px){

    .square {
      float:left;
      position: relative;
      width: 132%;
      padding-bottom : 132%;
      margin:1.66%;
      display: inline;
      background-color: white;
      overflow:hidden;
    }

    .postDetails{
      text-align: center;
      width: 54%;
      height: auto;
      line-height: 15px;
      max-width:100%;
      top: 65%;
      left: 10%;
      background-color: rgba(255, 255, 255, 1);;
      z-index: 4;
    }

    .content .rs{
      width:auto;
      height:auto;
      max-height:80%;
      max-width:100%;
      float: left;
    }

    .bg{
      width: 88%;
      padding-bottom: 88%;
      float: left;
      border-radius: 1em;
      background-position: center center;
      background-repeat: no-repeat;
      border: solid;
      background-size:cover;
    }

    .links {
      position: absolute;
      left: 85%;
      top: 5%;
      float: right;
    }

    .squareLinkItem {
      padding-bottom: 4px;
      background-color: #fff;
    }

    .squareLinkItem p {
      padding-bottom: 0px;
      margin-bottom: 0px;
    }

    .postName {
      position: relative;
      left: -9%;
      text-align: center;
      width: 90%;
      font-weight: bold;
    }

  .myPhoto {
      border:1px solid #fff;
      border-radius: 90px;
      resize: both;
      width:40px;
      height:40px;
      margin-top: -20px;
      margin-left: -20px;
      top: 61%;
      left: 37%;
      z-index: 5;
    }

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
  

    .link {
      float: left;
      padding: 0px 10px 0px 10px;
    }

    .nav {
      font-size: 18px;
    }

  </style> 
  
  <script>
	
		//UPVOTE/DOWNVOTE FUNCTIONALITY
		function Upvote(el, post_id){				
				
				var upvoteID = '.upvote' + post_id;
				var downvoteID = '.downvote' + post_id;
				
				//Check if we already upvoted this
				var $c= rgb2hex($(el).css("color"));
				if ($c == '#000000' ) {	
				  var upvote = 1;
					 $.ajax
					({ 
						url: 'updatePoint.php',
						 type: "POST",
						  data: "post_id=" + post_id +"&upvote=" + upvote,
						  success: function(msg){
							$(upvoteID).css('color', '#66ff00');
							$(downvoteID).css('color', '#000');
						  }
					}); 
				}
				
				else {
					 $.ajax
					({ 
						url: 'deletePoint.php',
						 type: "POST",
						  data: "post_id=" + post_id,
						  success: function(msg){
							$(upvoteID).css('color', '#000');
							$(downvoteID).css('color', '#000');
						  }
					}); 
				}
				
			}

		function Downvote(el, post_id){
				
				var upvoteID = '.upvote' + post_id;
				var downvoteID = '.downvote' + post_id;
				
				//Check if we already upvoted this
				var $c= rgb2hex($(el).css("color"));
			
				if ($c == '#000000' ) {			
					var upvote = 0;						
					$.ajax		  
					({ 
						url: 'updatePoint.php',
						 type: "POST",
						  data: "post_id=" + post_id +"&upvote=" + upvote,
						  success: function(msg){
							  $(upvoteID).css('color', '#000');
							 $(downvoteID).css('color', '#ff0000');
						  }
					});
				}
				
				else {
					
					 $.ajax
					({ 
						url: 'deletePoint.php',
						 type: "POST",
						  data: "post_id=" + post_id,
						  success: function(msg){
							$(upvoteID).css('color', '#000');
							$(downvoteID).css('color', '#000');
						  }
					}); 
				}
		}
		
		function rgb2hex(rgb) {
			if (/^#[0-9A-F]{6}$/i.test(rgb)) return rgb;

			rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
			function hex(x) {
				return ("0" + parseInt(x).toString(16)).slice(-2);
			}
			return "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
		}
		
		function removePost(post_id){	
								var confirmed = confirm('Delete this post?');		
								var postDiv = "#" + post_id;
								var modal = ".modal-backdrop";								
								
									if (confirmed){					 
										 $.ajax
										({ 
											url: 'deletePost.php',
											 type: "POST",
											  data: "pid=" + post_id,
											  success: function(msg){
												 $(modal).hide();		
												 $('body').css('overflow', 'auto');
												 $(postDiv).remove();	
											  }
										}); 
									 }
								}
  </script>
  
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


	
	
<body onload="loadPosts()">
		<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.6&appId=1596248447282369";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

 window.fbAsyncInit = function() {
		FB.init({
		  appId      : '1692957604301627',
		  xfbml      : true,
		  version    : 'v2.6'
		});
	  };	
	 </script>
	
	
<div class="col-md-12">
	<div class="col-md-2">
	</div>
	<div class ="col-md-8">
		<div class="col-md-4" style="padding-left: 0;">		
		</div>
		<div class="col-md-4" style="text-align: center">
			<h3 style= " padding: 10px; "> Search Results </h3>
		</div>
		<div class="col-md-4">
				<br/>
		</div> 
	</div>
	<div class="col-md-2"></div>
</div>
	

  <div class="col-md-4 col-md-offset-4" id = "sort" style="text-align: center;">
    <div class="btn-group" id="toggle_search_type">
      <div class="btn-group" id="toggle_search_type">
   		<h4 type="button" id="searchLive" <?php  if ($searchType == 0) { echo 'style= "text-shadow: 2px 2px lightblue;"'; } ?>>Live</h4>
		<h4 type="button" id="searchRandom"  <?php  if ($searchType == 1) { echo 'style= "text-shadow: 2px 2px lightblue;"';} ?>>Spotlight</h4>
		<h4 type="button" id="searchPeople"  <?php  if ($searchType == 2) { echo 'style= "text-shadow: 2px 2px lightblue;"'; } ?>>Store</h4>
		<h4 type="button" id="searchShelves"  <?php  if ($searchType == 3) { echo 'style= "text-shadow: 2px 2px lightblue;"'; } ?>>Shelf</h4>
	  </div>
    </div>
  </div>
  <div class = "col-md-4" style="padding-bottom: 15px;">  
  </div>
  <hr/>


  <!-- Main Body Container  -->
  <div class="col-md-12" style="margin-top:2%;">
    <div class="col-md-10 col-md-offset-1">
	   <div class="row" id="posts">

			<!-- Search Results -->
			
		</div>
		<div class="col-md-12 more" onclick="loadPosts()" style="text-align: center; padding: 2%; background-color: rgba(0,0,0,.1)"><h4 style="width: 40%; margin-left: auto; margin-right: auto;">Show More</h4></div>
	</div>
	</div>
</div>

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
                  <script>
					$(function () {
						
						
						var benchmark = 0;
						var totalPostSort = 0;
						var i = 0;
						$(window).scroll(function() {
							
							var wh = $(window).height();
							var scrollheight = $(document).scrollTop();
							console.log(wh);
							console.log(scrollheight);
							if (scrollheight > (wh * .30)) 
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
										
										//loadPosts
										setTimeout(loadPosts, 500);	
										
										//Get the new count
										var count = $('.postSort').length - totalPostSort;
										totalPostSort = totalPostSort + count;
									}
								}								
							}	
						});
						
						
						
							//TOGGLE Search
							$('#searchLive').click(function(){
								window.location.href = 'search.php?qry=<?php echo $key ?>&type=0';								
							});	
							
							$('#searchRandom').click(function(){
								window.location.href = 'search.php?qry=<?php echo $key ?>&type=1';								
							});	
							
							$('#searchPeople').click(function(){
								window.location.href = 'search.php?qry=<?php echo $key ?>&type=2';								
							});	
							
							$('#searchShelves').click(function(){
								window.location.href = 'search.php?qry=<?php echo $key ?>&type=3';								
							});	
																
					});
					
						
					function loadPosts(){		
												
							var getLastID = '';
							var qry = '<?php echo $key;?>';
							var searchType = '<?php echo $searchType; ?>';

							if (('.postSort') != undefined) {
								getLastID=$('.postSort:last').attr('id');
							}
						
							if (searchType == 0) {
								
									$.ajax({											
										type: "POST",
										url: "loadMoreLiveSearchPosts.php",
										data: {sort : getLastID, qry : qry},
										cache: false,
										success: function(msg){
												
													$(' #posts' ).append( msg );
													
													$('.more').text('Show more');
												
													if(!msg){
														
														$('.more').text('All posts have been loaded.').css({"cursor":"pointer"});
													}												
												}
									}); 
							} 		
							
							if (searchType == 1) {
									$.ajax({											
										type: "POST",
										url: "loadMoreRandomSearch.php",
										data: {sort : getLastID, qry : qry},
										cache: false,
										success: function(msg){
												
													$(' #posts' ).append( msg );
													
													$('.more').text('Show more');
												
													if(!msg){
														
														$('.more').text('All posts have been loaded.').css({"cursor":"pointer"});
													}												
												}
									}); 
							} 						
							
							
							if (searchType == 2) {
									
									$.ajax({											
												type: "POST",
												url: "loadMoreUsers.php",
												data: {sort : getLastID, qry : qry},
												cache: false,
												success: function(msg){
														
															$(' #posts' ).append( msg );
															
															$('.more').text('Show more');
														
															if(!msg){
																
																$('.more').text('All people have been loaded.').css({"cursor":"pointer"});
															}												
														}
											}); 
							} 			
							
							if (searchType == 3) {
									
									$.ajax({											
												type: "POST",
												url: "loadMoreShelves.php",
												data: {sort : getLastID, qry : qry},
												cache: false,
												success: function(msg){
														
															$(' #posts' ).append( msg );
															
															$('.more').text('Show more');
														
															if(!msg){
																
																$('.more').text('All shelves have been loaded.').css({"cursor":"pointer"});
															}												
														}
											}); 
							} 			
							
						};

					</script>

                
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
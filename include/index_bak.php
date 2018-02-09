<?PHP
require_once("./include/membersite_config.php");

if(isset($_POST['submitted']))
{
   if($fgmembersite->Login())
   {
        $fgmembersite->RedirectToURL("home.php");
   }
}

$loggedIn = false;

//How are we sorting
if ($_GET['sort'] == 1 || $_GET['sort'] == "") {
	$live = true;
}
else {
	$live = false;
}

if($fgmembersite->CheckLogin())
{
	$loggedIn = true;
	$fgmembersite->RedirectToURL("home.php");
}


	$servername = "localhost";
	$username = "tylre";
	$password = "tylrePass";
	$db = "YellowstoneDB1";


	// Create connection
	$conn = new mysqli($servername, $username, $password, $db);

?>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="icon" href="favicon.ico">
  <title>Leyff | Home</title>

<link href="bootstrap-3.3.6-dist/css/fileinput.css" media="all" rel="stylesheet" type="text/css">
 <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>	
  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
  <!-- Optional theme -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">  
  <link rel="stylesheet" href="icomoon/style.css">

  <style>

  * {
	margin: 0;
}
html, body {
	height: 100%;
}
.wrapper {
	min-height: 100%;
	height: auto !important; /* This line and the next line are not necessary unless you need IE6 support */
	height: 100%;
	margin: 0 auto -155px; /* the bottom margin is the negative value of the footer's height */
}
.footer, .push {
	height: 155px; /* .push must be the same height as .footer */
}
  
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
    bottom: 30%;
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
    color: yellow;
  }

  body { padding-top: 50px; }

  @media(max-width:767px){

  	  .nav {
			margin-top:5%;
			display: block;
			width: 100%;
	  }
	  
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
      top: 0%;
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
      top: 0%;
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
      top: 0%;
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
      top: 0%;
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
      top: 0%;
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
      top: 0%;
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

.link {
      float: left;
      padding: 0px 10px 0px 10px;
    }

    .nav {
      font-size: 18px;
    }
	
	.nav .btn-default {
		height: 34px;
	}
  </style>
</head>


<body>
   
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
          <li><a href="login.php" id="login"><button type="button" class="btn btn-info">Login | Sign Up</button></a></li>
          <li><a href="login.php"><span class="glyphicon glyphicon-leaf"></span></a></li>
          <li><a href="login.php"><span class="glyphicon glyphicon-globe"></span></a></li>
          <li><a href="login.php"><span class="glyphicon glyphicon-envelope"></span></a></li>
        </ul>
      </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
	</div>
	</nav>

  <div class="col-md-12">
  <hr/>
    <div class="col-md-3 col-md-offset-1">	
		<p>
		<i>Create collections about your interests to express who you are! Explore what interests your friends and the world have!</i>
		</p>
	
	</div>
	
    <div class="col-md-4" style="text-align: center">
      <h2 class="glyphicon glyphicon-home" style="text-align:center;">  </h2>
    </div>
	<div class="col-md-4" style="text-align: right">     
    </div>	
  </div>

  <!-- Main Body Container  -->
  <div class="col-md-12">
  <hr/>
    <div class="col-md-10 col-md-offset-1">
	      <div class="row">
	<?php
	
	$allPosts = $fgmembersite->GetAllRandomPosts();
	
	//If there were no posts within the past 24hrs, display the top posts ever
	if (sizeOf($allPosts) == 0) {		
		$allPosts=$fgmembersite->GetAllTopPosts();	
	}
	
	
		
		//Go through each post
		for ($i = 0; $i < sizeOf($allPosts); $i++) {
			
			$post_id = $allPosts[$i]['post_id'];

			//Post Information
			$sql = "SELECT * FROM post where post_id = '" .$post_id."'";
			if(!$result = $conn->query($sql)){
					echo $sql;
					die('There was an error running the post query [' . mysqli_error($conn) . ']');
			}
										
			$row = mysqli_fetch_assoc($result);	
				
			//Make sure we have info
			if (empty($row)) {
				echo 'no post found ' . $sql;
				 $fgmembersite->RedirectToURL("index.php");
				exit;
			}
			//Get Post Specifics
			$postText =  $row['post_text'];	
			$postImage = $row['post_image'];
			$userID   =  $row['author_id'];
			$time	  =  $row['time'];
			
			if ($postImage == "") {
				$postImage = "images/LinkDefaultImg.jpg";
			}

			$myPost = false;
			$currVal = '0';
			$upvoteBackground = '#fff';
			$downvoteBackground = '#fff';
			$favorited = false;
			$myID = NULL;
		
				
			//Author Details
			$sql = "Select connectName from person where id_user = '".$userID."' ";
			if(!$result = $conn->query($sql)){
					echo $sql;
					die('There was an error running the email query [' . mysqli_error($conn) . ']');
			}
										
			$row = mysqli_fetch_assoc($result);	
				
			//Make sure we have author's info
			if (empty($row)) {
				echo 'no connectName found';
				 $fgmembersite->RedirectToURL("index.php");
				exit;
			}

			$connectName=  $row['connectName'];	

			//Author's Picture
			$sql = "Select photo_location from photo where user_id=".$userID." and photo_type='1';";
			if(!$result = $conn->query($sql)){
					echo $sql;
					die('There was an error running the email query [' . mysqli_error($conn) . ']');
			}
										
			$row = mysqli_fetch_assoc($result);	
			$userPicture=  $row['photo_location'];	
			
			//Default author picture
			if (empty($row)) {
				$userPicture = 'images/myPicture.jpg';
			}
			
			?>


        <div class="col-xs-6 col-md-3"> 
          <div class="square">
					<div class= "postName"><a href="post.php?pid=<?php echo $post_id ?>" target="_blank"> <?php echo $allPosts[$i]['post_text'] ?> </a></div>
					<div class="content">
						<a href="go.php?id=<?php echo $post_id ?>&url=<?php echo $allPosts[$i]['post_link']?>" target="_blank">
							<div class= "bg" style="background-image: url(<?php echo $allPosts[$i]['post_image'] ?>)"></div>
						</a>		   
						<div class= "links">
							<div class="squareLinkItem"><p style="color: black"><?php echo $allPosts[$i]['views'] ?></p></div>
							
							<div class="squareLinkItem" id = "upvote"> <span class="glyphicon glyphicon-thumbs-up" style = "background-color: <?php echo $upvoteBackground ?>;"> </div>
							<div class="squareLinkItem" id = "downvote"> <span class="glyphicon glyphicon-thumbs-down" style = "background-color: <?php echo $downvoteBackground ?>;"> </div>
							
							<div class="squareLinkItem"><p style ="color:green;"> <?php echo $allPosts[$i]['post_points']?> </p></div>
							<div class="squareLinkItem"><a href="http://www.facebook.com/sharer.php?s=100&p[title]=<?php echo $allPosts[$i]['post_text']; ?>&p[url]=<?php echo "http://www.leyff.com/v2/post.php?pid=$post_id"; ?>&p[caption]=<?php echo $allPosts[$i]['post_text']; ?>&p[description]=<?php echo $allPosts[$i]['post_text']; ?>&p[images][0]=<?php echo $allPosts[$i]['post_image']; ?>" target="_blank"><i class="glyphicon glyphicon-share"></i></a></div>
						 </div>
					</div>
					<div>
						<a href="profile.php?user=<?php echo $connectName ?>"><img src = "<?php echo $fgmembersite->GetProfilePictureByConnectName($connectName)?>" style="color: black" alt = "My Picture" class= "myPhoto"/></a>
					</div>
					<div class="postDetails">
						<?php echo $fgmembersite->GetNameFromConnectName($connectName); ?>
						<br/>
						<?php echo $fgmembersite->humanTiming(strtotime($time)); ?>
					</div>
				 </div>
			  </div>
			  	
			<?php 							
				}
						
			?>
		</div>
	</div>
	</div>
	<div class="col-md-1"></div>
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

<script>
var d = new Date();
document.getElementById("date").innerHTML += d.getFullYear();
</script>
				<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
                 
                  </html>


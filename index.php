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
  <link rel="stylesheet" href="css/style.css">

</head>


<body>
   
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
					  <li><a href="login.php" id="login">Login | Sign Up</a></li>
					  <li><a href="login.php"><span class="glyphicon glyphicon-globe"></span></a></li>
					</ul>
				  </div><!-- /.navbar-collapse -->
				</div><!-- /.container-fluid -->

		</nav>
	</div>

<div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2" style="padding-top: 1%;">
  <div class="col-md-12">

    <div class="col-md-4">	
		<p>
			<i>Crypto Stores: 1. Create a virtual store 2. Post ads on the bitroad.org then link them to your store 3. Display, organize, share, and spread products and services for cryptocurrency. 4. Build reputation</i>
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


			<div class="col-xs-12 col-sm-6 col-md-3"> 
				<div class="square">
					<div class="postDetailsHead">
						<div class= "postLocation"><a href="#" target="_blank"> Post location </a></div>
						<div class= "postCost"><a href="#" target="_blank"> Post cost </a></div>
					</div>
					<div class="content">
						<a href="go.php?id=<?php echo $post_id ?>&url=<?php echo $allPosts[$i]['post_link']?>" target="_blank">
							<div class= "bg" style="background-image: url(<?php echo $allPosts[$i]['post_image'] ?>)"></div>
						</a>		   
						<div class= "links">
							<div class="squareLinkItem" id = "upvote"> <span class="glyphicon glyphicon-thumbs-up" style = "background-color: <?php echo $upvoteBackground ?>;"> </div>
						</div>
					</div>
					<div>
						<a href="profile.php?user=<?php echo $connectName ?>">
							<img src = "<?php echo $fgmembersite->GetProfilePictureByConnectName($connectName)?>" style="color: black" alt = "My Picture" class= "myPhoto"/>
						</a>
					</div>
					<div class="postDetails">
						<div class= "postName"><a href="post.php?pid=<?php echo $post_id ?>" target="_blank"> <?php echo $allPosts[$i]['post_text'] ?> </a></div>
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
	
</div>

                    

                  </body>
                  <div class="col-xs-12"><div id="footer"></div> </div>
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


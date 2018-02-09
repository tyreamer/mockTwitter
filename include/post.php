<?php

require_once("./include/membersite_config.php");

if(!$fgmembersite->CheckLogin())
{
	$loggedIn = false;
}
else {
	$loggedIn = true;
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


$post_id = $_GET['pid'];

//Post Information
$sql = "SELECT * FROM post where post_id = '" .$post_id."'";
if(!$result = $conn->query($sql)){
		echo $sql;
		die('There was an error running the email query [' . mysqli_error($conn) . ']');
}
							
	$row = mysqli_fetch_assoc($result);	
	
	//Make sure we have info
	if (empty($row)) {
		 $fgmembersite->RedirectToURL("index.php");
		exit;
	}
		//Get Post Information
		$postText	 =  $row['post_text'];	
		$userID   	 =  $row['author_id'];
		$time	 	 =  $row['time'];
		$views	 	 =  $row['views'];
		$postLink	 =  $row['post_link'];
		$postImage	 =  $row['post_image'];
		$post_points =  $row['post_points'];
		
		if ($loggedIn) {
				//Get My ID
				$sql = "Select id_user from person where email = '".$_SESSION['email_of_user']."' ";
				if(!$result = $conn->query($sql)){
						echo $sql;
						die('There was an error running the ID query [' . mysqli_error($conn) . ']');
				}
										
				$row = mysqli_fetch_assoc($result);	
				
				//Make sure we have info
				if (empty($row)) {
					 $fgmembersite->RedirectToURL("index.php");
					exit;
				}
			
					//Get ID Information
					$myID =  $row['id_user'];	


				if ( strcmp($userID,$myID) == 0) {
					$myPost = true;
				}

				else {
					$myPost = false;
				}
		}
		else {
			$myPost = false;
			$myID = '';
		}

//User Details
$sql = "Select connectName from person where id_user = '".$userID."' ";
if(!$result = $conn->query($sql)){
		echo $sql;
		die('There was an error running the email query [' . mysqli_error($conn) . ']');
}
							
	$row = mysqli_fetch_assoc($result);	
	
//Make sure we have info
if (empty($row)) {
	echo 'no connectName found';
	 $fgmembersite->RedirectToURL("index.php");
    exit;
}

$connectName=  $row['connectName'];	

//User Picture
$sql = "Select photo_location from photo where user_id=".$userID." and photo_type='1';";
if(!$result = $conn->query($sql)){
		echo $sql;
		die('There was an error running the email query [' . mysqli_error($conn) . ']');
}
							
	$row = mysqli_fetch_assoc($result);	

$userPicture=  $row['photo_location'];	

if (empty($row)) {
	$userPicture = 'images/myPicture.jpg';
}


foreach ($postArr as $key => $val) {
   echo $val;
}

if ($loggedIn) {
		//Do we favorite this?
		$sql = "Select favorite_id from `favorite` where sender_id=".$myID." and post_id=".$post_id.";";

		if(!$result = $conn->query($sql)){
				die('There was an error running the query [' . mysqli_error($conn) . ']');
		}
									
			$row = mysqli_fetch_assoc($result);	

		$favorited = true;

		if (empty($row)) {
			$favorited = false;
		} 

		//Do we favorite this?
		$sql = "Select upvote from `point` where sender_id=".$myID." and post_id=".$post_id.";";

		if(!$result = $conn->query($sql)){
				die('There was an error running the query [' . mysqli_error($conn) . ']');
		}
									
		$row = mysqli_fetch_assoc($result);	

		//Assign colors to thumbs-up and thumbs-down
		if (($row['upvote'] == '1')) {
			$currVal = '1';
			$upvoteBackground = '#66ff00';
			$downvoteBackground = '#000';
		} 

		else if ($row['upvote'] == '0'){	
			$currVal = '-1';
			$upvoteBackground = '#000';
			$downvoteBackground = '#ff0000';
		}

		else {
			$currVal = '0';
			$upvoteBackground = '#000';
			$downvoteBackground = '#000';
		}
}

else {
	$favorited = false;
	$upvoteBackground = '#000';
	$downvoteBackground = '#000';
}


$totalComments = $fgmembersite->GetTotalComments($post_id);


/* Variables

$post_id
$userID
$connectName
$postText
$time
$userPicture
$myPost
$upvoted
*/

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="favicon.ico">
	<meta property="og:image" content="<?php echo $postImage; ?>" />
	<meta property="og:title" content="<?php echo $postText; ?>" />
	<title>Leyff | View Post </title>
	
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

<body onload="loadComments()">
	<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.6&appId=1596248447282369";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<div id="body-wrapper" style = "background-color: #fff; height: 100%;">
	<!-- Post Page --> 
	<div id="post" style="margin-top: 5%;">

		<div class="main">
				<div class="col-md-12" style = "background-color: #FFFFFF;">
					<div class ="row" style="text-align: center;">
							<h2> <?php echo $postText ?></h2>
						
							<div class="col-xs-8 col-xs-offset-2 col-md-4 col-md-offset-4">
								<div class="square">
									<div class="content">
										<a class="postLink" href="go.php?id=<?php echo $post_id ?>&url=<?php echo $postLink ?>" target="_blank">
											<div class= "bg" style="background-image: url(<?php echo $postImage ?>)"></div>
										</a>			   
										<div class= "links">
												<div class="squareLinkItem"><p style="color: black; text-align: center;"><?php echo $views ?></p></div>					
												<div class="squareLinkItem"> <span class="glyphicon glyphicon-thumbs-up upvote upvote<?php echo $post_id ?>"" onclick="Upvote(this,<?php echo $post_id ?>)" id="upvote" style = "color: <?php echo $upvoteBackground ?>;"> </div>
												<div class="squareLinkItem"> <span class="glyphicon glyphicon-thumbs-down downvote downvote<?php echo $post_id ?>"" id="downvote" onclick="Downvote(this,<?php echo $post_id ?>)" style = "color: <?php echo $downvoteBackground ?>;"> </div>			
												<div class="squareLinkItem"><p style ="color:green; text-align: center;"> <?php echo $post_points?> </p></div>
												<?php //If we're logged in we can favorite this	
															if($favorited) { 
																echo '<a href="favoritePost.php?pid='.$post_id.'&uid='.$myID.'">';
															}
															else {
																echo '<a href="selectcollection.php?pid='.$post_id.'">';
															}
												?>
												<div class="squareLinkItem"> <span class="glyphicon <?php 
																		
																		//Do we favorite this?
																		if($favorited) { 
																			echo 'glyphicon-heart favoriteBtnColored';
																		}
																		
																		else {
																			echo 'glyphicon-heart-empty';
																		}
																		
															?>			" > </div></a>
												<a href="http://www.facebook.com/sharer.php?s=100&p[title]=<?php echo $postText; ?>&p[url]=<?php echo "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>&p[images][0]=<?php echo $postImage; ?>&p[caption]=<?php echo $postText; ?>&p[description]=<?php echo $postText; ?>;" target="_blank"><i class="glyphicon glyphicon-share"></i></a>
										 </div>
									</div>
									<div>
										<a href="profile.php?user=<?php echo $connectName ?>"><img src = "<?php echo $fgmembersite->GetProfilePictureByConnectName($connectName)?>" style="color: black" alt = "My Picture" class= "myPhoto"/></a>
								
										<div class="postDetails">
											<?php echo $fgmembersite->GetNameFromConnectName($connectName); ?>
											<br/>
											<?php echo $fgmembersite->humanTiming(strtotime($time)); ?>
										</div>
									</div>
								 </div>
							</div>							
						</div>

						
						<div class="comments col-xs-12 col-md-4 col-md-offset-4" style="text-align: center;">
							<ul class = "list-group" style = "width: 100%; padding: 5px; font-weight: bold; background-color: #e6f3f7; text-align: center; margin-right: auto; margin-left: auto;">
													<div class="row" style="text-align: center;"> 
														<div class="login col-md-8 col-md-offset-2" style = "text-align: center; ">															   
															   <form action = "" method = "POST" enctype = "multipart/form-data">	
																	<label class="control-label"></label>
																	<input type="text" class="form-control commentText" placeholder="Comment..." name ="commentText" rows="1" id="commentText" required></textarea>									
																	<br/>
																	<div id="textarea_feedback"></div>
																											
																	<input type="submit" name="submit" class = "btn btn-large btn-info" id="submit" style = "display: none;">									 
																</form>
														</div>
													</div>
								<div id="col-md-12" style="text-align: center;"><p style="font-size: 10px; font-weight: normal;">Total Comments: <?php echo $totalComments ?></p></div>
								<div id="comments">
								
								</div>
								<?php if ($totalComments > 12) {?><div class="col-md-12 more" onclick="loadComments()" id="loadMore" style="text-align: center; padding: 2%; margin: 1% 0% 1% 0%; background-color: rgba(0,0,0,.1)"><h4 style="width: 40%; margin-left: auto; margin-right: auto;"></h4></div><?php } ?>
							</ul>
							<div class= "row">
									
							</div>
						</div>
				</div>
		</div>		    
			
	</div>		
</div> <!-- Body Wrapper -->

  </body>
	

	
 <!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	
<script>
		$("#navbar").load("navbar.html");
 		
			function loadComments(){	

			var getLastID = '';			
			var post = '<?php echo $post_id; ?>';
			
			if (('.commentSort') != undefined) {
				getLastID=$('.commentSort:last').attr('id');
			}
			
			 $.ajax({											
						type: "POST",
						url: "loadMoreComments.php",
						data: {sort : getLastID, post_id : post},
						cache: false,
						success: function(msg){
								
									$(' #comments' ).append( msg );
									
									$('.more').text('Show more');
									
									if(!msg){
										
										$('.more').text('All comments have been loaded.').css({"cursor":"pointer"});
									}												
								}
					}); 
			 }
		
		function removeComment(comment_id){	
			var confirmed = confirm('Delete this comment?');		
			var commentDiv = "#" + comment_id;
				if (confirmed){					 
					 $.ajax
					({ 
						url: 'deleteComment.php',
						 type: "POST",
						  data: "cid=" + comment_id,
						  success: function(msg){
							 $(commentDiv).remove();
						  }
					}); 
				 }
			}
			
			
			$(function() {
				$('form').each(function() {
					$(this).find('input').keypress(function(e) {
						// Enter pressed?
						if(e.which == 10 || e.which == 13) {
							this.form.submit();
						}
					});

					$(this).find('input[type=submit]').hide();
				});
			});
	
	
  //UPVOTE/DOWNVOTE FUNCTIONALITY
		function Upvote(el, post_id){				
				
				if (<?php echo $loggedIn?>) {
				
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
				
			}

		function Downvote(el, post_id){
				
				if (<?php echo $loggedIn?>) {
						
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
		}
		
		function rgb2hex(rgb) {
			if (/^#[0-9A-F]{6}$/i.test(rgb)) return rgb;

			rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
			function hex(x) {
				return ("0" + parseInt(x).toString(16)).slice(-2);
			}
			return "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
		}

</script>
	<script>
					  window.fbAsyncInit = function() {
						FB.init({
						  appId      : '1692957604301627',
						  xfbml      : true,
						  version    : 'v2.6'
						});
					  };
	</script>
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
<?PHP
require_once("./include/membersite_config.php");

$loggedIn = true;

//How are we sorting
if ($_GET['sort'] == 1 || $_GET['sort'] == "") {
	$live = true;
}
else {
	$live = false;
}

if(!($fgmembersite->CheckLogin()))
{
	$loggedIn = false;
	$fgmembersite->RedirectToURL("index.php");
}	

$servername = "localhost";
$username = "tylre";
$password = "tylrePass";
$db = "YellowstoneDB1";


// Create connection
$conn = new mysqli($servername, $username, $password, $db);	
	
//Check notifications & messages	
$notificationCount = $fgmembersite->GetNotificationAlerts();
$messageCount = $fgmembersite->GetMessageAlerts();
		
?>

<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta property="og:url"           content="http://www.leyff.com/" />
  <meta property="og:type"          content="website" />
  <meta property="og:title"         content="Leyff" />
  <meta property="og:description"   content="Sharing the best of the web." />
  <meta property="og:image" content="http://leyff.com/v2/images/LinkDefaultImg.jpg" />

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
						  data: {post_id: post_id, upvote: upvote},
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
						  data: {post_id: post_id, upvote: upvote},
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
}(document, 'script', 'facebook-jssdk'));</script>

<div class="col-sm-10 col-sm-offset-1">
	<div class="col-md-2">
	</div>
	<div class ="col-md-8">
		<div class="col-md-4" style="padding-left: 0;">	
			
		</div>
		<div class="col-md-4" style="text-align: center">
			
			<h2 style= " padding: 10px; "> <i class="glyphLg glyphicon glyphicon-home"> </i> </h2>
		</div>
		<div class="col-md-4">
				<br/>
		</div> 
	</div>
	<div class="col-md-2"></div>
</div>
	

  <div class="col-md-4 col-md-offset-4" id = "sort" style="text-align: center;">
    <div class="btn-group" id="toggle_event_editing">
      <div class="btn-group" id="toggle_event_editing">
		<h4 type="button" class="<?php if ($live) { echo 'locked_active'; } else { echo 'unlocked_inactive'; }?>" <?php if ($live) { echo 'style= "text-shadow: 2px 2px lightblue;"'; } ?>  ><p class="glyphicon glyphicon-user" style="margin: 0; padding: 0px 5px 0px 0px;"></p>Following</h4>	 
		<h4 type="button" class="<?php if (!($live)) { echo 'locked_active'; } else { echo 'unlocked_inactive'; }?>" <?php if (!$live) { echo 'style= "text-shadow: 2px 2px lightblue;"'; } ?> ><p class="glyphicon glyphicon-thumbs-up" style="margin: 0; padding: 0px 5px 0px 0px;"></p>Activity</h4>			
	  </div>
    </div>
  </div>
  <div class = "col-md-4" style="padding-bottom: 15px;">  
  </div>


  <!-- Main Body Container  -->
<div class="main col-sm-10 col-sm-offset-1"> 
 <div class="col-md-12">
 
    <div class="col-md-10 col-md-offset-1">
	   <div class="row" id="posts">   
	   
		</div>
		<div class="col-md-12 more" onclick="loadPosts()" style="text-align: center; padding: 2%; background-color: rgba(0,0,0,.1)"><h4 style="width: 40%; margin-left: auto; margin-right: auto;">Show More</h4></div>
	</div>	
	<div class="col-md-1"></div>
 </div>
 <!-- Posts -->
	<div class="col-md-12" id="noPosts">
		<div class="col-md-10 col-md-offset-1">
				<div class="alert alert-warning" style="text-align: center;">Nobody you follow seems to be <?php if ($live) { echo 'posting'; } else { echo 'liking posts'; }?>...  try <b>searching for more items and stores</b> above. <br/>We've automatically populated your timeline below with our <b>most popular Leyffs</b> from the past 24 hours</div>
				  <div class="row">
	<?php
	
	
	$allPosts = $fgmembersite->GetAllRandomPosts();
	
	//If there were no posts within the past 24hrs, display the top posts ever
	if (sizeOf($allPosts) == 0) {		
		$allPosts=$fgmembersite->GetAllTopPosts();	
	}
	
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
						$postImage = "images/1462239593IMG_3262.jpg";
					}

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

						//Did we upvote this?
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
					
					//If we're not logged in
					else {
						$myPost = false;
						$currVal = '0';
						$upvoteBackground = '#000';
						$downvoteBackground = '#000';
						$favorited = false;
						$myID = NULL;
					}

						
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

		   
				<div class="col-xs-6 col-md-3" id="<?php echo $post_id ?>">
				  <div class="square">
							<div class= "postName"><a href="post.php?pid=<?php echo $post_id ?>" target="_blank"> <?php echo $allPosts[$i]['post_text'] ?> </a></div>
							<div class="content">
								<a class="postLink" href="go.php?id=<?php echo $post_id ?>&url=<?php echo $allPosts[$i]['post_link']?>" target="_blank">
									<div class= "bg" style="background-image: url(<?php echo $allPosts[$i]['post_image'] ?>)"></div>
								</a>
												
								<!-- Modal -->
								<div class="modal fade" id="myModal<?php echo $post_id;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-body">	
												<div class= "container" style="max-width: 100%; text-align: center;">
													<?php 
																if ($loggedIn) {
																	echo '<a href= "post.php?pid='.$post_id.'"> ';
																}
													?>
													<div class="modalItem">													
															<span class="glyphicon glyphicon-comment"></span>													
													</div>
													<?php 
																if ($loggedIn) {  
																	echo '</a>' ;
																	
																//If we're logged in we can favorite this	
																	if($favorited) { 
																		echo '<a href="favoritePost.php?pid='.$post_id.'&uid='.$myID.'">';
																	}
																	else {
																		echo '<a href="selectcollection.php?pid='.$post_id.'">';
																	} 
																}
													?> 
													<div class="modalItem">													
															<span class="glyphicon												
													<?php 
																
																//Do we favorite this?
																if($favorited) { 
																	echo 'glyphicon-star favoriteBtnColored';
																}
																
																else {
																	echo 'glyphicon-star-empty';
																}
																
													?>			"></span>
													</div>											
															
													<?php 
																if ($loggedIn) {  
																	echo '</a>' ;
																}		
													?>	
													<div class="modalItem">													
													<div class="squareLinkItem"><a href="http://www.facebook.com/sharer.php?s=100&p[title]=<?php echo $allPosts[$i]['post_text']; ?>&p[url]=<?php echo "www.leyff.com/v2/post.php?pid=$post_id"; ?>&p[caption]=<?php echo $allPosts[$i]['post_text']; ?>&p[description]=<?php echo $allPosts[$i]['post_text']; ?>&p[images][0]=<?php echo $allPosts[$i]['post_image'];?>;" target="_blank"><i class="glyphicon glyphicon-share"></i></a></div>										
													</div>	
													<?php 														
																if ($myPost){														
																	echo '
																		
																				<div class="modalItem">													
																					<span class="glyphicon glyphicon-trash" onclick="removePost('. $post_id. ')"></span>													
																				</div>														
																	';
																}															
													?>										
												</div>										
											</div>
										</div>
									</div>
								</div>		
												   
								<div class= "links">
													   <div class="squareLinkItem"><p style="color: black; text-align: center;"><?php echo $allPosts[$i]['views'] ?></p></div>					
										   <div class="squareLinkItem"> <span class="glyphicon glyphicon-thumbs-up upvote upvote<?php echo $post_id ?>"  onclick="Upvote(this,<?php echo $post_id ?>)"  style = "color: <?php echo $upvoteBackground ?>;"> </div>
										   <div class="squareLinkItem"> <span class="glyphicon glyphicon-thumbs-down downvote downvote<?php echo $post_id ?>" onclick="Downvote(this,<?php echo $post_id ?>)" style = "color: <?php echo $downvoteBackground ?>;"> </div>			
													   <div class="squareLinkItem"><p style ="color:green; text-align: center;"> <?php echo $allPosts[$i]['post_points']?> </p></div>
									<a href="#myModal<?php echo $post_id?>" data-toggle="modal"><div class="squareLinkItem"> <span class= "glyphicon glyphicon-option-horizontal" style ="color:green;"> </span></div></a>
								 </div>
							</div>
							<div>
								<a href="profile.php?user=<?php echo $connectName ?>"><img src = "<?php echo $fgmembersite->GetProfilePictureByConnectName($connectName)?>" style="color: black" alt = "My Picture" class= "myPhoto"/></a>
							</div>
							<div class="postDetails">
								<?php echo $fgmembersite->GetNameFromConnectName($connectName); ?>
								<br/>
								<?php 	echo $fgmembersite->humanTiming(strtotime($time)); ?>
							</div>
						 </div>
					  </div>
						
					<?php 				
					
					/* Variables

					$post_id
					$userID
					$connectName
					$postText
					$time
					$userPicture
					$myPost
					$upvoted
					$loggedIn
					$postImage
					$postPoints
					$postViews
					*/
	}//for
						
			?>
		</div>
	</div>
	</div>
</div> <!-- Main -->

	
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
							
							if (scrollheight > (wh * .70)) 
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
						
						
							setTimeout(showMessage, 1000);
							
							function showMessage() {
								if ($('.postSort').length == 0){									
									$('#noPosts').show();
									$('.more').hide();
								}
							}
							
							//TOGGLE LIVE VS ADVENTURE
							$('#toggle_event_editing h4').click(function(){
								
								/* its set to adventure */
								if($(this).hasClass('locked_active') || $(this).hasClass('unlocked_inactive')){
										<?php if ($live) { ?> window.location.href = 'home.php?sort=0'; <?php } else {?>  window.location.href = 'home.php?sort=1'; <?php } ?>
								}
								
								/* it's live */
								else{
									/* code to do when locking */
									$('#switch_status').html('live');
										
								}
								
								/* reverse locking status */
								$('#toggle_event_editing button').eq(0).toggleClass('locked_inactive locked_active btn-default btn-info');
								$('#toggle_event_editing button').eq(1).toggleClass('unlocked_inactive unlocked_active btn-info btn-default');
							});	
																
					});
					
					
						
						function loadPosts(){		
																				
							var getLastID = '';
							if (('.postSort') != undefined) {
								getLastID=$('.postSort:last').attr('id');
							}

								
							<?php if ($live) { ?>
									 $.ajax({											
												type: "POST",
												url: "loadFollowedPosts.php",
												data: "sort="+getLastID,
												cache: false,
												success: function(msg){
														
															$(' #posts' ).append( msg );
															
															$('.more').text('Show more');
															
															if(!msg){
																
																$('.more').text('All leyffs have been loaded.').css({"cursor":"pointer"});
															}												
														}
											}); 
							<?php } ?>
							
							<?php if (!$live) { ?>
									 $.ajax({											
												type: "POST",
												url: "loadFollowerLikedPosts.php",
												data: "sort="+getLastID,
												cache: false,
												success: function(msg){
														
															$(' #posts' ).append( msg );
															
															$('.more').text('Show more');
															
															if(!msg){
																
																$('.more').text('All leyffs have been loaded.').css({"cursor":"pointer"});
															}												
														}
											}); 
							<?php } ?>		
						};
						
						
						
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

  </body>
   <script>
	   $(function() {
		  $("#footer").load("footer.html");		 
		 
		  $.ajaxSetup({
			async: false
		  });
		});
   </script>
                  </html>


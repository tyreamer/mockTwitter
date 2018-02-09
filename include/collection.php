<?PHP
require_once("./include/membersite_config.php");

$loggedIn = true;


//Are we logged in
if(!($fgmembersite->CheckLogin()))
{
	$loggedIn = false;
}
else {
	$loggedIn = true;
}

//make sure we have a Collection ID
if (!(isset($_GET['id']))) 
{
	$fgmembersite->RedirectToURL('home.php');
}
else 
{
	$cid = $_GET['id'];
}

//Check notifications & messages	
$notificationCount = $fgmembersite->GetNotificationAlerts();
$messageCount = $fgmembersite->GetMessageAlerts();


//Collection Info
$allPosts = $fgmembersite->GetPostsByCollection($cid);
$collection = $fgmembersite->GetCollectionInfo($cid);
$collectionName = $collection['collection_name'];
$userProfile = $fgmembersite->GetConnectName($collection['collection_owner']);
$currentUser = $fgmembersite->ConnectName();
	
	$servername = "localhost";
	$username = "tylre";
	$password = "tylrePass";
	$db = "YellowstoneDB1";

	// Create connection
	$conn = new mysqli($servername, $username, $password, $db);
	


//Check this user exists
if (!$fgmembersite->GetUserFromConnectName($userProfile)) {
   $fgmembersite->RedirectToURL("index.php");
}

//Is this our page?
$isMyPage = false;

if ($userProfile != $fgmembersite->ConnectName()) {
		$isMyPage = false;
}
else {
	$isMyPage = true;
	$myCollections = $fgmembersite->myCollections();
}

if ($isMyPage) {
	$redirect = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
   if(isset($_FILES['collectionbackground'])){
      $errors= array();
      $file_name = $_FILES['collectionbackground']['name'];
      $file_size = $_FILES['collectionbackground']['size'];
      $file_tmp = $_FILES['collectionbackground']['tmp_name'];
      $file_type = $_FILES['collectionbackground']['type'];
      $file_ext=strtolower(end(explode('.',$_FILES['collectionbackground']['name'])));
      
      $expensions= array("jpeg","jpg","png");
      
      if(in_array($file_ext,$expensions)=== false){
         $errors[]="Please choose a JPEG or PNG file.";
      }
      
      if(empty($errors)==true) {
			
			//Get our user ID
			$userID =  $fgmembersite->GetUserFromConnectName($userProfile);
								
			$imageName = time(). $file_name ;
			
			//Get current Image
			$photoQry = "Select collection_background from collection where collection_id= '" . $cid. "'";  									
			if(!$result2 = $conn->query($photoQry)){
				die('There was an error running the query [' . $conn->error . ']');
			}					
			$row2 = mysqli_fetch_assoc($result2);	
			 
			$update_query = 'Update collection SET collection_background = "images/'.$userID.'/'. $imageName . '" WHERE collection_id = "' . $cid . '"';
		 
				if(!$insert = $conn->query($update_query))
				{			
					$fgmembersite->RedirectToURL($redirect, false);
					return false;
				}  
								
				$path = "images/".$userID;
				
				if ( ! is_dir($path)) {
					mkdir($path);
				}
				move_uploaded_file($file_tmp, $path.'/'.$imageName);	
				
				//Remove the old photo
				unlink($row2['collection_background']);
			
			$fgmembersite->RedirectToURL($redirect, false);
			return true;
										
      }
	  
	  else{
         for ($i = 0; $i<sizeOf($errors); $i++) {
			 echo  '<script language="javascript">'; echo 'alert("'.$errors[$i].'")'; echo '</script>'; 
		 }
      }
   }
}

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
  <meta property="og:image:type" content="image/jpeg" />
	  <link rel="icon" href="favicon.ico">
	  
	<title>Leyff | <?php echo  $collectionName; ?></title>
	
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
	<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.6&appId=1596248447282369";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

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


<div class="row" style="text-align: center; margin-top: 2%;">
<h2><strong style="margin-right: 15px;"><?php echo $collectionName ?></strong><?php if($isMyPage) { echo '<a href = "#myRenameModal" data-toggle="modal"><button type="button" class="btn btn-sm btn-default btn-circle"><i class="glyphicon glyphicon-pencil"></i></button></a>';} ?></h2> 
<p>By: <a href="profile.php?user=<?php echo $userProfile; ?>"><?php echo $fgmembersite->GetNameFromConnectName($userProfile) ?></a> </p>
</div>

<!-- RenameCollectionModal -->
						<div class="modal fade" id="myRenameModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-body">	
										<div class= "container" style="max-width: 100%; text-align: center;">
											<?php 
														if ($isMyPage) {
															
											?>
												<div class="modalItem">													
													<div class="input-group" style="border-right-style:none;">													
													<div class="alert alert-danger" id="text-error" style="display: none;"></div>
														<form method="POST" action="renameCollection.php" enctype = "multipart/form-data">
															<input type="text" name = "collection_name" id="renamecollection" class="form-control" placeholder="Rename collection..."/>
															<input type="hidden" name="collection_id" value="<?php echo $cid; ?>"/>
															<input class="form-control" type="submit" value="Enter"></input>	
														</form>														
													</div>								
												</div>
											<?php 
														}															
											?>
										</div>										
									</div>
								</div>
							</div>
						</div>
						
<!-- Picture CollectionModal -->
						<div class="modal fade" id="myPictureModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-body">	
											<?php 
														if ($isMyPage) {
															
											?>												
													
														<div style="text-align: center;">
															<h3>New background for:<br/> <?php echo $collectionName; ?></h3>				
														</div>							
														<div style = "text-align: center; ">
															   <form action = "" id="form" method = "POST" enctype = "multipart/form-data">	
																<label class="control-label"></label>
																<input type = "file" class="file" name = "collectionbackground" />
																<br/>
																 <img src="images/loading.gif" id="gif" style="margin: 0 auto; display:none; visibility: hidden;">
																<input type="submit" name="submit" class = "btn btn-large" id="submit" value="Submit" style = "background-color: #75C0C0; width: 100%;"></p>									 
															  </form>
														</div>
													
											<?php 
														}															
											?>									
									</div>
								</div>
							</div>
						</div>

					

  <!-- Main Body Container  -->
  

  <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2" id="main" style="padding-top: 2%;">
   
	   <div class="row">
	<?php
			if (sizeOf($allPosts) == 0) {		
				echo '<div class="row" style="text-align:center; background-color: #fff; margin: 5% 0% 5% 0%; padding: 2%;">
							No posts have been added yet.
					  </div>';	
			}
			
			else {
				
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
						}
					}	
	?>
		</div>
	</div>	
	</div>
</div>




	
 <!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
  
			
<script>

		$('#form').submit(function() {
			$('#gif').css('visibility', 'visible');
			$('#gif').css('display', 'block');
			 $(this).find('input[type=submit]').prop('disabled', true);
			return true;
		});
		
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
</script>
	
<script type='text/javascript'>
		var frmvalidator  = new Validator("login");
		frmvalidator.EnableOnPageErrorDisplay();
		frmvalidator.EnableMsgsTogether();

		frmvalidator.addValidation("connectName","req","Please provide your connect name.");
		
		frmvalidator.addValidation("password","req","Please provide the password");

		})

	  window.fbAsyncInit = function() {
		FB.init({
		  appId      : '1692957604301627',
		  xfbml      : true,
		  version    : 'v2.6'
		});
	  };		
	
</script>

	
</body>
  <div class="col-sm-12">
		<div id="footer"></div>
	   </div>	
   <script>
	   $(function() {
		  $("#footer").load("footer.html");		 
		 
		  $.ajaxSetup({
			async: false
		  });
		});
   </script>
</html>
<?PHP
require_once("./include/membersite_config.php");

$loggedIn = true;

//How are we sorting
if ($_GET['sort'] == 1) {
	$posts = true;
}
else {
	$posts = false;
}

//Are we logged in
if(!($fgmembersite->CheckLogin()))
{
	$loggedIn = false;
}
else {
	$loggedIn = true;
	}


//Check notifications & messages	
$notificationCount = $fgmembersite->GetNotificationAlerts();
$messageCount = $fgmembersite->GetMessageAlerts();

//Get the user profile
if (empty($_GET)) {
	$userProfile = $fgmembersite->ConnectName();
}
else {
	
$userProfile = $_GET['user'];

}

$currentUser = $fgmembersite->ConnectName();

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
}




	$servername = "localhost";
	$username = "tylre";
	$password = "tylrePass";
	$db = "YellowstoneDB1";


	// Create connection
	$conn = new mysqli($servername, $username, $password, $db);
	
	
//Bitcoin address
$sql = "SELECT * FROM person where id_user = '" .$fgmembersite->GetUserFromConnectName($userProfile)."'";
if(!$result = $conn->query($sql)){
echo $sql;
die('There was an error running the post query [' . mysqli_error($conn) . ']');
}
					
$row = mysqli_fetch_assoc($result);	
$bitcoinaddress = rtrim($row['bitcoin_address']);

//Location
$location = $row['location'];


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
	  
	<title>Leyff | <?php echo  $userProfile . "'s Store" ?></title>
	
	
	
	  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
     <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	
<link href="bootstrap-3.3.6-dist/css/fileinput.css" media="all" rel="stylesheet" type="text/css">

	
  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

  <!-- Optional theme -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
  
  <link rel="stylesheet" href="icomoon/style.css">
  <link rel="stylesheet" href="css/style.css">

  <style>
	.modal .modalItem:hover {
		cursor: pointer;
	}
  </style>
  
</head>


<body onload="loadPosts();">



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


<div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2" style="padding: 0%;"> 
	<div class ="col-md-12 myBg1" style= "background-image: url('<?php echo $fgmembersite->GetBackgroundImage($fgmembersite->GetUserFromConnectName($userProfile));?>');margin: 0; padding: 0% 0% 0% 0%; overflow: hidden;">	
		<div class="col-md-12 myInfo">
			<div class="col-md-12 details" style="margin: 0; padding: 0;">
				<div class="details-wrapper" style="padding-top: 1%;">
					<a href="#main" style="color: white; " >
						<div class="col-sm-2 col-xs-3">
							<p>Items <br/><span style=";"><?php echo $fgmembersite->humanNumbers($fgmembersite->GetPostCount($userProfile)) ?></span>	</p>
						</div>
					</a>
					<a href="viewfollowers.php?cname=<?php echo $userProfile ?>">
						<div class="col-sm-2 col-xs-3">
							<p>Followers  <br/><?php echo $fgmembersite->humanNumbers($fgmembersite->GetFollowerCount($userProfile)) ?> </p>
						</div>
					</a>
					<a href="viewfollowing.php?cname=<?php echo $userProfile ?>">
						<div class="col-sm-2 col-xs-3">
							<p>Following  <br/><span style=""><?php echo $fgmembersite->humanNumbers($fgmembersite->GetFollowingCount($userProfile)) ?></span> </p>
						</div>
					</a>
				</div>
			</div>
		</div>
	</div>

<div style="max-width:900px; position:relative;" class="container ppic"> 
<div class="ppContainer1">
	<img class= "profilePic1" src= "<?php echo $fgmembersite->GetProfilePictureByConnectName($userProfile); ?>"/>
	<h4 style="text-align: center;">My Crypto Store</h4>
</div> <div class="clearfix"></div></div>
<div class="mySubject" style="text-align: center;">	
	<div style="background-color: rgba(0,0,0,.2); width: 100%; font-weight: bold;"> 
		<?php 
				//Store name
				echo $userProfile;
				if ($location != '') 
				{
					echo ' - '. $location;
				}
				
				else if ($location == '' && $isMyPage) {
					echo ' - <a href="editProfile.php">Set your location</a>';
				}
		?> 
	</div>
</div>

</div>


<div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2" id="heading" style="padding-top: 1%;">
		<div class="col-md-4 col-sm-4 col-xs-12" id="pagetitle" style="text-align: right; padding: 0px;">
			
		</div>
		  <div class="col-md-4 col-sm-4 col-xs-12" id = "sort" style="padding: 5px 0px 0px 0px;text-align: center;">
			<div class="btn-group" id="toggle_event_editing">
			  <div class="btn-group" id="toggle_event_editing">	
					<h4 type="button" class="<?php if (!($posts)) { echo 'locked_active'; } else { echo 'unlocked_inactive'; }?>" <?php if (!$posts) { echo 'style= "text-shadow: 2px 2px lightblue;"'; } ?>  >Shelf</h4>	
					<h4 type="button" class="<?php if ($posts) { echo 'locked_active'; } else { echo 'unlocked_inactive'; }?>" <?php if ($posts) { echo 'style= "text-shadow: 2px 2px lightblue;"'; } ?>  >Leyffs</h4>
			  </div>
			</div>
		  </div>

		  <div class = "col-md-4 col-sm-4 col-xs-12" style="padding: 0px 0px 15px 0px;">  
			<div class="interactions" style="text-align: left;"> 
					<?php
						//Make sure it's not our profile 
						if (!$isMyPage && $loggedIn) {
					?> 
													
					<?php 
							$user = $fgmembersite->GetUserFromConnectName($userProfile);
							$follower = $fgmembersite->GetUserFromConnectName($fgmembersite->ConnectName());		

							$isFollowing = $fgmembersite->IsFollowing($user, $follower);
						
							//If not following
							if ($isFollowing == 0) {
					?>
					
								<a href= "follow.php?user=<?php echo $user ?>&follower=<?php echo $follower ?>&following=0"> 
									<div class ="btn icon-user-plus" style="color: rgba(0,0,0,.5); font-size: 20px;" title="Follow <?php echo $userProfile;?>"> </div>
								</a>
							
					<?php
							}
							
							else {
					?>
								<a href= "follow.php?user=<?php echo $user ?>&follower=<?php echo $follower ?>&following=1"> 
									<div class ="btn icon-user-minus"  style="color: lightblue; font-size: 20px;" title="Unfollow <?php echo $userProfile;?>"> </div>
								</a>
							
					<?php 
							}
					?>
							
							
					<?php	
						}																		
					?>
							
							<div style="display: inline;">
								<div class ="btn glyphicon glyphicon-ok bitcoinBtn" style="color: rgba(0,0,0,.5); font-size: 20px;" title="<?php echo $bitcoinaddress; ?>">
									<div class="alert alert-success" id="bcCopied" style="text-align: center; z-index: 100;">  Copied! </div>
									<?php if ($bitcoinaddress != '') { ?>
										<div id="bitcoinAddress">
											<input type="text" id="bca" readonly value="<?php echo $bitcoinaddress ?>">	<button class="glyphicon  glyphicon-duplicate" onclick="copyToClipboard('#bca')" style="display: inline; float:right; margin-top: -1px; height: 33px;" id="copyAddress" title="Copy"></button>	</input>
										
										</div>
									<?php } ?>
								</div>
							</div>
			</div>
		  </div>
</div>


  <!-- Main Body Container  -->
  <div class="col-md-12 col-sm-12" id="main" style="padding-top: 2%; padding-bottom: 5%;">
    <div class="col-md-10 col-md-offset-1 col-sm-12">
	   <div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="container" id="userposts" style="max-width: 900px;">
				</div>
			</div>
		</div>
		<div class="row" id="collectionsContainer">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="container" id="collections" style="max-width: 900px;">				
				</div>
			</div>
		</div>
		<div class="col-md-12 more" onclick="loadPosts()" id="loadMore" style="text-align: center; padding: 2%; background-color: rgba(0,0,0,.1)"><h4 style="width: 40%; margin-left: auto; margin-right: auto;">Show More</h4></div>
	</div>
	<div class="col-md-1"></div>
	<!-- Modal -->
   <div class="modalFiller"></div>
						
</div>
</div>




 <!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

  <script src="helpers.js" type="text/javascript"></script>
  <script src="scrollFix.js" type="text/javascript"></script>
  
			
<script>

		function removeCollection(collection_id){
			var confirmed = confirm('Are you sure you want to permanently delete this collection?');		
			var collectionDiv = "#" + collection_id;						
			
				if (confirmed){					 
					 $.ajax
					({ 
						url: 'deleteCollection.php',
						 type: "POST",
						  data: "cid=" + collection_id,
						  success: function(msg){										 
							  $(collectionDiv).remove();
							  $('.modal-backdrop').remove();
							  $('.modal').remove();
						  }
					}); 
				 }
			}
		
		
		
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
		
		
		function loadPosts(){	

			var getLastID = '';			
			var user = '<?php echo $userProfile; ?>';
			var posts = '<?php echo $posts; ?>';
			
			if (('.postSort') != undefined) {
				getLastID=$('.postSort:last').attr('id');
			}

			if (posts == 1) {
				 $.ajax({											
							type: "POST",
							url: "loadMoreProfilePosts.php",
							data: {sort : getLastID, user : user},
							cache: false,
							success: function(msg){
										$('#userposts' ).append( msg );
										
										$('.more').text('Show more');
										
										if(!msg){
											
											$('.more').text('All leyffs have been loaded.').css({"cursor":"pointer"});
										}												
									}
						}); 
			 } 
			
			else if (posts == 0) {
					 $.ajax({											
								type: "POST",
								url: "loadMoreUserCollections.php",
								data: {sort : getLastID, user : user},
								cache: false,
								success: function(msg){
										
										$(' #collections' ).append( msg );
										
										$('.more').text('Show more');
										
										if(!msg){
											
											$('.more').text('All collections have been loaded.').css({"cursor":"pointer"});
										}												
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
		
		function copyToClipboard(element) {
		  var $temp = $("<input>");
		  $("body").append($temp);
		  $temp.val($(element).val()).select();
		  document.execCommand("copy");
		  $temp.remove();
		  $("#bcCopied").show().delay(1000).fadeOut();
		}	 		
		
</script>
	
	  
	  </div>
	
	  <div class="col-sm-12">
		<div id="footer"></div>
	   </div>	  
</body>
 
   <script>
	   $(function() {
		   jQuery.noConflict();
			
			
			$('#toggle_event_editing h4').click(function(){
				
				var url = 'profile.php?user=<?php echo $userProfile ?>';
				
				/* its set to collections */
				if($(this).hasClass('locked_active') || $(this).hasClass('unlocked_inactive')){
						<?php 
							if ($posts) 
							{ 
						?> 		url +='&sort=0';
								window.location.href =  url;
						<?php 
							} 
							else 
							{
						?>  	url += '&sort=1';
								window.location.href = url; 
						<?php 
							} 
						?>
				}
				
				/* it's posts */
				else{
					/* code to do when locking */
					$('#switch_status').html('posts');
						
				}
				
				/* reverse locking status */
				$('#toggle_event_editing button').eq(0).toggleClass('locked_inactive locked_active btn-default btn-info');
				$('#toggle_event_editing button').eq(1).toggleClass('unlocked_inactive unlocked_active btn-info btn-default');
			});	
			
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
								
								//loadPosts
								setTimeout(loadPosts, 500);	
								
								//Get the new count
								var count = $('.postSort').length - totalPostSort;
								totalPostSort = totalPostSort + count;
						}
					}								
				}	
			});
		   
		   
		  $("#footer").load("footer.html");		 
		 
		  $.ajaxSetup({
			async: false
		  });
		});
   </script>
</html>
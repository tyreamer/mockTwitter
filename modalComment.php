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



<script>
loadComments();

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
			$('#comment-form').each(function() {
				$(this).find('input').keypress(function(e) {
					// Enter pressed?
					if(e.which == 10 || e.which == 13) {
						this.form.submit();
					}
				});

				$(this).find('input[type=submit]').hide();
			});
			
			$('#comment-form').submit(function() {
				var text  = $('#comment-form').find('input[name="commentText"]').val();	
				var post  = $('#comment-form').find('input[name="post_id"]').val();
				
				$.ajax({ 
						url: 'addComment.php',
						type: "POST",
						data: {commentText:text, post_id: post},
						  success: function(msg){
							 loadComments();							
						  }
					}); 
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

	<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.6&appId=1596248447282369";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<!--Collection Image Modal-->
<div class="modal fade" id="myCommentModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" style="position: absolute; top: 25%; height: 500px; overflow-y: auto;">
		<div class="modal-content">
			<div class="modal-header">	
				<div style="text-align: center;">
					<h3> <?php echo $postText ?></h3>		
				</div>		
			</div>
			<div class="modal-body">	
				<div class="row">
							<div style="margin-left: auto; margin-right:auto; width: 300px;">
								<a  href="go.php?id=<?php echo $post_id ?>&url=<?php echo $postLink ?>" target="_blank">
									<img src="<?php echo $postImage ?>" style="height: 300px; width: 300px;"></img>
								</a>
							</div>	
							
							<div class= "commentInteractions" style="margin-left: auto; margin-right: auto; padding: 2%;">
									<div class="col-xs-6">
								 	<a href="profile.php?user=<?php echo $connectName ?>"><img src = "<?php echo $fgmembersite->GetProfilePictureByConnectName($connectName)?>" style="color: black; height: 50px; width: 50px;" alt = "My Picture"/>
										
									<br/>
									<?php echo $fgmembersite->GetNameFromConnectName($connectName); ?>
										</a>
									<br/>										
									<?php echo $fgmembersite->humanTiming(strtotime($time)); ?>
								</div>
								<div class="col-xs-6">									
										<h4 class="glyphicon glyphicon-thumbs-up upvote upvote<?php echo $post_id ?>"" onclick="Upvote(this,<?php echo $post_id ?>)" id="upvote" style = "color: <?php echo $upvoteBackground ?>; float: right; "> </h4>
									
								
									<?php //If we're logged in we can favorite this	
												if($favorited) { 
													echo '<a href="favoritePost.php?pid='.$post_id.'&uid='.$myID.'">';
												}
												else {
													echo '<a href="selectcollection.php?pid='.$post_id.'">';
												}
									?>
									<h4 style="float: right; padding-right: 20px;" class="glyphicon <?php 
															
															//Do we favorite this?
															if($favorited) { 
																echo 'glyphicon-heart favoriteBtnColored';
															}
															
															else {
																echo 'glyphicon-heart-empty';
															}
															
												?>			" ></h4> 
									</a>
									<a href="http://www.facebook.com/sharer.php?s=100&p[title]=<?php echo $postText; ?>&p[url]=<?php echo "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>&p[images][0]=<?php echo $postImage; ?>&p[caption]=<?php echo $postText; ?>&p[description]=<?php echo $postText; ?>;" target="_blank">
										<h4 class="glyphicon glyphicon-share" style="float: right; padding-right: 20px;"></h4>
									</a>
									</div>
									
									
								 
							
							</div>
				</div>		
				<div class="row">
					<div class="comments col-xs-12" style="margin-top: 3%;text-align: center;">
							<ul class = "list-group" style = "width: 100%; padding: 5px; font-weight: bold; background-color: #eee; text-align: center; margin-right: auto; margin-left: auto;">
								<div class="row" style="text-align: center;"> 
									<div class="login col-md-8 col-md-offset-2" style = "text-align: center; ">															   
										   <form method = "POST" id="comment-form" enctype = "multipart/form-data">	
												<label class="control-label"></label>
												<input type="text" class="form-control commentText" placeholder="Comment..." name ="commentText" rows="1" id="commentText" required></textarea>									
												<input type="hidden" name="post_id" class="post_id" value="<?php echo $post_id ?>"/>
												<br/>
												<div id="textarea_feedback"></div>
																						
												<input type="submit" name="submit" class = "btn btn-large btn-info" id="submit" style = "display: none;">									 
											</form>
									</div>
								</div>
								<div id="row" style="text-align: center;"><p style="font-size: 10px; font-weight: normal;">Total Comments: <?php echo $totalComments ?></p></div>
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
	</div>
</div>
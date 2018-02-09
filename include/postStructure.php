<?php

require_once("./include/membersite_config.php");

$loggedIn = true;

if(!($fgmembersite->CheckLogin()))
{
	$loggedIn = false;
}	

$servername = "localhost";
$username = "tylre";
$password = "tylrePass";
$db = "YellowstoneDB1";

// Create connection
$postConnection = new mysqli($servername, $username, $password, $db);	


function constructPost($postArr) {

			//Get Post Specifics
			$post_id = $postArr['post_id'];		
			$postText =  $postArr['post_text'];	
			$postImage = $postArr['post_image'];
			$userID   =  $postArr['author_id'];
			$time	  =  $postArr['time'];	
			$postLink =  $postArr['post_link'];
			$views 	  =  $postArr['views'];
			$points   =  $postArr['post_points'];
			$time 	  =  $postArr['time'];
			
			if ($loggedIn) {
				//Get My ID
				$sql = "Select id_user from person where email = '".$_SESSION['email_of_user']."' ";
				if(!$result = $postConnection->query($sql)){
						echo $sql;
						die('There was an error running the ID query [' . mysqli_error($postConnection) . ']');
				}
										
				$row = mysqli_fetch_assoc($result);	
				
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

				if(!$result = $postConnection->query($sql)){
						die('There was an error running the favorite query['.mysqli_error($postConnection).']');
				}
								echo $sql;
						
				$row = mysqli_fetch_assoc($result);	

				$favorited = true;

				if (empty($row)) {
					$favorited = false;
				} 

				//Did we upvote this?
				$sql = "Select upvote from `point` where sender_id=".$myID." and post_id=".$post_id.";";

				if(!$result = $postConnection->query($sql)){
						die('There was an error running the upvote query['.mysqli_error($postConnection).']');
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
			if(!$result = $postConnection->query($sql)){
					
					die('There was an error running the email query [' . mysqli_error($postConnection) . ']');
			}
										
			$row = mysqli_fetch_assoc($result);	
			$postConnectionectName=  $row['connectName'];	

			//Author's Picture
			$sql = "Select photo_location from photo where user_id=".$userID." and photo_type='1';";
			if(!$result = $postConnection->query($sql)){
				die('There was an error running the photo query['.mysqli_error($postConnection).']');
			}
										
			$row = mysqli_fetch_assoc($result);	
			$userPicture=  $row['photo_location'];	
			
			//Default author picture
			if (empty($row)) {
				$userPicture = 'images/myPicture.jpg';
			}
			
		
			?>
   
        <div class="col-md-3 col-sm-6 col-xs-12" id="<?php echo $post_id; ?>">		
          <div class="square">
					<div class="postDetailsHead">
						<div class= "postLocation"><a href="#" target="_blank"> Post location </a></div>
						<div class= "postCost"><a href="#" target="_blank"> Post cost </a></div>
					</div>
					<div class="content">
						<a class="postLink" href="go.php?id=<?php echo $post_id ?>&url=<?php echo $postLink?>" target="_blank">
							<div class= "bg" style="background-image: url(<?php echo $postImage ?>)"></div>
						</a>	
						<div class= "links">			
							<div class="squareLinkItem"> <span class="glyphicon glyphicon-thumbs-up upvote upvote<?php echo $post_id ?>"  onclick="Upvote(this,<?php echo $post_id ?>)"  style = "color: <?php echo $upvoteBackground ?>;"> </div>		
							<a href="#myModal<?php echo $post_id;?>" data-toggle="modal">
								<div class="squareLinkItem"> 
									<span class= "glyphicon glyphicon-option-horizontal" style ="color:green;"> </span>
								</div>
							</a>							
						 </div>
					</div>
					<div class="postDetails">
						<a href="profile.php?user=<?php echo $postConnectionectName ?>">
							<img src = "<?php echo $fgmembersite->GetProfilePictureByConnectName($postConnectionectName)?>" style="color: black" alt = "My Picture" class= "myPhoto"/>
						</a>
							<div class= "postName"><a href="post.php?pid=<?php echo $post_id ?>" target="_blank"> <?php echo $postText ?> </a></div>
							<?php echo $fgmembersite->GetNameFromConnectName($postConnectionectName);?>
							<br/>
							<?php 	echo $fgmembersite->humanTiming(strtotime($time)); ?>
					</div>
				 </div>
				 <!-- For querying more -->
				 <span class="postSort" id="<?php echo $time?>"></span>
				 	<!-- Modal -->
					<div class="modal fade" id="myModal<?php echo $post_id;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-body">	
									<div class= "container" style="max-width: 100%; text-align: center;">
										<?php 
													if ($loggedIn) {
														echo '<a href= "#" onclick="openCommentModal('.$post_id.')"> ';
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
														echo 'glyphicon-heart favoriteBtnColored';
													}
													
													else {
														echo 'glyphicon-heart-empty';
													}
													
										?>			"></span>
										</div>											
												
										<?php 
													if ($loggedIn) {  
														echo '</a>' ;
													}		
										?>	
										<div class="modalItem">													
										  <a href="http://www.facebook.com/sharer.php?s=100&p[title]=<?php echo $allPosts[$i]['post_text']; ?>&p[url]=<?php echo "www.leyff.com/v2/post.php?pid=$post_id"; ?>&p[caption]=<?php echo $allPosts[$i]['post_text']; ?>&p[description]=<?php echo $allPosts[$i]['post_text']; ?>&p[images][0]=<?php echo $allPosts[$i]['post_image'];?>;" target="_blank"><i class="glyphicon glyphicon-share" style="color: black;"></i></a>										
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
		 </div>

<?php 

}
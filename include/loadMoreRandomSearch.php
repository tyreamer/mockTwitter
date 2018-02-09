<?PHP

require_once("./include/membersite_config.php");

$loggedIn = true;

if(!($fgmembersite->CheckLogin()))
{
	$loggedIn = false;
}	

$key = $_POST['qry'];

$servername = "localhost";
$username = "tylre";
$password = "tylrePass";
$db = "YellowstoneDB1";

$offset = $_POST['sort'];

// Create connection
$conn = new mysqli($servername, $username, $password, $db);	

		
		if (isset($_POST['sort']) && $_POST['sort'] != '' && $_POST['sort'] != undefined) {
				
			$qry = "SELECT * FROM post 
							INNER JOIN (person) 
								ON (person.id_user = post.author_id) 
							WHERE post.post_text LIKE '%".$key."%'
								  AND post.time >= now() - INTERVAL 1 DAY	
								  
							GROUP BY post_id
							ORDER BY post.post_points DESC
							LIMIT 12 OFFSET ".$offset."";
							
		}	
		
		else {
		
			$qry = "SELECT * FROM post 
							INNER JOIN (person) 
								ON (person.id_user = post.author_id) 
							WHERE post.post_text LIKE '%".$key."%'
								  AND post.time >= now() - INTERVAL 1 DAY		
							GROUP BY post_id
							ORDER BY post.post_points DESC
							LIMIT 12";
		}
		
		$result = $conn->query($qry);
		
		$postArr = array();
		
		while ($row = $result->fetch_assoc())
		{	
			array_push($postArr, $row);
		}
		
		
			if (sizeOf($postArr) > 0) {
		//Go through each post
		for ($i = 0; $i < sizeOf($postArr); $i++) {
					
					$offset = $offset + 1;
					
					$post_id = $postArr[$i]['post_id'];

					//Post Information
					$sql = "SELECT * FROM post where post_id = '" .$post_id."'";
					if(!$result = $conn->query($sql)){
							echo $sql;
							die('There was an error running the post query [' . mysqli_error($conn) . ']');
					}
												
					$row = mysqli_fetch_assoc($result);	
						
					//Make sure we have info
					if (empty($row)) {						
						 $fgmembersite->RedirectToURL("index.php");
						exit;
					}
					//Get Post Specifics
					$postText =  $row['post_text'];	
					$postImage = $row['post_image'];
					$userID   =  $row['author_id'];
					$time	  =  $row['time'];		
					
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
							echo 'no person found';
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

		   
				<div class="col-xs-6 col-md-3" id="<?php echo $post_id; ?>">
				  <div class="square">
							<div class= "postName"><a href="post.php?pid=<?php echo $post_id ?>" target="_blank"> <?php echo $postArr[$i]['post_text'] ?> </a></div>
							<div class="content">
								<a class="postLink" href="go.php?id=<?php echo $post_id ?>&url=<?php echo $postArr[$i]['post_link']?>" target="_blank">
									<div class= "bg" style="background-image: url(<?php echo $postArr[$i]['post_image'] ?>)"></div>
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
												   
								<div class= "links">
													   <div class="squareLinkItem"><p style="color: black; text-align: center;"><?php echo $postArr[$i]['views'] ?></p></div>					
										   <div class="squareLinkItem"> <span class="glyphicon glyphicon-thumbs-up upvote upvote<?php echo $post_id ?>"  onclick="Upvote(this,<?php echo $post_id ?>)"  style = "color: <?php echo $upvoteBackground ?>;"> </div>		
													   <div class="squareLinkItem"><p style ="color:green; text-align: center;"> <?php echo $postArr[$i]['post_points']?> </p></div>
									<a href="#myModal<?php echo $post_id;?>" data-toggle="modal"><div class="squareLinkItem"> <span class= "glyphicon glyphicon-option-horizontal" style ="color:green;"> </span></div></a>							
								 </div>
							</div>
							<div>
								<a href="profile.php?user=<?php echo $connectName ?>"><img src = "<?php echo $fgmembersite->GetProfilePictureByConnectName($connectName)?>" style="color: black" alt = "My Picture" class= "myPhoto"/></a>
							</div>
							<div class="postDetails">
								<?php echo $fgmembersite->GetNameFromConnectName($connectName);?>
								<br/>
								<?php 	echo $fgmembersite->humanTiming(strtotime($time)); ?>
							</div>
						 </div>
						  <span class="postSort" id="<?php echo $offset;?>"></span>
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
		else {?>
			<div class="row" style="text-align: center; padding: 50px;"> Sorry, nothing matched those search terms. </div> 
		<?php }

?>
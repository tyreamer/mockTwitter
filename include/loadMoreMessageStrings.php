<?PHP
require_once("./include/membersite_config.php");

$loggedIn = true;

if(!($fgmembersite->CheckLogin()))
{
	$loggedIn = false;
	$fgmembersite->RedirectToURL("index.php");
}	

$currentUser = $fgmembersite->ConnectName();
$currentUserID = $fgmembersite->CurrentUser();

$user_id = '';
$offset = '';

if (isset($_POST['user'])) {
	$user_id = $_POST['user'];
}

else 
{
	return false;
}

if (isset($_POST['sort'])) {
	$offset = $_POST['sort'];
}

$servername = "localhost";
$username = "tylre";
$password = "tylrePass";
$db = "YellowstoneDB1";


// Create connection
$conn = new mysqli($servername, $username, $password, $db);	

		if ($currentUserID == $user_id ) 
		{
			if (isset($_POST['sort']) && $_POST['sort'] != '' && $_POST['sort'] != undefined) {
					
				$qry = "SELECT * FROM user_message_string 
								 INNER JOIN message ON (user_message_string.user_message_string_id = message.message_string_id) where userA_id = '" .$user_id."' 
								 OR userB_id = '".$user_id."' 
									GROUP BY user_message_string_id 
										ORDER BY sent_time DESC
											LIMIT 12 OFFSET ".$offset."";
			}	
			
			else {
				$qry = "SELECT * FROM user_message_string 
								 INNER JOIN message ON (user_message_string.user_message_string_id = message.message_string_id) where userA_id = '" .$user_id."' 
								 OR userB_id = '".$user_id."' 
									GROUP BY user_message_string_id 
										ORDER BY sent_time DESC
											LIMIT 12";
			}
		}
		else 
		{
			return false;
		}
			
	
				
		$result = $conn->query($qry);
		
		$allMessages = array();
		
		while ($row = $result->fetch_assoc())
		{	
				array_push($allMessages, $row);
		}
		
			
				
		for ($i = 0; $i < sizeOf($allMessages); $i++) {
				
				$offset = $offset + 1;
				
				if ($allMessages[$i]['userA_id'] == $currentUserID) {
						$friendID = $allMessages[$i]['userB_id'];
						$friendName = $fgmembersite->GetConnectName($allMessages[$i]['userB_id']);
				}
		
				else {
					$friendID = $allMessages[$i]['userA_id'];
					$friendName = $fgmembersite->GetConnectName($allMessages[$i]['userA_id']);
				}
				
				$lastMessage = $fgmembersite->GetLastMessage($allMessages[$i]['message_string_id']);
				
				$lastMessageSender = $fgmembersite->GetConnectName($lastMessage[0]['sender_id']);
				
	?>
		<div class="col-xs-6 col-md-3" id="<?php echo $post_id ?>">
			<div class="square">
				<div class= "postName"><a href="viewmessage.php?id=<?php echo $allMessages[$i]['message_string_id'] ?>"> <?php echo $friendName; ?> </a></div>
				<div class="content">
					<a href="viewmessage.php?id=<?php echo $allMessages[$i]['message_string_id'] ?>">
						<div class= "bg" style="background-image: url('<?php echo $fgmembersite->GetProfilePictureByConnectName($friendName) ?>')"></div>
					</a>
				</div>
				<div>
					<a href="profile.php?user=<?php echo $currentUser ?>"><img src = "<?php echo $fgmembersite->GetProfilePictureByConnectName($currentUser)?>" style="color: black" alt = "My Picture" class= "myPhoto"/></a>							
				</div>	
				<a href="viewmessage.php?id=<?php echo $allMessages[$i]['message_string_id'] ?>"><div class="lastMesage"><p><?php echo $lastMesssageText = $lastMessageSender.' said: <br/>'. $lastMessage[0]['message_text'] ?></p></div></a>
			</div>
		 <span class="postSort" id="<?php echo $offset;?>"></span>	
		</div>	

<?php 
		}//for

?>
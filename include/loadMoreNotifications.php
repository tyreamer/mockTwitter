<?PHP
require_once("./include/membersite_config.php");

$loggedIn = true;

if(!($fgmembersite->CheckLogin()))
{
	$loggedIn = false;
	$fgmembersite->RedirectToURL("index.php");
}	

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
					
				$qry = "SELECT * 
							FROM notification 
								where user_id = '". $user_id ."'
										ORDER BY time DESC
											LIMIT 12 OFFSET ".$offset."";
			}	
			
			else {
				$qry = "SELECT * 
							FROM notification 
								where user_id = '". $user_id ."'
										ORDER BY time DESC
											LIMIT 12";
			}
		}
		else 
		{
			return false;
		}
			
	
				
		$result = $conn->query($qry);
		
		$notifications = array();
		
		while ($row = $result->fetch_assoc())
		{	
			array_push($notifications, $row);
		}
		
			
				
		for ($i = 0; $i < sizeOf($notifications); $i++) {
				$offset = $offset + 1;
	?>
			<div class = "row" style= "padding: 0px; margin: 5px; border-width: 100%; width: 100%;">												
				<a href="<?php echo $notifications[$i]['link'] ?>" style="color: black;">											
					<div class = "col-md-12" style= "line-height: 50px; border-radius: 20px;   background-color: lightblue;">											
						<p>	<?php  echo $notifications[$i]['notification_text'] ?>	</p>	
						<hr class="mobileBreak" style="display:none;"/>
						<p style= "position: absolute; margin: 0px; bottom: 0; right: 5%;"> 
							<?php 
						
								$time = strtotime($notifications[$i]['time']);
						
								echo $fgmembersite->humanTiming($time);

								 ?> 
						</p>											
					</div>											
				</a>
				 <!-- For querying more -->
				<span class="postSort" id="<?php echo $offset;?>"></span>
			</div>

<?php 
		}//for

?>
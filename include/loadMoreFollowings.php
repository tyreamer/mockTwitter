<?PHP

require_once("./include/membersite_config.php");

$loggedIn = true;

if(!($fgmembersite->CheckLogin()))
{
	$loggedIn = false;
}	

$offset = $_POST['sort'];

$servername = "localhost";
$username = "tylre";
$password = "tylrePass";
$db = "YellowstoneDB1";

// Create connection
$conn = new mysqli($servername, $username, $password, $db);	


		if (isset($_POST['user'])) {
			
			$user = $fgmembersite->GetUserFromConnectName($_POST['user']);
			
			if (isset($_POST['sort']) && $_POST['sort'] != '' && $_POST['sort'] != undefined) {
				
					$qry = "Select user_id from follower where follower_user_id= $user LIMIT 12 OFFSET $offset";
					
			}
			else 
			{		
				$qry = "Select user_id from follower where follower_user_id=$user ORDER BY user_id DESC LIMIT 12";
			}
		
		}
		else 
		{
			return false;
		}
		
		
		$result = $conn->query($qry);
		
		$allFollowers = array();
		
		while ($row = $result->fetch_assoc())
		{	
				array_push($allFollowers, $row);
		}
		
		if (sizeOf($allFollowers) == 0 ) {
			return false;
		}
		
		for ($j = 0; $j < sizeOf($allFollowers); $j++) {
				$offset = $offset +1;
				
				$cName  = $fgmembersite->GetConnectName($allFollowers[$j]['user_id']);
				$cImage = $fgmembersite->GetProfilePictureByConnectName($cName);
				
				if ($cImage == '') {
					$cImage = 'images/LinkDefaultImg.jpg';
				}				
				
	?>				<div class="col-xs-12 col-sm-3">
					<div class="followers" style="margin-left: auto; margin-right: auto; width: 100px;">
						<a href="profile.php?user=<?php echo  $cName?>">
							<div class= "followingName"> 
								<center><?php echo $cName ?></center>
							</div>
							<img style="height: 100px; width: 100px; margin-left: auto; margin-right: auto;" src="<?php echo $cImage ?>"></img>	
						</a>
					</div>
					<span class="postSort" id="<?php echo $offset?>"></span>		<!-- For querying more -->
				</div>	
						
							
	<?php		
				
			} //for
	?>
		
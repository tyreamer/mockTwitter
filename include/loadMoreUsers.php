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

		 
		
		$locationqry = "Select location_name from location where location_name = '".$key."' LIMIT 1";
		$result = $conn->query($locationqry);
		
		//Subjects match this search
		if ($row = $result->fetch_assoc())
		{
			$sid = $row['location_name'];
		
			if (isset($_POST['sort']) && $_POST['sort'] != '' && $_POST['sort'] != undefined) {
					
				$qry = "Select name,connectName,  count(follower_id) as 'followers'
							from person 
								left join follower ON person.id_user = follower.user_id
									WHERE name LIKE '%".$key."%' OR location = '".$sid."'
										GROUP BY person.id_user
											LIMIT 12 OFFSET ".$_POST['sort']."";
			}	
			
			else {
				$qry = "Select name,connectName,  count(follower_id) as 'followers'
							from person 
								left join follower ON person.id_user = follower.user_id
									where name LIKE '%".$key."%' OR location = '".$sid."'
										GROUP BY person.id_user
											LIMIT 12";
			}
			
		} 
		
		//No store locations associated
		else 
		{
			if (isset($_POST['sort']) && $_POST['sort'] != '' && $_POST['sort'] != undefined) {
			
				$qry = "Select name,connectName, count(follower_id) as 'followers'
							from person 
								left join follower ON person.id_user = follower.user_id
									where name LIKE '%".$key."%'
										GROUP BY person.id_user
											LIMIT 12 OFFSET ".$_POST['sort']."";
			}	
			
			else {
				$qry = "Select name,connectName, count(follower_id) as 'followers'
							from person 
								left join follower ON person.id_user = follower.user_id
									where name LIKE '%".$key."%'
										GROUP BY person.id_user
											LIMIT 12";
			}
		}
				
		$result = $conn->query($qry);
		
		$personArr = array();
		
		while ($row = $result->fetch_assoc())
		{	
				array_push($personArr, $row);
		}
		
		$tmp=array();
		$p=0;
		//Sort by followers
		for($j=$p+1;$j<count($personArr);$j++){
			
			if($personArr[$p]['followers']<$personArr[$j]['followers']){			
				$tmp = $personArr[$p];
				$personArr[$p] = $personArr[$j];
				$personArr[$j] = $tmp;
			}
		}
		
		
		if (sizeOf($personArr) > 0) {
				
			for ($i = 0; $i < sizeOf($personArr); $i++) {
				$offset = $offset +1;
				//Get Person Specifics
				$name 		 = $personArr[$i]['name'];
				$user		 = $personArr[$i]['connectName'];
				$followers   = $personArr[$i]['followers'];
				
	   
				$sql = "SELECT photo_location FROM photo where user_id = '" .$fgmembersite->GetUserFromConnectName($user) ."' AND photo_type = 1";
				if(!$result = $conn->query($sql)){
						
				}
											
				$row = mysqli_fetch_assoc($result);	
										
				$userPicture = $row['photo_location'];
							
					
				//Default author picture
				if (empty($row)) {
					$userPicture = 'images/myPicture.jpg';
				}

				?>

				   
						<div class="col-xs-6 col-md-3">
						  <div class="square">
								<div class= "postName"><a href="profile.php?user=<?php echo $user ?>"> <?php echo $fgmembersite->GetNameFromConnectName($user) ?> </a></div>
								<div class="content">
									<a href="profile.php?user=<?php echo $user ?>">
										<div class= "bg" style="background-image: url(<?php echo $userPicture ?>)"></div>
									</a>
								</div>
						  </div>
						  <!-- For querying more -->
						  <span class="postSort" id="<?php echo $offset;?>"></span>
						</div>
	   

	<?php 
			}//for
		}
		else {?>
			<div class="row" style="text-align: center; padding: 50px;"> Sorry, no stores matched those search terms. </div> 
		<?php }

?>
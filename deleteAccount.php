<?php
require_once("./include/membersite_config.php");

$servername = "localhost";
$username = "tylre";
$password = "tylrePass";
$db = "YellowstoneDB1";
if ($fgmembersite->CheckLogin()) {
	$userID = $fgmembersite->CurrentUser();
}
$valid = false;
if (!(isset($_POST['uid']))) {
	return false;
}
// Create connection
$conn = new mysqli($servername, $username, $password, $db);

$userqry = "Select id_user from person where id_user = '".$userID."' LIMIT 1";

		$result = $conn->query($userqry);
				
		if ($row = $result->fetch_assoc())
		{
			$uid = $row['id_user'];
		}
		
		if ($uid == $_POST['uid']) {
			$valid = true;
		}

		if ($valid)  {
			$sql = "Delete from person where id_user=".$uid.";";		
		}

	if(!$result = $conn->query($sql)){		

			return false;
	}
	$fgmembersite->LogOut();
	
	return true;
?>	
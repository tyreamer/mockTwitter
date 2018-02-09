<?php
require_once("./include/membersite_config.php");

if(!$fgmembersite->CheckLogin())
{	
}


$servername = "localhost";
$username = "tylre";
$password = "tylrePass";
$db = "YellowstoneDB1";

// Create connection
$conn = new mysqli($servername, $username, $password, $db);

$cid = $_POST['cid'];

$collection = $fgmembersite->GetCollectionInfo($cid);
$cOwner = $collection['collection_owner'];
$myID = $fgmembersite->GetUserFromConnectName($fgmembersite->ConnectName());

if ($cOwner == $myID) { 

	$sql = "Select collection_image from collection where collection_id= '" . $cid . "'"; 
		 if(!$result = $conn->query($sql)){
			return false;
		}
		$row = mysqli_fetch_assoc($result);			
		$currentImage =  $row['collection_image'];
		unlink($currentImage);


	$sql = "Delete from collection where collection_id=".$cid.";";

	if(!$result = $conn->query($sql)){	

			die('There was an error running the query [' . mysqli_error($conn) . ']');
			return false;
	}
	
	return true;
}

 else {
	return false;
}


?>		
<?php
require_once("./include/membersite_config.php");

$servername = "localhost";
$username = "tylre";
$password = "tylrePass";
$db = "YellowstoneDB1";

// Create connection
$conn = new mysqli($servername, $username, $password, $db);

$post_id = $_POST['pid'];

$sql = "Select post_image from post where post_id=".$post_id.";";

if(!$result = $conn->query($sql)){
		die('There was an error running the query [' . mysqli_error($conn) . ']');
		return false;
}

$row = mysqli_fetch_assoc($result);	
unlink($row['post_image']);

$sql = "Delete from post where post_id=".$post_id.";";

if(!$result = $conn->query($sql)){		

		die('There was an error running the query [' . mysqli_error($conn) . ']');
		return false;
}


?>			
<script>
  window.history.go(-2);
</script>
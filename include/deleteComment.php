<?php
require_once("./include/membersite_config.php");

$servername = "localhost";
$username = "tylre";
$password = "tylrePass";
$db = "YellowstoneDB1";

// Create connection
$conn = new mysqli($servername, $username, $password, $db);

$comment_id = $_POST['cid'];

	$sql = "Delete from comment where comment_id=".$comment_id.";";

	if(!$result = $conn->query($sql)){		

			die('There was an error running the query [' . mysqli_error($conn) . ']');
			return false;
	}
?>			
<script>
  window.history.go(-2);
</script> 
<?php
require_once("./include/membersite_config.php");

$servername = "localhost";
$username = "tylre";
$password = "tylrePass";
$db = "YellowstoneDB1";

// Create connection
$conn = new mysqli($servername, $username, $password, $db);

$post_id = $_GET['id'];
$url  = $_GET['url'];
 

//Add one to the post views but do not update the timestamp
$sql = "Update post set views = views + 1 where post_id=".$post_id.";";

$conn->query($sql);

$fgmembersite->RedirectToURL($url);
?>
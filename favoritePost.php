<?php
require_once("./include/membersite_config.php");

$servername = "localhost";
$username = "tylre";
$password = "tylrePass";
$db = "YellowstoneDB1";
$redirect = "home.php";

// Create connection
$conn = new mysqli($servername, $username, $password, $db);

if (isset($_GET['pid']) && isset( $_GET['uid'])) {
	$post_id = $_GET['pid'];
	$user  = $_GET['uid'];
	$redirect = $_SERVER['HTTP_REFERER'];
}

else {
	
	$post_id = $_POST['pid'];
	$user  = $_POST['uid'];
	$collection = $_POST['cid'];
	$redirect = "post.php?pid=".$post_id;	
	
	//Does this collection have an image?
	$collectionQry = "Select collection_image from collection where collection_id= '" . $collection. "'";  
						
	if(!$result = $conn->query($collectionQry)){
		die('There was an error running the query [' . $conn->error . ']');
	}
		 
	$row = mysqli_fetch_assoc($result);	
	
	//Use this post's image
	if ($row['collection_image'] == '') {
			
		$imgQry = "Select post_image from post where post_id= '" . $post_id. "'";  
						
		if(!$result2 = $conn->query($imgQry)){
			die('There was an error running the query [' . $conn->error . ']');
		}	

		$row2 = mysqli_fetch_assoc($result2);	
		$img = $row2['post_image'];
		
		$update_query = 'Update collection SET collection_image = "'.$img.'" WHERE collection_id = "' . $collection . '" ';

		if(!$insert = $conn->query($update_query))
		{		
			Redirect($redirect, false);
			return false;
		}  
	}
}
 
//Do we favorite this?
$sql = "Select favorite_id from `favorite` where sender_id=".$user." and post_id=".$post_id.";";

if(!$result = $conn->query($sql)){
		die('There was an error running the query [' . mysqli_error($conn) . ']');
}
							
	$row = mysqli_fetch_assoc($result);	

$favorited = true;

if (empty($row)) {
	$favorited = false;
} 

if (!$favorited) {

	//Favorite this post
	$sql = "Insert into `favorite`	(sender_id, post_id, collection)
			Values 				(".$user.", ".$post_id.",".$collection.")";		

	if(!$result = $conn->query($sql)){
			echo $sql;
			die('There was an error running the insert query [' . mysqli_error($conn) . ']');
	}
	
	
								
	//Notify the author
	$sql = "Select author_id from post where post_id=".$post_id.";";
	
	if(!$result = $conn->query($sql)){
		die('There was an error running the query [' . mysqli_error($conn) . $sql.']');
	}
							
	$row = mysqli_fetch_assoc($result);	
	
	 $toUser = $row['author_id'];
	 $type = "post||favorite";
	 $text="test";
	 
	$fgmembersite->NotifyUser($toUser, $type, $post_id, $text, $user);
}

else {	
		
	//Get the post image
	$imageQry = "Select collection_id, collection_image, post_image 
					from favorite 
						right join collection on favorite.collection = collection.collection_id 
						right join post on favorite.post_id = post.post_id
							where post.post_id='" . $post_id. "'"; 	
							
	if(!$result2 = $conn->query($imageQry)){ die('There was an error running the query [' . $conn->error . ']');	}		 
	$row2 = mysqli_fetch_assoc($result2);		
	$img = $row2['post_image'];
	$cImage = $row2['collection_image'];
	$collection = $row2['collection_id'];
	
	//Check if the collection image was from this post
	if ( $cImage == $img) {		
		
		$update_query = 'Update collection SET collection_image = "images/LinkDefaultImg.jpg" WHERE collection_id = "' . $collection . '" ';

		if(!$insert = $conn->query($update_query))
		{		
			Redirect($redirect, false);
			return false;
		}  
	}

	//Delete it from favorites
	$sql = "DELETE FROM `favorite`
			WHERE	sender_id=".$user." and post_id=".$post_id;
	if(!$result = $conn->query($sql)){
			
			die('There was an error running the delete query [' . mysqli_error($conn) . ']');
	}

}

	$fgmembersite->RedirectToURL($redirect);



?>
<?php

require_once("./include/membersite_config.php");

function validateLink($link) {
    // TODO server side validation
    return true;
}

function validateText($text) {
    // TODO server side validation
    return true;
}

function getImageList($link) {
    // Get all images from the site
    $imgList = array();
    libxml_use_internal_errors(true);
    $c = file_get_contents($link);
    $d = new DomDocument();
    $d->loadHTML($c);
    $xp = new domxpath($d);
    foreach ($xp->query("//meta[@property='og:image']") as $el) {
        $imgList[] = $el->getAttribute("content");
    }
    
    foreach ($xp->query("//img") as $ele) {
		if ($ele->getAttribute("src") != "") { 
			$imgList[] =$ele->getAttribute("src");
		}
	}
    
    return $imgList;
}

if(!$fgmembersite->CheckLogin()) {
    echo json_encode(array('success'=>false, 'message'=>'Error: Must be logged in.'));
}
else if (isset($_POST['postLink']) && isset($_POST['postText'])) {
    // Try to submit the post
    $link = $_POST['postLink'];
    $text = $_POST['postText'];
    
    // Validate link
    if (!validateLink($link)) {
        echo json_encode(array('success'=>false, 'message'=>'Error: Invalid link provided.'));
    }
    
    // Validate text
    if (!validateText($text)) {
        echo json_encode(array('success'=>false, 'message'=>'Error: Invalid text provided.'));
    }
  
    // Try a custom image
    if (isset($_FILES['customImage']) && $_FILES['customImage']['error'] == UPLOAD_ERR_OK) {
        // Make the post images folder if it doesn't exist
        if (!file_exists('images/postImages')) {
            mkdir('images/postImages', 0777, false);
        }
        
        // Generate a random 8 character name for the image
        // Generate up to 10 times to find a unique name
        $nameLength = 8;
        $tryCount = 10;
        $name = "";
        $tries = 0;
        $validChars = "abcdefghijklmnopqrstuvwxyz0123456789";
        while ($tryCount > 0 && strlen($name) == 0) {
            $tmpName = "";
            for ($i = 0; $i < $nameLength; $i++) {
                $tmpName = $tmpName . substr($validChars, rand(0, strlen($validChars)), 1);
            }
            if (!file_exists('images/postImages/' . $tmpName)) {
                // Found a name
                $name = $tmpName;
            }
        }
        
        if (strlen($name) > 0) {
            $path = 'images/postImages/';
			$img = 'images/postImages/' . $name;
            move_uploaded_file($_FILES['customImage']['tmp_name'], $img);
			$fgmembersite->imageResizing($path, $name, 400, 400);
            
            if ($fgmembersite->CreatePost($text, $link, $img)) {
                echo json_encode(array('success'=>true, 'message'=>'Successfully created post.'));
            }
            else {
                echo json_encode(array('success'=>false, 'message'=>'Error: Failed to insert post. Please try again.'));
            }
        }
        else {
            echo json_encode(array('success'=>false, 'message'=>'Error: Upload custom image. Please try again.'));
        }
    }
    else if (isset($_POST['postImageIndex'])) {
	
        // Get default image
        $defaultImg = "images/LinkDefaultImg.jpg";
        $imgList = getImageList($link);
        
        // Check if an image has been selected (-1 if default)
        $imgIndex = -1;
        if (is_numeric($_POST['postImageIndex'])) {
            $imgIndex = intval($_POST['postImageIndex']);
        }
        
        if (count($imgList) > 0) {
            if ($imgIndex >= count($imgList) || $imgIndex < 0) {
                $imgIndex = 0;
            }
        }
        else {
            $imgIndex = -1;
        }
        
        // Attempt to create the post
        if ($imgIndex < 0) {
            $img = $defaultImg;
        }
        else {
            $img = $imgList[$imgIndex];
        }
        
        if ($fgmembersite->CreatePost($text, $link, $img)) {
            echo json_encode(array('success'=>true, 'message'=>'Successfully created post.'));
        }
        else {
            echo json_encode(array('success'=>false, 'message'=>'Error: Failed to insert post. Please try again.'));
        }
    }
}
else if (isset($_POST['queryLink'])) {
    $link = $_POST['queryLink'];
    
    // Validate link
    if (!validateLink($link)) {
        echo json_encode(array('success'=>false, 'message'=>'Error: Invalid link provided.'));
    }
    
    // Provide a list of possible images
    $imgList = getImageList($link);
    echo json_encode(array('success'=>false, 'data'=>$imgList, 'message'=>'Select an image to use for the post.'));
}
else {
    echo json_encode(array('success'=>false, 'message'=>'Error: Link and text cannot be left blank.'));
}

?>

<?php

require_once("./include/membersite_config.php");

if(!$fgmembersite->CheckLogin())
{
   return false;
}

//Adding comments
if(isset($_POST['commentText']) && isset($_POST['post_id']))
{			
	$text = addslashes($_POST['commentText']);	
		
	if ($fgmembersite->Comment($text, $_POST['post_id'])) {
		return true;
	}			
	else {
		echo '<script language="javascript">';
		echo 'alert("Failed to add comment: '. $text .' Please try again.")';
		echo '</script>'; 
	}
}
?>
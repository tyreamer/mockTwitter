<?php

require_once("./include/membersite_config.php");

if(!$fgmembersite->CheckLogin())
{
   return false;
}



if ($fgmembersite->UpdatePoint($_POST['post_id'], $_POST['upvote'])) 
{
   return true;
}

else {
	return false;
}



?>
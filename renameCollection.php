<?php

require_once("./include/membersite_config.php");

if(!$fgmembersite->CheckLogin())
{
    $fgmembersite->RedirectToURL("index.php");
    exit;
}

if (isset($_POST['collection_name']) && isset($_POST['collection_id'])) {
	$redirect = "collection.php?id=".$_POST['collection_id']."";
	$fgmembersite->RenameCollection($_POST['collection_name'], $_POST['collection_id']);	
	$fgmembersite->RedirectToURL($redirect);
}

?>
<?php

require_once("./include/membersite_config.php");

if(!$fgmembersite->CheckLogin())
{
    $fgmembersite->RedirectToURL("index.php");
    exit;
}
if (isset($_POST['collection_name'])) {
	$newCollectionID = $fgmembersite->CreateCollection($_POST['collection_name']);

	echo $newCollectionID;
}

?>
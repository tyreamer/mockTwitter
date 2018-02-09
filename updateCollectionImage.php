<?php

require_once("./include/membersite_config.php");

if(!$fgmembersite->CheckLogin())
{
    $fgmembersite->RedirectToURL("index.php");
    exit;
}


if (isset($_FILES['collectionbackground']) && isset($_POST['collection_id'])) {
	 $fgmembersite->UpdateCollectionImage($_POST['collection_id'], $_FILES['collectionbackground']);
}

 $fgmembersite->RedirectToURL("profile.php");
   
   ?>
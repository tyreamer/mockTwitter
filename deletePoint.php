<?php

require_once("./include/membersite_config.php");

if(!$fgmembersite->CheckLogin())
{
    $fgmembersite->RedirectToURL("index.php");
    exit;
}

$p = $fgmembersite->DeletePoint($_POST['post_id']);

return $p;

?>
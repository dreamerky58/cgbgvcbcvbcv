<?php
session_start();
$dil	=strip_tags($_GET["dil"]);
if ($dil =="tr" || $dil == "en" || $dil == "de" || $dil == "ru"){
	$_SESSION["dil"] = $dil;
	header("Location:index.html");
}else {
	header("Location:index.html");
}

?>
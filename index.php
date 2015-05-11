<?php
include "config/config.php";

$currentPage="accueil";
if (array_key_exists("page", $_GET)) {
	$currentPage=$_GET["page"];
}

//Pour se déconnecter
if ("logout"==$currentPage) {
	session_destroy();
	header("Location:index.php?page=login");
	die();
}


$controller="controller/".$currentPage."Controller.php";
$vue="vue/".$currentPage."View.phtml";

//On fait un test pour afficher ou non une page 404
if (!file_exists($controller) || !file_exists($vue) ) {
	include "vue/404View.phtml";
	die();
}

include	$controller;
include	$vue;
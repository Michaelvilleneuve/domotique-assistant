<?php
/*
* Copyright 2015 Michaël Villeneuve
* 
* Routeur.php 
*
* Routeur
* Ce fichier permet le routage des requêtes entrantes dans l'assistant. 
*
*/ 
require_once 'app/Controllers/AppController.php';

$url = (isset($_GET['q']) AND !empty($_GET['q'])) ? $_GET['q'] : 'index';
$controller = new AppController();
$params = '';

if ($url == 'ajax') {
	if (strpos($_GET['action'],'lampe') !== false) {
		$params = str_replace('lampe','',$_GET['action']);
		$url = 'lampe';
	} else {
		$url = $_GET['action'];
	}
}

$controller->$url($params);
?>

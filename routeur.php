<?php
/*
* Copyright 2015 Michaël Villeneuve
* 
* Routeur.php 
*
* Routeur
* Ce fichier permet le routage des requêtes entrantes dans l'assistant. 
* Vous pouvez ainsi facilement ajouter des routes dans le tableau $routes.
*
*/ 
require_once 'controller.php';
$url = (isset($_GET['q']) AND !empty($_GET['q'])) ? $_GET['q'] : 'index';
$controller = new Controller($url);
$routes = array(
	'index',
	'ajax',
);
/** Auto utilisé pour les requètes envoyées par une arduino **/
if ( empty($_GET['auto']) ) {
	if ( in_array($url, $routes) ) {
		$action = $url.'Action';
		$controller->$action();
	}
	else {
		$controller->erreur();
	}
}
else {
	$heure = date("H");
		if (in_array($url, $routes)) {
			$action = $url.'Action';
			$controller->$action();
		}
		else {
			$controller->erreur();
		}
	
}

?>

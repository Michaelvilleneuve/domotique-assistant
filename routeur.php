<?php
require_once 'controller.php';
$url = (isset($_GET['q']) AND !empty($_GET['q'])) ? $_GET['q'] : 'index';
$controller = new Controller($url);
$routes = [
	'index',
	'wol',
	'ajax',
	'cron',
];
/** Auto utilisé pour les requètes envoyées par une arduino **/
if(empty($_GET['auto'])){
	if (in_array($url, $routes)) {
		$action = $url.'Action';
		$controller->$action();
	}
	else {
		$controller->erreur();
	}
}
else{
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

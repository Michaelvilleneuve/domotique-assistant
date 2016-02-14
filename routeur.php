<?php

require_once(getcwd().'/config/config.php');

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

function __autoload($class_name) {
	$controller = getcwd().'/app/Controllers/'.$class_name.'.php';
	$model = getcwd().'/app/Models/'.$class_name.'.php';

    if (file_exists($model))
    	include_once($model);
    elseif (file_exists($controller))
    	include_once($controller);
    else 
    	return false;

}
?>

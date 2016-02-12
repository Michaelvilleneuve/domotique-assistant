<?php

define('SMSENVOI_EMAIL','votre@mail.fr');
define('SMSENVOI_APIKEY','VOTRECLEAPI');
define('SMSENVOI_VERSION','3.0.4');

function iscurlinstalled() {
	if  (in_array  ('curl', get_loaded_extensions())) {
		return true;
	}
	else{
		return false;
	}
}

if(!iscurlinstalled()){ die("L'API SMSENVOI NECESSITE L'INSTALLATION DE CURL"); }

?>
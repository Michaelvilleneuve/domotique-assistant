<?php

// Email sms envoi
define('SMSENVOI_EMAIL','votre@mail.fr');
// Clé api sms envoi
define('SMSENVOI_APIKEY','VOTRECLEAPI');
// Version sms envoi
define('SMSENVOI_VERSION','3.0.4');
// Adresse Mac du PC à réveiller
define('ADRESSEMAC','XX:XX:XX:XX:XX:XX');
// Ip du PC à réveiller
define('IPPC','192.168.X.X');
// Numéro SMS sans le 0
define('NUMSMS','606060606');

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
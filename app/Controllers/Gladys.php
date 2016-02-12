<?php 
/*
* Copyright 2015 Michaël Villeneuve
* 
* Gladys.php 
*
* Assistant
* Ce fichier correspond à toutes les fonctionnalitées de l'onglet assistant de l'app
* Le switch contient la variable postée dans le formulaire. Vous pouvez ainsi facilement rajouter des cas à traiter
* 
*
*
*	TODO : Refactoriser et faire de gladys une class, idem pour les méthodes musique
*
*/ 
switch($post){

	case 'Nova':
		exec('sudo pkill mpg321');
		exec('mpg321 "http://novazz.ice.infomaniak.ch/novazz-128.mp3"');
	break;

	case 'Virgin':
		exec('sudo pkill mpg321');
		exec('mpg321 "http://vipicecast.yacast.net/virginradio_192"');
	break;
	
	case 'Vol':
		exec('sudo amixer cset numid=1 -- '.$phraseadire.'');
	break;

	
	case 'Hello':
	case 'Salut':
	case 'Bonjour':
	case 'bonjour':
		$this->direPhrase('Bonjour, comment allez-vous ?');
	break;
	
	case 'Exec':
		echo exec($phraseadire);
	break;

	case 'Dis':
		$this->direPhrase($phraseadire);
	break;
	
	case 'Play':
	case 'Joue':
	case 'Musique':
		exec('sudo pkill mpg321');
		$this->pause();
		$this->direPhrase('Je lance la musique');
		sleep(1);
		$this->play();
	break;
	
	case 'Off':
	case 'off':
	case 'stop':
	case 'Stop':
	case 'Pause':
			$this->pause();
	break;
	
	case 'precedent':
	case 'Precedent':
	case 'précedent':
			$this->precedent();
	break;
	
	case 'Suivant':
			$this->suivant();
	break;
	
	case 'On':
	case 'Lumières':
	case 'Allume':
	case 'Allume les lumières':
	case 'Lumière':
		$this->allumertout();
		$this->direPhrase('Toutes les lumières ont été allumées.');
	break;
	
	case 'Eteins':
	case 'Eteindre':
	case 'Éteins':
	case 'Éteins les lumières':
	case 'Éteint les lumières':
	case 'Éteindre':
		$this->eteindretout();
		$this->direPhrase('Toutes les lumières ont été éteintes.');
	break;
	
	default:
		$this->direPhrase('desolé, je n\'ai pas compris');
	break;
	
}
?>

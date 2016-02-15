<?php
class GladysModel extends AppModel {
	public function direPhrase($phrase) {
		exec('sudo amixer cset numid=1 -- 0');
		exec('mpg321 temp.mp3'); 
		exec('sudo amixer cset numid=1 -- 2000');
		
		return $phrase;
	}
	public function find($name) {
		foreach ($this->globals as $global_name => $global) {
			if ($global_name == $name) {
				$this->name = $global_name;
				$this->statut = $global['statut'];
				return $prise;
			}
		}
	}
	public function update() {
		$this->datas['globals'][$this->name]['statut'] = $this->statut;
		parent::save();
	}
	public function respond($tothisphrase) {
		$tothisphrase = ucfirst($tothisphrase);

		if ( stripos($tothisphrase, 'Dis') !== false && stripos($tothisphrase, 'dis') == 0 ) {
			$phraseadire = str_replace('Dis', '', $tothisphrase);
			$tothisphrase = 'Dis';
		}
		if ( stripos($tothisphrase, 'Dit') !== false && stripos($tothisphrase, 'dis') == 0 ) {
			$phraseadire = str_replace('Dit', '', $tothisphrase);
			$tothisphrase = 'Dis';
		}
		if ( stripos($tothisphrase, 'Exec') !== false && stripos($tothisphrase, 'Exec') == 0 ) {
			$phraseadire = str_replace('Exec ', '', $tothisphrase);
			$tothisphrase = 'Exec';
		}
		if ( stripos($tothisphrase, 'Vol') !== false && stripos($tothisphrase, 'Vol') == 0 ) {
			$phraseadire = str_replace('Vol ', '', $tothisphrase);
			$post = 'Vol';
		}
		switch($tothisphrase){

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
				$this->Music->pause();
				$this->direPhrase('Je lance la musique');
				sleep(1);
				$this->Music->play();
			break;
			
			case 'Off':
			case 'off':
			case 'stop':
			case 'Stop':
			case 'Pause':
					$this->Music->pause();
			break;
			
			case 'precedent':
			case 'Precedent':
			case 'précedent':
					$this->Music->precedent();
			break;
			
			case 'Suivant':
					$this->Music->suivant();
			break;
			
			case 'On':
			case 'Lumières':
			case 'Allume':
			case 'Allume les lumières':
			case 'Lumière':
				$this->Prise->allumertout();
				$this->direPhrase('Toutes les lumières ont été allumées.');
			break;
			
			case 'Eteins':
			case 'Eteindre':
			case 'Éteins':
			case 'Éteins les lumières':
			case 'Éteint les lumières':
			case 'Éteindre':
				$this->Prise->eteindretout();
				$this->direPhrase('Toutes les lumières ont été éteintes.');
			break;
			
			default:
				$this->direPhrase('desolé, je n\'ai pas compris');
			break;
			
		}
	}
	public function __construct() {
		parent::__construct();
		$this->globals = $this->datas['globals'];
	}

}
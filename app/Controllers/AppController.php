<?php 
/*
* Copyright 2015 Michaël Villeneuve
* 
* Controller.php 
*
* Ce fichier controlle l'ensemble des fonctionnalités de l'assistant.
* Il permet aussi la configuration globale via les attributs privés du controlleur.
*
* 
* 
*
*/

class AppController {	

	public function index() {
		$states = $this->App->getCurrentState();
		// On passe le tout au layout et à la vue initiale
		include($this->layoutpath.'header.php');
		include($this->viewpath.'index-view.php');
		include($this->layoutpath.'footer.php');
	}
	public function temp() {
		if (isset($_GET['temp'])){
			if ($this->App->validateTempResult()) {
				$file = $this->App->ecrireFichier('temperature.txt', str_replace('.00',$temp));
				$this->App->toggleChauffage($temp);
			}
		}
	}
	public function allumertout() {
		$this->Prise->code = '10101';
		$this->Prise->toggleSeveral(array(1,2,3,4),1);
		$this->Gladys->direPhrase('C\'est fait.');
	}
	public function deverrouiller() {
		$this->App->ecrireFichier('verouillage.txt', $this->val);
	}
	public function verouiller() {
		$this->Prise->code = '10101';
		$this->Prise->toggleSeveral(array(1,2,3,4),0);
		$this->Gladys->pause();
		$this->Gladys->direPhrase('Maison verouillée.');
	}
	public function eteindretout() {
		$this->Prise->code = '10101';
		$this->Prise->toggleSeveral(array(1,2,3,4),0);
		$this->Gladys->direPhrase('C\'est fait.');
	}
	public function serveur() {
		$this->Gladys->direPhrase('Le serveur redémarre.');
		$this->App->ecrireDate('reboot');
		$this->App->augmenterVisite('serveur');
		exec('sudo reboot');
	}
	public function ouvrir() {
		$this->Prise->code = '11100';
		$this->Prise->toggle('3','verouillage',0);
		$this->Prise->code = '11100';
		$this->Prise->toggle('2','',0);	
		sleep(7);
		$this->Prise->toggle('2','',1);	
	}
	public function pc() {
		if($this->val) { 
			exec('wakeonlan '.$this->adressemac.'');
			$this->App->augmenterVisite('pc');
			$this->Gladys->direPhrase('Démarrage.');
		}
		else {
			exec('sudo curl http://'.IPPC.':7760/poweroff > /dev/null 2>&1');
			$this->Gladys->direPhrase('Extinction');
		}
	}
	public function reveil() {
		$reveil = $this->App->lireFichier('datas/auto-reveil.txt');
	}
	public function stats() {
		$verrouillage = $this->App->getOneState('verouillage');
		$reveilauto = $this->App->getOneState('auto-reveil');
		$chaufauto = $this->App->getOneState('auto-chauffage');
		
		$nb = $this->Prise->getStatsForEeach();
		
		include $this->viewpath.'stats-view.php';
	}
	public function chauffage() {
		$this->App->ecrireFichier('datas/auto-chauffage.txt',$this->val);
	}
	public function mouvement() {
		$contenu = $this->App->lireFichier('datas/verouillage.txt'); 
		if($contenu == 1){
			$this->Sms->sendSMS('+33'.NUMSMS.'','Alerte déclenchée, mouvement détecté dans la maison.','PREMIUM','Gladys');
			$this->Gladys->direPhrase('Alarme. Appel vocal en cours vers le commissariat.');
		}
	}
	public function ping() {
		$pc = exec('ping -c 1 -W 1 '.IPPC.'');
		if ($pc == "") {
			$checked = '';
		} else {
			$checked = 'checked';
		}
		include($this->viewpath.'ping-view.php');
	}
	public function decodeur() {
		$this->Prise->code = '11100';
		$this->Prise->toggle('1','decodeur',$this->val);			
	}

	public function tablette() {
		$this->Prise->code = '11100';
		$this->Prise->toggle('2','',$this->val);			
	}

	public function lampe($lampe_to_toggle) {
		$this->Prise->code = '10101';
		$this->Prise->toggle($lampe_to_toggle,'lampe'.$lampe_to_toggle,$this->val);			
	}

	public function camera() {
		include $this->viewpath.'musique-view.php';
	}

	public function routeur() {
		include $this->viewpath.'gladys-view.php'; 
	}

	public function glad() {
		$question = $_POST['text'];
		$this->Gladys->respond($question);
	}

	public function __construct() {
		$this->layoutpath = getcwd().'/app/Views/layout/';
		$this->viewpath = getcwd().'/app/Views/';
		$this->rootpath = getcwd().'/';

		$models = ['AppModel','SmsModel','MusicModel','GladysModel','PriseModel'];
		foreach ($models as $model) {
			$instance_name = str_replace('Model','',$model);
			$this->$instance_name = new $model;
		}

		if ( isset($_POST['val']) )
			$this->val=$_POST['val'];
		elseif ( isset($_GET['val']) )
			$this->val=$_GET['val'];

	}
}

<?php 
class AppController {	

	public function index() {
		$states = $this->App->getCurrentState();
		include($this->layoutpath.'header.php');
		include($this->viewpath.'index-view.php');
		include($this->layoutpath.'footer.php');
	}
	public function temp() {
		if ($this->App->validateTempResult()) {
			$temperature = $_GET['temp'];
			$this->Capteur->find('auto-chauffage');
			$this->Capteur->value($temperature);
			$this->Capteur->update();
			$this->App->toggleChauffage($temp);
		}
	}
	public function allumertout() {
		$this->Prise->code = '10101';
		$this->Prise->toggleSeveral(array(1,2,3,4),1);
		$this->Gladys->direPhrase('C\'est fait.');
	}
	public function eteindretout() {
		$this->Prise->code = '10101';
		$this->Prise->toggleSeveral(array(1,2,3,4),0);
		$this->Gladys->direPhrase('C\'est fait.');
	}
	public function deverrouiller() {
		$this->Prise->find('verouillage');
		$this->Prise->toggle(1);
		$this->App->save();
	}
	public function verouiller() {
		$this->Prise->code = '10101';
		$this->Prise->toggleSeveral(array(1,2,3,4),0);
		$this->Gladys->pause();
		$this->Gladys->direPhrase('Maison verouillée.');
	}
	public function serveur() {
		$this->Gladys->direPhrase('Le serveur redémarre.');
		$this->App->ecrireDate('reboot');
		$this->App->augmenterVisite('serveur');
		exec('sudo reboot');
	}
	public function ouvrir() {
		$this->Prise->find('porte');
		$this->Prise->toggle(0);
		sleep(7);
		$this->Prise->toggle(1);	
	}
	public function pc() {
		if($this->val) { 
			$this->Pc->turnOn();
		}
		else {
			$this->Pc->turnOff();
		}
	}
	public function reveil() {
		$reveil = $this->Gladys->find('auto-reveil');
	}
	public function stats() {
		$verrouillage = $this->App->getOneState('verouillage');
		$reveilauto = $this->App->getOneState('auto-reveil');
		$chaufauto = $this->App->getOneState('auto-chauffage');
		
		$nb = $this->Prise->getStatsForEeach();
		
		include $this->viewpath.'stats-view.php';
	}
	public function chauffage() {
		$this->Gladys->find('auto-chauffage');
		$this->Gladys->statut = $this->val;
		$this->Gladys->update();
	}
	public function mouvement() {
		$contenu = $this->App->lireFichier('datas/verouillage.txt'); 
		if($contenu == 1){
			$this->Sms->sendSMS('+33'.NUMSMS.'','Alerte déclenchée, mouvement détecté dans la maison.','PREMIUM','Gladys');
			$this->Gladys->direPhrase('Alarme. Appel vocal en cours vers le commissariat.');
		}
	}
	public function ping() {
		$checked = $this->Pc->ping();
		include($this->viewpath.'ping-view.php');
	}
	public function decodeur() {
		$this->Prise->find('decodeur');			
		$this->Prise->toggle($this->val);
	}

	public function lampe($lampe_to_toggle) {
		$this->Prise->find('lampe'.$lampe_to_toggle);			
		$this->Prise->toggle($this->val);
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

		$models = scandir(getcwd().'/app/Models/', 1);

		foreach ($models as $model) {
			if (!is_dir(getcwd().'/app/Models/'.$model)) {
				$instance_name = str_replace('Model.php','',$model);
				$model_name = str_replace('.php','',$model);
				$this->$instance_name = new $model_name;
			}
		}

		if ( isset($_POST['val']) )
			$this->val=$_POST['val'];
		elseif ( isset($_GET['val']) )
			$this->val=$_GET['val'];

	}
}

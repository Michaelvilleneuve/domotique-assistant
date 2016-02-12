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
		$states = $this->Model->getCurrentState();
		// On passe le tout au layout et à la vue initiale
		include($this->layoutpath.'header.php');
		include($this->viewpath.'index-view.php');
		include($this->layoutpath.'footer.php');
	}
	// Méthodes publiques
	public function temp() {
		if (isset($_GET['temp'])){
			if ($this->Model->validateTempResult()) {
				$file = $this->Model->ecrireFichier('temperature.txt', str_replace('.00',$temp));
				$this->Model->toggleChauffage($temp);
			}
		}
	}
	public function allumertout() {
		$this->Prise->toggleSeveral(array(1,2,3,4),1,'10101');
		$this->Prise->toggle('1','',1,'11100');		
		$this->Gladys->direPhrase('C\'est fait.');
	}
	public function deverrouiller() {
		$this->Model->ecrireFichier('verouillage.txt', $this->val);
	}
	public function verouiller() {
		$this->Prise->toggleSeveral(array(1,2,3,4),0,'10101');	
		$this->Prise->toggle('1','',0,'11100');
		$this->Gladys->pause();
		$this->Gladys->direPhrase('Maison verouiller.');
	}
	public function eteindretout() {
		$this->Prise->toggleSeveral(array(1,2,3,4),0,'10101');
		$this->Prise->toggle('1','',0,'11100');		
		$this->Gladys->direPhrase('C\'est fait.');
	}
	public function serveur() {
		$this->Gladys->direPhrase('Le serveur redémarre.');
		$this->Model->ecrireDate('reboot');
		$this->Model->augmenterVisite('serveur');
		exec('sudo reboot');
	}
	public function ouvrir() {
		$this->Prise->toggle('3','verouillage',0,'11100');
		$this->Prise->toggle('2','',0,'11100');	
		sleep(7);
		$this->Prise->toggle('2','',1,'11100');	
	}
	public function pc() {
		if($_POST['val']==1) {exec('wakeonlan '.$this->adressemac.'');
			$this->Model->augmenterVisite('pc');
			$this->Gladys->direPhrase('demarrage.');
		}
		else {
			exec('sudo curl http://'.IPPC.':7760/poweroff > /dev/null 2>&1');
			$this->Gladys->direPhrase('Extinction');
		}
	}
	public function reveil() {
		$reveil = $this->Model->lireFichier('datas/auto-reveil.txt');
	}
	public function stats() {
		$verrouillage = $this->Model->getOneState('verouillage');
		$reveilauto = $this->Model->getOneState('auto-reveil');
		$chaufauto = $this->Model->getOneState('auto-chauffage');
		
		$nb = $this->Prise->getStatsForEeach();
		
		include $this->viewpath.'stats-view.php';
	}
	public function chauffage() {
		$this->Model->ecrireFichier('datas/auto-chauffage.txt',$this->val);
	}
	public function mouvement() {
		$contenu=file_get_contents('datas/verouillage.txt'); 
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
		$content = '
		     <input type="checkbox" id="pc"'.$checked.'>
	         <div class="checkbox"></div>
             <script>
             	$("#pc").change(function() {
					post(\'pc\');
				});
			</script>
         ';
		echo $content;
	}
	public function decodeur() {
		$this->Prise->toggle('1','decodeur',$this->val,'11100');			
	}

	public function tablette() {
		$this->Prise->toggle('2','',$this->val,'11100');			
	}

	public function lampe($lampe_to_toggle) {
		$this->Prise->toggle($lampe_to_toggle,'lampe'.$lampe_to_toggle,$this->val,'10101');			
	}

	public function camera() {
		include $this->viewpath.'musique-view.php';
	}

	public function routeur() {
		include $this->viewpath.'gladys-view.php'; 
	}

	public function glad() {
		include 'Gladys.php';
	}

	public function __construct() {
		include_once(getcwd().'/config/config.php');
		include_once(getcwd().'/app/Models/AppModel.php');
		include_once(getcwd().'/app/Models/SmsModel.php');
		include_once(getcwd().'/app/Models/GladysModel.php');
		include_once(getcwd().'/app/Models/PriseModel.php');

		$this->layoutpath = getcwd().'/app/Views/layout/';
		$this->viewpath = getcwd().'/app/Views/';
		$this->rootpath = getcwd().'/';

		$this->Sms = new SmsModel();
		$this->Model = new AppModel();
		$this->Gladys = new GladysModel();
		$this->Prise = new PriseModel();

		if ( isset($_POST['val']) )
			$this->val=$_POST['val'];
		elseif ( isset($_GET['val']) )
			$this->val=$_GET['val'];

	}
	private function load($folder, $class_name) {
		include_once(getcwd().'/app/Models/'.$class_name.'.php');
	}
}

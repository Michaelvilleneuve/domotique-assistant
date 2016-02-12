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

include_once("smsenvoi/smsenvoi.php");

class Controller {	
	// Adresse IP du raspberrypi
	private $iprasp = '192.168.X.X';
	// Adresse Mac du PC à réveiller
	private $adressemac = 'XX:XX:XX:XX:XX:XX';
	// Ip du PC à réveiller
	private $ippc = '192.168.X.X';
	// Numéro SMS sans le 0
	private $numsms = '606060606';
	// Utilisation d'un capteur de température
	private $capteurtemp = true;
	// Utilisation d'un capteur d'humidité
	private $capteurhum = true;
	private $numbprises = 4;
	private $val;
	private $sms;
	
	public function indexAction() {
		$this->getCurrentState();
		// On passe le tout au layout et à la vue initiale
		include('header.php');
		include('index-view.php');
		include('footer.php');
	}
	// Méthodes publiques
	public function temp() {
		if (isset($_GET['temp'])){
			if ($this->validateTempResult()) {
				$file = $this->ecrireFichier('temperature.txt', str_replace('.00',$temp));
				$this->toggleChauffage($temp);
			}
		}
	}
	
	public function allumertout() {
		$this->changerPrises(array(1,2,3,4),1,'10101');
		$this->changerPrise('1','',1,'11100');		
		$this->direPhrase('C\'est fait.');
	}
	public function deverrouiller() {
		$deverouillage = fopen('verouillage.txt', 'r+');
		fseek($deverouillage, 0);
		fputs($deverouillage, $this->val); 
		fclose($deverouillage);
	}
	public function verouiller() {
		$this->changerPrises(array(1,2,3,4),0,'10101');	
		$this->changerPrise('1','',0,'11100');
		$this->pause();
		exec("sudo pkill mpg321");
		$this->direPhrase('Maison verouiller.');
	}
	public function eteindretout() {
		$this->changerPrise(array(1,2,3,4),0,'10101');
		$this->changerPrise('1','',0,'11100');		
		$this->direPhrase('C\'est fait.');
	}
	public function serveur() {
		$this->direPhrase('Le serveur redémarre.');
		$this->ecrireDate('reboot');
		$this->augmenterVisite('serveur');
		exec('sudo reboot');
	}
	public function ouvrir() {
		$this->changerPrise('3','verouillage',0,'11100');
		$this->changerPrise('2','',0,'11100');	
		sleep(7);
		$this->changerPrise('2','',1,'11100');	
	}
	public function pc() {
		if($_POST['val']==1) {exec('wakeonlan '.$this->adressemac.'');
			$this->augmenterVisite('pc');
			$this->direPhrase('demarrage.');
		}
		else {
			exec('sudo curl http://'.$this->ippc.':7760/poweroff > /dev/null 2>&1');
			$this->direPhrase('Extinction');
		}
	}
	public function reveil() {
		$reveil = $this->lireFichier('datas/auto-reveil.txt', 'r+');
	}
	public function stats() {
		$verrouillage = file_get_contents('datas/verouillage.txt');

		if($verrouillage=='1'){$verrouillage='checked';}else{$verrouillage='';}
		
		$chaufauto = fopen('datas/auto-chauffage.txt', 'r+');
		$chaufauto= fgets($chaufauto);

		if($chaufauto=='1'){$chaufauto='checked';}else{$chaufauto='';}
		
		$reveilauto = fopen('datas/auto-reveil.txt', 'r+');
		$reveilauto= fgets($reveilauto);

		if($reveilauto=='1'){$reveilauto='checked';}else{$reveilauto='';}
		
		$nb = $this->getStatsForEeach();
		
		include('stats-view.php');
	}
	public function chauffage() {
		$chauffage = fopen('datas/auto-chauffage.txt', 'r+');
		fseek($chauffage, 0);
		fputs($chauffage, $this->val); 
		fclose($chauffage);
	}
	public function mouvement() {
		$contenu=file_get_contents('datas/verouillage.txt'); 
		if($contenu == 1){
			$this->sms->sendSMS('+33'.$this->numsms.'','Alerte déclenchée, mouvement détecté dans la maison.','PREMIUM','Gladys');
			$this->direPhrase('Alarme. Appel vocal en cours vers le commissariat.');
		}
	}
	public function ping() {
		$pc = exec('ping -c 1 -W 1 '.$this->ippc.'');
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
		$this->changerPrise('1','decodeur',$this->val,'11100');			
	}

	public function tablette() {
		$this->changerPrise('2','',$this->val,'11100');			
	}

	public function lampe($lampe_to_toggle) {
		$this->changerPrise($lampe_to_toggle,'lampe'.$lampe_to_toggle,$this->val,'10101');			
	}

	public function camera() {
		include('musique-view.php');
	}

	public function routeur() {
		include 'gladys-view.php'; 
	}

	public function glad() {
		include 'Gladys.php';
	}

	public function __construct() {

		$this->sms=new smsenvoi();
		if ( isset($_POST['val']) )
			$this->val=$_POST['val'];
		elseif ( isset($_GET['val']) )
			$this->val=$_GET['val'];

	}

	// Méthodes privées

	private function validateTempResult() {
		$temp = $_GET['temp'];
		$temperatureprec = file_get_contents('datas/temperature.txt');
		$difference = $temp-$temperatureprec;
		return ($difference < 3 && $difference > -3) ? true : false;
	}

	private function toggleChauffage($temp) {
		$statut= file_get_contents('datas/auto-chauffage.txt');
		if ($statut == 1) {
			if ($temp < 19) {
				$this->changerPrise('4','',1,'11100');
			}
			elseif ($temp > =20) {
				$this->changerPrise('4','',0,'11100');
			}
		}
		if($temp>32){
			$this->sms->sendSMS('+33'.$this->numsms.'','Température anormale détectée dans la maison.','PREMIUM','Gladys');
		}
	}
	private function getCurrentState() {
		// On regarde l'état des différentes prises
		$states = [];
		for ($i = 0; $i < $this->numbprises; $i++) {
			$states['lampe'.$i] = $this->lireFichier('datas/lampe'.$i.'.txt', 'r+');
			$states['lampe'.$i] = ($states['lampe'.$i] == '1') ? 'checked' : '';
		}

		$states['decodeur'] = $this->lireFichier('datas/decodeur.txt');
		$states['decodeur'] = ($states['decodeur'] == '1') ? 'checked' : '';

		if($this->capteurtemp){
			// Temperature 
			$states['temperature'] = $this->lireFichier('datas/temperature.txt')."°C"; 
			$states['temperature'] = str_replace('.000', '', $states['temperature']);
		}
		if($this->capteurhum){
			// Humidité 
			$states['humidite'] = $this->lireFichier('datas/humidite.txt')."%"; 
			$states['humidite'] = str_replace('.000', '', $states['humidite']);
		}
		return $states;
	}
	private function direPhrase($phrase) {
		exec('sudo amixer cset numid=1 -- 0');
		exec('mpg321 temp.mp3'); 
		exec('sudo amixer cset numid=1 -- 2000');
		
		return $phrase;
	}
	private function ecrireDate($fichier){
		$date = date('j/n \à H:i');
		$this->ecrireFichier('stats/'.$fichier.'-compteur.txt',$date)
	}
	private function augmenterVisite($appareil){
		$nombrevisite = $this->lireFichier('stats/'.$appareil.'-compteur.txt');
		$nombrevisite++;
		$this->ecrireFichier($fichier,$nombrevisite);
	}
	private function afficherUtilisation($appareil) {
		$this->lireFichier('stats/'.$appareil.'-compteur.txt');
		
	}
	
	// Gestion des données
	private function ecrireFichier($fichier,$contenu) {
		fopen($fichier, 'r+');
		fseek($fichier, 0);
		fputs($fichier, $contenu); 
		fclose($fichier);
	}

	private function lireFichier($fichier) {
		$fichier = fopen($fichier, 'r+');
		$content = fgets($fichier);
		fclose($fichier);

		return $content;
	}

	// Gestion de la musique
	private function play() {
		exec('sudo mocp -S');
		exec('sudo mocp -c');
		exec('sudo mocp -a /var/www/music');
		exec('sudo mocp -t shuffle');
		exec('sudo mocp -p');
	}
	
	private function pause() {
		exec('sudo mocp -x');
		exec('sudo pkill mpg321');
	}

	private function suivant() {
		exec('sudo mocp -f');
	}

	private function precedent() {
		exec('sudo mocp -r');
	}

	// Gestion des prises 
	private function changerPrise($numero,$nom,$val,$code){
		exec('sudo /home/pi/rcswitch-pi/./send '.$code.' '.$numero.' '.$val.'');
		$lampe = fopen('datas/'.$nom.'.txt', 'r+');
		if(!empty($nom)){
			fseek($lampe, 0);
			fputs($lampe, $val); 
			fclose($lampe);
			$this->augmenterVisite($nom);
		}
	}

	private function changerPrises($prises,$val,$code){
		foreach($prises as $prise) {
			$this->changerPrise($prise,'lampe'.$prise,$val,$code)
		}
	}

	private function getStatsForEeach() {
		$nb = array('lampe1','lampe2','lampe3','lampe4','pc','sonnette','serveur');
		foreach ($nb as $stat) {
			$this->afficherUtilisation($stat);
		}
		$nb['datereboot'] = $this->afficherUtilisation('reboot');

		return $nb;
	}
}

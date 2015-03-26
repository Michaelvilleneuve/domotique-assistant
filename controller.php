<?php 
include_once("smsenvoi/smsenvoi.php");

class Controller {	
	// Adresse IP du raspberrypi
	private $iprasp = '192.168.X.X';
	// Adresse Mac du PC à réveiller
	private $adressemac = 'XX:XX:XX:XX:XX:XX'
	// Ip du PC à réveiller
	private $ippc = '192.168.X.X';
	// Numéro SMS sans le 0
	private $numsms = '606060606';
	// Utilisation d'un capteur de température
	private $capteurtemp = true;
	private $val;
	
	public function indexAction() {
		$lampe1 = fopen('datas/lampe1.txt', 'r+');
		$lampe1= fgets($lampe1);
		if($lampe1=='1'){$lampe1='checked';}else{$lampe1='';}
		$lampe2 = fopen('datas/lampe2.txt', 'r+');
		$lampe2= fgets($lampe2);
		if($lampe2=='1'){$lampe2='checked';}else{$lampe2='';}
		$lampe3 = fopen('datas/lampe3.txt', 'r+');
		$lampe3= fgets($lampe3);
		if($lampe3=='1'){$lampe3='checked';}else{$lampe3='';}
		$lampe4 = fopen('datas/lampe4.txt', 'r+');
		$lampe4= fgets($lampe4);
		if($lampe4=='1'){$lampe4='checked';}else{$lampe4='';}
		$decodeur = fopen('datas/decodeur.txt', 'r+');
		$decodeur = fgets($decodeur);
		if($decodeur=='1'){$decodeur='checked';}else{$decodeur='';}
		if($this->capteurtemp == true){
		// Temperature 
		$temperature = file_get_contents('datas/temperature.txt')."°C"; 
		$temperature = str_replace('.000', '', $temperature);
		}
		if($this->capteurtemp == true){
		// Humidité 
		$humidite = file_get_contents('datas/humidite.txt')."%"; 
		$humidite = str_replace('.000', '', $humidite);
		}
		include('Header.php');
		include('index-view.php');
		include('Footer.php');
	}
	private function direPhrase($phrase) {
		exec('sudo amixer cset numid=1 -- 0');
		$heure = date("H");
		if ($heure<23 && $heure>7){
		exec('mpg321 "http://translate.google.com/translate_tts?tl=fr&q='.urlencode($phrase).'"');
		}
		exec('sudo amixer cset numid=1 -- 2000');
		
		echo $phrase;
	}
	private function ecrireDate($fichier){
		$file = fopen('stats/'.$fichier.'-compteur.txt', 'r+');
		$date = date('j/n \à H:i');
					fseek($file, 0);
					fputs($file, $date); 
					fclose($file);
	}
	private function augmenterVisite($idlampe){
		$fichier = fopen('stats/'.$idlampe.'-compteur.txt', 'r+');
		$nb = fgets($fichier);
		$nb++;
					fseek($fichier, 0);
					fputs($fichier, $nb); 
					fclose($fichier);
	}
	private function afficherUtilisation($idlampe) {
		$fichier = fopen('stats/'.$idlampe.'-compteur.txt', 'r+');
		$nb = fgets($fichier);
		fclose($fichier);
		return $nb;
	}
	
	/************* METHODES MUSIQUES **********/
	
	private function play() {
		exec('sudo mocp -S');
		exec('sudo mocp -c');
		exec('sudo mocp -a /var/www/music');
		exec('sudo mocp -t shuffle');
		exec('sudo mocp -p');
	}
	
	private function pause() {
		exec('sudo mocp -x');
	}
	private function suivant() {
		exec('sudo mocp -f');
	}
	private function precedent() {
		exec('sudo mocp -r');
	}
	/**********************************************************************/
	private function changerPrise($numero,$nom,$val,$code){
		exec('sudo /home/pi/rcswitch-pi/./send '.$code.' '.$numero.' '.$val.'');
		$lampe = fopen('datas/'.$nom.'.txt', 'r+');
		if(!empty($nom)){
		fseek($lampe, 0);
		fputs($lampe, $val); 
		fclose($lampe);
		$this->augmenterVisite($nom);}
	}
	public function ajaxAction() {
		switch($_GET['action']){
			/* RAFRAICHISSEMENT TEMPERATURE + GESTION CHAUFFAGE */
			case 'temp':
				if($this->capteurtemp == true){
					// Temperature 
				if (isset($_GET['temp'])){
					$temp = $_GET['temp'];
					$temperatureprec = file_get_contents('datas/temperature.txt');
					$difference = $temp-$temperatureprec;
					$min = -3;
					if($difference<3 && $difference>$min){
					////////
					$file = fopen('temperature.txt', 'r+');
					fseek($file, 0);
					fputs($file, $temp); 
					fclose($file);
					$statut= file_get_contents('datas/auto-chauffage.txt');
						if($statut == 1){
								if($temp<19){
									exec('curl http://'.$this->iprasp.'/index.php?q=ajax\&action=lampe4\&val=1  > /dev/null 2>&1');
								}
								elseif($temp>=20){
									$lampe4= file_get_contents('lampe1.txt');
											if($lampe4==1){
											exec('curl http://'.$this->iprasp.'/index.php?q=ajax\&action=lampe4\&val=0  > /dev/null 2>&1');
											}
								}
						}
						if($temp>32){
							$sms=new smsenvoi();
							$sms->sendSMS('+33'.$this->numsms.'','Température anormale détectée dans la maison.','PREMIUM','Gladys');
						}
					/////////
					}
				}
				}
			break;
			/* TOUT ALLUMER */
			case 'allumertout':
					$this->changerPrise('4','lampe4',1,'10101');	
					$this->changerPrise('3','lampe4',1,'10101');	
					$this->changerPrise('2','lampe4',1,'10101');	
					$this->changerPrise('1','lampe4',1,'10101');
					$this->changerPrise('1','',1,'11100');		
					$this->direPhrase('ses fait.');
			break;
			case 'deverrouiller':
					$deverouillage = fopen('verouillage.txt', 'r+');
					fseek($deverouillage, 0);
					fputs($deverouillage, $this->val); 
					fclose($deverouillage);
			break;
			/* VEROUILLAGE, ALARME, EXTINCTION */
			case 'verouiller':
					$this->changerPrise('4','lampe4',0,'10101');	
					$this->changerPrise('3','lampe4',0,'10101');	
					$this->changerPrise('2','lampe4',0,'10101');	
					$this->changerPrise('1','lampe4',0,'10101');
					// decodeur tv
					$this->changerPrise('1','',0,'11100');
					$this->pause();
					exec("sudo pkill mpg321");
					$this->direPhrase('Maison verouiller.');
					sleep(5);
					$this->changerPrise('3','verouillage',1,'11100');
			break;
			/* EXTINCTION */
			case 'eteindretout':
					$this->changerPrise('4','lampe4',0,'10101');	
					$this->changerPrise('3','lampe4',0,'10101');	
					$this->changerPrise('2','lampe4',0,'10101');	
					$this->changerPrise('1','lampe4',0,'10101');
					$this->changerPrise('1','',0,'11100');		
					$this->direPhrase('ses fait.');
			break;
			/* REBOOT SERVEUR */
			case 'serveur':
				$this->direPhrase('Le serveur redemarre.');
				$this->ecrireDate('reboot');
				$this->augmenterVisite('serveur');
				exec('sudo reboot');
				
			break;
			/* OUVERTURE VERROU */
			case 'ouvrir':
				$this->changerPrise('3','verouillage',0,'11100');
				$this->changerPrise('2','',0,'11100');	
				sleep(7);
				$this->changerPrise('2','',1,'11100');	
			
			break;
			/* FERMETURE VERROU */
			case 'fermer':
				$this->changerPrise('3','',1,'11100');
			break;
			/* ALLUMAGE, EXCTINCTION PC */
			case 'pc':
				if($_POST['val']==1) {exec('wakeonlan '.$adressemac.'');
				$this->augmenterVisite('pc');
				$this->direPhrase('demarage.');}
				else {
				exec('sudo curl http://'.$this->ippc.':7760/poweroff > /dev/null 2>&1');
				$this->direPhrase('Le pc va saiteindre');
				}
			break;
			/* Décodeur */
			case 'decodeur':
			$this->changerPrise('1','decodeur',$this->val,'11100');			
			break;
			/* tablette */ 
			case 'tablette':
			$this->changerPrise('2','',$this->val,'11100');			
			break;
			/* CHAUFFAGE */ 
			case 'lampe4':
			$this->changerPrise('4','lampe4',$this->val,'10101');			
			break;
			/* LED */
			case 'lampe3':
			$this->changerPrise('3','lampe3',$this->val,'10101');				
			break;
			/* LAMPE SECONDAIRE 2 */
			case 'lampe2':
			$this->changerPrise('2','lampe2',$this->val,'10101');	
			break;
			/* LAMPE PRINCIPALE */ 
			case 'lampe1':
			$this->changerPrise('1','lampe1',$this->val,'10101');	
			break;
			/* Réveil auto */
			case 'reveil':
				$reveil = fopen('datas/auto-reveil.txt', 'r+');
				fseek($reveil, 0);
				fputs($reveil, $this->val); 
				fclose($reveil);
			break;
			/* Chauffage auto */
			case 'chauffage':
				$chauffage = fopen('datas/auto-chauffage.txt', 'r+');
				fseek($chauffage, 0);
				fputs($chauffage, $this->val); 
				fclose($chauffage);
			break;
			/* DÉTECTION */
			case 'mouvement':
				$contenu=file_get_contents('datas/verouillage.txt'); 
				if($contenu == 1){
				$sms=new smsenvoi();
				$sms->sendSMS('+33'.$this->numsms.'','Alerte déclenchée, mouvement détecté dans la maison.','PREMIUM','Gladys');
				$this->direPhrase('Alarme enclencher. Appel vocal en cours vers le commissariat.');
				}
			break;
			// Affichage direct caméra
			case 'camera':
				include('musique-view.php');
			break;
			// Affichage stats
			case 'stats':
				$verrouillage = file_get_contents('datas/verouillage.txt');
				if($verrouillage=='1'){$verrouillage='checked';}else{$verrouillage='';}
				$chaufauto = fopen('datas/auto-chauffage.txt', 'r+');
				$chaufauto= fgets($chaufauto);
				if($chaufauto=='1'){$chaufauto='checked';}else{$chaufauto='';}
				$reveilauto = fopen('datas/auto-reveil.txt', 'r+');
				$reveilauto= fgets($reveilauto);
				if($reveilauto=='1'){$reveilauto='checked';}else{$reveilauto='';}
				$nb=[];
				$nb['lampe1'] = 	$this->afficherUtilisation('lampe1');
				$nb['lampe2'] = 	$this->afficherUtilisation('lampe2');
				$nb['lampe3'] = 	$this->afficherUtilisation('lampe3');
				$nb['lampe4'] = 	$this->afficherUtilisation('lampe4');
				$nb['pc'] = 		$this->afficherUtilisation('pc');
				$nb['sonnette'] = 	$this->afficherUtilisation('sonnette');
				$nb['serveur'] = 	$this->afficherUtilisation('serveur');
				$nb['datereboot'] = $this->afficherUtilisation('reboot');
				include('stats-view.php');
			break;
			case 'routeur':
				include 'gladys-view.php'; 
			break;
			// PING DU PC UNIQUEMENT
			case 'ping':
					$pc = exec('ping -c 1 -W 1 '.$this->ippc.'');
					if ($pc == "") {
						echo '<input type="checkbox" id="pc">
             <div class="checkbox"></div>
             <script>$("#pc").change(function() {
	post(\'pc\');
});</script>
             ';
						} else {
							echo '<input type="checkbox" id="pc" checked>
             <div class="checkbox"></div>
             <script>$("#pc").change(function() {
	post(\'pc\');
});</script>
             ';
							}
			break;
			/* VUE GLADYS */
			case 'glad':
				include 'Gladys.php';
				break;
		}
	}
	public function __construct(){
		if(isset($_POST['val'])){
			$this->val=$_POST['val'];
		}
		elseif(isset($_GET['val'])) {
			$this->val=$_GET['val'];
		}
	}
}

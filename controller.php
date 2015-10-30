<?php 
/*
* Copyright 2015 Michaël Villeneuve
* 
* Controller.php 
*
* Ce fichier controlle l'ensemble des fonctionnalités de l'assistant.
* Il permet aussi la configuration globale via les attributs privés du controlleur.
*
* La méthode indexAction est appellée au lancement du site pour afficher le layout et la première vue.
* Les autres vues sont appellées en Ajax via la méthode ajaxAction et les différents cases du switch
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
	private $val;
	private $sms;
	
	public function indexAction() {
		// On regarde l'état des différentes prises
		$lampe1 = fopen('datas/lampe1.txt', 'r+');
		$lampe1= fgets($lampe1);
		$lampe1 = ($lampe1 == '1') ? 'checked' : '';

		$lampe2 = fopen('datas/lampe2.txt', 'r+');
		$lampe2= fgets($lampe2);
		$lampe2 = ($lampe2 == '1') ? 'checked' : '';

		$lampe3 = fopen('datas/lampe3.txt', 'r+');
		$lampe3= fgets($lampe3);
		$lampe3 = ($lampe3 == '1') ? 'checked' : '';

		$lampe4 = fopen('datas/lampe4.txt', 'r+');
		$lampe4= fgets($lampe4);
		$lampe4 = ($lampe4 == '1') ? 'checked' : '';

		$decodeur = fopen('datas/decodeur.txt', 'r+');
		$decodeur = fgets($decodeur);
		$decodeur = ($decodeur == '1') ? 'checked' : '';

		if($this->capteurtemp){
			// Temperature 
			$temperature = file_get_contents('datas/temperature.txt')."°C"; 
			$temperature = str_replace('.000', '', $temperature);
		}
		if($this->capteurhum){
			// Humidité 
			$humidite = file_get_contents('datas/humidite.txt')."%"; 
			$humidite = str_replace('.000', '', $humidite);
		}

		// On passe le tout au layout et à la vue initiale
		include('Header.php');
		include('index-view.php');
		include('Footer.php');
	}
	private function direPhrase($phrase) {
		exec('sudo amixer cset numid=1 -- 0');
		$heure = date("H");
		if ($heure<23 && $heure>7){
			// Création d'une nouvelle ressource cURL 
			$ch = curl_init(); $fh = fopen('temp.mp3', 'w'); 
			// Configuration de l'URL et d'autres options 
			$header[0]="Accept: audio/webm,audio/ogg,audio/wav,audio/\*;q=0.9,application/ogg;q=0.7,video/\*;q=0.6,\*/\*;q=0.5"; 
			$header[]="Accept-Language: fr,fr-FR;q=0.8,en-US;q=0.5,en;q=0.3"; 
			$header[]="DNT: 1"; 
			$header[]="Range: bytes=0"; 
			$header[]="Cookie: "; 
			$header[]="Connection: keep-alive";
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
			curl_setopt($ch, CURLOPT_URL, 'https://translate.google.com/translate_tts?ie=UTF-8&q='.urlencode($phrase).'&tl=fr&total=1&idx=0&textlen=16&tk=411432&client=t&prev=input'); curl_setopt($ch, CURLOPT_HEADER, $header); curl_setopt($ch, CURLOPT_REFERER, "https://translate.google.com/?q=lyra+raspberry"); curl_setopt($ch, CURLOPT_COOKIEFILE, ""); curl_setopt($ch, CURLOPT_USERAGENT, "User-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64; rv:40.0) Gecko/20100101 Firefox/40.0"); curl_setopt($ch, CURLOPT_FILE, $fh); 
			// Récupération de l'URL et affichage sur le naviguateur 
			$final=curl_exec($ch); echo $final; 
			// Fermeture de la session cURL 
			curl_close($ch); 
			exec('mpg321 temp.mp3'); 
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
			$this->augmenterVisite($nom);
		}
	}
	public function ajaxAction() {
		switch($_GET['action']){
			/* RAFRAICHISSEMENT TEMPERATURE + GESTION CHAUFFAGE */
			case 'temp':
				if ($this->capteurtemp) {
					// Temperature 
					if (isset($_GET['temp'])){
						/* On écarte les erreurs (température supérieure à 3 degrès par rapport à la mesure précédente) */
						$temp = $_GET['temp'];
						$temperatureprec = file_get_contents('datas/temperature.txt');
						$difference = $temp-$temperatureprec;
						$min = -3;
						if($difference<3 && $difference>$min){
							$file = fopen('temperature.txt', 'r+');
							fseek($file, 0);
							fputs($file, $temp); 
							fclose($file);
							$statut= file_get_contents('datas/auto-chauffage.txt');
							if ($statut == 1) {
								if ($temp<19) {
									exec('curl http://'.$this->iprasp.'/index.php?q=ajax\&action=lampe4\&val=1  > /dev/null 2>&1');
								}
								elseif ($temp>=20) {
									$lampe4= file_get_contents('datas/lampe1.txt');
									if ($lampe4==1)
										exec('curl http://'.$this->iprasp.'/index.php?q=ajax\&action=lampe4\&val=0  > /dev/null 2>&1');
								}
							}
							if($temp>32){
								$this->sms->sendSMS('+33'.$this->numsms.'','Température anormale détectée dans la maison.','PREMIUM','Gladys');
							}
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
				$this->direPhrase('C\'est fait.');
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
				$this->direPhrase('C\'est fait.');
			break;
			/* REBOOT SERVEUR */
			case 'serveur':
				$this->direPhrase('Le serveur redémarre.');
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
				if($_POST['val']==1) {exec('wakeonlan '.$this->adressemac.'');
					$this->augmenterVisite('pc');
					$this->direPhrase('demarrage.');
				}
				else {
					exec('sudo curl http://'.$this->ippc.':7760/poweroff > /dev/null 2>&1');
					$this->direPhrase('Extinction');
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
					$this->sms->sendSMS('+33'.$this->numsms.'','Alerte déclenchée, mouvement détecté dans la maison.','PREMIUM','Gladys');
					$this->direPhrase('Alarme. Appel vocal en cours vers le commissariat.');
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
				
				$nb=array();
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
		$this->sms=new smsenvoi();
		if ( isset($_POST['val']) )
			$this->val=$_POST['val'];
		elseif ( isset($_GET['val']) )
			$this->val=$_GET['val'];
	}
}

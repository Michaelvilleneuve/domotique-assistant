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
*/ 
$post = str_replace(' glad', '', $_POST['text']);
$post = str_replace(' Glad', '', $post);
$post = str_replace(' Glad ', '', $post);
$post = ucfirst($post);
if(stripos($post, 'Dis') !== false && stripos($post, 'dis') == 0){
	$phraseadire = str_replace('Dis', '', $post);
	$post = 'Dis';
}
if(stripos($post, 'Dit') !== false && stripos($post, 'dis') == 0){
	$phraseadire = str_replace('Dit', '', $post);
	$post = 'Dis';
}
if(stripos($post, 'Exec') !== false && stripos($post, 'Exec') == 0){
	$phraseadire = str_replace('Exec ', '', $post);
	$post = 'Exec';
}
if(stripos($post, 'Vol') !== false && stripos($post, 'Vol') == 0){
	$phraseadire = str_replace('Vol ', '', $post);
	$post = 'Vol';
}
switch($post){
				
												case 'Radio':
													echo 'Nova, frmusique, Classique, Fip. Radio p pour arrêter';
												break;
												
												case 'Nova':
												exec('sudo pkill mpg321');
													exec('mpg321 "http://novazz.ice.infomaniak.ch/novazz-128.mp3"');
												break;
												case 'Virgin':
												exec('sudo pkill mpg321');
													exec('mpg321 "http://vipicecast.yacast.net/virginradio_192"');
												break;
												case 'Nrj':
												exec('sudo pkill mpg321');
													exec('mpg321 "http://95.81.147.24/8470/nrj_165631.mp3"');
												break;
												
												case 'Fun':
												exec('sudo pkill mpg321');
													exec('mpg321 "http://streaming.radio.funradio.fr/fun-1-44-128"');
												break;
												
												case 'Frmusique':
												exec('sudo pkill mpg321');
													exec('mpg321 "http://mp3lg.tdf-cdn.com/francemusique/all/francemusiquehautdebit.mp3"');
												break;
												
												case 'Classique':
												exec('sudo pkill mpg321');
												exec('mpg321 "http://radioclassique.ice.infomaniak.ch/radioclassique-high.mp3?ua=wwwradioclassique"');
												
												case 'Fip':
												exec('sudo pkill mpg321');
													exec('mpg321 "http://mp3lg.tdf-cdn.com/fip/all/fiphautdebit.mp3"');
												break;
											
												case 'Radio p':
													exec('sudo pkill mpg321');
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
												
												case 'Coucou':
															$this->direPhrase('Salut');
															sleep(1);
															$this->direPhrase('toi');
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
															exec('sudo pkill mpg321');
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
															exec('sudo /home/pi/rcswitch-pi/./send 10101 1 1
sudo /home/pi/rcswitch-pi/./send 10101 2 1
sudo /home/pi/rcswitch-pi/./send 10101 3 1
sudo /home/pi/rcswitch-pi/./send 10101 4 1
					');
					$lampe1 = fopen('lampe1.txt', 'r+');
					fseek($lampe1, 0);
					fputs($lampe1, 1); 
					fclose($lampe1);
					$this->augmenterVisite('lampe1');
					$lampe2 = fopen('lampe2.txt', 'r+');
					fseek($lampe2, 0);
					fputs($lampe2, 1); 
					fclose($lampe2);
					$this->augmenterVisite('lampe2');
					$lampe3 = fopen('lampe3.txt', 'r+');
					fseek($lampe3, 0);
					fputs($lampe3, 1); 
					fclose($lampe3);
					$this->augmenterVisite('lampe3');
					$lampe4 = fopen('lampe4.txt', 'r+');
					fseek($lampe4, 0);
					fputs($lampe4, 1); 
					fclose($lampe4);
					$this->augmenterVisite('lampe4');
					$this->direPhrase('Toutes les lumiaires ont aitai allumer.');
												break;
												
												case 'Eteins':
												case 'Eteindre':
												case 'Éteins':
												case 'Éteins les lumières':
												case 'Éteint les lumières':
												case 'Éteindre':
												exec('sudo /home/pi/rcswitch-pi/./send 10101 1 0
sudo /home/pi/rcswitch-pi/./send 10101 2 0
sudo /home/pi/rcswitch-pi/./send 10101 3 0
sudo /home/pi/rcswitch-pi/./send 10101 4 0
					');
					// On éteint tout 
					$lampe1 = fopen('lampe1.txt', 'r+');
					fseek($lampe1, 0);
					fputs($lampe1, 0); 
					fclose($lampe1);
					$this->augmenterVisite('lampe1');
					$lampe2 = fopen('lampe2.txt', 'r+');
					fseek($lampe2, 0);
					fputs($lampe2, 0); 
					fclose($lampe2);
					$this->augmenterVisite('lampe2');
					$lampe3 = fopen('lampe3.txt', 'r+');
					fseek($lampe3, 0);
					fputs($lampe3, 0); 
					fclose($lampe3);
					$this->augmenterVisite('lampe3');
					$lampe4 = fopen('lampe4.txt', 'r+');
					fseek($lampe4, 0);
					fputs($lampe4, 0); 
					fclose($lampe4);
					$this->augmenterVisite('lampe4');
					$this->direPhrase('C fait.');
												break;
												
												default:
													 // $this->direPhrase('desoler, je nez pas compris');
												break;
	
}?>

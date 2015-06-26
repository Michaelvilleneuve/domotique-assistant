<?php 
$reveilauto = file_get_contents('auto-reveil.txt');
$iprasp = '192.168.0.0';
$lampeaallumer = 'lampe3';
$city="Bordeaux";
$country="FR";

// Si l'application est en mode réveil automatisé
if($reveilauto == 1){
	// On récupère la météo
	$url="http://api.openweathermap.org/data/2.5/weather?q=".$city.",".$country."&units=metric&cnt=7&lang=fr";
	$json=file_get_contents($url);
	$data=json_decode($json,true);
	$data['main']['temp']= explode(".", $data['main']['temp']);
	$date = strftime('%A %d %B %Y, et il est %H:%M');
	$healthy = array("Monday", "Tuesday", "Wednesday","Thursday","Friday","Saturday","Sunday","January","February","March","April","May","June","July","August","September","October","November","December");
	$yummy   = array("Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi","Samedi","Dimanche","Janvier","Fevrier","Mars","Avril","Mai","Juin","Juillet","Aout","Septembre","Octobre","Novembre","Décembre");
	$date = str_replace($healthy, $yummy, $date);

	// On traduit la météo
	$mot = array("Clear","Clouds","Rain");
	$mot2 = array("Clair","nuageux","Pluvieux");
	$data['weather'][0]['main'] = str_replace($mot, $mot2, $data['weather'][0]['main']);

	// On formule les phrases
	$text = 'Bonjour';
	$text1 ='Nous somme le  '.$date.''; 
	$text2 ='Il fait '.$data['main']['temp'][0].' degres, et le temps est '.$data['weather'][0]['main'].'.'; 
	$weekend = strftime('%A');

	// Si on est ni un week end, ni un jour férié
	if ($weekend != 'Saturday' && $weekend != 'Sunday'){
		exec('sudo amixer cset numid=1 -- -3000');
		exec('mpg321 "http://'.$iprasp.'/reveil/bonjourmonsieur.mp3"');

		// On parle les phrases
		exec('mpg321 "http://translate.google.com/translate_tts?tl=fr&q='.urlencode($text1).'"');
		exec('mpg321 "http://translate.google.com/translate_tts?tl=fr&q='.urlencode($text2).'"');

		// On allume une lampe
		exec('curl http://'.$iprasp.'/index.php?q=ajax\&action='.$lampeaallumer.'\&val=1  > /dev/null 2>&1');
		sleep(1);
		exec('sudo amixer cset numid=1 -- -4000');

		// On lance une radio doucement
		exec('mpg321 "http://mp3lg.tdf-cdn.com/fip/all/fiphautdebit.mp3"');
	}
}
?>

<?php
class PriseModel extends AppModel {
	// Gestion des prises 
	public function toggle($numero,$nom,$val,$code){
		exec('sudo /home/pi/rcswitch-pi/./send '.$code.' '.$numero.' '.$val.'');
		$lampe = 'datas/'.$nom.'.txt';
		if(!empty($nom)){
			parent::ecrireFichier($lampe,$val);
			parent::augmenterVisite($nom);
		}
	}

	public function toggleSeveral($prises,$val,$code){
		foreach($prises as $prise) {
			$this->changerPrise($prise,'lampe'.$prise,$val,$code);
		}
	}

	public function getStatsForEeach() {
		$nb = ['sonnette'=>'sonnette', 'serveur'=>'serveur','datereboot'=>'reboot'];
		for ($i = 1; $i < $this->numbprises; $i++) {
			$nb['lampe'.$i] = 'lampe'.$i;
		}
		foreach ($nb as $key => $stat) {
			$nb[$key] = parent::afficherUtilisation($stat);
		}
		return $nb;
	}


}
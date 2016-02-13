<?php
class PriseModel extends AppModel {
	public $code;
	// Gestion des prises 
	public function toggle($numero,$nom,$val){
		exec('sudo /home/pi/rcswitch-pi/./send '.$this->code.' '.$numero.' '.$val.'');
		$lampe = 'datas/'.$nom.'.txt';
		if(!empty($nom)){
			parent::ecrireFichier($lampe,$val);
			parent::augmenterVisite($nom);
		}
	}

	public function toggleSeveral($prises,$val){
		foreach($prises as $prise) {
			$this->toggle($prise,'lampe'.$prise,$val,$this->code);
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
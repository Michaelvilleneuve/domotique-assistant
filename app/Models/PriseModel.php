<?php
class PriseModel extends AppModel {
	public $name;
	public $prise_number;
	public $code;
	public $statut;

	public function toggle($val){
		$this->statut = $val;
		exec('sudo /home/pi/rcswitch-pi/./send '.$this->code.' '.$this->prise_number.' '.$val.'');
		$this->update();
	}

	private function update() {
		$this->datas['devices'][$this->name]['statut'] = $this->statut;
		parent::save();
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
	public function find($name) {
		foreach ($this->prises as $prise_name => $prise) {
			if ($prise_name == $name) {
				$this->name = $prise_name;
				$this->code = $prise['code'];
				$this->prise_number = $prise['prise_number'];
				$this->statut = $prise['statut'];
				return $prise;
			}
		}
	}
	public function __construct() {
		parent::__construct();
		$this->prises = $this->datas['devices'];
	}
}
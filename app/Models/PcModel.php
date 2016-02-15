<?php 
class PcModel {
	public function turnOn() {
		exec('wakeonlan '.$this->adressemac.'');
		$this->App->augmenterVisite('pc');
		$this->Gladys->direPhrase('Démarrage.');		
	}
	public function turnOff() {
		exec('sudo curl http://'.$this->ip.':7760/poweroff > /dev/null 2>&1');
		$this->Gladys->direPhrase('Extinction');
	}
	public function ping() {
		$pc = exec('ping -c 1 -W 1 '.IPPC.'');
		$checked = ($pc == "") ? '':'checked';
		
		return $checked;
	}
	public function __construct() {
		$this->adressemac = ADRESSEMAC;
		$this->ip = IPPC;
	}
}
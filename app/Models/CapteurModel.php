<?php 
class CapteurModel extends AppModel {
	public function find($name) {
		foreach ($this->capteurs as $capteur_name => $value) {
			if ($capteur_name == $name) {
				$this->name = $capteur_name;
				$this->value = $value;
				return $value;
			}
		}
	}
	public function update() {
		$this->datas['sensors'][$this->name] = $this->value;
		parent::save();
	}
	public function get_temperature() {
		return str_replace('.00','',$this->find('temperature')).'°C';
	}
	public function get_humidite() {
		return str_replace('.00','',$this->find('humidite')).'%';
	}
	public function __construct() {
		parent::__construct();
		$this->capteurs = $this->datas['sensors'];
	}
}
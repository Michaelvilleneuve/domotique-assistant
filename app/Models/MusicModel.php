<?php
class MusicModel {
	// Gestion de la musique
	public function play() {
		exec('sudo mocp -S');
		exec('sudo mocp -c');
		exec('sudo mocp -a /var/www/app/assets/music');
		exec('sudo mocp -t shuffle');
		exec('sudo mocp -p');
	}
	
	public function pause() {
		exec('sudo mocp -x');
		exec('sudo pkill mpg321');
	}

	public function suivant() {
		exec('sudo mocp -f');
	}

	public function precedent() {
		exec('sudo mocp -r');
	}
}
?>
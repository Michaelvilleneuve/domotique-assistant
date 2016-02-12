<?php
class GladysModel {
	public function direPhrase($phrase) {
		exec('sudo amixer cset numid=1 -- 0');
		exec('mpg321 temp.mp3'); 
		exec('sudo amixer cset numid=1 -- 2000');
		
		return $phrase;
	}

}
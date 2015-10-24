<?php

class ValidateInput {

		
	public function validateLength($string) {
		if (mb_strlen($string) < 3 || empty($string)) {
			return false;
		}
		return true;		
	}

		
	public function validateCharacters($string) {
		if($string != strip_tags($string)) {
			return false;
		}
		return true;
	}	
}
<?php

class ValidateUsername {


	public function validateUsernameLength($username) {
		if (mb_strlen($username) < 3 || empty($username)) {
			return false;
		}
		return true;		
	}

	public function validateCharacters($username) {
		if($username != strip_tags($username)) {
			return false;
		}
		return true;
	}
} 

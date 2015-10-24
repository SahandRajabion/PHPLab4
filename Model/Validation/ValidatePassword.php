<?php

class ValidatePassword {


	public function validatePasswordLength($password, $confirmPassword) {
		if(mb_strlen($password) && mb_strlen($confirmPassword) < 6 || empty($password) && empty($confirmPassword) ) {
			return false;
		} 
		return true;
	}

	public function validateIfSamePassword($password, $confirmPassword) {
		if ($password != $confirmPassword) {
			return false;
		}
		return true;	
	}	
} 

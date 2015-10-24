<?php

class User {

	private $username;
	private $password;

	public function __construct($username, $password) {
		
		$this->username = $username;
		$this->password = $password;
	}

	public function getUsr() {
		return $this->username;
	}

	public function getPass() {
		return $this->password;
	}	
} 

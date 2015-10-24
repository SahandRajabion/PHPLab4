<?php

require_once("Model/User.php");
require_once("Model/Dao/Repository.php");

class UserRepository extends Repository {
	
	private static $hash = 'hash';
	private static $username = 'username';
	private $db;


	public function __construct() {
		
		$this->dbTable = 'user';
		$this->db = $this->connection();
	}
	

	public function addUser(User $user) {

			$sql = "INSERT INTO $this->dbTable (". self::$username .", ". self::$hash .") VALUES (?,?)";
			$params = array($user->getUsr(), $user->getPass());
			$query = $this->db->prepare($sql);
			$query->execute($params);
	}

	public function getUser($username) {

			$sql = "SELECT * FROM $this->dbTable WHERE (" . self::$username . ") = ?";
			$params = array($username);
			$query = $this->db->prepare($sql);
			$query->execute($params);

			$result = $query->fetch();

			return $result;
	}

	public function userExists($username) {
			
			$sql = "SELECT * FROM $this->dbTable WHERE " . self::$username . " = ?";
			$params = array($username);
			$query = $this->db->prepare($sql);
			$query->execute($params);

			$results = $query->fetch();

			if ($results == false) {

				return false;
			}
			return true;
	}
}
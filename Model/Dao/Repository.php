<?php

require_once("Settings.php");

abstract class Repository {
	protected $dbCon;
    protected $dbTable;

    protected function connection() {
    	if($this->dbCon == null) {
        $this->dbCon = new \PDO(Settings::$DB_CONNECTION, Settings::$DB_USERNAME, Settings::$DB_PASSWORD);
        
        $this->dbCon->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        return $this->dbCon;
    }
  }
} 

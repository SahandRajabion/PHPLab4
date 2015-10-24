<?php

class UserAgent{
    private $userAgent;

    public function __construct(){
        $this->userAgent = $_SERVER['HTTP_USER_AGENT'];
    }
    public function getUserAgent(){
        return $this->userAgent;
    }
} 

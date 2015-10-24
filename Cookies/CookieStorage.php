<?php


class CookieStorage {

    public function save($name, $user, $expire){
        setcookie($name, $user, $expire);

    }

    public function load($name){
        $ret ="";
        if(isset($_COOKIE[$name])){
           $ret = $_COOKIE[$name];
        }

        return $ret;

    }
} 

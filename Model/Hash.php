<?php

class Hash {


   //Method for hashing script found at : http://php.net/manual/en/function.password-hash.php
   public function crypt($password) {
        $cost = 10;

        $salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');

        $salt = sprintf("$2a$%02d$", $cost) . $salt;

        $hash = crypt($password, $salt);

        return $hash;
   }
} 

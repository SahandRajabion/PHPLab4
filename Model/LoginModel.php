<?php
require_once('./Controller/LoginController.php');

class LoginModel{
    private $username;
    private $hash;
    private $messageId;
    private $cookieExpireTime;
    private $userRepository;

    public function __construct() {
        $this->userRepository = new userRepository();
    }

    
    //Checks active session.
    public function isLoggedIn(){
        if (isset($_SESSION['loggedIn'])){
            return true;
        }
        return false;
    }

    //Logging out user.
    public function doLogOut(){
        if (isset($_SESSION['loggedIn'])) {
           
            $this->messageId = 11;
            session_unset("loggedIn");
        }
    }

    public function setMsg($msgId){
        $this->messageId = $msgId;
    }

    public function getMsg(){
        return $this->messageId;
    }

    /*Handles the login and returns true if user input is correct, 
    else returns validation message*/
    public function doLogInUser($usr, $pass, $msgId){
       
       if (empty($usr) == false) {
           
           $this->getUser($usr);
       }

       //If input is empty (both username&password) or just username.
       if (empty($usr) && empty($pass) || empty($usr)) {
          
           $this->messageId = 0;

        }

        //If password field is empty.
        else if (empty($pass)) {
            
            $this->messageId = 1;

        }

        //If input missmatch stored data.
        else if($usr !== $this->username || crypt($pass, $this->hash) !== $this->hash){
           
            $this->messageId = 2;
        }

        //If input match stored data, then login user.
        if ($usr === $this->username && crypt($pass, $this->hash) === $this->hash) {          
            
            if (isset($_SESSION['loggedIn']) == false) {
            
                $_SESSION['loggedIn'] = $usr;
            }

            $this->messageId = $msgId;

            return true;

        }

        return false;

    }


    //Gets the user info from database.
    public function getUser($username) {
        $data = $this->userRepository->getUser($username);

        $this->username = $data[1];
        $this->hash = $data[2];
    }


    public function getUsername(){
        return $_SESSION['loggedIn'];
    }

     /*If user logged in then store the 
    useragent in a session*/
    public function setUA($userAgent){
        if(isset($_SESSION['userAgent']) == false){
            $_SESSION['userAgent'] = $userAgent;
        }
    }

    /*Returns true if user agent is already logged in, 
    else false (if session is hacked/manipulated).*/
    public function checkUserAgent($ua){
       
        if(isset($_SESSION['userAgent'])){
         
            if($ua === $_SESSION['userAgent']){
           
            return true;

            }
        }
        return false;

    }

    public function getUserAgent(){
        return $_SESSION['userAgent'];
    }

    //Writes expire time for coockie to textfile.
    public function writeCookieTimeToFile(){
        file_put_contents("expire.txt", $this->cookieExpireTime);
    }

    //Gets expire time for coockie from textfile.
    public function getCookieTimeFromFile(){
        return file_get_contents("expire.txt");
    }
    
    //Sets the time interval for expire.
    public function setCookieTime(){
        $this->cookieExpireTime = time()+250;
    }

   public function getCookieTime(){
        return $this->cookieExpireTime;
    }

     public function encryptedPassword($pwd){
        return base64_encode($pwd);
    }

    public function decryptPassword($pwd){
        return base64_decode($pwd);
    }

} 

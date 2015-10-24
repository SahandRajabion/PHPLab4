<?php

require_once("HTMLView.php");
require_once("./Cookies/CookieStorage.php");
require_once("./Model/LoginModel.php");


class LoginView {
    
    private $htmlView;
    private $model;
    private $message;
    
    private $usernameLocation = "username";
    private $checkBoxLocation = "checkbox";
    private $passwordLocation = "password";
    private $registerLocation = "register";
    private $submitLocation = "submit";
    private $register = false;

    private $usr;
    private $pass;

    private $cookiePass;
    private $cookieTime;
    private $cookie;
    private $cryptPass;


    public function __construct(){

        $this->cookie = new CookieStorage();
        $this->htmlView = new HTMLView();
        $this->model = new LoginModel();
        
    }

   

    public function didUserPressLogin(){

        if(isset($_POST[$this->submitLocation])){

            return true;
        }
        
        return false;
    }

   
    public function showLoginpage(){
        
        $username = "";
       
        if($this->register == true || isset($_POST[$this->submitLocation])){
            
            $username = $this->usr;
        }

        $html = "

        <a href='?$this->registerLocation' name='$this->registerLocation'>Registrera ny användare</a>

                   <h1>Logga in</h1>
                   <h4>Ej Inloggad</h4>
                    <form action=?login class='form-horizontal' method=post enctype=multipart/form-data>
                       <fieldset>
                         <legend>Skriv in användarnamn och lösenord</legend>
                         $this->message

                         <div class='form-group'>
                           <label class='col-sm-2 control-label' for='$this->usernameLocation'>Användarnamn: </label>
                           <div class='col-sm-10'>
                             <input id='$this->usernameLocation' class='form-control' value='$username' name='$this->usernameLocation' type='text' maxlength='30' size='20' />
                           </div>
                         </div>

                         <div class='form-group'>
                            <label class='col-sm-2 control-label' for='$this->passwordLocation'>Lösenord: </label>
                            <div class='col-sm-10'>
                              <input id='$this->passwordLocation' class='form-control' name='$this->passwordLocation' type='password' maxlength='20' size='20'>
                            </div>
                         </div>

                         <div class='form-group'>
                           <div class='col-sm-offset-2 col-sm-10'>
                              <div class='checkbox'>
                                 <label>
                                 <input class='$this->checkBoxLocation' type='checkbox' name='$this->checkBoxLocation'/> Håll mig inloggad
                                 </label>
                              </div>
                            </div>
                         </div>

                        <div class='form-group'>
                          <div class='col-sm-offset-2 col-sm-10'>
                            <input class='btn btn-default' name='$this->submitLocation' type='submit' value='Logga in' />
                          </div>
                        </div>
                      </fieldset>
                  </form>";

        return $html;
    }

    public function setRegister($username) {
        
        $this->usr = $username;
        $this->register = true;
    }  


     public function setCookie(){

        if (isset($_POST[$this->checkBoxLocation])) {
            $this->cookie->save($this->usernameLocation, $this->usr, $this->cookieTime);
            $this->cookie->save($this->passwordLocation, $this->cryptPass, $this->cookieTime);
        }

    }

    public function loadCookie(){

        if (isset($_COOKIE[$this->usernameLocation])) {

            $cookieUser = $this->cookie->load($this->usernameLocation);
            $this->usr = $cookieUser;
            $this->cookiePass = $this->cookie->load($this->passwordLocation);
            

            return true;
        }
        return false;
    } 

    public function setCookieTime($expireTime){
        $this->cookieTime = $expireTime;
    }

    public function getCookiePassword(){
        return $this->cookiePass;
    }

    //Unsetting/deleting coockie data.
    public function unsetCookies(){
       
        $this->cookie->save($this->usernameLocation, null, time()-1);
        $this->cookie->save($this->passwordLocation, null, time()-1);
    }

    public function keepLoggedIn(){

        if(isset($_POST[$this->checkBoxLocation])){

            return true;
        }

        return false;

    }

    public function getUserData(){
        $this->usr = $_POST[$this->usernameLocation];
        $this->pass = $_POST['password'];

    }

   
    public function setEncryptedPassword($pwd){
        $this->cryptPass = $pwd;

    }

    public function setDecryptedPassword($pwd){
        $this->pass = $pwd;
    }

    public function getEncryptedPassword(){
        return $this->cryptPass;
    }
    
    
    public function setMsg($message){
        $this->message = $message;

    }

    //Returns string.
    public function getUsr(){
        return $this->usr;
    }

    //Returns password.
    public function getPass(){
        return $this->pass;
    }

    
    public function didUserPressGoToRegisterPage() {

        if(isset($_GET[$this->registerLocation])) {

            return true;
        }

        return false;
    }


   

    
} 

<?php

require_once("View/LoginView.php");
require_once("View/LoggedInView.php");
require_once("View/HTMLView.php");
require_once("View/LoginMessage.php");
require_once("Model/LoginModel.php");
require_once("Cookies/UserAgent.php");
require_once("View/RegisterView.php");
require_once("Model/Validation/ValidatePassword.php");
require_once("Model/Validation/ValidateUsername.php");
require_once("Model/Hash.php");
require_once("Model/Dao/UserRepository.php");
require_once("Settings.php");
require_once("Model/User.php");

class LoginController {

    private $username;
    private $password;
    private $registerView;
    private $htmlView;

    private $displayLoggedInPage;
    private $displayRegisterPage;
    private $loggedInView;
    private $loginView;
    private $model;
    private $hash;

    private $validateUsername;
    private $validatePassword;
    private $userAgents;
    private $userAgent;
  
   

    public function __construct() {

        $this->htmlView = new HTMLView();
        $this->validateUsername = new ValidateUsername();
        $this->validatePassword = new ValidatePassword();
        $this->loggedInView = new LoggedInView();
        $this->loginView = new LoginView();
        $this->registerView = new RegisterView();
        $this->model = new LoginModel();
        $this->userRepository = new UserRepository();
        $this->hash = new Hash();
    }

    //Calling controlfunctions
    public function doControll() {
        
            $this->visitRegisterPage();
            $this->registerNewUser();
            $this->returnToLoginPage();
            $this->cookieLogIn();
            $this->logOut();
            $this->logIn();
            $this->isLoggedIn();
            $this->renderPage();
        
       
    }

    /*User login function if pressed login 
    & checks if already logged in or not.*/
    public function logIn(){
       
        if ($this->model->isLoggedIn() == false) {
         
            if ($this->loginView->didUserPressLogin() == true) {
                $this->loginView->getUserData();
               
                if($this->loginView->keepLoggedIn() == true) {
                    
                    $msgId = 9;
                }
                else {
                   
                    $msgId = 10;
                }

                $this->setUsername();

                $this->setPassword();


                /*If user input checks with stored user credentials password
                 will be encrypted and coockies data will be stored in the following order.*/
                if ($this->model->doLogInUser($this->username, $this->password, $msgId)) {
                   
                    $this->userRepository->getUser($this->username);

                    $userAgent = new UserAgent();
                    $this->userAgent = $userAgent->getUserAgent();
                    $this->setMsg();

                    $this->cryptPass();
                    $this->getCookieTime();
                    $this->loginView->setCookie();
                    $this->model->setCookieTime();
                    
                    $this->model->writeCookieTimeToFile();
                    $this->model->setUA($this->userAgent);
                    
                    $this->displayLoggedInPage = true;

                } 
                else {

                    $this->setMsg();
                    $this->displayLoggedInPage = false;
                }
            }

            else {

                $this->displayLoggedInPage = false;
            }
        }
    }


    public function getCookieTime(){
        $this->loginView->setCookieTime($this->model->getCookieTime());
    }


    //Logging out user if pressed logout button.
    public function logOut() {

        if ($this->model->isLoggedIn()) {

            if ($this->loggedInView->didUserPressLogOut() == true) {
                if ($this->loginView->loadCookie()) {
                    $this->loginView->unsetCookies();                   
                }

                $this->model->doLogOut();
                $this->setMsg();
            }
        }
    }

    //Sets Username.
    public function setUsername(){
        $this->username = $this->loginView->getUsr();

    }

    //Sets Password.
    public function setPassword(){
        $this->password = $this->loginView->getPass();
    }

   //Checks if user has logged in & if the session in use is not manipulated.
    public function isLoggedIn() {
        $userAgent = new UserAgent();
        $this->userAgents = $userAgent->getUserAgent();

        if($this->model->isLoggedIn() && $this->model->checkUserAgent($this->userAgents)) {
            //Enables logged in page.
            $this->displayLoggedInPage = true;
        }
    }

    /*Function that controls cookie login & checks if user has
     an unexpired cookie stored from last login*/
    public function cookieLogIn() {
        if ($this->model->isLoggedIn() == false && 
            $this->loggedInView->didUserPressLogOut() == false 
            && $this->loginView->didUserPressLogin() == false 
            && $this->loginView->loadCookie() == true) {
            
            //Checks if user cookie actually expired or not.
            if (time() < $this->model->getCookieTimeFromfile()) {
                $this->setUsername();
                $this->setDecryptedPassword();
                $msgId = 13;

                //if user are able to login with stored cookies.
                if ($this->model->doLogInUser($this->username, $this->password, $msgId)) {
                    $userAgent = new UserAgent();
                    $this->userAgent = $userAgent->getUserAgent();
                    $this->model->setUA($this->userAgent);

                    $this->setMsg();

                    //Enables logged in page.
                    $this->displayLoggedInPage = true;
                }

                //if the cookie data was incorrect.
                else {
                    $msgId = 3;
                    $this->model->setMsg($msgId);
                    $this->setMsg();

                    //Unsets cookies if data missmatch.
                    $this->loginView->unsetCookies();
                }

            }

            //else, if the cookie data is expired.
            else {
                $msgId = 3;
                $this->model->setMsg($msgId);
                $this->setMsg();

                //Unsets old cookies if data expired.
                $this->loginView->unsetCookies();

            }
        }
    }

    //Get UserAgent data.
    public function getUserAgent(){
        return $this->userAgent;
    }

  
    //Controls wich pages to be rendered.
    public function renderPage(){
       
        if($this->displayLoggedInPage == true) {
           
            $this->htmlView->echoHTML($this->loggedInView->displayLoggedInPage());  
        }

        else {
            if ($this->displayRegisterPage == true) {
                $this->htmlView->echoHTML($this->registerView->showRegisterPage());
            }
            else {
                //If non of abow is true then stay at login page.
                $this->htmlView->echoHTML($this->loginView->showLoginpage());
            }
        }
    }

   
    //Register a new user.
    public function registerNewUser() {

        if ($this->registerView->didUserPressSubmit() == true) {
            
            $errors = 0;
            
            //Get the new user data registered.
            $username = $this->registerView->getUsr();            
            $password = $this->registerView->getPass();
            $confirmPassword = $this->registerView->getConfirmPass();

            //Validates the new user input (lenght of username).
            if($this->validateUsername->validateUsernameLength($username) == false) {
                $errors++;
                $msgId = 8;
                
                $this->model->setMsg($msgId);
                $this->setMsg();
            }
            else {
                //Validates the new user input (chars of username).
                if($this->validateUsername->validateCharacters($username) == false) {
                    $errors++;
                    $msgId = 4;
                    
                    $this->model->setMsg($msgId);
                    $this->setMsg();                    
                }
            }

            //Validates the new user input (lenght of password).
            if($this->validatePassword->validatePasswordLength($password, $confirmPassword) == false) {
                $errors++;
                $msgId = 7;
                

                $this->model->setMsg($msgId);
                $this->setMsg();
            }
            else {
                //Validates the new user input (match of password).
                if($this->validatePassword->validateIfSamePassword($password, $confirmPassword) == false) {
                    $errors++;
                    $msgId = 6;
                    

                    $this->model->setMsg($msgId);
                    $this->setMsg();
                }
            }

            //If all validation passed..
            if($errors == 0) {


               $hash = $this->hash->crypt($password);
               $newUser = new User($username, $hash);

               //Store new data with cryptes pass in database if username don´t already exists. 
               if ($this->userRepository->userExists($username) == false) {
                $msgId = 12;
                $this->model->setMsg($msgId);
                $this->setMsg();

                $this->userRepository->addUser($newUser);
                $this->loginView->setRegister($username);  

                //Disable register page.
                $this->displayRegisterPage = false;              
               }

               else {

                $msgId = 5;
                $this->model->setMsg($msgId);
                $this->setMsg();
               }     
            }

        }
    }

    //Controls & sets the messages.
    public function setMsg(){
        
        $msg = new LoginMessage($this->model->getMsg());

        if ($this->model->isLoggedIn() == false) {
            
            if ($this->displayRegisterPage == true) {
                $this->registerView->setMsg($msg->getMsg());
            }      

            $this->loginView->setMsg($msg->getMsg());
        }
        else{ 

            $this->loggedInView->setMsg($msg->getMsg());
        }
    }

    //Encrypting passwords.
    public function cryptPass(){
        $this->loginView->setEncryptedPassword($this->model->encryptedPassword($this->loginView->getPass()));
    }

    //Decrypting passwords.
    public function setDecryptedPassword(){
        $this->password = $this->model->decryptPassword($this->loginView->getCookiePassword());
    }

    //Sets variable to true if register page should be shown.
    public function visitRegisterPage() {
        if ($this->loginView->didUserPressGoToRegisterPage()) {
            $this->displayRegisterPage = true;
        }
    }

    //Gets useragent data.
    public function getUserAgents(){
        return $this->userAgents;
    }

    //Sets variable to true if login page should be shown.
    public function returnToLoginPage() {
        if ($this->registerView->didUserPressReturnToLoginPage()) {
            $this->displayRegisterPage = false;
        }
    }

} 

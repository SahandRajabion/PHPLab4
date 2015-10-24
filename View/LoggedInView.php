<?php
require_once("./View/LoginView.php");
require_once("./Model/LoginModel.php");

class LoggedInView{

    private $username;
    private $model;
    private $message;
    private $logOutLocation = 'logOut';
    

    public function __construct(){

        $this->model = new LoginModel();
    }

    public function didUserPressLogOut(){
        if(isset($_GET[$this->logOutLocation])){
            
            return true;
        }

        return false;

    }

    public function setMsg($message){
        $this->message = $message;
    }


      public function displayLoggedInPage(){
        $this->username = $this->model->getUsername();

        $html = "
            <h4>$this->username är inloggad!</h4>
            
            $this->message
            
            <a class='btn btn-default' name='logOut' href='?logOut'>Logga ut</a>";

        return $html;
    }
} 

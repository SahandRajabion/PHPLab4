<?php

class RegisterView {


	private $confirmPassLocation = "confirmpassword";
	private $registerLocation = 'register';
	private $loginLocation = 'login';
	private $usrLocation = "username";
	private $passLocation = "password";

	private $message;

   
	public function didUserPressReturnToLoginPage() {
		
		if (isset($_GET[$this->loginLocation])) {

			return true;
		}

		return false;
	}

	public function showRegisterPage() {
       
        $username = "";
       
        if(isset($_POST[$this->registerLocation])){
            
            $usernameInput = $this->getUsr();
            $username .= strip_tags($usernameInput);
        }

		$html = "

		<a href='?$this->loginLocation'>Tillbaka</a>
			   <h1>Registrera användare</h1>
               <h3>Ej Inloggad, vänligen registrera dig</h3>

                    <form action='' class='form-horizontal' method=post enctype=multipart/form-data>
                       <fieldset>

					      <legend>Registrera ny användare - Skriv in användarnamn och lösenord</legend>
					      $this->message

					      <div class='form-group'>
					        <label class='col-sm-2 control-label' for='$this->usrLocation'>Användarnamn: </label>
					        <div class='col-sm-10'>
					          <input id='username' class='form-control' value='$username' name='$this->usrLocation' type='text' size='20' maxlength='30'/>
					        </div>
					      </div>

					      <div class='form-group'>
					         <label class='col-sm-2 control-label' for='$this->passLocation'>Lösenord: </label>
					         <div class='col-sm-10'>
					           <input id='password' class='form-control' name='$this->passLocation' type='password' size='20' maxlength='20'>
					         </div>
					      </div>

					      <div class='form-group'>
					         <label class='col-sm-2 control-label' for='$this->confirmPassLocation'>Repetera Lösenord: </label>
					         <div class='col-sm-10'>
					           <input id='password2' class='form-control' name='$this->confirmPassLocation' type='password' size='20' maxlength='20'>
					         </div>
					      </div>

					     <div class='form-group'>
				           <div class='col-sm-offset-2 col-sm-10'>
					         <input class='btn btn-default' name='$this->registerLocation' type='submit' value='Registrera' />
					       </div>
					     </div>

					   </fieldset>
			       </form>";

		return $html;
	}



	public function setMsg($message){
        $this->message .= $message;
    }	

 	public function getConfirmPass() {
		if (isset($_POST[$this->confirmPassLocation])) {
			return $_POST[$this->confirmPassLocation];
		}
	}   

	public function getUsr() {
		if (isset($_POST[$this->usrLocation])) {
			return $_POST[$this->usrLocation];
		}
	}

	public function getPass() {
		if (isset($_POST[$this->passLocation])) {
			return $_POST[$this->passLocation];
		}

	}

	public function didUserPressSubmit() {
		
		if (isset($_POST[$this->registerLocation])) {

			return true;
		}
		
		return false;
	}



	
}
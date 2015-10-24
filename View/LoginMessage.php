<?php

class LoginMessage{
    
    
    
    private $messages = array('Användarnamnet saknas, försök igen.', 'Lösenord saknas, försök igen.', "Felaktigt användarnamn och/eller lösenord.",
                              "Felaktig cookie information.", 'Användarnamnet innehåller felaktiga tecken, försök igen.',
                              'Användarnamnet är upptaget.', 'Lösenord matchar ej.', 'Lösenordet har få tecken. Minst 6 tecken måste anges.',
                              'Användarnamnet har för få tecken. Minst 3 tecken måste anges.', "Inloggningen lyckades och vi kommer komma ihåg dig nästa gång.", 
                              "Inloggningen lyckades.", "Du är nu utloggad.",'Registrering av ny användare lyckades.', "Inloggning lyckades via cookies.");

    private $id;

    public function __construct($msgId){
        
        $this->id = $msgId;
    }

    
    //Returns messages in different states of the login system.
    public function getMsg(){
       
       $message = $this->messages[$this->id];

        if($this->id <10){

            $alert = "<div class='alert alert-danger alert-error'>";
        }   

        else{

            $alert = "<div class='alert alert-success'>";
        }

        if(!empty($message)){
           
            $ret = "
                  $alert <p>$message</p> </div>

            ";
        }

        else{

            $ret = "<p>$message</p>";
        }

        return $ret;
    }
} 

<?php

class HTMLView {

    Public function echoHTML($body){
        if($body == NULL){
            throw new \Exception("Body is null");
        }
        
    echo "
       <!DOCTYPE html>
       <html>
       <head>
       <meta charset='utf-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1'>
                <title>Login</title>
       </head>
       <body>

        <div class='container'>
         $body
        </div>

       </body>
       </html>";

    }

} 

<?php

namespace App\config;

class HTTP
{
    public function redirect(string $url) : void 
    {
        header("Location: $url");
        exit;
    }

    public function dynamicRedirect(string $url, Session $session) : void 
    {
        if($session->get('previousURI')){
            $url = $session->use('previousURI');
        }
        $this->redirect($url);
    }
}
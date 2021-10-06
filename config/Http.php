<?php

namespace App\Config;

class HTTP
{
    public function redirect(string $url) : void 
    {
        header("Location: $url");
        exit(0);
    }

    public function dynamicRedirect(string $url, Session $session) : void 
    {
        if ($session->get('previousURI')) {
            $url = $session->use('previousURI');
        }
        $this->redirect($url);
    }
}

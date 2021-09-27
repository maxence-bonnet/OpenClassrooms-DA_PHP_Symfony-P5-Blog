<?php

namespace App\config;

class HTTP
{
    public static function redirect(string $url) : void 
    {
        header("Location: $url");
        exit();
    }

    public static function dynamicRedirect(string $url, Session $session) : void 
    {
        if($session->get('previousURL')){
            $url = $session->use('previousURL');
        }
        self::redirect($url);
    }
}
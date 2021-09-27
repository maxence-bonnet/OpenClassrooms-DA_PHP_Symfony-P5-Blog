<?php

namespace App\config;

use App\src\utils\AlertMessage;

class Session
{
    private $session;

    public function __construct($session)
    {
        $this->session = $session;
    }

    public function set($name, $value)
    {
        $_SESSION[$name] = $value;
    }

    public function get($name)
    {
        if(isset($_SESSION[$name])){
            return $_SESSION[$name];
        }
    }

    public function use($name)
    {
        if(isset($_SESSION[$name])){
            $key = $this->get($name);
            $this->remove($name);
            return $key;
        }
    }

    public function remove($name)
    {
        unset($_SESSION[$name]);
    }

    public function addMessage(string $type, string $message) : void
    {
        $_SESSION['messages'][] = new AlertMessage($type,$message);
    }

    public function messages()
    {
        if(isset($_SESSION['messages'])){
            foreach($_SESSION['messages'] as $key => $alertMessage){
                echo $alertMessage->getAlertMessage();     
            }
            $this->removeMessages();
        }
    }

    public function removeMessages()
    {
        unset($_SESSION['messages']);
    }


    public function start()
    {
        session_start();
    }
    
    public function stop()
    {
        session_destroy();
    }
}
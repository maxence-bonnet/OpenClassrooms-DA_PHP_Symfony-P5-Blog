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

    public function set($name, $value) : void
    {
        $_SESSION[$name] = $value;
        $this->session[$name] = $value;
    }

    public function remove($name) : void
    {
        unset($_SESSION[$name]);
    }

    public function get($key) : mixed
    {
        return isset($this->session[$key]) ? $this->session[$key] : null;
    }

    /**
     * Return $value for the given $key then delete $key
     * 
     * @param $key
     * @return mixed
     */
    public function use($key) : mixed
    {
        if($this->get($key)){
            $value = $this->get($key);
            $this->remove($key);
            return $value;
        }
        return null;
    }

    /**
     * Add new message to be displayed later (kind of a notification)
     * 
     * @param $type, $message
     * @return void
     */
    public function addMessage(string $type, string $message) : void
    {
        $this->set('messages', array(new AlertMessage($type,$message)));
    }

    public function start() : void
    {
        session_start();
    }
    
    public function stop() : void
    {
        session_destroy();
    }
}
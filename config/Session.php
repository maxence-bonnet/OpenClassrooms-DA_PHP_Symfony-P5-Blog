<?php

namespace App\Config;

use App\Src\Utils\AlertMessage;

class Session
{
    const REFRESH_INTERVAL = 7200; // 2 hours

    public $session;

    public function __construct($session)
    {
        $this->session = $session;
        $this->checkRefresh();
    }

    private function checkRefresh()
    {
        if ($this->get('refreshed') && $this->get('refreshed') + self::REFRESH_INTERVAL < time()) {
            $this->refreshSessId();
        }
    }

    private function refreshSessId()
    {
        $previousSessId = session_id();
        $result = session_regenerate_id();
        if (!$result) {
            session_id($previousSessId);
            return;
        }
        $this->set('refreshed', time());
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
    public function use($key)
    {
        if ($this->get($key)) {
            $value = $this->get($key);
            $this->remove($key);
            return $value;
        }
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
        session_unset();
        session_destroy();
    }
}
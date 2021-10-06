<?php

namespace App\Config;

class Request
{
    private $get;
    private $post;
    private $server;
    private $session;
    
    public function __construct()
    {
        $this->get = isset($_GET) ? new Parameter($_GET) : null;
        $this->post = isset($_POST) ? new Parameter($_POST) : null;
        $this->server = isset($_SERVER) ? new Parameter($_SERVER) : null;
        $this->session = isset($_SESSION) ? new Session($_SESSION) : null;
    }

    /**
     * @return mixed
     */
    public function getGet()
    {
        return $this->get;
    }

    /**
     * @return mixed
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * @return mixed
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     * @return mixed
     */
    public function getSession()
    {
        return $this->session;
    }
}

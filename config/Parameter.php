<?php

namespace App\config;

class Parameter
{
    private $parameter;

    public function __construct($parameter)
    {
        $this->parameter = $parameter;
    }

    /**
     * @param $key
     * @return mixed
     */
    public function get($key)
    {
        return isset($this->parameter[$key]) ? $this->parameter[$key] : null;
    }

    /**
     * @param $key, $value
     * @return void
     */    
    public function set($key, $value)
    {
        $this->parameter[$key] = $value;
    }

    /**
     * @return Parameter
     */
    public function all()
    {
        return $this->parameter;
    }
}
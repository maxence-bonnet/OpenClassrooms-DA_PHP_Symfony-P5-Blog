<?php

namespace App\Config;

class Parameter
{
    private $parameter;

    public function __construct($parameter)
    {
        $this->parameter = $parameter;
    }

    /**
     * @param  $key
     * @return mixed
     */
    public function get($key)
    {
        return isset($this->parameter[$key]) ? $this->parameter[$key] : null;
    }

    /**
     * @param  $key, $value
     * @return void
     */    
    public function set($key, $value) : void
    {
        $this->parameter[$key] = $value;
    }

    /**
     * @return Parameter
     */
    public function all() : array
    {
        return $this->parameter;
    }
}

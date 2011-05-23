<?php

class Core_Request
{

    public $route = null;
    private $_config = null;

    public function __CONSTRUCT()
    {
        $this->_config = new Config_Bootstrap;
    }

    public function getRoute()
    {
        return $this->route;
    }

    public function getBootstrap()
    {
        return $this->_config;
    }

}
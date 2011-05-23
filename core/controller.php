<?php

class Core_Controller
{

    public function __CONSTRUCT()
    {
        if(is_callable(array($this, 'begin'))) {
            $this->begin();
        }
    }

    public function __DESTRUCT()
    {
        if(is_callable(array($this, 'end'))) {
            $this->end();
        }
    }

    public function begin()
    {
    }

    public function end()
    {
    }

    public function run($action_name)
    {
        $acl = new Config_Acl;
        foreach($acl->list as $level) {
            $action = $action_name . $level;
            if(is_callable(array($this, $action))) {
                $acl_check = 'acl' . $level;
                if($acl->$acl_check()){
                    $this->$action();
                    return;
                }
            }
        }
        Core_Factory::make('Core_Error')->fatal("501 - Action doesn't exist or is out of your reach.");
    }

    public function getRequest()
    {
        if(isset($GLOBALS['request'])) {
            return $GLOBALS['request'];
        } else {
            return Core_Factory::make('Core_Error')->fatal('503 - Request object not present.');
        }
    }

    protected function privateResource($resource)
    {
        return realpath('./resources/' . $resource);
    }
}
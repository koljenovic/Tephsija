<?php

class Core_Route
{

    private $_config = null;
    private $_route = null;

    public function __CONSTRUCT()
    {
        $this->_config = new Config_Route;
    }

    // Maps URL parameters in groups according to config
    // Returns an array
    public function map($raw_route)
    {
        $map = array();
        $exploded_route['cont'] = explode('/', $raw_route);
        $exploded_route['map'] = array();
        $ruleset = $this->_config->ruleset;
        while(list($key, $value) = each($ruleset)) {
            for($i = $key; $i < count($exploded_route['cont']); $i++) {
                $exploded_route['map'][$i] = $key;
            }
        }
        return $this->_route = $exploded_route;
    }

    // Intercharges route id/name
    // Returns false if no match found
    private function interchange($id_or_name)
    {
        if(is_int($id_or_name) && ($id_or_name < count($this->_route['map']))) {
            $id_or_name = $this->_route['map'][$id_or_name];
        }
        foreach($this->_config->ruleset as $key => $name) {
            if($key === $id_or_name) {
                return $name;
            }
            if($name == $id_or_name) {
                return $key;
            }
        }
        return false;
    }

    // Pimped _GET alternative
    // Returns false if there is no element or element out of range
    public function get($id, $sanitize = true)
    {
        if(!is_int($id)) {
            $id = $this->interchange($id);
        }
        if(($id !== false) && ($id < count($this->_route['cont'])) && ($id >= 0)) {
            if($sanitize) {
                return filter_var($this->_route['cont'][$id],
                                  FILTER_SANITIZE_ENCODED,
                                  FILTER_FLAG_ENCODE_LOW |
                                  FILTER_FLAG_ENCODE_HIGH);
            } else {
                return $this->_route['cont'][$id];
            }
        }
        return false;
    }

    // Returns an array of all the elements of the same group
    // Returns false if wrong group is supplied
    public function getGroup($id, $sanitize = true)
    {
        $unified = array();
        if($id < 0) {
            return false;
        }
        if(!is_int($id)) {
            $id = $this->interchange($id);
            if($id === false) {
                return false;
            }
        }
        foreach($this->_route['map'] as $id_route => $id_group) {
            if($id_group == $id) {
                if($sanitize) {
                    $unified[] = filter_var($this->_route['cont'][$id_route],
                                            FILTER_SANITIZE_ENCODED,
                                            FILTER_FLAG_ENCODE_LOW |
                                            FILTER_FLAG_ENCODE_HIGH);
                } else {
                    $unified[] = $this->_route['cont'][$id_route];
                }
            }
        }
        return $unified;
    }

    public function URL($route = '')
    {
        $url = (!empty($_SERVER['HTTPS'])) ?
            "https://" . $_SERVER['SERVER_NAME'] :
            "http://" . $_SERVER['SERVER_NAME'];
        return $url . '/' . $GLOBALS['request']->getBootstrap()->app_path . $route;
    }

    public function resourceURL($route = '')
    {
        $url = (!empty($_SERVER['HTTPS'])) ?
            "https://" . $_SERVER['SERVER_NAME'] :
            "http://" . $_SERVER['SERVER_NAME'];
        return $url . '/' . $GLOBALS['request']->getBootstrap()->app_path . 'public/' . $route;
    }
}
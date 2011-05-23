<?php

class Core_View
{
  
    private $data = array();
    private $response = '';
    private $_prepend = array();

    public function __get($name)
    {
        return $this->data[$name];
    }

    public function set($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function prepend($class, $method)
    {
        $this->_prepend[] = array('class' => $class, 'method' => $method);
    }

    public function render($class = null, $method = null)
    {
        foreach($this->_prepend as $prepend){
            $this->append($prepend['class'], $prepend['method']);
        }
        echo $this->response;
        if(($class !== null) && ($method !== null)){
            $path = realpath('./app/view/' . $class . '/');
            require_once($path . '/' . $method . '.tph');
        }
    }

    public function append($class, $method, $render = FALSE, $where = 'app')
    {
        $path = realpath('./' . $where  . '/view/' . $class . '/');
        ob_start();
        require($path . '/' . $method . '.tph');
        $this->response .= ob_get_contents();
        ob_end_clean();
        return $this->response;
        if($render === TRUE){
            echo $this->response;
        }
    }
}
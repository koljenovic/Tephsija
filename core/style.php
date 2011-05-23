<?php

class Core_Style{

    private $data = array();
    private $response = '';

    public function __get($name)
    {
        return $this->data[$name];
    }

    public function set($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function render($stylesheet, $dir = '')
    {
        $path = realpath('./public/' . $dir);
        ob_start();
        require_once($path . '/' . $stylesheet . '.css');
        $this->response .= ob_get_contents();
        ob_end_clean();
        return $this->response = '<style type="text/css">' . "\n" . $this->response . "\n</style>\n";
    }
}
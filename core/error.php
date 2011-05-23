<?php

class Core_Error
{

    private $_defaultView = null;
    private $_errorView = null;

    public function __CONSTRUCT()
    {
        $this->_defaultView = Core_Factory::make('Core_View');
        $this->_defaultView->prepend('layout', 'default');
        $this->_errorView = Core_Factory::make('Core_View');
    }

    public function error($msg)
    {
        $msg = 'Error: ' . $msg;
        $this->_errorView->set('msg', $msg);
        $this->_defaultView->set('content', $this->_errorView->append('index', 'error'));
        $this->_defaultView->render();
        return false;
    }

    public function fatal($msg)
    {
        $msg = 'Fatal error: ' . $msg;
        $this->_errorView->set('msg', $msg);
        $this->_defaultView->set('content', $this->_errorView->append('index', 'error'));
        $this->_defaultView->render();
        return false;
    }
}
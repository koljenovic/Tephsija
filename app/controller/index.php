<?php

class App_Controller_Index extends Core_Controller
{

    private $_defaultView = null;

	public function begin() {
		$this->_defaultView = Core_Factory::make('Core_View');
		$this->_defaultView->prepend('layout', 'default');
	}

    public function indexPublic()
    {
        $indexView = Core_Factory::make('Core_View');
        $indexView->set('image', $this->getRequest()->getRoute()->resourceURL('img/logo.jpg'));
        $this->_defaultView->set('content', $indexView->append('index', 'placeholder'));
		$this->_defaultView->render();
    }
}
<?php

setlocale(LC_ALL, 'bs_BA.utf8');
//ini_set('display_errors', 1);

function __autoload($class)
{
    $path = strtolower(str_replace('_', '/', $class));
    if(file_exists($path . '.php')) {
        require_once($path . '.php');
    } else if(file_exists($path . '/main.php')) {
        require_once($path . '/main.php');
    } else {
        throw new Exception('Terminal error: class ' . $path . 'desn\'t exist.');
    }
}

$instance = null;
$request = $GLOBALS['request'] = Core_Factory::make('Core_Request');
$route = $GLOBALS['request']->route = Core_Factory::make('Core_Route');
$bootstrap = $request->getBootstrap();

$data = preg_replace('%/' . $bootstrap->app_path . '%sim', '', $_SERVER['REQUEST_URI'], 1);
$route->map($data);

if(($controller = $route->get('controller')) != null) {
    if(Core_Factory::exists('Gear_' . $controller) !== FALSE) {
        $instance = Core_Factory::make('Gear_' . $controller);
    } else {
        $instance = Core_Factory::make($route->get('module') . '_Controller_' . $controller);
    }
    if((($action = $route->get('action')) != null) & ($instance != null)) {
        $instance->run($action);
    } else if($instance != null) {
        $instance->run($bootstrap->default_action);
    }
} else {
    $instance = Core_Factory::make(
                                   ucfirst($bootstrap->default_module) .
                                   '_Controller_' .
                                   ucfirst($bootstrap->default_controller)
                                   );
    $instance->run($bootstrap->default_action);
}
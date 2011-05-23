<?php

class Core_Factory 
{

    public static function exists($class)
    {
        $path = strtolower(str_replace('_', '/', $class)) . '.php';
        if(is_file($path)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public static function make($class, $error = TRUE)
    {
        $path = strtolower(str_replace('_', '/', $class)) . '.php';
        if(is_file($path)) {
            require_once($path);
            return new $class;
        } else if($error !== FALSE) {
            Core_Factory::make('Core_Error')->fatal('501 - ' . strtolower($class) . " -  Requested controller doesn't exist.");
        }
    }

    public static function module($path, $class, $constructOptions = NULL, $error = TRUE)
    {
        $path = 'module/' . strtolower(str_replace('_', '/', $path)) . '.php';
        if(is_file($path)) {
            require_once($path);
            // $class = substr(strrchr($class, '_'), 1);
            return new $class;
        } else if($error !== FALSE) {
            Core_Factory::make('Core_Error')->fatal('501 - ' . strtolower($class) . " - Requested module doesn't exist.");
        }
    }
}
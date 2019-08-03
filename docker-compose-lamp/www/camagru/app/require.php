<?php
// load config
    require_once 'config/config.php';

// util_functions
	require_once 'util_functions/redirect.php';
// autoload libs
    spl_autoload_register(function($className){
        require_once 'libraries/' . $className . '.php';
    });
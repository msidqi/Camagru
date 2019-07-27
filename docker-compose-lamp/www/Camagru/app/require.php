<?php
// load config
    require_once 'config/config.php';

// load libs
    // require_once 'libraries/Core.php';
    // require_once 'libraries/Controller.php';
    // require_once 'libraries/Database.php';


// autoload libs
    spl_autoload_register(function($className){
        require_once 'libraries/' . $className . '.php';
    });
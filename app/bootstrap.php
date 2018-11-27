<?php 
    // load config
    require_once('config/config.php');

    // load helps
    require_once 'helpers/url_helper.php';
    require_once 'helpers/session_helper.php';

    // Autoload Core libraries
    // Takes in parameters from url and picks out the libraries
    // This way it requires less lines of code and clutter
    spl_autoload_register(function($classname){
        require_once 'libraries/' . $classname . '.php';
    });
?>
<?php
require_once '../app/config/config.php';
require_once '../app/helpers/session_helper.php';
require_once '../app/helpers/data_helper.php';


spl_autoload_register(function ($class){
    require_once '../app/libraries/' . $class . '.php';
});

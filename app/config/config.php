<?php
//Url root
define('URLROOT', 'http://localhost/oop_MVC');

//App root
define('APPROOT', dirname(dirname(__FILE__)));

//Site Name
define('SITENAME', 'MVC Site');

//DB

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'mvc_site');

function redirect($page)
{
    header('location:' . URLROOT . '/' . $page);
}
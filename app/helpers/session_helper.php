<?php
session_start();

function isLoggedIn()
{
    if (isset($_SESSION['user_id'])) {
        return true;
    } else {
        return false;
    }
}

function flash($name = "", $message = "", $class = "col-lg-6 col-md-10 col-sm-12 mx-auto alert alert-success"){
    if (!empty($name)) {
        if (!empty($message) && empty($_SESSION[$name])) {
            if(!empty($_SESSION[$name])){
                unset($_SESSION[$name]);
                unset($_SESSION[$name . '_class']);
            }
            if(!empty($_SESSION[$name. '_class'] )){
                unset($_SESSION[$name . '_class']);
            }
            $_SESSION[$name] = $message;
            $_SESSION[$name . '_class'] = $class;

        }else if(empty($message) && !empty($_SESSION[$name])){
            $class = !empty($_SESSION[$name.'_class'])?$_SESSION[$name.'_class']:'';
            echo '<div class="'. $class .'" id="msg-flash">'.$_SESSION[$name].'</div>';
            unset($_SESSION[$name]);
            unset($_SESSION[$name . '_class']);

        }
    }
}
<?php
/*
    * Base controller
    * Load Models and Views
    */

class Controller{
    //Load model
    public function model($model)
    {
        //Required model file
        require_once('../app/models/' . ucwords($model) . '.php');

        //Instantiate the model
        return new $model;
    }
    
    public function view($view, $data = [])
    {
        //check if view file exit
        if (file_exists('../app/views/' . $view . '.php')) {
            require_once('../app/views/' . $view . '.php');
        } else {
            die('View does not exit');
        }
    }
}

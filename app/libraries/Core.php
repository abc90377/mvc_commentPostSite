<?php
    /*
    *main app core class
    *create url and load core controller
    *url format /controller/method/params
    */
    class Core{
        protected $currentController = 'Pages';
        protected $currentMethod = 'index';
        protected $params = [];

        public function __construct()
        {
            $url = $this->getUrl();
            //look in controller for first index
            if(file_exists('../app/controllers/' . ucwords($url[0]) . '.php')){
            //if exist set as currentController,if not it will remain default
            $this->currentController = ucwords($url[0]);
            unset($url[0]);
            }
            
            //required the controller
            require_once '../app/controllers/' . $this->currentController . '.php';

            //Instantiated Controller
            $this->currentController = new $this->currentController;

            //Check for Second part of url(currentMethod)
            if (isset($url[1])) {
                if(method_exists($this->currentController, $url[1])){
                    $this->currentMethod = $url[1];
                }
                unset($url[1]);
            }
            
             //Check for third part of url(Param)
             $this->params = $url ? array_values($url): [];

             call_user_func_array([$this->currentController, $this->currentMethod], $this->params);

        }
        public function getUrl(){
            //將根目錄後的url轉成陣列
            if (isset($_GET['url'])) {
                $url = rtrim($_GET['url'], '/');
                $url = filter_var($url, FILTER_SANITIZE_URL);
                $url = explode('/', $url);

                return $url;
            }
        }

    }
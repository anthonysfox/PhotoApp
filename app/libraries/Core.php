<?php
    /*
    * App Core class
    * Creates url and loads core controller
    * URL FORMAT - /controller/method/params
    */

    class Core {
        protected $currentController = 'Pages';
        protected $currentMethod = 'index';
        protected $params = [];

        public function __construct(){
            //print_r($this->getUrl());

            $url = $this->getUrl();

            // look in controllers for the first value of the array from url
            // ucwords capitalises the first letter of the word to make it like the controllers 
            if(file_exists('../app/controllers/' . ucwords($url[0]) . '.php')){
                $this->currentController = ucwords($url[0]); // setting the controller being used
                // unset 0 index
                unset($url[0]);
            }

            // require the controller
            require_once('../app/controllers/' . $this->currentController . '.php'); // loading the controller

            // instantiate it 
            $this->currentController = new $this->currentController;

            // check for second part of url
            if(isset($url[1])){
                // check to see if method exists
                // method_exists is built in php function 
                if(method_exists($this->currentController, $url[1])){
                    $this->currentMethod = $url[1]; // setting the current method that will be used in the controller that was set above
                    // unset 1 index
                    unset($url[1]);
                }
            }

            //  Get params. if there are parameter itll get added to the array and if not itll just be empty 
            $this->params = $url ? array_values($url) : [];

            // call a callback with array of params
            call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
        }

        public function getUrl() {
            if(isset($_GET['url'])) {
                $url = rtrim($_GET['url'], '/');
                $url = filter_var($url, FILTER_SANITIZE_URL);
                $url = explode('/', $url); // <- this breaks url into parts to take in controller, method, and any parameters 
                return $url;
            }
        }
    }
?>
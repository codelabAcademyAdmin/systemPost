<?php
    class AppRoutes {
        private $routes = array();
        public  function addRoutes($method, $path, $callback) {
            $this->routes[] = array(
                'method' => $method,
                'path' => $path,
                'callback' => $callback
            );
        }
        public  function getRoutes() {
            return $this->routes;
        }
    }

    class AppViews {

        private $routes;
        public function __construct($listRoutes) {
            $this->routes = $listRoutes;
        }

        public function loadViews() {
            $requestMethod = $_SERVER['REQUEST_METHOD'];
            if (isset($_GET['url'])) {
                $requestPath = $_GET['url'];
            }else {
                $requestPath = '/';
            }
            foreach ($this->routes as $route) {
                if ($requestMethod === $route['method'] && $requestPath === $route['path']) {
                    call_user_func($route['callback']);
                    return;
                }
            }   
        }

    }

    class AppScript {
        private $routes;
        public function __construct($listRoutes) {
            $this->routes = $listRoutes;
        }
        public function loadScript() {
            $requestMethod = $_SERVER['REQUEST_METHOD'];
            if (isset($_GET['url'])) {
                $requestPath = $_GET['url'];
            }else {
                $requestPath = '/';
            }
            foreach ($this->routes as $route) {
                if ($requestMethod === $route['method'] && $requestPath === $route['path']) {
                    if ($route['path']=='/') {
                        echo '<script src="scripts/script.index.js"></script>';
                    }else {
                        echo '<script src="scripts/script.'.$route['path'].'.js"></script>';
                    }
                     return;
                }
            }   
        }
    }

?>


<?php
class App {
    protected $controller = 'AuthController';
    protected $method = 'index';
    protected $params = [];

    public function __construct() {
        $url = $this->parseUrl();

        // 1. Controller Logic
        if (isset($url[0])) {
            $controllerName = ucfirst($url[0]) . "Controller";
            if (file_exists("../App/Controllers/" . $controllerName . ".php")) {
                $this->controller = $controllerName;
                unset($url[0]);
                
                // Default the method to index if a specific controller is found
                $this->method = 'index';
            }
        }

        require_once "../App/Controllers/{$this->controller}.php";
        $this->controller = new $this->controller;

        // 2. Method Logic
        if (isset($url[1])) {
            // We check for the method here, but wait for is_callable in step 3
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        // Special case: If the controller is Auth and no method is set, use 'login'
        if (get_class($this->controller) == 'AuthController' && $this->method == 'index' && !isset($url[1])) {
            if (method_exists($this->controller, 'login')) {
                $this->method = 'login';
            }
        }

        // 3. Params & Execution
        $this->params = $url ? array_values($url) : [];
        
        // Use is_callable to ensure the method is PUBLIC
        if (is_callable([$this->controller, $this->method])) {
            call_user_func_array([$this->controller, $this->method], $this->params);
        } else {
            die("Error: Method '{$this->method}' is private, protected, or does not exist in " . get_class($this->controller));
        }
    } // <--- THIS WAS THE MISSING BRACE THAT CAUSED YOUR ERROR

    protected function parseUrl() {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        // Default route if no URL is provided
        return ['Auth', 'login']; 
    }
}
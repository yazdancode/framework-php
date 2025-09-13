<?php

namespace App\Core;

use App\Utilities\Url;

class StupidRouter
{
    private $routes = [];

    public function __construct()
    {
        $this->routes = [
            '/colors/blue' => 'colors/blue.php',
            '/colors/red' => 'colors/red.php',
            '/colors/green' => 'colors/green.php',
        ];
    }

    public function addRoute($route, $view)
    {
        $this->routes[$route] = $view;
    }

    public function run()
    {
        $current_route = Url::current_route();

        foreach ($this->routes as $route => $view) {
            if ($current_route === $route) {
                $this->includeAndDie(BASEPATH . "views/$view");
            }
        }
        header('HTTP/1.0 404 Not Found');
        $this->includeAndDie(BASEPATH . "views/errors/404.php");
    }

    private function includeAndDie($viewPath)
    {
        include $viewPath;
        die();
    }
}

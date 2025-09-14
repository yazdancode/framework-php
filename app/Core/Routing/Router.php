<?php

namespace App\Core\Routing;

use App\Core\Request;

class Router
{
    private $request;

    private $routes;

    private $current_route;

    public function __construct()
    {
        $this->request = new Request();
        $this->routes = Route::routes();
        $this->current_route = $this->findRoute($this->request) ?? null;
        var_dump($this->current_route);

    }

    public function findRoute(Request $request)
    {
    $requestMethod = strtoupper($request->method());
    $requestUri = trim($request->uri(), '/');

    foreach ($this->routes as $route) {
        $routeMethods = array_map('strtoupper', $route['methods']);
        $routeUri = trim($route['uri'], '/');
        if (in_array($requestMethod, $routeMethods) && $requestUri === $routeUri) {
            return $route;
        }
    }
    return null;
    }


    public function run()
    {
    // If no route matched the URI, return 404
    if (!$this->current_route) {
        http_response_code(404);
        echo "404 Not Found";
        return;
    }

    $requestMethod = strtoupper($this->request->method());
    $routeMethods = array_map('strtoupper', $this->current_route['methods']);

    // If the request method is not allowed for this URI, return 405
    if (!in_array($requestMethod, $routeMethods)) {
        http_response_code(405);
        echo "405 Method Not Allowed";
        return;
    }

    $action = $this->current_route['action'] ?? null;

    // If action is null, nothing to execute
    if (!$action) {
        echo "No action defined for this route.";
        return;
    }

    // If action is a Closure
    if ($action instanceof \Closure) {
        echo $action($this->request);
        return;
    }

    // If action is a Controller@method string
    if (is_string($action)) {
        // Example: 'HomeController@index'
        [$controllerName, $method] = explode('@', $action);
        $controllerClass = "\\App\\Controllers\\$controllerName";

        if (class_exists($controllerClass) && method_exists($controllerClass, $method)) {
            $controller = new $controllerClass();
            echo $controller->$method($this->request);
            return;
        }

        echo "Controller or method not found.";
        return;
    }

    // If action is an array [Controller, method]
    if (is_array($action) && count($action) === 2) {
        [$controllerClass, $method] = $action;
        $controllerClass = "\\App\\Controllers\\$controllerClass";

        if (class_exists($controllerClass) && method_exists($controllerClass, $method)) {
            $controller = new $controllerClass();
            echo $controller->$method($this->request);
            return;
        }

        echo "Controller or method not found.";
        return;
    }

    echo "Invalid action type.";
    }

}

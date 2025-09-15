<?php

namespace App\Core\Routing;

use App\Core\Request;
use Closure;
use JetBrains\PhpStorm\NoReturn;
use RuntimeException;

class Router
{
    private Request $request;
    private array $routes;
    private mixed $current_route;

    public function __construct()
    {
        $this->request = new Request();
        $this->routes = Route::routes();
        $this->current_route = $this->findRoute($this->request);
        // Remove var_dump in production - use logging instead
        // var_dump($this->current_route);
//        var_dump($this->routes);
    }

    public function findRoute(Request $request): ?array
    {
        $requestMethod = strtoupper($request->method());
        $requestUri = trim($request->uri(), '/');

        foreach ($this->routes as $route) {
            $routeMethods = array_map('strtoupper', $route['methods']);
            $routeUri = trim($route['uri'], '/');
            if (in_array($requestMethod, $routeMethods, true) && $this->matchUriPattern($requestUri, $routeUri)) {
                return $route;
            }
        }
        return null;
    }

    /**
     * Match URI pattern with support for route parameters
     */
    private function matchUriPattern(string $requestUri, string $routeUri): bool
    {
        // Exact match
        if ($requestUri === $routeUri) {
            return true;
        }

        // Convert route pattern to regex for parameter matching
        $pattern = preg_replace('/\{(\w+)}/', '(?P<$1>[^/]+)', $routeUri);
        $pattern = "#^$pattern$#";

        return (bool) preg_match($pattern, $requestUri);
    }

    #[NoReturn]
    public function dispatch404(): void
    {
        header('HTTP/1.0 404 Not Found');
        view('errors.404');
        exit;
    }

    #[NoReturn]
    public function dispatch405(): void
    {
        header('HTTP/1.1 405 Method Not Allowed');
        view('errors.405');
        exit;
    }

    public function run(): void
    {
        if (!$this->current_route) {
            $this->dispatch404();
        }

        $requestMethod = strtoupper($this->request->method());
        $routeMethods = array_map('strtoupper', $this->current_route['methods']);

        if (!in_array($requestMethod, $routeMethods, true)) {
            $this->dispatch405();
        }

        $action = $this->current_route['action'] ?? null;

        if (is_null($action) || empty($action)) {
            return;
        }
        if ($action instanceof Closure) {
            echo $action($this->request);
            return;
        }

        // Handle string actions (Controller@method)
        if (is_string($action)) {
            $this->handleStringAction($action);
            return;
        }

        // Handle array actions [Controller, method]
        if (is_array($action) && count($action) === 2) {
            $this->handleArrayAction($action);
            return;
        }

        throw new RuntimeException("Invalid action type for route.");
    }

    private function handleStringAction(string $action): void
    {
        if (!str_contains($action, '@')) {
            throw new RuntimeException("Invalid action format. Expected 'Controller@method'.");
        }

        [$controllerName, $method] = explode('@', $action);
        $controllerClass = "\\App\\Controllers\\$controllerName";

        $this->executeControllerMethod($controllerClass, $method);
    }

    private function handleArrayAction(array $action): void
    {
        [$controllerClass, $method] = $action;

        // If controller is provided as string without namespace
        if (is_string($controllerClass) && !class_exists($controllerClass)) {
            $controllerClass = "\\App\\Controllers\\$controllerClass";
        }

        $this->executeControllerMethod($controllerClass, $method);
    }

    private function executeControllerMethod(string $controllerClass, string $method): void
    {
        if (!class_exists($controllerClass)) {
            throw new RuntimeException("Controller class '$controllerClass' not found.");
        }

        if (!method_exists($controllerClass, $method)) {
            throw new RuntimeException("Method '$method' not found in controller '$controllerClass'.");
        }

        $controller = new $controllerClass();

        // Check if method is callable
        if (!is_callable([$controller, $method])) {
            throw new RuntimeException("Method '$method' in controller '$controllerClass' is not callable.");
        }

        echo $controller->$method($this->request);
    }
}
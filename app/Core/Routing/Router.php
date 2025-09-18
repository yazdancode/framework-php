<?php

namespace App\Core\Routing;

use App\Core\Request;
use Closure;
use JetBrains\PhpStorm\NoReturn;
use ReflectionException;
use ReflectionMethod;
use RuntimeException;
use Yazdan\Helpers\ViewHelper;

class Router
{
    private Request $request;
    private array $routes;
    private ?array $current_route;
    private array $route_params = [];

    public function __construct()
    {
        $this->request = new Request();
        $this->routes = Route::routes();
        $this->current_route = $this->findRoute($this->request);
    }

    public function run(): void
    {
        if (is_null($this->current_route)) {
            $this->dispatch404();
        }

        $requestMethod = strtoupper($this->request->method());
        $routeMethods = array_map('strtoupper', $this->current_route['methods']);

        if (!in_array($requestMethod, $routeMethods, true)) {
            $this->dispatch405();
        }

        $this->runRouteMiddleware();

        $action = $this->current_route['action'] ?? null;

        if (is_null($action) || (is_string($action) && empty($action))) {
            throw new RuntimeException("Route action is not defined for " . ($this->current_route['uri'] ?? 'unknown route'));
        }

        $this->request->setRouteParams($this->route_params);

        if ($action instanceof Closure) {
            echo $action($this->request, ...array_values($this->route_params));
            return;
        }

        if (is_string($action)) {
            $this->handleStringAction($action);
            return;
        }

        if (is_array($action) && count($action) === 2) {
            $this->handleArrayAction($action);
            return;
        }

        throw new RuntimeException("Invalid action type for route.");
    }

    private function runRouteMiddleware(): void
    {
        $middleware = $this->current_route['middleware'] ?? [];

        foreach ($middleware as $middleware_class) {
            if (!class_exists($middleware_class)) {
                throw new RuntimeException("Middleware class '$middleware_class' not found.");
            }

            $middleware_object = new $middleware_class;

            if (!method_exists($middleware_object, 'handle')) {
                throw new RuntimeException("Middleware '$middleware_class' must implement a handle() method.");
            }

            $response = $middleware_object->handle($this->request, $this->route_params);
            if ($response !== null) {
                echo $response;
                exit;
            }
        }
    }

    public function findRoute(Request $request): ?array
    {
        $requestUri = trim($request->uri(), '/');
        $requestMethod = strtoupper($request->method());

        foreach ($this->routes as $route) {
            $routeUri = trim($route['uri'], '/');
            $patternAndParamNames = $this->generateRegexAndParamNames($routeUri);
            $regexPattern = "/^" . $patternAndParamNames['pattern'] . "$/";

            $paramNames = $patternAndParamNames['param_names'];

            if (preg_match($regexPattern, $requestUri, $matches)) {
                $this->route_params = [];

                foreach ($paramNames as $index => $name) {
                    if (isset($matches[$index + 1])) {
                        $this->route_params[$name] = $matches[$index + 1];
                    }
                }

                $routeMethods = array_map('strtoupper', $route['methods']);

                if (in_array($requestMethod, $routeMethods, true)) {
                    return $route;
                }
            }
        }

        return null;
    }

    private function generateRegexAndParamNames(string $routeUri): array
    {
        $paramNames = [];

        $pattern = preg_replace_callback('/\{([a-zA-Z0-9_]+)}/', static function ($matches) use (&$paramNames) {
            $paramName = $matches[1];
            $paramNames[] = $paramName;

            if ($paramName === 'id') {
                return '(\d+)';
            }

            return '([^/]+)';
        }, $routeUri);

        $pattern = str_replace('/', '\/', $pattern);

        return [
            'pattern' => $pattern,
            'param_names' => $paramNames
        ];
    }

    #[NoReturn]
    public function dispatch404(): void
    {
        header('HTTP/1.0 404 Not Found');
        if ($this->request->wantsJson()) {
            echo json_encode(['error' => 'Not Found'], JSON_UNESCAPED_UNICODE);
        } else {
            ViewHelper::view('errors.404');
        }
        exit;
    }

    #[NoReturn]
    public function dispatch405(): void
    {
        header('HTTP/1.1 405 Method Not Allowed');
        if ($this->request->wantsJson()) {
            echo json_encode(['error' => 'Method Not Allowed'], JSON_UNESCAPED_UNICODE);
        } else {
            ViewHelper::view('errors.405');
        }
        exit;
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

        if (!is_callable([$controller, $method])) {
            throw new RuntimeException("Method '$method' in controller '$controllerClass' is not callable.");
        }

        try {
            $reflectionMethod = new ReflectionMethod($controller, $method);
        } catch (ReflectionException $e) {
            throw new RuntimeException("Error in reflecting method '$method' of '$controllerClass'", 0, $e);
        }

        $parameters = $reflectionMethod->getParameters();
        $args = [];

        foreach ($parameters as $param) {
            $paramName = $param->getName();
            $paramType = $param->getType();

            if ($paramType && $paramType->getName() === Request::class) {
                $args[] = $this->request;
            } elseif (isset($this->route_params[$paramName])) {
                $value = $this->route_params[$paramName];
                if ($paramType && $paramType->isBuiltin()) {
                    switch ($paramType->getName()) {
                        case 'int': $value = (int) $value; break;
                        case 'float': $value = (float) $value; break;
                        case 'bool': $value = (bool) $value; break;
                        default: break;
                    }
                }
                $args[] = $value;
            } elseif ($param->isDefaultValueAvailable()) {
                $args[] = $param->getDefaultValue();
            } else {
                throw new RuntimeException("Cannot resolve parameter '$paramName' for controller method '$controllerClass::$method'.");
            }
        }

        echo call_user_func_array([$controller, $method], $args);
    }
}

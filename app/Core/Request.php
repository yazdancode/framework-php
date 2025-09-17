<?php

namespace App\Core;

use Yazdan\Helpers\UrlHelper;

class Request {
    private $params;
    private $method;
    private $agent;
    private $ip;
    private $headers;
    private $json;
    private $uri;
    private $routeParams = [];

    public function __construct()
    {
        $this->method = strtolower($_SERVER['REQUEST_METHOD']);
        $this->agent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
        $this->ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $this->uri = strtok($_SERVER['REQUEST_URI'] ?? '/', '?');
        $this->headers = getallheaders();
        $this->params = $_REQUEST;

        // پردازش درخواست‌های JSON
        if (strpos($_SERVER['CONTENT_TYPE'] ?? '', 'application/json') !== false) {
            $this->json = json_decode(file_get_contents('php://input'), true) ?? [];
            $this->params = array_merge($this->params, $this->json);
        }
    }

    public function __get($key)
    {
        return $this->input($key);
    }

    public function input($key, $default = null)
    {
        $value = $this->params[$key] ?? $default;
        return is_string($value) ? htmlspecialchars($value, ENT_QUOTES, 'UTF-8') : $value;
    }

    public function rawInput($key, $default = null)
    {
        return $this->params[$key] ?? $default;
    }

    public function all()
    {
        return $this->params;
    }

    public function method()
    {
        return $this->method;
    }

    public function agent()
    {
        return $this->agent;
    }

    public function ip()
    {
        return $this->ip;
    }

    public function headers()
    {
        return $this->headers;
    }

    public function isAjax()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    public function files($key = null)
    {
        if ($key) {
            return $_FILES[$key] ?? null;
        }
        return $_FILES;
    }

    public function redirect($route)
    {
        header("Location: " . UrlHelper::siteUrl($route));
        exit;
    }

    public function uri()
    {
        return $this->uri;
    }

    public function setRouteParams(array $params)
    {
        $this->routeParams = $params;
        $this->params = array_merge($this->params, $params);
    }

    public function route($key, $default = null)
    {
        return $this->routeParams[$key] ?? $default;
    }

    public function has($key)
    {
        return isset($this->params[$key]);
    }

    public function filled($key)
    {
        return !empty($this->params[$key]);
    }

    public function missing($key)
    {
        return !isset($this->params[$key]);
    }

    public function hasFile($key)
    {
        return isset($_FILES[$key]) && $_FILES[$key]['error'] !== UPLOAD_ERR_NO_FILE;
    }

    public function isValidFile($key)
    {
        return isset($_FILES[$key]) && $_FILES[$key]['error'] === UPLOAD_ERR_OK;
    }

    public function contentType()
    {
        return $_SERVER['CONTENT_TYPE'] ?? '';
    }

    public function isJson()
    {
        return strpos($this->contentType(), 'application/json') !== false;
    }

    public function isFormData()
    {
        return strpos($this->contentType(), 'multipart/form-data') !== false;
    }

    public function expectsJson()
    {
        $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
        return strpos($accept, 'application/json') !== false || $this->isAjax();
    }

    public function json($key = null, $default = null)
    {
        if ($key === null) {
            return $this->json ?? [];
        }
        return $this->json[$key] ?? $default;
    }

    public function isMethod($method)
    {
        return strtoupper($this->method) === strtoupper($method);
    }

    public function isGet()
    {
        return $this->isMethod('GET');
    }

    public function isPost()
    {
        return $this->isMethod('POST');
    }

    public function isPut()
    {
        return $this->isMethod('PUT');
    }

    public function isPatch()
    {
        return $this->isMethod('PATCH');
    }

    public function isDelete()
    {
        return $this->isMethod('DELETE');
    }
}
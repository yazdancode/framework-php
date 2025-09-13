<?php

namespace App\Core;

class Request {
    private $params;
    private $method;
    private $agent;
    private $ip;
    private $headers;
    private $json;

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $this->agent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
        $this->ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $this->headers = getallheaders();
        $this->params = $_REQUEST;
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
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
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
        header("Location: " . site_url($route));
        exit;
    }
}


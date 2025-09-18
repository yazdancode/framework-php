<?php

namespace App\Core;

use JetBrains\PhpStorm\NoReturn;
use Yazdan\Helpers\UrlHelper;

class Request
{
    private array $params;
    private string $method;
    private string $agent;
    private string $ip;
    private array $headers;
    private ?array $json = [];
    private string $uri;
    private array $routeParams = [];

    public function __construct()
    {
        $this->method = strtolower($_SERVER['REQUEST_METHOD'] ?? 'get');
        $this->agent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
        $this->ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $this->uri = strtok($_SERVER['REQUEST_URI'] ?? '/', '?');
        $this->headers = function_exists('getallheaders') ? getallheaders() : [];

        $this->params = $_REQUEST ?? [];

        // پردازش درخواست‌های JSON
        if ($this->isJson()) {
            $this->json = json_decode(file_get_contents('php://input'), true) ?? [];
            $this->params = array_merge($this->params, $this->json);
        }
    }

    /* ------------------- مدیریت پارامترهای روت ------------------- */
    public function addRouteParam(string $key, mixed $value): void
    {
        $this->routeParams[$key] = $value;
    }

    public function getRouteParam(string $key, mixed $default = null): mixed
    {
        return $this->routeParams[$key] ?? $default;
    }

    public function setRouteParams(array $params): void
    {
        $this->routeParams = $params;
        $this->params = array_merge($this->params, $params);
    }

    public function route(string $key, mixed $default = null): mixed
    {
        return $this->routeParams[$key] ?? $default;
    }

    /* ------------------- ورودی‌ها ------------------- */
    public function __get(string $key): mixed
    {
        return $this->input($key);
    }

    public function input(string $key, mixed $default = null): mixed
    {
        return $this->params[$key] ?? $default;
    }

    public function safeInput(string $key, mixed $default = null): mixed
    {
        $value = $this->params[$key] ?? $default;
        return is_string($value) ? htmlspecialchars($value, ENT_QUOTES, 'UTF-8') : $value;
    }

    public function rawInput(string $key, mixed $default = null): mixed
    {
        return $this->params[$key] ?? $default;
    }

    public function all(): array
    {
        return $this->params;
    }

    /* ------------------- اطلاعات درخواست ------------------- */
    public function method(): string
    {
        return $this->method;
    }

    public function isMethod(string $method): bool
    {
        return strtoupper($this->method) === strtoupper($method);
    }

    public function isGet(): bool    { return $this->isMethod('GET'); }
    public function isPost(): bool   { return $this->isMethod('POST'); }
    public function isPut(): bool    { return $this->isMethod('PUT'); }
    public function isPatch(): bool  { return $this->isMethod('PATCH'); }
    public function isDelete(): bool { return $this->isMethod('DELETE'); }

    public function agent(): string
    {
        return $this->agent;
    }

    public function ip(): string
    {
        return $this->ip;
    }

    public function uri(): string
    {
        return $this->uri;
    }

    public function headers(): array
    {
        return $this->headers;
    }

    /* ------------------- نوع درخواست ------------------- */
    public function contentType(): string
    {
        return $_SERVER['CONTENT_TYPE'] ?? '';
    }

    public function isJson(): bool
    {
        return stripos($this->contentType(), 'application/json') === 0;
    }

    public function isFormData(): bool
    {
        return stripos($this->contentType(), 'multipart/form-data') === 0;
    }

    public function expectsJson(): bool
    {
        $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
        return stripos($accept, 'application/json') !== false || $this->isAjax();
    }

    public function wantsJson(): bool
    {
        return $this->expectsJson();
    }

    /* ------------------- JSON ------------------- */
    public function json(?string $key = null, mixed $default = null): mixed
    {
        if ($key === null) {
            return $this->json ?? [];
        }
        return $this->json[$key] ?? $default;
    }

    /* ------------------- متفرقه ------------------- */
    public function isAjax(): bool
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    public function files(?string $key = null): mixed
    {
        if ($key) {
            return $_FILES[$key] ?? null;
        }
        return $_FILES;
    }

    public function hasFile(string $key): bool
    {
        return !empty($_FILES[$key]) && $_FILES[$key]['error'] !== UPLOAD_ERR_NO_FILE;
    }

    public function isValidFile(string $key): bool
    {
        return !empty($_FILES[$key]) && $_FILES[$key]['error'] === UPLOAD_ERR_OK;
    }

    #[NoReturn]
    public function redirect(string $route): void
    {
        header("Location: " . UrlHelper::siteUrl($route));
        exit;
    }

    public function has(string $key): bool
    {
        return isset($this->params[$key]);
    }

    public function filled(string $key): bool
    {
        return !empty($this->params[$key]);
    }

    public function missing(string $key): bool
    {
        return !isset($this->params[$key]);
    }
}

<?php 

namespace App\Utilities;


class Url
{
    public static function current(): string
    {
    $isSecure = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
    $protocol = $isSecure ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $requestUri = $_SERVER['REQUEST_URI'] ?? '/';
    return $protocol . "://" . $host . $requestUri;
    }

    public static function current_route(): string
    {
        return strtok($_SERVER['REQUEST_URI'], '?');
    }
    

}
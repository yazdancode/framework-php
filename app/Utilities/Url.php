<?php 

namespace App\Utilities;


class Url
{
    public static function current()
    {
    $isSecure = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
    $protocol = $isSecure ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $requestUri = $_SERVER['REQUEST_URI'] ?? '/';
    return $protocol . "://" . $host . $requestUri;
    }
    

}
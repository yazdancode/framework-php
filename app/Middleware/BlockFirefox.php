<?php

namespace App\Middleware;
use App\Middleware\Contract\MiddlewareInterface;



class BlockFirefox implements MiddlewareInterface
{
    public function handle(): void
    {
        $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
        if (stripos($ua, 'Firefox') !== false) {
            header('HTTP/1.1 403 Forbidden');
            exit('Access denied: Firefox is not supported.');
        }
    }
}

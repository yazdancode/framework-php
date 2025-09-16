<?php

namespace App\Middleware;

use App\Middleware\Contract\MiddlewareInterface;
use JetBrains\PhpStorm\NoReturn;

class BlockOpera implements MiddlewareInterface
{
    #[NoReturn]
    public function handle(): void
    {
        $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
        if (stripos($ua, 'OPR') !== false || stripos($ua, 'Opera') !== false) {
            header('HTTP/1.1 403 Forbidden');
            exit('دسترسی با مرورگر Opera مسدود است.');
        }
    }
}

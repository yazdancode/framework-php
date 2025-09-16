<?php

namespace App\Middleware;

use App\Middleware\Contract\MiddlewareInterface;
use JetBrains\PhpStorm\NoReturn;

class BlockIE implements MiddlewareInterface
{
    #[NoReturn]
    public function handle(): void
    {
        $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
        if (stripos($ua, 'MSIE') !== false || stripos($ua, 'Trident') !== false) {
            header('HTTP/1.1 403 Forbidden');
            exit('دسترسی با مرورگر Internet Explorer مسدود است.');
        }
    }
}

<?php

namespace App\Middleware;

use App\Middleware\Contract\MiddlewareInterface;
use JetBrains\PhpStorm\NoReturn;
use hisorange\BrowserDetect\Parser;

class BlockIE implements MiddlewareInterface
{
    #[NoReturn]
    public function handle(): void
    {
        $browser = new Parser();
        if ($browser->browserFamily() === 'Internet Explorer') {
            header('HTTP/1.1 403 Forbidden');
            exit('Access denied: Internet Explorer is not supported. ');
        }
    }
}

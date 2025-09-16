<?php

namespace App\Middleware;

use App\Middleware\Contract\MiddlewareInterface;
use hisorange\BrowserDetect\Parser;

class BlockFirefox implements MiddlewareInterface
{
    public function handle(): void
    {
        $browser = new Parser();
        if ($browser->browserFamily() === 'Firefox') {
            header('HTTP/1.1 403 Forbidden');
            exit('Access denied: Firefox is not supported.');
        }
    }
}

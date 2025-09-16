<?php

namespace App\Middleware;

use App\Middleware\Contract\MiddlewareInterface;
use hisorange\BrowserDetect\Parser;

class BlockChrome implements MiddlewareInterface
{
    public function handle(): void
    {
        $browser = new Parser();
        if ($browser->browserFamily() === 'Chrome') {
            header('HTTP/1.1 403 Forbidden');
            exit('Access denied: Chrome is not supported.');
        }
    }
}


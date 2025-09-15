<?php

namespace App\Middleware;


use App\Middleware\Contract\Contract\MiddlewareInterface;

class BlockFirefox implements MiddlewareInterface
{
    public function handle(): void
    {
        global $request;
        die("BlockFirefox");

    }


}

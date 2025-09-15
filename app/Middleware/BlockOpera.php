<?php

namespace App\Middleware;


use App\Middleware\Contract\Contract\MiddlewareInterface;
use JetBrains\PhpStorm\NoReturn;

class BlockOpera implements MiddlewareInterface
{

    #[NoReturn]
    public function handle(): void
    {
        global $request;
        die("BlockOpera");

    }


}

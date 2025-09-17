<?php

namespace App\Controllers;
use Yazdan\Helpers\DebugHelper;

class PostController
{
    public function single(): void
    {
        DebugHelper::niceDump($_GET);
    }
}

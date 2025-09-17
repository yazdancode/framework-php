<?php

namespace Yazdan\Helpers;

class DebugHelper
{
    public static function niceDump(mixed $var): void
    {
        echo "<pre style='display:block; text-align:left; background:#f9f9f9; padding:10px; border:1px solid #ccc;'>";
        var_dump($var);
        echo "</pre>";
    }

    public static function niceDd(mixed $var): void
    {
        self::niceDump($var);
        die();
    }
}

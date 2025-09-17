<?php

namespace Yazdan\Helpers;

class MiscHelper
{
    public static function randomElement(array $arr): mixed
    {
        shuffle($arr);
        return array_pop($arr);
    }

    public static function strContains(string $str, string $needle, bool $caseSensitive = false): bool
    {
        $pos = $caseSensitive ? strpos($str, $needle) : stripos($str, $needle);
        return $pos !== false;
    }
}

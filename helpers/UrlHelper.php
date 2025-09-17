<?php

namespace Yazdan\Helpers;

class UrlHelper
{
    public static function siteUrl(string $route): string
    {
        return getenv('HOST') . $route;
    }

    public static function assetsUrl(string $route): string
    {
        return self::siteUrl("/assets/" . $route);
    }
}

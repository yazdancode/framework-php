<?php

namespace App\Utilities;

class Assets
{
    /**
     * Build the full asset path based on type and route.
     */
    private static function build(string $type, string $route): string
    {
        $host = rtrim($_ENV['HOST'] ?? '', '/');
        return "{$host}/assets/{$type}/{$route}";
    }

    public static function get(string $route): string
    {
        return self::build('', $route);
    }

    public static function css(string $route): string
    {
        return self::build('css', $route);
    }

    public static function js(string $route): string
    {
        return self::build('js', $route);
    }

    public static function img(string $route): string
    {
        return self::build('img', $route);
    }

    public static function fonts(string $route): string
    {
        return self::build('fonts', $route);
    }
}

<?php

use Yazdan\Helpers\DebugHelper;

include "bootstrap/init.php";

// مسیر تعریف‌شده با پارامتر داینامیک
$route = '/post/{slug}';

// تبدیل مسیر به الگوی regex
$route_pattern = '#^' . preg_replace('/\{(\w+)\}/', '(?<$1>[-%\w]+)', $route) . '$#';

// تست با URIهای مختلف
$uri1 = '/post/what-is-php';
$uri2 = '/post/why-you-must-chose-7learn';

// اجرای تست
$result1 = preg_match($route_pattern, $uri1, $matches1);
$result2 = preg_match($route_pattern, $uri2, $matches2);

// خروجی‌ها
DebugHelper::niceDump($route);
DebugHelper::niceDump($route_pattern);

DebugHelper::niceDump($uri1);
DebugHelper::niceDump($result1);
DebugHelper::niceDump($matches1);

DebugHelper::niceDump($uri2);
DebugHelper::niceDump($result2);
DebugHelper::niceDump($matches2);

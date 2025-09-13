<?php

use App\Utilities\Url;
use App\Core\StupidRouter;

include "bootstrap/init.php";

// ایجاد نمونه‌ای از Router
$router = new StupidRouter();

// افزودن مسیرهای جدید (اختیاری، چون در constructor تعریف شده‌اند)
$router->addRoute('/colors/blue', 'colors/blue.php');
$router->addRoute('/colors/red', 'colors/red.php');
$router->addRoute('/colors/green', 'colors/green.php');

// اجرای Router
$router->run();

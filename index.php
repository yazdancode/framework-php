<?php

use App\Core\Routing\Router;
use Yazdan\Helpers\DebugHelper;




include "bootstrap/init.php";


// اجرای Router
//$router = new Router();
//
//$router->run();

//$route = '/post/{slug}';
$route_pattern = '#^/post/(?<slug>[-%\w]+)$#';

$uri1 = 'post/what-is-php';

$uri2 = '/post/why-you-must-chose-7learn';

$result = preg_match($route_pattern, $uri1);
DebugHelper::niceDump($result);
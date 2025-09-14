<?php

use App\Core\Routing\Router;




include "bootstrap/init.php";


// اجرای Router
$router = new Router();

$router->run();
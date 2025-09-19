<?php
use App\Core\Routing\Router;
use App\Models\User;
use Random\RandomException;

include "bootstrap/init.php";

try {
    $user_data = [
        'id' => random_int(5, 1000),
        'name' => "Sara"
    ];
} catch (RandomException $e) {

}

$userModel = new User();
$userModel->create($user_data);

$router = new Router();
$router->run();




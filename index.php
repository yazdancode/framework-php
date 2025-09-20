<?php

use App\Models\User;
use Medoo\Medoo;

include "bootstrap/init.php";

$db = new Medoo([
    'database_type' => 'mysql',
    'database_name' => 'framework',
    'server' => 'localhost',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4'
]);

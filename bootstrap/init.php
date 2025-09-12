<?php
define('BASEPATH', __DIR__ . "/../");

require BASEPATH . "vendor/autoload.php";


use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(BASEPATH);
$dotenv->load();


require BASEPATH . "/helpers/helpers.php";

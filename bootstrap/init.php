<?php
const BASEPATH = __DIR__ . "/../";

require BASEPATH . "vendor/autoload.php";


use App\Core\Request;
use Dotenv\Dotenv;
$request = new Request();
$dotenv = Dotenv::createImmutable(BASEPATH);
$dotenv->load();


require BASEPATH . "/helpers/UrlHelper.php";
require BASEPATH . "/helpers/ViewHelper.php";
require BASEPATH . "/helpers/DebugHelper.php";
require BASEPATH . "/helpers/MiscHelper.php";
require BASEPATH . "/routes/web.php";



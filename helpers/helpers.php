<?php
function site_url($route): string
{
    return $_ENV['HOST']. $route;
}

function assets_url($route): string
{
    return site_url("assets/".$route);
}


function random_element($arr): string
{
    shuffle($arr);
    return array_pop($arr);
}


function view($path): void
{
    $safe_path = preg_replace('/[^a-zA-Z0-9_.]/', '', $path);
    $view_path = str_replace('.', '/', $safe_path);
    $full_path = BASEPATH . "views/$view_path.php";
    if (file_exists($full_path)) {
        include $full_path;
    } else {
        echo "View not found.";
    }
}
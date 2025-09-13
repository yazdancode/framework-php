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
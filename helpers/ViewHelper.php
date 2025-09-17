<?php

namespace Yazdan\Helpers;

class ViewHelper
{
    public static function view(string $path, array $data = []): void
    {
        extract($data);

        $safePath = str_replace(['..', './', '../'], '', $path);
        $viewPath = str_replace('.', '/', $safePath);
        $fullPath = BASEPATH . "views/$viewPath.php";

        if (file_exists($fullPath)) {
            include $fullPath;
        } else {
            echo "View not found.";
        }
    }
}

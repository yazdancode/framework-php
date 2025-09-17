<?php

namespace App\Controllers;

use Yazdan\Helpers\ViewHelper;

class ArchiveController
{
    public function index(): void
    {
        ViewHelper::view("archive.index");

    }

    public function products()
    {
        ViewHelper::view("archive.products");

    }

    public function articles()
    {
        ViewHelper::view("archive.articles");

    }

}

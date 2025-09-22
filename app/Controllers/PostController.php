<?php

namespace App\Controllers;

use App\Core\Request;
use App\Models\User;

class PostController
{
    public function single(Request $request): void
    {
        $slug = $request->route('slug');
        echo "slug: {$slug}";
    }
    public function comment(Request $request): void
    {
        $slug = $request->route('slug');
        $cid  = $request->route('comment_id');

        echo "slug: {$slug}<br>comment_id: {$cid}";
    }
}

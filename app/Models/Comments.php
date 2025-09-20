<?php 


namespace App\Models;

use App\Models\Contracts\JsonBaseModel;

class Comments extends JsonBaseModel
{
    #todo: should file test with User inside file tests create CommentsjsonTest.php The test should be related.
    protected string $table = 'commends';
}
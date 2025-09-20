<?php 


namespace App\Models;
use App\Models\Contracts\MysqlBaseModel;

class Comments extends MysqlBaseModel
{
    protected string $table = 'commends';
}
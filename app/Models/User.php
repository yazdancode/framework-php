<?php 


namespace App\Models;

use App\Models\Contracts\MysqlBaseModel;

class User extends MysqlBaseModel
{
    public string $table = 'users'; #title persian users

}
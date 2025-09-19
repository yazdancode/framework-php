<?php 


namespace App\Models;

use App\Models\Contracts\JsonBaseModel;

class User extends JsonBaseModel
{
    #todo: should file test with User inside file tests create UserjsonTest.php The test should be related.

    public string $table = 'users'; #title persian users

}
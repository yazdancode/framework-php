<?php

namespace App\Utilities;

class Cureency
{
    public static function format_price_in_hezar_toman(int $amount)
    {
        return $amount / 1000;
    }

    public static function format_price_in_toman(int $amount)
    {
        return $amount;
    }

    public static function format_price_in_rial(int $amount)
    {
        return $amount *10;
    }

    


} 
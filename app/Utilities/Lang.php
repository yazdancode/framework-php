<?php

class Lang
{
    public static function persian_numbers($input)
    {

        $persianNumbers = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $englishNumbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        return str_replace($persianNumbers, $englishNumbers, $input);
    }

    public static function latin_numbers($input)
    {
    $persian = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'];
    $arabic  = ['٠','١','٢','٣','٤','٥','٦','٧','٨','٩'];
    $latin   = ['0','1','2','3','4','5','6','7','8','9'];

    $output = str_replace($persian, $latin, $input);
    $output = str_replace($arabic, $latin, $output);

    return $output;
    }
}
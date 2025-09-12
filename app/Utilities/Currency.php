<?php

namespace App\Utilities;

class Currency
{
    const UNIT_RIAL = 'rial';
    const UNIT_TOMAN = 'toman';
    const UNIT_HEZAR_TOMAN = 'hezar_toman';

    /**
     * تبدیل مبلغ به واحد مورد نظر
     *
     * @param int $amount مبلغ به تومان
     * @param string $unit واحد مورد نظر (rial, toman, hezar_toman)
     * @return float|int
     */
    public static function format(int $amount, string $unit)
    {
        switch ($unit) {
            case self::UNIT_RIAL:
                return $amount * 10;
            case self::UNIT_TOMAN:
                return $amount;
            case self::UNIT_HEZAR_TOMAN:
                return $amount / 1000;
            default:
                throw new \InvalidArgumentException("واحد پولی نامعتبر است");
        }
    }
}

<?php

namespace App\Helpers;

use NumberFormatter;

/**
 * Format response.
 */

class Helpers
{
    public static function insertBeforeKey($array, $key, $newKey, $newValue)
    {
        $newArray = [];
        $inserted = false;

        foreach ($array as $k => $a) {
            if (!$inserted && $k === $key) {
                $newArray[$newKey] = $newValue;
            }
            $newArray[$k] = $a;
        }

        return $newArray;
    }

    public static function getCurrency($value)
    {
        $currency = new NumberFormatter('id_ID', NumberFormatter::CURRENCY);

        return $currency->formatCurrency($value, 'IDR');
    }
}

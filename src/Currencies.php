<?php
declare(strict_types = 1);

namespace Attogram\Currency;

use function array_key_exists;

class Currencies
{
    public static $currencies = [
        'CHF' => [
            'name' => 'Swiss Franc',
        ],
        'EUR' => [
            'name' => 'Euro',
        ],
        'ILS' => [
            'name' => 'New Israeli Sheqel',
        ],
        'RUB' => [
            'name' => 'Russian Ruble',
        ],
        'USD' => [
            'name' => 'US Dollar',
        ],
    ];

    public static function isValidCurrencyCode(string $code)
    {
        if (array_key_exists($code, static::$currencies)) {
            return true;
        }
        return false;
    }
}
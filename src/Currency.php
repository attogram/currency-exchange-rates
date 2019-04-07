<?php
declare(strict_types = 1);

namespace Attogram\Currency;

class Currency
{
    public static $currencies = [
        'EUR' => [
            'name' => 'Euro',
        ],
        'USD' => [
            'name' => 'US Dollar',
        ],
        'CHF' => [
            'name' => 'Swiss Franc',
        ],
        'ILS' => [
            'name' => 'New Israeli Sheqel',
        ],
        'RUB' => [
            'name' => 'Russian Ruble',
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
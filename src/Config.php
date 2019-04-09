<?php
declare(strict_types = 1);

namespace Attogram\Currency;

use function array_key_exists;

class Config
{
    /** @var array */
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

    /** @var array */
    public static $feeds = [
        'BankEurope' => [
            'currency' => 'EUR',
            'name'     => 'European Central Bank',
            'home'     => 'http://www.ecb.europa.eu/',
            'api'      => 'http://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml',
            'info'     => 'Daily rates at 3:00 PM Central European Time (CET)',
        ],
        'BankSwitzerland' => [
            'currency' => 'CHF',
            'name'     => 'Swiss National Bank',
            'home'     => 'http://www.snb.ch/',
            'api'      => 'http://www.snb.ch/selector/en/mmr/exfeed/rss',
            'info'     => 'Daily rates at 11:00 AM Central European Time (CET)',
        ],
        'BankIsrael' => [
            'currency' => 'ILS',
            'name'     => 'Bank of Israel',
            'home'     => 'http://www.boi.org.il/en/',
            'api'      => 'http://www.boi.org.il/currency.xml',
            'info'     => 'Daily rates',
        ],
        'BankRussia' => [
            'currency' => 'RUB',
            'name'     => 'Central Bank of the Russian Federation',
            'home'     => 'http://www.cbr.ru/eng/',
            'api'      => 'http://www.cbr.ru/scripts/XML_daily.asp',
            'info'     => 'Daily rates',
        ],
    ];

    /**
     * @param string $code
     * @return bool
     */
    public static function isValidCurrency(string $code = '') :bool
    {
        return array_key_exists($code, static::$currencies)
            ? true
            : false;
    }

    /**
     * @param string $code
     * @return bool
     */
    public static function isValidFeed(string $code = '') :bool
    {
        return array_key_exists($code, static::$feeds)
            ? true
            : false;
    }

    /**
     * @param string $feedCode
     * @return string
     */
    public static function getFeedApi(string $feedCode) :string
    {
        return empty(static::$feeds[$feedCode]['api'])
            ? ''
            : static::$feeds[$feedCode]['api'];
    }

    /**
     * @param string $feedCode
     * @return string
     */
    public static function getFeedName(string $feedCode) :string
    {
        return empty(static::$feeds[$feedCode]['name'])
            ? ''
            : static::$feeds[$feedCode]['name'];
    }
}
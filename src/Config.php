<?php
declare(strict_types = 1);

namespace Attogram\Currency;

use function array_key_exists;

class Config
{
    /** @var array */
    public static $currencies = [
        'AUD' => ['name' => 'Australian Dollar'],
        'AMD' => ['name' => 'Armenian Dram'],
        'AZN' => ['name' => 'Azerbaijani Manat'],
        'BGN' => ['name' => 'Bulgarian Lev'],
        'BRL' => ['name' => 'Brazilian Real'],
        'BYN' => ['name' => 'Belarusian Ruble'],
        'CAD' => ['name' => 'Canadian Dollar'],
        'CHF' => ['name' => 'Swiss Franc'],
        'CNY' => ['name' => 'Chinese Yuan'],
        'CZK' => ['name' => 'Czech Koruna'],
        'DKK' => ['name' => 'Danish Krone'],
        'EGP' => ['name' => 'Egyptian Pound'],
        'EUR' => ['name' => 'Euro'],
        'GBP' => ['name' => 'British Pound'],
        'HKD' => ['name' => 'Hong Kong Dollar'],
        'HRK' => ['name' => 'Croatian Kuna'],
        'HUF' => ['name' => 'Hungarian Forint'],
        'IDR' => ['name' => 'Indonesian Rupiah'],
        'ILS' => ['name' => 'New Israeli Sheqel'],
        'INR' => ['name' => 'Indian Rupee'],
        'ISK' => ['name' => 'Icelandic Króna'],
        'JOD' => ['name' => 'Jordanian Dinar'],
        'JPY' => ['name' => 'Japanese Yen'],
        'KGS' => ['name' => 'Kyrgystani Som'],
        'KRW' => ['name' => 'South Korean Won'],
        'KZT' => ['name' => 'Kazakhstani Tenge'],
        'LBP' => ['name' => 'Lebanese Pound'],
        'MDL' => ['name' => 'Moldovan Leu'],
        'MXN' => ['name' => 'Mexican Peso'],
        'MYR' => ['name' => 'Malaysian Ringgit'],
        'NOK' => ['name' => 'Norwegian Krone'],
        'NZD' => ['name' => 'New Zealand Dollar'],
        'PHP' => ['name' => 'Philippine Piso'],
        'PLN' => ['name' => 'Poland Złoty'],
        'RON' => ['name' => 'Romanian Leu'],
        'RUB' => ['name' => 'Russian Ruble'],
        'SEK' => ['name' => 'Swedish Krona'],
        'SGD' => ['name' => 'Singapore Dollar'],
        'THB' => ['name' => 'Thai Baht'],
        'TJS' => ['name' => 'Tajikistani Somoni'],
        'TMT' => ['name' => 'Turkmenistan Manat'],
        'TRY' => ['name' => 'Turkish Lira'],
        'UAH' => ['name' => 'Ukrainian Hryvnia'],
        'USD' => ['name' => 'United States Dollar'],
        'UZS' => ['name' => 'Uzbekistani Som'],
        'XDR' => ['name' => 'Special Drawing Rights'],
        'ZAR' => ['name' => 'South African Rand'],
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
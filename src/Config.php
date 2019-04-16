<?php
declare(strict_types = 1);

namespace Attogram\Currency;

use function array_key_exists;

class Config
{
    /** @var array ISO 4217 => name */
    public static $currencies = [
        'AED' => ['name' => 'United Arab Emirates Dirham'],
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
        'PGK' => ['name' => 'Papua New Guinean Kina'],
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
        'TWD' => ['name' => 'Taiwan New Dollar'],
        'UAH' => ['name' => 'Ukrainian Hryvnia'],
        'USD' => ['name' => 'United States Dollar'],
        'UZS' => ['name' => 'Uzbekistani Som'],
        'VND' => ['name' => 'Vietnamese Dong'],
        'XDR' => ['name' => 'Special Drawing Rights'],
        'ZAR' => ['name' => 'South African Rand'],
    ];

    /** @var array */
    public static $feeds = [
        'BankEurope' => [
            'currency' => 'EUR',
            'targets'  => [
                'AUD', 'BGN', 'BRL', 'CAD', 'CHF', 'CNY', 'CZK', 'DKK',
                'GBP', 'HKD', 'HRK', 'HUF', 'IDR', 'ILS', 'INR', 'ISK',
                'JPY', 'KRW', 'MXN', 'MYR', 'NOK', 'NZD', 'PHP', 'PLN',
                'RON', 'RUB', 'SEK', 'SGD', 'THB', 'TRY', 'USD', 'ZAR',
            ],
            'name'     => 'European Central Bank',
            'home'     => 'https://www.ecb.europa.eu/',
            'api'      => 'https://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml',
            'freq'     => 'Daily rates at 3:00 PM Central European Time (CET)',
            'about'    => 'The European Central Bank (ECB) is the central bank for '
                . ' 19 European Union countries: Austria, Belgium, Cyprus, Estonia, Finland,'
                . ' France, Germany, Greece, Ireland, Italy, Latvia, Lithuania, Luxembourg,'
                . ' Malta, The Netherlands, Portugal, Slovakia, Slovenia, and Spain.'
                . ' The ECB was founded in 1998.',
        ],
        'BankSwitzerland' => [
            'currency' => 'CHF',
            'targets'  => ['EUR', 'GBP', 'JPY', 'USD'],
            'name'     => 'Swiss National Bank',
            'home'     => 'https://www.snb.ch/',
            'api'      => 'https://www.snb.ch/selector/en/mmr/exfeed/rss',
            'freq'     => 'Daily rates at 11:00 AM Central European Time (CET)',
            'about'    => 'The Swiss National Bank (SNB) is the central bank of Switzerland.'
                . ' The SNB was founded in 1826.',
        ],
        'BankIsrael' => [
            'currency' => 'ILS',
            'targets'  => [
                'AUD', 'CAD', 'CHF', 'DKK', 'EGP', 'EUR', 'GBP', 'JOD',
                'JPY', 'LBP', 'NOK', 'SEK', 'USD', 'ZAR',
            ],
            'name'     => 'Bank of Israel',
            'home'     => 'https://www.boi.org.il/en/',
            'api'      => 'https://www.boi.org.il/currency.xml',
            'freq'     => 'Daily',
            'about'    => 'The Bank of Israel ( בנק ישראל) is the central bank of Israel.'
                . ' The bank was founded in 1954.',
        ],
        'BankRussia' => [
            'currency' => 'RUB',
            'targets'  => [
                'AMD', 'AUD', 'AZN', 'BGN', 'BRL', 'BYN', 'BYR', 'CAD',
                'CHF', 'CNY', 'CZK', 'DKK', 'EUR', 'GBP', 'HKD', 'HUF',
                'INR', 'JPY', 'KGS', 'KRW', 'KZT', 'MDL', 'NOK', 'PLN',
                'RON', 'SEK', 'SGD', 'TJS', 'TMT', 'TRY', 'UAH', 'USD',
                'UZS', 'XDR', 'ZAR',
            ],
            'name'     => 'Central Bank of the Russian Federation',
            'home'     => 'https://www.cbr.ru/eng/',
            'api'      => 'https://www.cbr.ru/scripts/XML_daily.asp',
            'freq'     => 'Daily',
            'about'    => 'The Central Bank of the Russian Federation'
                . ' (Центральный банк Российской Федерации)'
                . ' is the central bank of Russia.'
                . ' The bank was founded in 1990.',
        ],
        'BankAustralia' => [
            'currency' => 'AUD',
            'targets' => [
                'AED', 'CAD', 'CHF', 'CNY', 'EUR', 'GBP', 'KRW',
                'HKD', 'IDR', 'INR', 'JPY', 'MYR', 'PGK', 'SGD',
                'SDR', 'THB', 'TWD', 'NZD', 'USD', 'VND',
            ],
            'name' => 'Reserve Bank of Australia',
            'home' => 'https://www.rba.gov.au/',
            'api' => 'https://www.rba.gov.au/rss/rss-cb-exchange-rates.xml',
            'freq' => 'Daily around 4.00 PM Eastern Australian Time',
            'about' => 'The Reserve Bank of Australia (RBA) is the central bank of Australia.'
                . ' The RBA was founded in 1960.',
        ]
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
     * @param string $code
     * @return string
     */
    public static function getFeedName(string $code) :string
    {
        return empty(static::$feeds[$code]['name'])
            ? ''
            : static::$feeds[$code]['name'];
    }

    /**
     * @param string $code
     * @return string
     */
    public static function getFeedCurrencyName(string $code) :string
    {
        return empty(static::$currencies[$code]['name'])
            ? ''
            : static::$currencies[$code]['name'];
    }
}

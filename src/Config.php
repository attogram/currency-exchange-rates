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
            'home'     => 'https://www.ecb.europa.eu/',
            'api'      => 'https://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml',
            'freq'     => 'Daily rates at 3:00 PM Central European Time (CET)',
            'about'    => 'The European Central Bank (ECB) is the central bank for '
                . ' 19 European Union countries: Austria, Belgium, Cyprus, Estonia, Finland,'
                . ' France, Germany, Greece, Ireland, Italy, Latvia, Lithuania, Luxembourg,'
                . ' Malta, The Netherlands, Portugal, Slovakia, Slovenia, and Spain.'
                . ' The ECB was founded in 1998.'
,
        ],
        'BankSwitzerland' => [
            'currency' => 'CHF',
            'name'     => 'Swiss National Bank',
            'home'     => 'https://www.snb.ch/',
            'api'      => 'https://www.snb.ch/selector/en/mmr/exfeed/rss',
            'freq'     => 'Daily rates at 11:00 AM Central European Time (CET)',
            'about'    => 'The Swiss National Bank (SNB) is the central bank of Switzerland.'
                . ' The SNB was founded in 1826.',
        ],
        'BankIsrael' => [
            'currency' => 'ILS',
            'name'     => 'Bank of Israel',
            'home'     => 'https://www.boi.org.il/en/',
            'api'      => 'https://www.boi.org.il/currency.xml',
            'freq'     => 'Daily',
            'about'    => 'The Bank of Israel ( בנק ישראל) is the central bank of Israel.'
                . ' The bank was founded in 1954.',
        ],
        'BankRussia' => [
            'currency' => 'RUB',
            'name'     => 'Central Bank of the Russian Federation',
            'home'     => 'https://www.cbr.ru/eng/',
            'api'      => 'https://www.cbr.ru/scripts/XML_daily.asp',
            'freq'     => 'Daily',
            'about'    => 'The Central Bank of the Russian Federation'
                . ' (Центральный банк Российской Федерации) is the central bank of Russia.'
                . ' The bank was founded in 1990.',
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

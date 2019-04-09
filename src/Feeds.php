<?php
declare(strict_types = 1);

namespace Attogram\Currency;

use function array_key_exists;

class Feeds
{
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

    public static function isValidFeedCode(string $code)
    {
        if (array_key_exists($code, static::$feeds)) {
            return true;
        }

        return false;
    }
}

<?php
declare(strict_types = 1);

namespace Attogram\Currency;

class Sources
{
    public static $sources = [
        'ecb' => [
            'currency'   => 'EUR',
            'sourceName' => 'European Central Bank',
            'sourceUri'  => 'http://www.ecb.europa.eu/',
            'apiUri'     => 'http://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml',
            'info'       => 'Daily rates at 3:00 PM Central European Time (CET)',
        ],
        'snb' => [
            'currency'   => 'CHF',
            'sourceName' => 'Swiss National Bank',
            'sourceUri'  => 'http://www.snb.ch/',
            'apiUri'     => 'http://www.snb.ch/selector/en/mmr/exfeed/rss',
            'info'       => 'Daily rates at 11:00 AM Central European Time (CET)',
        ],
        'boi' => [
            'currency'   => 'ILS',
            'sourceName' => 'Bank of Israel',
            'sourceUri'  => 'http://www.boi.org.il/en/',
            'apiUri'     => 'http://www.boi.org.il/currency.xml',
            'info'       => 'Daily rates',
        ],
        'cbr' => [
            'currency'   => 'RUB',
            'sourceName' => 'Central Bank of the Russian Federation',
            'sourceUri'  => 'http://www.cbr.ru/eng/',
            'apiUri'     => 'http://www.cbr.ru/scripts/XML_daily.asp',
            'info'       => 'Daily rates',
        ],
    ];
}

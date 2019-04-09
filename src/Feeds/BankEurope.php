<?php
declare(strict_types = 1);

namespace Attogram\Currency\Feeds;

use function explode;
use function preg_match;

final class BankEurope extends Feed {

    public function __construct()
    {
        $this->api = 'http://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml';
    }

    public function process()
    {
        if (!$this->raw) {
            return;
        }
        $currency = [];
        $date = '';
        foreach (explode("\n", $this->raw) as $line) {
            if (preg_match("/time='([[:graph:]]+)'/", $line, $day)) {
                $date = $day[1];
            }
            if (preg_match("/currency='([[:alpha:]]+)'/", $line, $currencyCode)) {
                if (preg_match("/rate='([[:graph:]]+)'/", $line, $rate)){
                    $currency[$currencyCode[1]] = $rate[1];
                }
            }
        }
        $this->insert('EUR', $date, 'ecb-daily', $currency);
    }
}

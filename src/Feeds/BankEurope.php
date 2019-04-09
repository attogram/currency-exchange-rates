<?php
declare(strict_types = 1);

namespace Attogram\Currency\Feeds;

use Attogram\Currency\Feed;

use function explode;
use function preg_match;

final class BankEurope extends Feed {

    public function process()
    {
        $raw = $this->get();
        if (!$raw || !is_string($raw)) {
            return;
        }
        $currency = [];
        $date = '';
        foreach (explode("\n", $raw) as $line) {
            if (preg_match("/time='([[:graph:]]+)'/", $line, $day)) {
                $date = $day[1];
            }
            if (preg_match("/currency='([[:alpha:]]+)'/", $line, $currencyCode)) {
                if (preg_match("/rate='([[:graph:]]+)'/", $line, $rate)){
                    $currency[$currencyCode[1]] = $rate[1];
                }
            }
        }
        $this->insert($date, 'EUR', 'BankEurope', $currency);
    }
}

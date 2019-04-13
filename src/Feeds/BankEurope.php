<?php
declare(strict_types = 1);

namespace Attogram\Currency\Feeds;

use function preg_match;

final class BankEurope extends Feed implements FeedsInterface
{
    public function process()
    {
        parent::process();
        $currency = [];
        $date = '';
        foreach ($this->lines as $line) {
            if (preg_match("/time='([[:graph:]]+)'/", $line, $day)) {
                $date = $day[1];
            }
            if (preg_match("/currency='([[:alpha:]]+)'/", $line, $code)
                && preg_match("/rate='([[:graph:]]+)'/", $line, $rate)
            ) {
                $currency[$code[1]] = $rate[1];
            }
        }
        $this->addData($currency, $date, 'EUR', 'BankEurope');
    }
}

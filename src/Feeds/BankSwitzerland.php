<?php
declare(strict_types = 1);

namespace Attogram\Currency\Feeds;

use function preg_match;
use function substr;

class BankSwitzerland extends Feed implements FeedsInterface
{
    public function process()
    {
        parent::process();
        $currency = [];
        $date = $rate = '';
        $count = 0;
        foreach ($this->lines as $line) {
            if (preg_match("/\<dc:date\>([[:graph:]]+)\<\/dc:date\>/", $line, $match)) {
                $date = $match[1];
                $date = substr($date, 0, 10);
            }
            if (preg_match("/\<cb:value\>([[:graph:]]+)\<\/cb:value\>/", $line, $match)) {
                $rate = $match[1];
            }
            if (preg_match("/\<cb:targetCurrency\>([[:graph:]]+)\<\/cb:targetCurrency\>/", $line, $match)) {
                $currencyCode = $match[1];
                $currency[$currencyCode] = (1 / $rate);
                $count++;
                if ($count == 4) {
                    break;
                }
            }
        }
        $this->addData($currency, $date, 'CHF', 'BankSwitzerland');
    }
}

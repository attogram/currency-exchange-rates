<?php
declare(strict_types = 1);

namespace Attogram\Currency\Sources;

use function explode;
use function preg_match;

class BankSwitzerland extends Source {

    public function __construct()
    {
        $this->api = 'http://www.snb.ch/selector/en/mmr/exfeed/rss';
    }

    public function process()
    {
        if (!$this->raw) {
            $this->result = false;

            return;
        }
        $currency = [];
        $date = $rate = '';
        $count = 0;
        foreach (explode("\n", $this->raw) as $line) {
            if(preg_match("/\<dcterms\:created\>([[:graph:]]+)\<\/dcterms\:created\>/", $line,$m)) {
                $date = $m[1];
                $date = substr($date, 0, 10);
            }
            if (preg_match("/\<cb:value\>([[:graph:]]+)\<\/cb:value\>/",$line,$m)) {
                $rate = $m[1];
            }
            if (preg_match("/\<cb:targetCurrency\>([[:graph:]]+)\<\/cb:targetCurrency\>/", $line, $m)) {
                $currencyCode = $m[1];
                $currency[$currencyCode] = round( (1/$rate), 8);
                $count++;
                if ($count ==  4) {
                    break;
                }
            }
        }
        $this->insert($base_currency='CHF', $date, $source='snb-daily', $currency);
    }
}

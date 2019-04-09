<?php
declare(strict_types = 1);

namespace Attogram\Currency\Feeds;

use Attogram\Currency\Feed;

use function explode;
use function preg_match;
use function round;
use function substr;

class BankSwitzerland extends Feed {

    /**
     * BankSwitzerland constructor.
     * @param string $api
     */
    public function __construct(string $api)
    {
        $this->api = $api;
    }

    public function process()
    {
        $raw = $this->get();
        if (!$raw || !is_string($raw)) {
            return;
        }
        $currency = [];
        $date = $rate = '';
        $count = 0;
        foreach (explode("\n", $raw) as $line) {
            if(preg_match("/\<dcterms\:created\>([[:graph:]]+)\<\/dcterms\:created\>/", $line,$m)) {
                $date = $m[1];
                $date = substr($date, 0, 10);
            }
            if (preg_match("/\<cb:value\>([[:graph:]]+)\<\/cb:value\>/",$line,$m)) {
                $rate = $m[1];
            }
            if (preg_match("/\<cb:targetCurrency\>([[:graph:]]+)\<\/cb:targetCurrency\>/", $line, $m)) {
                $currencyCode = $m[1];
                $currency[$currencyCode] = round((1/$rate), 8);
                $count++;
                if ($count ==  4) {
                    break;
                }
            }
        }
        $this->insert($date, 'CHF','BankSwitzerland', $currency);
    }
}

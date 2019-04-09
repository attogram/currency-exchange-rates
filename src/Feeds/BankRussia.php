<?php
declare(strict_types = 1);

namespace Attogram\Currency\Feeds;

use Attogram\Currency\Feed;

use function explode;
use function preg_match;
use function round;
use function str_replace;

final class BankRussia extends Feed {

    /**
     * BankRussia constructor.
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
        $date = $xcurrency = '';
        foreach (explode("\n", $raw) as $line) {
            if (preg_match("/Date=\"([[:graph:]]+)\"/", $line, $match) ) {
                $date = $match[1];
                $da = explode('.', $date);
                $date = $da[2] . '-' . $da[1] . '-' . $da[0];
                continue;
            }
            if (preg_match("/<CharCode>([[:alpha:]]+)<\/CharCode>/", $line, $match) ) {
                $xcurrency = $match[1];
                continue;
            }
            if (preg_match("/<Value>([[:graph:]]+)<\/Value>/", $line, $match) ) {
                $rate = $match[1];
                $rate = str_replace(',', '.', $rate);
                $rate = round((1/$rate), 4);
                $currency[$xcurrency] = $rate;
                $xcurrency = false;
            }
        }
        $this->insert($date, 'RUB','BankRussia', $currency);
    }
}

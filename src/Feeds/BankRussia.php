<?php
declare(strict_types = 1);

namespace Attogram\Currency\Feeds;

use function explode;
use function preg_match;
use function str_replace;

final class BankRussia extends Feed implements FeedsInterface {

    public function transform()
    {
        $this->raw = mb_convert_encoding(
            $this->raw,
            'UTF-8',
            'Windows-1251'
        );
    }

    public function process()
    {
        parent::process();
        $currency = [];
        $date = $target = '';
        foreach ($this->lines as $line) {
            if (preg_match("/Date=\"([[:graph:]]+)\"/", $line, $match) ) {
                $date = $match[1];
                $da = explode('.', $date);
                $date = $da[2] . '-' . $da[1] . '-' . $da[0];
                continue;
            }
            if (preg_match("/<CharCode>([[:alpha:]]+)<\/CharCode>/", $line, $match) ) {
                $target = $match[1];
                continue;
            }
            if (preg_match("/<Value>([[:graph:]]+)<\/Value>/", $line, $match) ) {
                $rate = $match[1];
                $rate = str_replace(',', '.', $rate);
                $rate = (1 / $rate);
                $currency[$target] = $rate;
                $target = false;
            }
        }
        foreach ($currency as $target => $rate) {
            $this->data[] = [
                'd' => $date,
                'r' => $rate,
                's' => 'RUB',
                't' => $target,
                'f' => 'BankRussia',
            ];
        }
    }
}

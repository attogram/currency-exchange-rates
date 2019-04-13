<?php
declare(strict_types = 1);

namespace Attogram\Currency\Feeds;

use function preg_match;

final class BankIsrael extends Feed implements FeedsInterface
{
    public function process()
    {
        parent::process();
        $currency = [];
        $date = $currencyCode = '';
        foreach ($this->lines as $line) {
            if (preg_match("/\<LAST_UPDATE\>([[:graph:]]+)\<\/LAST_UPDATE\>/", $line, $m)) {
                $date = $m[1];
            }
            if (preg_match("/\<CURRENCYCODE\>([[:graph:]]+)\<\/CURRENCYCODE\>/", $line, $m)) {
                $currencyCode = $m[1];
            }
            if (preg_match("/\<RATE\>([[:graph:]]+)\<\/RATE\>/", $line, $m)) {
                $rate = $m[1];
                $currency[$currencyCode] = $rate;
            }
        }
        foreach ($currency as $target => $rate) {
            $this->data[] = [
                'd' => $date,
                'r' => $rate,
                's' => 'ILS',
                't' => $target,
                'f' => 'BankIsrael',
            ];
        }
    }
}

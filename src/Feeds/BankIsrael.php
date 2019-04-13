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
            if (preg_match("/\<LAST_UPDATE\>([[:graph:]]+)\<\/LAST_UPDATE\>/", $line, $match)) {
                $date = $match[1];
            }
            if (preg_match("/\<CURRENCYCODE\>([[:graph:]]+)\<\/CURRENCYCODE\>/", $line, $match)) {
                $currencyCode = $match[1];
            }
            if (preg_match("/\<RATE\>([[:graph:]]+)\<\/RATE\>/", $line, $match)) {
                $rate = $match[1];
                $currency[$currencyCode] = $rate;
            }
        }
        $this->addData($currency, $date, 'ILS', 'BankIsrael');
    }
}

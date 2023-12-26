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
        $rates = json_decode($this->lines[0], true);
        foreach ($rates['exchangeRates'] as $code) {
            $date = substr($code['lastUpdate'], 0, 10);
            $currencyCode = $code['key'];
            $currency[$currencyCode] = $code['currentExchangeRate'];
            $this->addData($currency, $date, 'ILS', 'BankIsrael');
        }
    }
}

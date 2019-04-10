<?php
declare(strict_types = 1);

namespace Attogram\Currency\Feeds;

use Exception;

use function preg_match;
use function substr;

class BankSwitzerland extends Feed implements FeedsInterface {

    /**
     * @throws Exception
     */
    public function process()
    {
        parent::process();
        $currency = [];
        $date = $rate = '';
        $count = 0;
        foreach ($this->raw as $line) {
            if(preg_match("/\<dcterms\:created\>([[:graph:]]+)\<\/dcterms\:created\>/", $line,$m)) {
                $date = $m[1];
                $date = substr($date, 0, 10);
            }
            if (preg_match("/\<cb:value\>([[:graph:]]+)\<\/cb:value\>/",$line,$m)) {
                $rate = $m[1];
            }
            if (preg_match("/\<cb:targetCurrency\>([[:graph:]]+)\<\/cb:targetCurrency\>/", $line, $m)) {
                $currencyCode = $m[1];
                $currency[$currencyCode] = (1/$rate);
                $count++;
                if ($count ==  4) {
                    break;
                }
            }
        }
        foreach ($currency as $target => $rate) {
            $this->data[] = [
                'day' => $date,
                'rate' => $rate,
                'source' => 'CHF',
                'target' => $target,
                'feed' => 'BankSwitzerland',
            ];
        }
    }
}

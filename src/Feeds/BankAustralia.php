<?php
/** @noinspection PhpUndefinedFieldInspection */
declare(strict_types = 1);

namespace Attogram\Currency\Feeds;

use function explode;
use function simplexml_load_string;

class BankAustralia extends Feed implements FeedsInterface
{
    public function process()
    {
        $currencyList = [];
        $date = '';
        $xml = simplexml_load_string($this->raw);
        foreach ($xml->item as $item) {
            $spaced = explode(' ', (string) $item->title);
            $date = $spaced[6];
            $target = $spaced[2];
            switch ($target) {
                case 'SDR': // Special Drawing Rights
                    $target = 'XDR';
                    break;
                case 'TWI_4pm':
                    continue 2;
            }
            $currencyList[$target] = $spaced[1];
        }
        $this->addData($currencyList, $date, 'AUD', 'BankAustralia');
    }
}

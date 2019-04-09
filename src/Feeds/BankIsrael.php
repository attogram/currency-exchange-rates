<?php
declare(strict_types = 1);

namespace Attogram\Currency\Feeds;

use Attogram\Currency\Feed;

use function explode;
use function preg_match;

final class BankIsrael extends Feed {

    /**
     * BankIsrael constructor.
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
        $date = $currencyCode = '';
        foreach (explode("\n", $raw) as $line) {
            if (preg_match("/\<LAST_UPDATE\>([[:graph:]]+)\<\/LAST_UPDATE\>/", $line, $m)){
                $date = $m[1];
            }
            if (preg_match("/\<CURRENCYCODE\>([[:graph:]]+)\<\/CURRENCYCODE\>/", $line, $m)){
                $currencyCode = $m[1];
            }
            if (preg_match("/\<RATE\>([[:graph:]]+)\<\/RATE\>/", $line, $m)) {
                $rate = $m[1];
                $currency[$currencyCode] = $rate;
            }
        }
        $this->insert($date, 'ILS','BankIsrael', $currency);
    }
}

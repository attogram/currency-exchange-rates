<?php
declare(strict_types = 1);

namespace Attogram\Currency\Sources;

final class BankIsrael extends Source {

    public function __construct()
    {
        $this->api = 'http://www.boi.org.il/currency.xml';
    }

    public function process()
    {
        if (!$this->raw) {
            $this->result = false;
            return;
        }
        $currency = [];
        $date = $currencyCode = '';
        foreach (explode("\n", $this->raw) as $line) {
            if (preg_match("/\<LAST_UPDATE\>([[:graph:]]+)\<\/LAST_UPDATE\>/", $line, $m)){
                $date = $m[1];
            }
            if (preg_match("/\<CURRENCYCODE\>([[:graph:]]+)\<\/CURRENCYCODE\>/", $line, $m)){
                $currencyCode= $m[1];
            }
            if (preg_match("/\<RATE\>([[:graph:]]+)\<\/RATE\>/",$line,$m)) {
                $rate = $m[1];
                $currency[$currencyCode] = $rate;
            }
        }
        $this->insert('ILS', $date, 'boi-daily', $currency);
    }
}

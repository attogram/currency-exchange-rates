<?php
declare(strict_types = 1);

namespace Attogram\Currency\Sources;

final class BankRussia extends Source {

    public function __construct()
    {
		$this->api = 'http://www.cbr.ru/scripts/XML_daily.asp';
	}

    public function process()
    {
        if (!$this->raw) {
            $this->result = false;
            return;
        }
        $currency = [];
        $date = $xcurrency = '';
        foreach (explode("\n", $this->raw) as $line) {
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
		$this->insert('RUB', $date, 'cbr-daily', $currency);
	}
}

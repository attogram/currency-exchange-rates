<?php
// Test ECB Parsing
declare(strict_types = 1);

$endpoint = 'http://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml';
$currency = [];
$date = '?';
foreach (file($endpoint) as $line) {
	$line = trim($line);
	if (preg_match("/time='([[:graph:]]+)'/", $line, $day)){
		$date = $day[1];
	}
	if (preg_match("/currency='([[:alpha:]]+)'/", $line, $currencyCode)) {
        if (preg_match("/rate='([[:graph:]]+)'/", $line, $rate)) {
            $currency[$currencyCode[1]] = $rate[1];
        }
	}
}
print "$endpoint\nECB Euro Rates $date\n" . print_r($currency,true);

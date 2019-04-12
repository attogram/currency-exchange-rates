<?php
declare(strict_types = 1);

namespace Attogram\Currency;

$autoload = '../vendor/autoload.php';
if (!is_readable($autoload)) {
    die('Vendor autoload file not found.  Please run composer install.');
}

/** @noinspection PhpIncludeInspection */
require_once $autoload;

new CurrencyExchangeRates();

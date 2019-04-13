<?php
declare(strict_types = 1);

use Attogram\Currency\Config;
use Attogram\Currency\CurrencyExchangeRates;

$autoload = '../vendor/autoload.php';
if (!is_readable($autoload)) {
    die('Vendor autoload file not found: ' . $autoload);
}

/** @noinspection PhpIncludeInspection */
require_once $autoload;

$code = $argv[1] ?? '';

if (!Config::isValidFeed($code)) {
    die(
        "\nAttogram Currency Exchange Rates Updater v" . CurrencyExchangeRates::VERSION . "\n\n"
        . "Usage: php " . $argv[0] . " FeedCode\n\n"
        . "Available Feed Codes:\n  - "
        . implode("\n  - ", array_keys(Config::$feeds)) . "\n"
    );
}

$class = "\\Attogram\\Currency\\Feeds\\" . $code;
if (!class_exists($class)) {
    die('Class Not Found: ' . $class);
}

$api = Config::getFeedApi($code);
if (empty($api)) {
    die($code . ' API Endpoint Not Found');
}

$verbosity = 0;

new $class($api, $verbosity);

<?php
declare(strict_types = 1);

use Attogram\Currency\Config;
use Attogram\Currency\CurrencyExchangeRates;

$autoload = __DIR__ . '/../vendor/autoload.php';
if (!is_readable($autoload)) {
    print 'Vendor autoload file not found: ' . $autoload . "\n";
    exit(1);
}

/** @noinspection PhpIncludeInspection */
require_once $autoload;

if (!isset($argv[1])) {
    print "\nAttogram Currency Exchange Rates Updater v" . CurrencyExchangeRates::VERSION . "\n\n"
        . "Usage: php " . $argv[0] . " FeedCode [VerbosityLevel]\n\n"
        . "Available Feed Codes:\n  - "
        . implode("\n  - ", array_keys(Config::$feeds)) . "\n";
    exit(0);
}
$code = $argv[1];
$verbosity = (int) ($argv[2] ?? 0);

if (!Config::isValidFeed($code)) {
    print 'Feed Code Not Found: ' . $code . "\n";
    exit(1);
}

$class = CurrencyExchangeRates::FEEDS_NAMESPACE . $code;
if (!class_exists($class)) {
    print 'Class Not Found: ' . $class . "\n";
    exit(1);
}

$api = Config::getFeedApi($code);
if (empty($api)) {
    print $code . ' API Endpoint Not Found' . "\n";
    exit(1);
}

new $class($api, $verbosity);

exit(0);

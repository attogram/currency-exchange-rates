<?php
declare(strict_types = 1);

namespace Attogram\Currency;

use function round;
use function sprintf;

class Format
{
    /**
     * @param array $rates
     * @param string $home
     * @return string
     */
    static function formatRates(array $rates, string $home)
    {
        if (empty($rates)) {
            return '';
        }
        $display = "-------  ----------\t-------  ----------\t----------    "
            . "<small>------------------------------------------------------</small>\n";
        foreach ($rates as $rate) {
            $display .= static::formatRate($rate, $home);
        }

        return $display;
    }

    /**
     * @param array $rate
     * @param string $home
     * @return string
     */
    static function formatRate(array $rate, string $home)
    {
        $pair = $rate['source'] . '/' . $rate['target'];
        $pairRate = sprintf("%.8f", round($rate['rate'], 8));
        $reverseRate = sprintf("%.8f", round((1 / $rate['rate']), 8));
        return '<a href="' . $home . $pair . '/">' . $pair . '</a>  '
            . $pairRate . "\t"
            . '<a href="' . $home . $rate['target'] . '/">' . $rate['target'] . '</a>/'
            . '<a href="' . $home . $rate['source'] . '/">' . $rate['source'] . '</a>  '
            . $reverseRate . "\t" . $rate['day'] . '    <small>retrieved '
            . $rate['last_updated'] . ' UTC from ' . $rate['feed'] . '</small>' . "\n";
    }
}

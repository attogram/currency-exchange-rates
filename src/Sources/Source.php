<?php
declare(strict_types = 1);

namespace Attogram\Currency\Sources;

use Attogram\Currency\Database;

class Source implements SourceInterface
{
    public $api;
    public $raw;
    public $result;

    function get()
    {
        if (!isset($this->api) || !$this->api) {
            $this->raw = false;
            return;
        }
        $this->raw = file_get_contents($this->api);
    }

    function process()
    {
        $this->result = false;
    }

    function insert(string $currency = '', string $day = '', string $source = '', array $rates = [])
    {
        foreach ($rates as $xcurrency => $rate) {
            Database::insert(
                'INSERT OR REPLACE INTO rate (day, source, currency, xcurrency, rate)'
                . ' VALUES (:day, :source, :currency, :xcurrency, :rate)',
                $bind = [
                    ':day' => $day,
                    ':rate' => $rate,
                    ':currency' => $currency,
                    ':xcurrency' => $xcurrency,
                    ':source' => $source,
                ]
            );
        }
    }
}
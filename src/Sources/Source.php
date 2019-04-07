<?php
declare(strict_types = 1);

namespace Attogram\Currency\Sources;

class Source implements SourceInterface {

    public $api;
    public $raw;
    public $result;

    function get() {
        if (!isset($this->api) || !$this->api ) {
            $this->raw = false;
            return;
        }
        $this->raw = file_get_contents($this->api);
    }

    function process() {
        $this->result = false;
    }

    function insert(
        string $base_currency = '',
        string $day = '',
        string $source = '',
        array $currency = []
    ) {
        foreach ($currency as $name => $value) {
            QUERYFUNCTION(
                'INSERT OR REPLACE INTO rate (day, source, currency, xcurrency, rate)'
                . ' VALUES (:day, :source, :currency, :xcurrency, :rate)',
                $bind = [
                    ':day' => $day,
                    ':source' => $source,
                    ':currency' => $base_currency,
                    ':xcurrency' => $name,
                    ':rate' => $value
                ]
            );
        }
    }
}
<?php
declare(strict_types = 1);

namespace Attogram\Currency\Feeds;

interface FeedsInterface
{
    public function get();
    public function process();
    public function insert(
        string $day = '',
        string $source = '',
        string $feed = '',
        array $rates = []
    );
}

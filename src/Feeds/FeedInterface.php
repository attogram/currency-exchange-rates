<?php
declare(strict_types = 1);

namespace Attogram\Currency\Feeds;

interface FeedInterface
{
    public function get();

    public function process();

    /**
     * @param string $source
     * @param string $day
     * @param string $feed
     * @param array $rates
     * @return mixed
     */
    public function insert(string $source = '', string $day = '', string $feed = '', array $rates = []);
}

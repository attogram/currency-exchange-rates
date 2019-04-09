<?php
declare(strict_types = 1);

namespace Attogram\Currency\Feeds;

use Attogram\Currency\Database;
use function file_get_contents;

class Feed implements FeedInterface
{
    /** @var string */
    public $api = '';

    /** @var string */
    public $raw = '';

    public function get()
    {
        if (empty($this->api)) {
            return;
        }
        $this->raw = file_get_contents($this->api);
    }

    public function process()
    {
        return;
    }

    /**
     * @param string $source
     * @param string $day
     * @param string $feed
     * @param array $rates
     */
    public function insert(string $source = '', string $day = '', string $feed = '', array $rates = [])
    {
        foreach ($rates as $target => $rate) {
            Database::insert(
                '
                INSERT OR REPLACE 
                INTO rate (day, rate, source, target, feed) 
                VALUES (:day, :rate, :source, :target, :feed)
                ',
                [
                    ':day' => $day,
                    ':rate' => $rate,
                    ':source' => $source,
                    ':target' => $target,
                    ':feed' => $feed,
                ]
            );
        }
    }
}
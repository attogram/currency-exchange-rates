<?php
declare(strict_types = 1);

namespace Attogram\Currency;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;

class Feed
{
    /** @var string */
    public $api = '';

    /** @var string */
    public $raw = '';

    public function get()
    {
        $this->raw = '';

        if (empty($this->api)) {
            return;
        }

        $client = new GuzzleClient();

        try {
            $result = $client->request('GET', $this->api);
        } catch (GuzzleException $exception) {
            return;
        }

        if ($result->getStatusCode() !== 200) {
            return;
        }

        $this->raw = $result->getBody();
    }

    /**
     * @param string $source
     * @param string $day
     * @param string $feed
     * @param array $rates
     */
    public function insert(
        string $source = '',
        string $day = '',
        string $feed = '',
        array $rates = []
    ) {
        $db = new Database();
        foreach ($rates as $target => $rate) {
           $db->insert(
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
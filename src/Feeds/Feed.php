<?php
declare(strict_types = 1);

namespace Attogram\Currency\Feeds;

use Attogram\Currency\Database;
use Exception;
use GuzzleHttp\Client as GuzzleClient;

class Feed
{
    /** @var string */
    protected $api = '';

    /**
     * @param string $api
     */
    public function __construct(string $api)
    {
        $this->api = $api;
    }

    /**
     * @return string
     * @throws Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function get() :string
    {
        if (empty($this->api)) {
            throw new Exception('API undefined');
        }
        $client = new GuzzleClient();
        $result = $client->request('GET', $this->api);
        if ($result->getStatusCode() !== 200) {
            throw new Exception("StatusCode = " . $result->getStatusCode());
        }
        $contents = $result->getBody()->getContents();
        //print "got: <hr>" . htmlentities($contents) . "<hr>";

        return $contents;
    }

    /**
     * @param string $day
     * @param string $source
     * @param string $feed
     * @param array $rates
     * @throws Exception
     */
    public function insert(
        string $day = '',
        string $source = '',
        string $feed = '',
        array $rates = []
    ) {
        $db = new Database();
        $sql = '
            INSERT OR REPLACE 
            INTO rates (day, rate, source, target, feed) 
            VALUES (:day, :rate, :source, :target, :feed)
        ';
        foreach ($rates as $target => $rate) {
            $bind = [
                'day' => $day,
                'rate' => $rate,
                'source' => $source,
                'target' => $target,
                'feed' => $feed,
            ];

            print "\nData: " . print_r($bind, true);

            if (!$db->queryBool($sql, $bind)) {
                throw new Exception('ERROR inserting ' . print_r($bind, true));
            }
        }
    }
}
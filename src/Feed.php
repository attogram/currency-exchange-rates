<?php
declare(strict_types = 1);

namespace Attogram\Currency;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;

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

    public function get() :string
    {
        if (empty($this->api)) {
            //print "\nERROR: no api\n";
            return '';
        }

        $client = new GuzzleClient();

        try {
            $result = $client->request('GET', $this->api);
        } catch (GuzzleException $exception) {
            //print "\nERROR: " . $exception->getMessage() . "\n";
            return '';
        }

        if ($result->getStatusCode() !== 200) {
            //print "\nERROR: StatusCode = " . $result->getStatusCode() . "\n";
            return '';
        }
        $contents = $result->getBody()->getContents();
        //print "got: <hr>" . htmlentities($contents) . "<hr>";

        return $contents;
    }

    /**
     * @param string $source
     * @param string $day
     * @param string $feed
     * @param array $rates
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
            $result = $db->queryBool($sql, $bind);
            if (!$result) {
                print "\nERROR inserting " . implode(', ', $bind). "\n";
            }
        }
    }
}
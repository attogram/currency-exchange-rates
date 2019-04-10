<?php
declare(strict_types = 1);

namespace Attogram\Currency\Feeds;

use Attogram\Currency\Database;
use Exception;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use Throwable;

use function explode;

class Feed
{
    /** @var string */
    protected $api = '';

    /** @var string|array */
    protected $raw;

    /** @var array */
    protected $data = [];

    /**
     * @param string $api
     * @throws GuzzleException
     * @throws Exception
     */
    public function __construct(string $api)
    {
        try {
            $this->api = $api;
            print "\nGetting feed: " . $this->api;
            $this->get();
            print "\nProcessing " . strlen($this->raw) . " characters";
            $this->process();
            print "\nProcessing " . count($this->raw) . " lines";
            print "\nInserting " . count($this->data) . " entries";
            $this->insert();
            print "\nDONE.";
        } catch (Throwable $error) {
            print "\nERROR: " . $error->getMessage();
        }
    }

    /**
     * Get the feed into $this->raw
     *
     * @throws Exception
     * @throws GuzzleException
     */
    public function get()
    {
        if (empty($this->api)) {
            throw new Exception('API undefined');
        }
        $client = new GuzzleClient();
        $result = $client->request('GET', $this->api);
        if ($result->getStatusCode() !== 200) {
            throw new Exception('StatusCode ' . $result->getStatusCode());
        }
        $this->raw = $result->getBody()->getContents();
    }

    /**
     * Process $this->raw into structured $this->data
     *
     * @throws Exception
     */
    public function process()
    {
        if (empty($this->raw)) {
            throw new Exception('Raw Not Found');
        }
        $this->raw = explode("\n", $this->raw);
        $this->data = [];
    }

    /**
     * Insert $this->data into database
     *
     * @throws Exception
     */
    public function insert() {
        if (empty($this->data)) {
            throw new Exception('Data Not Found');
        }
        $database = new Database();
        foreach ($this->data as $bind) {
            $database->insert(
                'REPLACE INTO rates (day, rate, source, target, feed) VALUES (:d, :r, :s, :t, :f)',
                $bind
            );
        }
    }
}
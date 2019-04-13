<?php
declare(strict_types = 1);

namespace Attogram\Currency\Feeds;

use Attogram\Currency\Database;
use Exception;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;

use function explode;

class Feed implements FeedsInterface
{
    /** @var string */
    protected $api = '';

    /** @var string */
    protected $raw;

    /** @var array */
    protected $lines = [];

    /** @var array */
    protected $data = [];

    /**
     * Feed constructor.
     * @param string $api
     * @param bool $silent
     * @throws Exception
     * @throws GuzzleException
     */
    public function __construct(string $api, bool $silent = false)
    {
        $this->api = $api;
        print ($silent ? '' : "\n\nGetting feed: " . $this->api);
        $this->get();
        print ($silent ? '' : "\n\nGot " . strlen($this->raw) . " characters\n")
            . ($silent ? '' : '<textarea rows="5" cols="100">' . $this->raw . '</textarea>');
        $this->transform();
        print ($silent ? '' : "\n\nTransformed to " . strlen($this->raw) . " characters\n")
            . ($silent ? '' : '<textarea rows="5" cols="100">' . $this->raw . '</textarea>');
        $this->process();
        print ($silent ? '' : "\n\nProcessed " . count($this->lines) . " lines\n")
            . ($silent ? '' : '<textarea rows="5" cols="100">' . print_r($this->lines, true) . '</textarea>');
        $this->insert();
        print ($silent ? '' : "\n\nInserted " . count($this->data) . " entries\n")
            . ($silent ? '' : '<textarea rows="10" cols="100">' . print_r($this->data, true) . '</textarea>');
    }

    /**
     * Get the feed into $this->raw
     *
     * @throws Exception
     * @throws GuzzleException
     */
    public function get()
    {
        if (empty($this->api) || !is_string($this->api)) {
            throw new Exception('API undefined');
        }
        $client = new GuzzleClient();
        $result = $client->request('GET', $this->api);
        if ($result->getStatusCode() !== 200) {
            throw new Exception('StatusCode ' . $result->getStatusCode());
        }
        $this->raw = (string) $result->getBody();
    }

    /**
     * If needed, transform $this->raw
     */
    public function transform()
    {
        return;
    }

    /**
     * Process $this->raw into structured $this->data
     *
     * @throws Exception
     */
    public function process()
    {
        if (empty($this->raw) || !is_string($this->raw)) {
            throw new Exception('Raw Not Found');
        }
        $this->lines = explode("\n", $this->raw);
        $this->data = [];
    }

    /**
     * Insert $this->data into database
     *
     * @throws Exception
     */
    public function insert()
    {
        if (empty($this->data) || !is_array($this->data)) {
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

    /**
     * @param array $exchangeData
     * @param string $date
     * @param string $source
     * @param string $feed
     */
    public function addData(
        array $exchangeData,
        string $date,
        string $source,
        string $feed
    ) {
        foreach ($exchangeData as $target => $rate) {
            $this->data[] = [
                'd' => $date,
                'r' => $rate,
                's' => $source,
                't' => $target,
                'f' => $feed,
            ];
        }
    }
}

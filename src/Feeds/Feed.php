<?php
declare(strict_types = 1);

namespace Attogram\Currency\Feeds;

use Attogram\Currency\CurrencyDatabase;
use Exception;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;

use function count;
use function explode;
use function is_array;
use function is_string;
use function print_r;
use function strlen;

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

    /** @var int */
    protected $verbosity = 0;

    /**
     * Feed constructor.
     * @param string $api
     * @param int $verbosity - 0 = quiet
     * @param string $raw
     * @throws Exception
     * @throws GuzzleException
     */
    public function __construct(string $api, int $verbosity = 1, string $raw = '')
    {
        $this->api = $api;
        $this->verbosity = $verbosity;
        if (!empty($raw)) {
            $this->verbose("\n\nUsing Raw: " . strlen($raw) . ' characters');
            $this->raw = $raw;
        }
        $this->verbose("\n\nGetting feed: " . $this->api);
        $this->get();
        $this->verbose("\n\nGot " . strlen($this->raw) . " characters\n");
        $this->verbose('<textarea rows="5" cols="100">' . $this->raw . '</textarea>');
        $this->transform();
        $this->verbose("\n\nTransformed to " . strlen($this->raw) . " characters\n");
        $this->verbose('<textarea rows="5" cols="100">' . $this->raw . '</textarea>');
        $this->process();
        if (!empty($this->lines)) {
            $this->verbose("\n\nProcessed " . count($this->lines) . " lines\n");
            $this->verbose('<textarea rows="5" cols="100">' . print_r($this->lines, true) . '</textarea>');
        }
        $this->insert();
        $this->verbose("\n\nInserted " . count($this->data) . " entries\n");
        $this->verbose('<textarea rows="10" cols="100">' . print_r($this->data, true) . '</textarea>');
    }

    /**
     * @param string $text
     */
    public function verbose(string $text)
    {
        if ($this->verbosity > 0) {
            print $text;
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
        if (!empty($this->raw)) {
            return $this->raw;
        }
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
        $currencyDatabase = new CurrencyDatabase();
        foreach ($this->data as $bind) {
            $currencyDatabase->insertExchangeRate($bind);
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

<?php
declare(strict_types = 1);

namespace Attogram\Currency;

use Attogram\Database\Database;
use Exception;

use function array_merge;
use function array_unique;

class CurrencyDatabase
{
    /** @var int */
    const DEFAULT_LIMIT = 100;

    /** @var Database|null */
    public $database;

    public function __construct()
    {
        $this->database = new Database();
        $this->database->setDatabaseFile(
            __DIR__ . DIRECTORY_SEPARATOR
            . '..' . DIRECTORY_SEPARATOR . 'db' . DIRECTORY_SEPARATOR . 'rates.sqlite'
        );
        $this->database->setCreateTables("
            CREATE TABLE IF NOT EXISTS 'rates' (
                'day' DATETIME NOT NULL,
                'rate' NUMERIC,
                'source' TEXT NOT NULL, 
                'target' TEXT NOT NULL,
                'feed' TEXT NOT NULL, 
                'last_updated' DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, 
                PRIMARY KEY ('day', 'source', 'target', 'feed')
            )
        ");
    }

    /**
     * @param int|null $limit
     * @return array
     * @throws Exception
     */
    public function getLatestRates(int $limit = self::DEFAULT_LIMIT)
    {
        return $this->database->query('SELECT * FROM rates ORDER BY last_updated DESC, day DESC LIMIT ' . $limit);
    }

    /**
     * @param string $source
     * @return array
     * @throws Exception
     */
    public function getCurrencyPairListBySource(string $source)
    {
        return $this->database->query(
            'SELECT DISTINCT source, target FROM rates WHERE source = :s ORDER BY target',
            ['s' => $source]
        );
    }

    /**
     * @param string $currency
     * @param int $limit
     * @return array
     * @throws Exception
     */
    public function getExchangeRatesByCurrency(string $currency, int $limit = self::DEFAULT_LIMIT)
    {
        return $this->database->query(
            'SELECT * FROM rates WHERE source = :s OR target = :t ORDER BY last_updated DESC LIMIT ' . $limit,
            ['s' => $currency, 't' => $currency]
        );
    }

    /**
     * @param string $source
     * @param string $target
     * @param int $limit
     * @return array
     * @throws Exception
     */
    public function getExchageRatesByCurrencyPair(string $source, string $target, int $limit = self::DEFAULT_LIMIT)
    {
        return  $this->database->query(
            'SELECT * FROM rates WHERE source = :s AND target = :t ORDER BY last_updated DESC LIMIT ' . $limit,
            ['s' => $source, 't' => $target]
        );
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getCurrencyCodes()
    {
        $codes = array_merge(
            $this->database->query('SELECT DISTINCT source AS currency FROM rates ORDER BY source'),
            $this->database->query('SELECT DISTINCT target AS currency FROM rates ORDER BY target')
        );
        $codes = array_unique($codes, SORT_REGULAR);
        sort($codes);

        return $codes;
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getCurrencyPairs()
    {
        return $this->database->query('SELECT DISTINCT source, target FROM rates ORDER BY source, target');
    }

    /**
     * @param array $bind
     * @throws Exception
     */
    public function insertExchangeRate(array $bind)
    {
        $this->database->raw(
            'REPLACE INTO rates (day, rate, source, target, feed) VALUES (:d, :r, :s, :t, :f)',
            $bind
        );
    }
}
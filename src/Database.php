<?php
declare(strict_types = 1);

namespace Attogram\Currency;

use Exception;
use PDO;

use function array_merge;
use function array_unique;
use function file_exists;
use function in_array;
use function is_writable;
use function print_r;
use function sort;
use function touch;

class Database
{
    /** @var PDO */
    public $pdo;

    /** @var string */
    public $dbFile = __DIR__ . '/../db/rates.sqlite';

    /**
     * @throws Exception
     */
    public function __construct()
    {
        if (!in_array('sqlite', PDO::getAvailableDrivers())) {
            $this->fail('sqlite driver not found');
        }
        $createTables = false;
        if (!file_exists($this->dbFile)) {
            touch($this->dbFile);
            $createTables = true;
        }
        if (!is_writable($this->dbFile)) {
            $this->fail('Database is not writeable');
        }
        $this->pdo = new PDO('sqlite:'. $this->dbFile);
        if ($createTables) {
            $this->createTables();
        }
    }

    /**
     * @param string $sql
     * @param array $bind
     * @return array
     * @throws Exception
     */
    public function query(string $sql, array $bind = []) :array
    {
        $statement = $this->pdo->prepare($sql);
        if (!$statement) {
            $this->fail('query: prepare statement failed');
        }
        if (!$statement->execute($bind)) {
            $this->fail('query: execute statement failed');
        }
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        if (!$result && ($this->pdo->errorCode() != '00000')) {
            $this->fail('query: statement fetchAll failed');
        }

        return $result;
    }

    /**
     * @param string $sql
     * @param array $bind
     * @throws Exception
     */
    public function insert(string $sql, array $bind = [])
    {
        $statement = $this->pdo->prepare($sql);
        if (!$statement) {
            $this->fail('insert: prepare statement failed');
        }
        $result = $statement->execute($bind);
        if (!$result && ($this->pdo->errorCode() != '00000')) {
            $this->fail('insert: execute statement failed');
        }
    }

    /**
     * @param string $message
     * @throws Exception
     */
    private function fail(string $message = 'ERROR')
    {
        throw new Exception(
            $message . ': ' . $this->pdo->errorCode()
            . ' - ' . print_r($this->pdo->errorInfo(), true)
        );
    }

    /**
     * @throws Exception
     */
    public function createTables()
    {
        $this->query("
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
     * @return array
     * @throws Exception
     */
    public function getCurrencyCodes()
    {
        $codes = array_merge(
            $this->query('SELECT DISTINCT source AS currency FROM rates ORDER BY source'),
            $this->query('SELECT DISTINCT target AS currency FROM rates ORDER BY target')
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
        return $this->query(
            'SELECT DISTINCT source, target FROM rates ORDER BY source, target'
        );
    }
}

<?php
declare(strict_types = 1);

namespace Attogram\Currency;

use Exception;
use PDO;

use function file_exists;
use function in_array;
use function print_r;

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
            throw new Exception('sqlite driver not found');
        }
        $createTables = false;
        if (!file_exists($this->dbFile)) {
            touch($this->dbFile);
            $createTables = true;
        }
        if (!is_writable($this->dbFile)) {
            throw new Exception('Database is not writeable');
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
            throw new Exception('prepare statement failed: '
                . print_r($this->pdo->errorInfo(), true));
        }
        if (!$statement->execute($bind)) {
            throw new Exception('execute statement failed: '
                . print_r($this->pdo->errorInfo(), true));
        }
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        if (!$result && ($this->pdo->errorCode() != '00000')) {
            throw new Exception('statement fetchAll failed: '
                . print_r($this->pdo->errorInfo(), true));
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
            throw new Exception(
                'insert prepare statement failed: '
                . print_r($this->pdo->errorInfo(), true)
            );
        }
        $result = $statement->execute($bind);
        if (!$result && ($this->pdo->errorCode() != '00000')) {
            throw new Exception(
                'insert execute statement failed: '
                . $this->pdo->errorCode() . ' - ' . print_r($this->pdo->errorInfo(), true)
            );
        }
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
        $sources = $this->query('SELECT DISTINCT source AS currency FROM rates ORDER BY source');
        $targets = $this->query('SELECT DISTINCT target AS currency FROM rates ORDER BY target');

        return array_merge($sources, $targets);
    }
}

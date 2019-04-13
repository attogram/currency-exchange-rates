<?php
declare(strict_types = 1);

namespace Attogram\Currency;

use Exception;
use PDO;

use function in_array;

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
            throw new Exception('prepare statement failed: ' . implode(', ', $this->pdo->errorInfo()));
        }
        if (!$statement->execute($bind)) {
            throw new Exception('execute statement failed: ' . implode(', ', $this->pdo->errorInfo()));
        }
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        if (!$result && ($this->pdo->errorCode() != '00000')) {
            throw new Exception('statement fetchAll failed: ' . implode(', ', $this->pdo->errorInfo()));
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
                'insert prepare statement failed: ' . print_r($this->pdo->errorInfo())
            );
        }
        $result = $statement->execute($bind);
        if (!$result && ($this->pdo->errorCode() != '00000')) {
            throw new Exception(
                'insert execute statement failed: ' . $this->pdo->errorCode() . ' - ' . print_r($this->pdo->errorInfo())
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
}

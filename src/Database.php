<?php
declare(strict_types = 1);

namespace Attogram\Currency;

use PDO;
use PDOException;

use function in_array;
use function print_r;

class Database
{
    /** @var PDO */
    public $db;

    /** @var string */
    public $dbFile = __DIR__ . '/../db/rates.sqlite';

    public function __construct()
    {
        if ($this->init()) {
            return;
        }
        // handle error
    }

    /**
     * @return bool
     */
    public function init() :bool
    {
        if (!in_array('sqlite', PDO::getAvailableDrivers())) {
            print "sqlite not available";
            return false;
        }
        try {
            $this->db = new PDO('sqlite:'. $this->dbFile);
            $this->createTables();

            return true;
        } catch(PDOException $e) {
            print $e->getMessage();

            return false;
        }
    }

    /**
     * @param string $sql
     * @param array $bind
     * @return array
     */
    public function queryArray(string $sql, array $bind = []) :array
    {
        $statement = $this->db->prepare($sql);
        if (!$statement) {
            return [];
        }
        foreach ($bind as $name => $value) {
            $statement->bindParam($name, $value);
        }
        if (!$statement->execute()) {
            return [];
        }
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        if (!$result && ($this->db->errorCode() != '00000')) {
            $result = [];
        }

        return $result;
    }

    /**
     * @param string $sql
     * @param array $bind
     * @return bool
     */
    function queryBool(string $sql, array $bind = []) :bool
    {
        $statement = $this->db->prepare($sql);
        if (!$statement) {
            //print "\nqueryBool: statement failed: " . print_r($this->db->errorInfo(), true) . "\n";

            return false;
        }
        foreach ($bind as $name => $value) {
            $statement->bindParam($name, $value);
        }
        if (!$statement->execute()) {
            //print "\nqueryBool: execute failed\n";

            return false;
        }

        return true;
    }

    function createTables() {
        return $this->queryBool("
            CREATE TABLE IF NOT EXISTS 'rates' (
                'day' DATETIME NOT NULL,
                'rate' NUMERIC,
                'source' TEXT NOT NULL, 
                'target' TEXT NOT NULL,
                'feed' TEXT NOT NULL, 
                'last_updated' DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, 
                PRIMARY KEY ('day', 'feed', 'source', 'target')
            )
        ");
    }
}

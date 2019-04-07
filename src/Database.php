<?php
declare(strict_types = 1);

namespace Attogram\Currency;

use Attogram\Router\Router;

class Database
{
    /**
     * @param string $sql
     * @param array $bind
     * @return bool
     */
    static public function insert(string $sql, array $bind = [])
    {
        return true;
    }
}

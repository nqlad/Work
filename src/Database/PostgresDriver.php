<?php

namespace App\Database;


class PostgresDriver
{
    /** @var \PDO */
    private $connection;

    public function __construct(string $dsn, string $username, string $password)
    {
        $this->connection = new \PDO($dsn, $username, $password);
    }


}

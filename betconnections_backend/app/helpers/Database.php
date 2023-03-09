<?php

namespace App\Helpers;

use \PDO;

class Database
{
    private $host;
    private $db;
    private $user;
    private $password;
    private $connection;

    public function __construct($host, $db, $user, $password)
    {
        $this->host     = $host;
        $this->db       = $db;
        $this->user     = $user;
        $this->password = $password;
    }

    public function connect()
    {
        $dsn        = "mysql:host={$this->host};dbname={$this->db};charset=utf8";
        $options    = [
            PDO::ATTR_ERRMODE               => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE    => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES      => false,
        ];

        try {
            $this->connection = new PDO($dsn, $this->user, $this->password, $options);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public function getConnection()
    {
        if (!$this->connection) {
            $this->connect();
        }

        return $this->connection;
    }
}

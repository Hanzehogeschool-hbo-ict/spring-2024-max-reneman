<?php

namespace Hive;
use mysqli;
use mysqli_result;
use RuntimeException;

class Database
{
    private mysqli $db;

    public function __construct() {
        $this->db = new mysqli($_ENV['DB_HOST'], $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], $_ENV['DB_DATABASE'], $_ENV['DB_PORT']);
    }

    // execute query with result
    public function Query(string $string): mysqli_result {
        $result = $this->db->query($string);        if ($result === false) {

            throw new RuntimeException($this->db->error);
        }
        return $result;
    }

    // execute query without result
    public function Execute(string $string): void
    {
        $result = $this->db->query($string);
        if ($result === false) {
            throw new RuntimeException($this->db->error);
        }
    }

    // escape string for mysql
    public function Escape(string $string): string {
        return mysqli_real_escape_string($this->db, $string);
    }

    // get last insert id
    public function GetInsertId(): int {
        return intval($this->db->insert_id);
    }
}
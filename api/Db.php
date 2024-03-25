<?php

class Db
{
    private static $instance;
    private $pdo;

    protected function __construct()
    {
        $host = "localhost";
        $user = "root";
        $password = "";
        $dbname = "php_fullstack";

        $this->pdo = new PDO(
            'mysql:host=' . $host . ';dbname=' . $dbname,
            $user,
            $password
        );

        $this->pdo->exec('SET NAMES UTF8');
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function query($sql, $params = [])
    {
        try {
            $sth = $this->pdo->prepare($sql);
            $result = $sth->execute($params);

            if (str_contains(strtolower($sql), 'select')) {
                return $sth->fetchAll();
            }
            else {
                return $result;
            }

        }
        catch (\PDOException $e) {

        }
    }

    public function getLastInsertId()
    {
        return $this->pdo->lastInsertId();
    }
}
<?php
    
namespace Lib\Application;

use PDO;
use PDOStatement;

class DB
{
    private static DB|null $instance = null;
    private PDO $connection;

    private function __construct()
    {
        $this->connection = new PDO($this->_resolveDSN(), env('DB_USER'), env('DB_PASS'), [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]);
    }

    public function query(string $sql, array $params = []) : PDOStatement
    {
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);

        return $stmt;
    }

    private function _resolveDSN(): string
    {
        $dbhost = env('DB_HOST', '');
        $dbname = env('DB_NAME', '');

        return 'mysql:host=' . $dbhost . ';dbname=' . $dbname . ';charset=utf8';
    }

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public static function fetchOne(string $sql, array $params = [])
    {
        $stmt = self::getInstance()->query($sql, $params);
        return $stmt->fetch();
    }

    public static function fetchAll(string $sql, array $params = [])
    {
        $stmt = self::getInstance()->query($sql, $params);
        return $stmt->fetchAll();
    }

    public static function execute(string $sql, array $params = [])
    {
        $stmt = self::getInstance()->query($sql, $params);
        return $stmt->rowCount();
    }
}
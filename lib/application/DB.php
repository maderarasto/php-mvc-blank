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
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET sql_mode="STRICT_ALL_TABLES"',
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

    public static function getInstance() : DB
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

    public static function columns(string $tableName)
    {
        $columns = self::fetchAll('SELECT `column_name` FROM INFORMATION_SCHEMA.COLUMNS WHERE `table_name` = :table', [
            'table' => $tableName
        ]);

        return array_map(function ($tableColumn) {
            return $tableColumn['column_name'];
        }, $columns);
    }

    public static function execute(string $sql, array $params = [])
    {
        $stmt = self::getInstance()->query($sql, $params);
        return $stmt->rowCount();
    }

    public static function lastInsertedId()
    {
        return self::getInstance()->connection->lastInsertId();
    }
}
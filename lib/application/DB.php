<?php
    
namespace Lib\Application;

use PDO;
use PDOStatement;

/**
 * Represents database object that provides interface for executing SQL queries.
 */
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

    /**
     * Executes a SQL query and binds parameters with it.
     * 
     * @param string $sql 
     * @param array $params 
     * @return bool|PDOStatement
     */
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

    /**
     * Retrieves an instance of database object.
     * 
     * @return DB|null
     */
    public static function getInstance() : DB
    {
        if (self::$instance == null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Fetches one record of select query.
     * 
     * @param string $sql 
     * @param array $params 
     * @return mixed one record
     */
    public static function fetchOne(string $sql, array $params = [])
    {
        $stmt = self::getInstance()->query($sql, $params);
        return $stmt->fetch();
    }

    /**
     * Fetches all records of select query.
     * 
     * @param string $sql 
     * @param array $params 
     * @return array list of records
     */
    public static function fetchAll(string $sql, array $params = [])
    {
        $stmt = self::getInstance()->query($sql, $params);
        return $stmt->fetchAll();
    }

    /**
     * Retrieves columns of given table.
     * 
     * @param string $tableName 
     * @return array list of columns
     */
    public static function columns(string $tableName)
    {
        $columns = self::fetchAll('SELECT `column_name` FROM INFORMATION_SCHEMA.COLUMNS WHERE `table_name` = :table', [
            'table' => $tableName
        ]);

        return array_map(function ($tableColumn) {
            return $tableColumn['column_name'];
        }, $columns);
    }

    /**
     * Executes a SQL query.
     * 
     * @param string $sql 
     * @param array $params 
     * @return int
     */
    public static function execute(string $sql, array $params = [])
    {
        $stmt = self::getInstance()->query($sql, $params);
        return $stmt->rowCount();
    }

    /**
     * Retrieves last inserted ID by current connection.
     * @return bool|string
     */
    public static function lastInsertedId()
    {
        return self::getInstance()->connection->lastInsertId();
    }
}
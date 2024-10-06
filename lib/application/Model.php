<?php

namespace Lib\Application;

class Model
{
    protected string $tableName = '';
    protected string $primaryKey = 'id';

    private array $attributes = [];


    public function __construct(array $data = [])
    {
        $this->_resolveTableName();
    }

    public function __get(string $name)
    {
        if (!isset($this->attributes[$name])) {
            return null;
        }
        
        return $this->attributes[$name];
    }
    
    public function __set(string $name, mixed $value)
    {
        $this->attributes[$name] = $value;  
    }

    public function save()
    {
        
    }

    private function _resolveTableName()
    {
        if (!empty($this->tableName)) {
            return;
        }

        $tokens = preg_split('/(?=[A-Z])/', self::getClassName());
        $tokens = array_filter($tokens, function ($token) {
            return !empty($token);
        });

        $tokens = array_map(function ($token) {
            return strtolower($token);
        }, $tokens);

        $this->tableName = implode('_', $tokens);
    }

    public static function find(int $id)
    {
        $model = self::createModel();
        $query = 'SELECT * FROM `' . $model->tableName . '` WHERE `' . $model->primaryKey . '` = :id';
        $result = DB::fetchOne($query, [ 'id' => $id]);

        if (empty($result))
            return null;

        foreach ($result as $column => $value) {
            $model->attributes[$column] = $value;
        }

        return $model;
    }

    public static function all(int|null $limit = null)
    {
        $models = [];
        
        $tableModel = self::createModel();
        $query = 'SELECT * FROM `' . $tableModel->tableName . '`';

        if ($limit) {
            $query .= ' LIMIT ' . $limit;
        }
        
        $result = DB::fetchAll($query);

        if (empty($result)) {
            return $models;;
        }

        foreach ($result as $row) {
            $model = self::createModel();

            foreach ($row as $column => $value) {
                $model->attributes[$column] = $value;
            }

            $models[] = $model;
        }

        return $models;
    }

    public static function create(array $data = [])
    {
        
    }

    public static function update(array $data = [])
    {
        
    }

    public static function delete(int $id)
    {
        
    }

    protected static function getClassName()
    {
        $class = get_called_class();
        $classTokens = explode('\\', $class);

        return end($classTokens);
    }

    protected static function createModel() : Model
    {
        $class = get_called_class();
        return new $class;
    }
}
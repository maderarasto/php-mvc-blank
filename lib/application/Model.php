<?php

namespace Lib\Application;
use CommonMark\Node\Text;

class Model
{
    protected string $tableName = '';
    protected string $primaryKey = 'id';
    protected array $fillable = [];
    protected array $hidden = [];

    private array $attributes = [];


    public function __construct()
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

    public function __tostring()
    {
        return $this->_printObject();
    }

    public function save()
    {
        
    }

    private function _printObject($indent = 1)
    {
        $base_indent = 16;
        $text = get_class_name($this) . ' (<br />';

        foreach (get_object_vars($this) as $key => $value) {
            $text .= '<span style="padding-left: ' . ($indent * $base_indent) . 'px;">"' . $key . '" => ';
            
            if (is_array($value)) {
                $text .= print_array($value, $indent + 1);
            } else if (is_string($value)) {
                $text .= '"' . $value . '",<br />';
            } else {
                $text .= $value . ',<br />';
            }
        }

        $text .= '<span style="padding-left: ' . (($indent - 1) * $base_indent) . 'px">),</span><br />';

        return $text;
    }

    private function _resolveTableName()
    {
        if (!empty($this->tableName)) {
            return;
        }

        $tokens = preg_split('/(?=[A-Z])/', get_class_name($this));
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
        $result = DB::fetchOne($query, [ $model->primaryKey => $id]);

        if (empty($result))
            return null;

        foreach ($result as $column => $value) {
            if (in_array($column, $model->hidden)) {
                continue;
            }

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
                if (in_array($column, $model->hidden)) {
                    continue;
                }

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

    protected static function createModel() : Model
    {
        $class = get_called_class();
        return new $class;
    }
}
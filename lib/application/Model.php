<?php

namespace Lib\Application;

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

    public function fill(array $data = [])
    {
        foreach ($data as $key => $value) {
            if (!in_array($key, $this->fillable)) {
                continue;
            }

            $this->attributes[$key] = $value;
        }
    }

    public function save()
    {
        $tableColumns = DB::columns($this->tableName);

        // Select attributes that can be stored into DB table.
        $foundAttrs = array_filter($this->attributes, function (string $attrKey) use ($tableColumns) {
            return in_array($attrKey, $tableColumns);
        }, ARRAY_FILTER_USE_KEY);

        if (empty($foundAttrs[$this->primaryKey])) {
            $result = $this->_insert($foundAttrs);
        } else {
            $result = $this->_update($foundAttrs);
        }

        if (!$result) {
            // POSSIBLE ERROR
            return;
        }
    }

    public function destroy()
    {
        $sql = 'DELETE FROM ' . $this->tableName . ' WHERE ' . $this->primaryKey . ' = :' . $this->primaryKey;
        $result = DB::execute($sql, [$this->primaryKey => $this->id]);
    }

    private function _insert(array $attributes)
    {
        $sql = 'INSERT INTO `' . $this->tableName . '`(';

        foreach (array_keys($attributes) as $column) {
            $sql .= $column . ', ';
        }

        if (count($attributes)) {
            $sql = substr($sql, 0, -2);
        }

        $sql .= ') VALUES (';

        foreach (array_keys($attributes) as $column) {
            $sql .= ':' . $column . ', ';
        }

        if (count($attributes) > 0) {
            $sql = substr($sql, 0, -2);
        }

        $sql .= ')';
        
        DB::execute($sql, $attributes);
        $resultId = DB::lastInsertedId();

        return $resultId;
    }

    private function _update(array $attributes)
    {
        $sql = 'UPDATE ' . $this->tableName . ' SET ';

        foreach (array_keys($attributes) as $column) {
            if ($column == 'id')
                continue;

            $sql .= $column . ' = :' . $column . ', ';
        }

        if (count($attributes) > 0) {
            $sql = substr($sql, 0, -2);
        }

        $sql .= ' WHERE ' . $this->primaryKey . ' = :' . $this->primaryKey;
        
        DB::execute($sql, $attributes);
        $resultId = DB::lastInsertedId();
        
        return $resultId;
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

    public static function findMany(array $ids = [])
    {
        $defaultModel = self::createModel();
        $sql = 'SELECT * FROM `' . $defaultModel->tableName . '` WHERE `' . $defaultModel->primaryKey . '` IN (';

        for ($i = 0; $i < count($ids); $i++) {
            $sql .= '?,';
        }

        if (count($ids)) {
            $sql = substr($sql, 0, -1);
        }

        $sql .= ')';

        return DB::fetchAll($sql, $ids);
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
        $model = self::createModel();
        $model->fill($data);
        $model->save();

        return $model;
    }

    public static function update(int $id, array $data = [])
    {
        $model = self::find($id);

        if (!$model) {
            return null;
        }

        $model->fill($data);
        $model->save();

        return $model;
    }

    public static function delete(int $id)
    {
        $model = self::find($id);

        if (!model) {
            return false;
        }

        $model->destroy();
        return true;
    }

    public static function deleteMany(array $ids)
    {
        $defaultModel = self::createModel();
        $sql = 'DELETE FROM ' . $defaultModel->tableName . ' WHERE ' . $defaultModel->primaryKey . ' IN (';

        for ($i = 0; $i < count($ids); $i++) {
            $sql .=  '?,';
        }

        if (count($ids) > 0) {
            $sql = substr($sql, 0, -1);
        }

        $sql .= ')';

        DB::execute($sql, $ids);
        return true;
    }

    protected static function createModel() : Model
    {
        $class = get_called_class();
        return new $class;
    }
}
<?php
namespace ultraphp\core;
use ultraphp\core\orm\Query;
use ultraphp\core\DBManager as DB;

/**
 * Model Class
 * Base Model
 * 
 * @author Neeraj Mourya <neeraj@egrapes.in>, <neerajmorya@gmail.com>
 * @copyright (c) 2018, Neeraj Mourya
 * @license https://opensource.org/licenses/MIT MIT
 * @since 2.1.0
 */
class Model {

    const TABLE_NAME = '';

    /**
     * Constructs the model object
     */
    public function __construct($arr = array()) {
        $this->import($arr);
    }

    /**
     * Insert row in the table
     * 
     * @param array $args
     * @return boolean|object returns object if succeed else false
     */
    public static function insert($args) {
        return DB::get_query(get_class($this))->insert($args);
    }

    /**
     * Insert row in the table
     * 
     * @param array $args
     * @return boolean/object returns object if succeed else false
     */
    public static function create($args) {
        return static::insert($args);
    }

    /**
     * Updates record in the table
     * 
     * @param array $args
     * @return boolean/object returns object if succeed else false
     */
    public static function update($args) {
        return DB::get_query(get_class($this))->update($args);
    }
    
    /**
     * Insert or update model
     * @return boolean|\ultraphp\core\Model
     */
    public function save() {
        $model = false;
        if (isset($this->id) && !empty($this->id) && $this->id != 0) {
            $model = static::update((array) $this);
        } else {
            $model = static::insert((array) $this);
        }

        if (isset($model) && $model != false) {
            $this->import($model);
            return $this;
        } else {
            return false;
        }
    }

    /**
     * Deletes multiple records from table
     * 
     * @param array $ids
     * @return boolean/int returns deleted records counts if succeed else false
     */
    public static function destroy($ids) {
        return DB::get_query(get_class($this))->destroy($ids);
    }

    

    /**
     * deletes record from table
     * 
     * @param int $id
     * @return boolean/int returns deleted record counts if succeed else false
     */
    public function delete() {
        DB::get_query(get_class($this))->delete($this->id);
    }

    /**
     * Import attributes from array or object
     * 
     * @param object/array $data
     * @return \UltraModel
     */
    public function import($data) {
        if (isset($data) && is_object($data)) {
            $data = get_object_vars($data);
        }
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
        return $this;
    }

    /**
     * returns column name with table
     * 
     * @param string $columnName
     * @return string
     */
    public static function column($columnName) {
        return static::TABLE_NAME . ".$columnName";
    }

    /**
     * Retrieves the record against given id
     * @param int $id
     * @return Model
     */
    public static function get($id) {
        $uq = DB::get_query(get_called_class());
        return $uq->columns("*")
                        ->from(static::TABLE_NAME)
                        ->where('id', $id)
                        ->first();
    }

    /**
     * Returns All the records for the model
     * @return array
     */
    public static function all() {
        $uq = DB::get_query(get_called_class());
        return $uq->columns("*")
                        ->from(static::TABLE_NAME)
                        ->get();
    }

    /**
     * Returns query with where conditions
     * @param string $args multiple arguments
     * @return Query
     */
    public static function where(...$args) {
        $uq = DB::get_query(get_called_class());
        return $uq->columns("*")
                        ->from(static::TABLE_NAME)
                        ->where(...$args);
    }

    /**
     * Returns query with orderby
     * @param string $key
     * @param string $order
     * @return Query
     */
    public static function orderBy($key, $order) {
        $uq = DB::get_query(get_called_class());
        return $uq->columns("*")
                        ->from(static::TABLE_NAME)
                        ->orderBy($key, $order);
    }

    /**
     * Returns Query for has many relationship
     * @param string $relationClass
     * @param string $foreignKey
     * @param string $referenceKey
     * @return Query
     */
    public function hasMany($relationClass, $foreignKey, $referenceKey = 'id') {
        return $relationClass::where($foreignKey,$this->$referenceKey);
    }
    
    /**
     * Returns Query for has one relationship
     * @param string $relationClass
     * @param string $foreignKey
     * @param string $referenceKey
     * @return Query
     */
    public function hasOne($relationClass, $foreignKey, $referenceKey = 'id') {
        return $relationClass::where($foreignKey,$this->$referenceKey)->first();
    }
    
    /**
     * Returns query for belongs to
     * @param string $relationClass
     * @param string $foreignKey
     * @param string $referenceKey
     * @return Query
     */
    public function belongsTo($relationClass, $foreignKey, $referenceKey = 'id'){
        return $relationClass::where($referenceKey,$this->$foreignKey)->first();
    }

}

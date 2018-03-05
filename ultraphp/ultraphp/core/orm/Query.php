<?php
namespace ultraphp\core\orm;
use ultraphp\core\Model;
use ultraphp\core\DBManager;
/**
 * Query Class
 * Creates Manages and Executes Queries
 * 
 * @author Neeraj Mourya <neeraj@egrapes.in>, <neerajmorya@gmail.com>
 * @since 2.1.0
 */
class Query {

    /**
     * Stores query model
     * @var string
     */
    public $model;
    
    /**
     * Stores query columns
     * @var string 
     */
    public $columns = "*";
    
    /**
     * Stores query from string
     * @var string 
     */
    public $from;
    
    /**
     * Stores query conditions
     * @var string
     */
    public $conditions;
    
    /**
     * Stores orderby
     * @var string 
     */
    public $orderBy;
    
    /**
     * Stores record limits
     * @var int
     */
    public $limit;
    
    /**
     * Stores records offset
     * @var int
     */
    public $offset;

    /**
     * Constructs the query
     * @param Model $model
     */
    public function __construct($model = null) {
        $this->model = $model;        
    }
    
    /**
     * Insert row in the table
     * 
     * @param array $args
     * @return boolean|Model returns object if succeed else false
     */
    public function insert($args){
        $columns = "";
        $values = "";
        foreach ($args as $key => $value) {
            $columns .= $key . ",";
            $values .= "'" . $value . "',";
        }
        if (isset($columns) && !empty($columns) && isset($values) && !empty($values)) {
            $columns = rtrim($columns, ",");
            $values = rtrim($values, ",");            
            $tableName = constant("$this->model::TABLE_NAME");
            $connection = DBManager::get_connection();
            try {
                $sql = "INSERT INTO $tableName ($columns) VALUES ($values)";
                // use exec() because no results are returned
                $connection->exec($sql);
                
                $model = $this->model;
                return $model::get($connection->lastInsertId());
            } catch (PDOException $e) {
                return false;
            }
        }
        return false;
    }
    
    /**
     * Insert row in the table
     * 
     * @param array $args
     * @return boolean/object returns object if succeed else false
     */
    public function create($args) {
        return $this->insert($args);
    }
    
    
    /**
     * Updates record in the table
     * 
     * @param array $args
     * @return boolean|Model returns object if succeed else false
     */
    public function update($args) {
        $values = "";
        foreach ($args as $key => $value) {
            $values .= "$key='$value',";
        }
        if (isset($values) && !empty($values)) {
            $values = rtrim($values, ",");
            $tableName = constant("$this->model::TABLE_NAME");
            $connection = UltraDBConnection::get_connection();
            try {
                $updated_at = date('Y-m-d h:i:s');
                $sql = "UPDATE $tableName SET $values "
                        . "WHERE id=" . $args['id'];
                // use exec() because no results are returned
                $connection->exec($sql);
                
                $model = $this->model;
                return $model::get($args['id']);
            } catch (PDOException $e) {
//            echo $e->getMessage();
                return false;
            }
        }
    }
    
    /**
     * Deletes multiple records from table
     * 
     * @param array $ids
     * @return boolean|int returns deleted records counts if succeed else false
     */
    public function destroy($ids) {
        if (isset($ids) && !empty($ids)) {
            try {
                $tableName = constant("$this->model::TABLE_NAME");
                $connection = DBManager::get_connection();

                $sql = "DELETE FROM $tableName WHERE id IN (" . implode(',', array_map('intval', $ids)) . ")";
                return $connection->exec($sql);
            } catch (PDOException $e) {
                
            }
        }
        return false;
    }
    
    /**
     * deletes record from table
     * 
     * @param int $id
     * @return boolean/int returns deleted record counts if succeed else false
     */
    public function delete($id) {
        try {
            $tableName = constant("$this->model::TABLE_NAME");
            $connection = DBManager::get_connection();

            $sql = "DELETE FROM $tableName WHERE id=$id";
            return $connection->exec($sql);
        } catch (PDOException $e) {
            
        }
        return false;
    }
    
    /**
     * Defines query columns
     * @param string $columns enter multiple columns seperated by commas
     */
    public function columns(...$columns){
        $this->columns = implode(",", $columns);
        return $this;
    }

    /**
     * Defines query tables
     * @param string $tables enter multiple tables seperated by commas
     */
    public function from(...$tables) {
        $this->from = implode(",", $tables);
        return $this;
    }

    /**
     * Defines query conditions
     * @param string $args enter multiple args seperated by commas
     */
    public function where(...$args) {
        return $this->buildConditions($args);
    }

    /**
     * Defines query OR conditions
     * @param string $args enter multiple args seperated by commas
     */
    public function orWhere(...$args) {
        return $this->buildConditions($args, "OR");
    }

    /**
     * Build conditions
     * @param array $args
     * @param string $operator
     * @return \ultraphp\core\orm\Query
     */
    private function buildConditions($args = array(), $operator = 'AND') {
        if (isset($args) && !empty($args)) {
            if (!is_array($args[0])) {
                if (isset($this->conditions) && !empty($this->conditions)) {
                    $this->conditions .= " $operator " . $this->condition($args);
                } else {
                    $this->conditions = $this->condition($args);
                }
            } else {
                $conditions = $this->conditions($args);
                if (isset($this->conditions) && !empty($this->conditions) && !empty($conditions)) {
                    $this->conditions .= " $operator (" . $conditions . ")";
                } else {
                    $this->conditions = "(" . $conditions . ")";
                }
            }
        }
        return $this;
    }

    /**
     * Assemble and return conditions
     * @param type $args
     * @return string
     */
    private function conditions($args = array()) {
        if (isset($args) && !empty($args)) {
            $conditions = "";
            for ($i = 0; $i < sizeof($args); $i++) {
                if ($i == 0) {
                    $conditions .= $this->condition($args[$i]);
                } else {
                    if (isset($args[$i][3])) {
                        $conditions .= " " . $args[$i][3] . " " . $this->condition($args[$i]);
                    } else {
                        $conditions .= " AND " . $this->condition($args[$i]);
                    }
                }
            }
            return $conditions;
        }
        return "";
    }

    /**
     * create and return condition
     * @param array $args
     * @return string
     */
    private function condition($args = array()) {
        if (isset($args) && !empty($args)) {
            if (sizeof($args) == 2) {
                return $args[0] . "='" . $args[1] . "'";
            } elseif (sizeof($args) > 2) {
                if (isset($args[4]) && !empty($args[4]) && $args[4] == true) {
                    return $args[0] . $args[1] . $args[2];
                } else {
                    return $args[0] . $args[1] . "'" . $args[2] . "'";
                }
            }
        }
        return "";
    }

    /**
     * Add query order
     * @param string $key
     * @param string $order
     * @return \ultraphp\core\orm\Query
     */
    public function orderBy($key, $order) {
        if (isset($this->orderBy) && !empty($this->orderBy)) {
            $this->orderBy .= ", $key $order";
        } else {
            $this->orderBy = "$key $order";
        }
        return $this;
    }

    /**
     * Add limit to the select query
     * @param int $limit
     * @return \ultraphp\core\orm\Query
     */
    public function limit($limit) {
        $this->limit = $limit;
        return $this;
    }

    /**
     * Adds offset to the select query
     * @param int $offset
     * @return \ultraphp\core\orm\Query
     */
    public function offset($offset) {
        $this->offset = $offset;
        return $this;
    }

    /**
     * Assembles the query as one string
     * @return string
     */
    public function getQuery() {
        if (isset($this->from) && !empty($this->from)) {
            $query = " FROM $this->from";
        }
        
        if (isset($this->conditions) && !empty($this->conditions)) {
            $query .= " WHERE $this->conditions";
        }

        if (isset($this->orderBy) && !empty($this->orderBy)) {
            $query .= " ORDER BY $this->orderBy";
        }

        if (isset($this->limit) && !empty($this->limit)) {
            $query .= " LIMIT $this->limit";
        }

        if (isset($this->offset) && !empty($this->offset)) {
            $query .= " OFFSET $this->offset";
        }
        return $query;
    }

    /**
     * Executes Query and return model rows
     * @return array|boolean returns array of records or false on fail
     */
    public function get() {
        try {
            $query = $this->getQuery();
            $connection = DBManager::get_connection();

            $sql = "SELECT $this->columns $query";
//            echo $sql;
            $sql = trim($sql);
            $stmt = $connection->query($sql);
            $rows = array();
            while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
                if(isset($this->model) && $this->model!=null){
                    $rows[] = new $this->model($row);
                }else{
                    $rows[] = $row;
                }                
            }
            return $rows;
        } catch (PDOException $e) {
            
        }
        return false;
    }
    
    /**
     * Executes the query and returns the first record of selection
     * @return boolean|Model
     */
    public function first() {
        try {
            $query = $this->getQuery();
            $connection = DBManager::get_connection();

            $sql = "SELECT $this->columns $query";
//            echo $sql;
            $sql = trim($sql);
            $stmt = $connection->query($sql);            
            if ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
                if(isset($this->model) && $this->model!=null){
                    return new $this->model($row);
                }else{
                    return $row;
                }                
            }
        } catch (PDOException $e) {
            
        }
        return false;
    }
    
    /**
     * Executes the query and returns the record counts
     * @return boolean|int record count on succeed else false
     */
    public function count() {
        try {
            $query = $this->getQuery();
            $connection = DBManager::get_connection();

            $sql = "SELECT COUNT(id) as total $query";
            echo $sql;
            $sql = trim($sql);
            $stmt = $connection->query($sql);            
            if ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
                return $row['total'];
            }
        } catch (PDOException $e) {
            
        }
        return false;
    }
}

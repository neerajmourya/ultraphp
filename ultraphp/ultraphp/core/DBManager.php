<?php

namespace ultraphp\core;
use PDO;
use PDOException;

/**
 * UltraDBConnection Class
 * 
 * Manages Database Connections
 * 
 * @author Neeraj Mourya <neeraj@egrapes.in>, <neerajmorya@gmail.com>
 * @copyright (c) 2018, Neeraj Mourya
 * @license https://opensource.org/licenses/MIT MIT
 * @since 2.1.0
 */
class DBManager {

    /**
     * stores PDO object instance
     * @var PDO
     */
    private static $conn;

    /**
     * Get the active instance of PDO Connection
     * 
     * @return PDOConnection
     * @since 1.0.0
     */
    public static function get_connection() {
        if (isset(self::$conn) && is_object(self::$conn)) {
            
        } else {
            try {
                
                $vendor = Config::get('database', 'DB_VENDOR');
                switch ($vendor) {
                    case 'mysql':
                        $mysql = Config::get('database', 'DB_MYSQL');
                        $mysql = (object) $mysql;
                        self::$conn = new PDO("mysql:host=$mysql->HOST;dbname=$mysql->NAME", $mysql->USERNAME, $mysql->PASSWORD);
                        // set the PDO error mode to exception
                        self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    //echo "Connected successfully";
                        break;
                    case 'oracle':
                        break;
                    case 'pgsql':
                        break;
                }
            } catch (PDOException $e) {
                //echo "Connection failed: " . $e->getMessage();
            }
        }

        return self::$conn;
    }
    
    public static function get_query($model = null){
        $vendor = Config::get('database', 'DB_VENDOR');
        switch ($vendor){
            case 'mysql':
                return new database\MySqlQuery($model);
            case 'oracle':
                return new database\Query($model);
            case 'pgsql':
                return new database\Query($model);
        }
    }
}

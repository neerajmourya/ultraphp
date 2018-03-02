<?php

namespace ultraphp\core;
/**
 * Request Class
 * 
 * Manages Request
 * @author Neeraj Mourya <neeraj@egrapes.in>, <neerajmorya@gmail.com>
 * @since 2.0.0
 */
class Request {
    /**
     * @var array stores request data 
     */
    private $data;
    
    /**
     * @var Request stores Request instance 
     */
    private static $request;
    
    /**
     * @var array stores old request data 
     */
    private $old;

    /**
     * Constructs the Request object
     */
    public function __construct() {
        $this->data = $_REQUEST;
        $this->old = array();
    }

    /**
     * Returns Request object if exists else create new object and return
     * @return Request returns active request object
     */
    public static function get() {
        if (isset(self::$request)) {
            return self::$request;
        } else {
            self::$request = new Request();
            return self::$request;
        }
    }

    /**
     * Returns value against the key from request
     * @param string $key
     * @param string $default
     * @return type return value from data array for a key
     */
    public function input($key, $default) {
        if (isset($this->data[$key]) && !empty($this->data[$key])) {
            return $this->data[$key];
        } else {
            return $default;
        }
    }

    /**
     * Returns request data as array
     * @return array Returns request data as array
     */
    public function all() {
        return $this->data;
    }

    /**
     * Stores current request data to session 
     * which is retrieved as old data in next request
     */
    public function toSession(){
        $data = $this->data;
        unset($data['_method']);
        unset($data['_csrf']);
        
        Session::put('old', $this->data);
    }
    
    /**
     * Sets the old data
     * @param array $old
     */
    public function setOld($old){
        $this->old = $old;
    }
    
    /**
     * Get the old data
     * @return array
     */
    public function getOld(){
        return $this->old;
    }
    
}

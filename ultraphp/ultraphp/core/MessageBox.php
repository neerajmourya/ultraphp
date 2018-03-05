<?php

namespace ultraphp\core;
/**
 * MessageBox class
 * Provides functions to store and manage messages
 * @author Neeraj Mourya <neeraj@egrapes.in>, <neerajmorya@gmail.com>
 * @copyright (c) 2018, Neeraj Mourya
 * @license https://opensource.org/licenses/MIT MIT
 * @since 2.0.0
 */
class MessageBox {
    /**
     * stores messages as key and value pairs
     * @var array 
     */
    private $messageBox;
    
    /**
     * Construct the MessageBox
     * @param array $messages Messages array
     */
    public function __construct($messages = array()) {
        $this->messageBox = $messages;
    }
    
    /**
     * set values against the key
     * @param string $key
     * @param string $values multiple values arguments ....
     */
    public function put($key, ...$values){
        $this->messageBox[$key] = $values;
    }
    
    /**
     * returns messages array as key value pairs.
     * @param string $key
     * @return array
     */
    public function get($key){
        return $this->messageBox[$key];
    }
    
    /**
     * Returns true if messages for particular key exists else false.
     * @author Neeraj Mourya <neeraj@egrapes.in>, <neerajmorya@gmail.com>
     * @since 2.0.0
     * @param string $key
     * @return boolean true if message key exists else false
     */
    public function has($key){
        if(isset($this->messageBox[$key]) && !empty($this->messageBox[$key])){
            return true;
        }
        return false;
    }
    
    /**
     * Removes messages agains give key
     * @param string $key
     */
    public function remove($key){
        if(isset($this->messageBox[$key]) && !empty($this->messageBox[$key])){
            unset($this->messageBox[$key]);
        }
    }    
    
    /**
     * Returns the current MessageBox
     * @return MessageBox
     */
    public function getBox(){
        return $this->messageBox;
    }
}
?>
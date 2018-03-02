<?php
namespace ultraphp\core;

/**
 * MessageBoxes class
 * Manages MessageBoxes
 * @author Neeraj Mourya <neeraj@egrapes.in>, <neerajmorya@gmail.com>
 * @since 2.0.0
 */
class MessageBoxes {

    /**
     *
     * @var array stores messageBoxes 
     */
    private static $messageBoxes = array();
    
    /**
     * Puts value against a key in the given box
     * @param string $key
     * @param type $value
     * @param string $box
     */
    public static function put($key, $value, $box = "global"){
        if(!isset(self::$messageBoxes[$box])){
            self::$messageBoxes[$box] = new MessageBox();
        }
        self::$messageBoxes[$box]->put($key, $value);
    }
    
    /**
     * Retrieves a value against given key from given box
     * @param string $key
     * @param string $box
     * @return type
     */
    public static function get($key, $box="global"){
        if(isset(self::$messageBoxes[$box]) && self::$messageBoxes[$box]->has($key)){
            return self::$messageBoxes[$box]->get($key);
        }
        return false;
    }
    
    /**
     * sets messagebox by the given boxname
     * @param string $boxName
     * @param MessageBox $box
     */
    public static function putBox($boxName, $box){
        self::$messageBoxes[$boxName] = $box;
    }
    
    /**
     * Retrieves the MessageBox by the given boxname
     * @param string $boxName
     * @return MessageBox
     */
    public static function getBox($boxName){
        if(isset(self::$messageBoxes[$boxName])){
            return self::$messageBoxes[$boxName];
        }
    }
    
    /**
     * checks if a box exists or not
     * @param type $boxName
     * @return boolean returns true if box exists else false
     */
    public static function hasBox($boxName){
        if(isset(self::$messageBoxes[$boxName])){
            return true;
        }
        return false;
    }
    
    /**
     * Returns MessageBoxes array
     * @return array
     */
    public static function getBoxes(){
        return self::$messageBoxes;
    }
    
    /**
     * Sets MessageBoxes array
     * @param array $messageBoxes
     */
    public static function setBoxes($messageBoxes){
        self::$messageBoxes = $messageBoxes;
    }
}
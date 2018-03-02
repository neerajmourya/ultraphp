<?php
namespace ultraphp\core;
/**
 * Session class
 * Manages Session Values
 * @author Neeraj Mourya <neeraj@egrapes.in>, <neerajmorya@gmail.com>
 * @since 2.0.0
 */
class Session {
    
    const CSRF_TOKENS = 'CSRF_TOKENS';
    const MESSAGE_BOXES = 'MESSAGE_BOXES';
    const USER_ID = 'USER_ID';
    const SESSION_ID = 'SESSION_ID';

    /**
     * Stores a value in session against a key
     * @param string $key
     * @param type $value
     */
    public static function put($key, $value){
        $_SESSION[$key] = UltraEncryption::encrypt(serialize($value));                
    }
    
    /**
     * Retrieves a value against a key
     * @param string $key
     * @return type
     */
    public static function get($key){
        if(isset($_SESSION[$key])){
            return unserialize(UltraEncryption::decrypt($_SESSION[$key]));
        }        
    }
    
    /**
     * Removes a value against key
     * @param string $key
     */
    public static function remove($key){
        if(isset($_SESSION[$key])){
            unset($_SESSION[$key]);
        }
    }
    
    /**
     * checks if value agains given key exists or not
     * @param string $key
     * @return boolean returns true if value exists
     */
    public static function has($key){
        if(isset($_SESSION[$key]) && !empty($_SESSION[$key])){
            return true;
        }
        return false;
    }
    
    /**
     * push value to session against given key
     * synonym to put function
     * @param string $key
     * @param type $value
     */
    public static function push($key, $value){
        self::put($key, $value);
    }
    
    /**
     * Retrieves a value against given key and removes from session
     * @param string $key
     * @return type
     */
    public static function pull($key){
        if(self::has($key)){
            $value = self::get($key);
            self::remove($key);
            return $value;
        }
    }
    
    /**
     * Invalidates the session
     */
    public static function invalidate() {
        session_destroy();
    }

}


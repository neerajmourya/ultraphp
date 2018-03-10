<?php

namespace ultraphp\core;

/**
 * Session class
 * Manages Session Values
 * @author Neeraj Mourya <neeraj@egrapes.in>, <neerajmorya@gmail.com>
 * @copyright (c) 2018, Neeraj Mourya
 * @license https://opensource.org/licenses/MIT MIT
 * @since 2.0.0
 */
class Session {

    const CSRF_TOKENS = 'CSRF_TOKENS';
    const MESSAGE_BOXES = 'MESSAGE_BOXES';
    const USER_ID = 'USER_ID';
    const AUTH_TOKEN = 'AUTH_TOKEN';
    const USER_IP = 'USER_IP';
    const USER_AGENT = 'USER_AGENT';

    public static function start() {
        //$session_name = Helper::randomString(16, true, true, false, true);   // Set a custom session name 
        $session_name = Config::get('session', 'SESSION_NAME');
        $secure = Config::get('session', 'SESSION_SECURE');
        // This stops JavaScript being able to access the session id.
        $httponly = Config::get('session', 'SESSION_HTTPONLY');
        // Forces sessions to only use cookies.
        if (ini_set('session.use_only_cookies', 1) === FALSE) {
            header("Location: ../error.php?err=Could not initiate a safe session (ini_set)");
            exit();
        }
        // Gets current cookies params.
        $cookieParams = session_get_cookie_params();
        session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure, $httponly);
        // Sets the session name to the one set above.
        session_name($session_name);
        session_start();            // Start the PHP session 
        session_regenerate_id(true);    // regenerated the session, delete the old one. 
        Session::preventHijack();
    }
    
    /**
     * Prevent Hijack attack by checking userip and user agent
     */
    public static function preventHijack() {
        //Storing user ip and browser in session
        $ipAddress = $_SERVER['REMOTE_ADDR'];
        if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
            $ipAddress = array_pop(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']));
        }
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
//        echo $user_agent;
        
        if (!Session::has(Session::USER_IP) && !Session::has(Session::USER_AGENT)) {
            Session::put(Session::USER_IP, $ipAddress);
            Session::put(Session::USER_AGENT, $user_agent);
        }else{
            $sessionIP = Session::get(Session::USER_IP);
            $sessionUserAgent = Session::get(Session::USER_AGENT);
            if($ipAddress!==$sessionIP || $user_agent!==$sessionUserAgent){
                Session::destroy();
                Session::start();
            }
        }
    }

    public static function destroy() {
        // Unset all session values 
        $_SESSION = array();

        // get session parameters 
        $params = session_get_cookie_params();

        // Delete the actual cookie.         
        setcookie(session_name(), '', 0, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);

        // Destroy session 
        session_destroy();
    }

    /**
     * Stores a value in session against a key
     * @param string $key
     * @param type $value
     */
    public static function put($key, $value) {
        $_SESSION[$key] = UltraEncryption::encrypt(serialize($value));
    }

    /**
     * Retrieves a value against a key
     * @param string $key
     * @return type
     */
    public static function get($key) {
        if (isset($_SESSION[$key])) {
            return unserialize(UltraEncryption::decrypt($_SESSION[$key]));
        }
    }

    /**
     * Removes a value against key
     * @param string $key
     */
    public static function remove($key) {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    /**
     * checks if value agains given key exists or not
     * @param string $key
     * @return boolean returns true if value exists
     */
    public static function has($key) {
        if (isset($_SESSION[$key]) && !empty($_SESSION[$key])) {
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
    public static function push($key, $value) {
        self::put($key, $value);
    }

    /**
     * Retrieves a value against given key and removes from session
     * @param string $key
     * @return type
     */
    public static function pull($key) {
        if (self::has($key)) {
            $value = self::get($key);
            self::remove($key);
            return $value;
        }
    }

    


}

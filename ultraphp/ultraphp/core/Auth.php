<?php

namespace ultraphp\core;

/**
 * Auth Class
 * 
 * Manages Authentication
 * @author Neeraj Mourya <neeraj@egrapes.in>, <neerajmorya@gmail.com>
 * @copyright (c) 2018, Neeraj Mourya
 * @license https://opensource.org/licenses/MIT MIT
 * @since 2.0.0
 */
class Auth {

    /**
     * Stores user authentication to session
     * @param int $userid
     */
    public static function setAuth($userid) {
        if (isset($userid) && !empty($userid)) {

            $session_id = self::generateSessionId($userid);
            Session::put(Session::USER_ID, $userid);
            Session::put(Session::SESSION_ID, $session_id);
//            $_SESSION['userinfo'] = $$userid;            
        }
    }

    /**
     * Authenticate the current user session
     * @return boolean returns true if authenticated else false
     */
    public static function authenticateSession() {
        if (Session::has(Session::SESSION_ID)) {
            $session_id = Session::get(Session::SESSION_ID);
            $userid = Session::get(Session::USER_ID);
            if (isset($userid) && !empty($userid)) {
                $gen_session_id = self::generateSessionId($userid);
                if ($session_id === $gen_session_id) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Generates session id
     * @param int $userid
     * @return string returns generated session id
     */
    public static function generateSessionId($userid) {
        $ipAddress = $_SERVER['REMOTE_ADDR'];
        if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
            $ipAddress = array_pop(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']));
        }

        $session_array = array(
            'userid' => $userid,
            'browser' => $_SERVER['HTTP_USER_AGENT'],
            'ip' => $ipAddress
        );

        //serializing data
        $session_id = serialize($session_array);

        //Encrypting data
        $session_id = UltraEncryption::encrypt($session_id);
        return $session_id;
    }

}

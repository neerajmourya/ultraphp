<?php

namespace ultraphp\core;
use app\models\User;
use app\models\UserLogin;
use app\models\FailedLoginAttempt;

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
    public static $user;
    public static $roles;

    /**
     * Authenticate and logs in user
     * @param string $username
     * @param string $password
     * @return boolean returns true if authenticated
     */
    public static function authenticate($username, $password) {
        $user = User::where('username',$username)
                ->where('password',$password)
                ->first();
        if(isset($user) && $user!=false){
            self::setAuth($user);
            UserLogin::login($user);
            return true;
        }else{
            //log failed login attempt
            FailedLoginAttempt::logAttempt($username);
            return false;
        }
    }
    
    /**
     * Sets the logged in user and roles
     * @param User|boolean $user
     */
    private static function setLoggedInUser($user = false){
        self::$user = $user;
        if($user){            
            self::$roles = $user->roles();
        }else{                        
            self::$roles = [\app\models\Role::getRole("Guest")];
        }
    }

    /**
     * Stores user authentication to session
     * @param int $userid
     */
    public static function setAuth($user) {
        if (isset($user) && $user!=false && isset($user->id) && !empty($user->id)) {
            $auth_token = self::generateToken($user);
            Session::put(Session::USER_ID, $user->id);
            Session::put(Session::AUTH_TOKEN, $auth_token);
//            $_SESSION['userinfo'] = $$userid;
        }
    }

    /**
     * Authenticate the current user session
     * @return boolean returns true if authenticated else false
     */
    public static function authenticateSession() {
        //starting secure session
        Session::start();
        
        if (Session::has(Session::AUTH_TOKEN)) {
            $auth_token = Session::get(Session::AUTH_TOKEN);
            $userid = Session::get(Session::USER_ID);                
            $user = User::get($userid);
            if (isset($user) && $user!=false) {
                $gen_session_id = self::generateToken($user);
                if ($auth_token === $gen_session_id) {
                    //Setting Authenticated User
                    self::setLoggedInUser($user);
                    return true;
                }
            }
        }
        
        //Setting Guest
        self::setLoggedInUser();
        return false;
    }

    /**
     * Generates session id
     * @param int $userid
     * @return string returns generated session id
     */
    public static function generateToken($user) {
        $ipAddress = $_SERVER['REMOTE_ADDR'];
        if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
            $ipAddress = array_pop(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']));
        }

        $session_array = array(
            'userid' => $user->id,
            'browser' => $_SERVER['HTTP_USER_AGENT'],
            'ip' => $ipAddress,
            'password' => $user->password
        );

        //serializing data
        $auth_token = serialize($session_array);

        //Encrypting data
        $auth_token = UltraEncryption::encrypt($auth_token);
        return $auth_token;
    }
    
    /**
     * Logsout the user
     */
    public static function logout(){
        UserLogin::logout(Session::get(Session::USER_ID));
        Session::destroy();
    }

}

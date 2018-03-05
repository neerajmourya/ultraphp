<?php

namespace ultraphp\core;

/**
 * CSRF Class
 * Manages CSRF Tokens
 * @author Neeraj Mourya <neeraj@egrapes.in>, <neerajmorya@gmail.com>
 * @copyright (c) 2018, Neeraj Mourya
 * @license https://opensource.org/licenses/MIT MIT
 * @since 2.0.0
 */
class CSRF {

    /**
     * initialise csrf tokens, removes old and expired tokens
     */
    public static function initialise() {
//        print_r(Session::get(Session::CSRF_TOKENS));
        if (Session::has(Session::CSRF_TOKENS)) {
            $tokens = Session::get(Session::CSRF_TOKENS);
            $tokens_limit = Config::get('app', 'CSRF_TOKEN_LIMITS');
            $diff = 0;
            if (sizeof($tokens) > $tokens_limit) {
                $diff = sizeof($tokens) - $tokens_limit;
            }

            $curTime = time();
            $validity = Config::get('app', 'CSRF_TOKEN_VALIDITY');
            $pastTime = $curTime - 3600;

            foreach ($tokens as $key => $value) {
                if ($diff > 0 || $value['time'] < $pastTime) {
                    $diff--;
                    unset($tokens[$key]);
                }
            }
            Session::put(Session::CSRF_TOKENS, $tokens);
        }
    }

    /**
     * Validates a csrf token
     * @param string $key
     * @return boolean returns true if validated else false
     */
    public static function validate($key) {
        if (Session::has(Session::CSRF_TOKENS)) {
            $tokens = Session::get(Session::CSRF_TOKENS);
            if (isset($tokens[$key]) && !empty($tokens[$key])) {

                $curTime = time();
                $validity = 3600;
                $pastTime = $curTime - 3600;

                $token_data = $tokens[$key];

                $ipAddress = $_SERVER['REMOTE_ADDR'];
                if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
                    $ipAddress = array_pop(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']));
                }
                if ($token_data['time'] > $pastTime && $token_data['ip'] === $ipAddress && $token_data['browser'] === $_SERVER['HTTP_USER_AGENT']) {
                    unset($tokens[$key]);
                    Session::put(Session::CSRF_TOKENS, $tokens);
                    return true;
                } else {
                    unset($tokens[$key]);
                    Session::put(Session::CSRF_TOKENS, $tokens);
                    return false;
                }
            }
        }
        return false;
    }

    /**
     * Generates a csrf token
     * @return string returns generated token
     */
    public static function token() {
        $ipAddress = $_SERVER['REMOTE_ADDR'];
        if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
            $ipAddress = array_pop(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']));
        }

        $token_array = array(
            'browser' => $_SERVER['HTTP_USER_AGENT'],
            'ip' => $ipAddress,
            'time' => time()
        );

        $token = Helper::randomString(10, true, true, false, true);

        $tokens = array();
        if (Session::has(Session::CSRF_TOKENS)) {
            $tokens = Session::get(Session::CSRF_TOKENS);
        }
        $tokens[$token] = $token_array;
        Session::put(Session::CSRF_TOKENS, $tokens);

        return $token;
    }

}

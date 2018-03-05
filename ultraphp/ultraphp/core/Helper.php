<?php

namespace ultraphp\core;
/**
 * Helper class
 * contains Helper functions
 * @author Neeraj Mourya <neeraj@egrapes.in>, <neerajmorya@gmail.com>
 * @copyright (c) 2018, Neeraj Mourya
 * @license https://opensource.org/licenses/MIT MIT
 * @since 2.0.0
 */
class Helper {

    /**
     * Returns absolute url to relative path
     * @param string $path
     * @return string returns absolute url
     */
    public static function url($path) {
        return Config::get('app', 'APP_URL') . $path;
    }

    /**
     * Redirect to given url with parameters
     * @param string $url
     * @param array $params
     */
    public static function redirect($url, $params = array()) {
        if (strpos($url, '/') === 0) {
            $url = self::url($url);
        }

        if (isset($params) && !empty($params)) {
            $url = $url . "?" . http_build_query($params);
        }

        header("Location: " . $url);
        die();
    }

    /**
     * Redirects back to previous url
     */
    public static function redirectBack() {
        header("Location: {$_SERVER['HTTP_REFERER']}");
        exit;
    }

    /**
     * Includes a view
     * @param string $view
     * @param array $variables
     */
    public static function view($view, $variables = array()) {
        //getting messageBoxes
        $messageBoxes = MessageBoxes::getBoxes();
        
        //Setting Request
        $request = Request::get();
        
        //extracting variables
        extract($variables);

        $file = str_replace(".", "/", $view);
        //initialising routes
        $dir = dirname(__FILE__, 3);
        include_once "$dir/app/views/$file.php";
    }

    
    /**
     * Outputs data as json
     * @param array $data
     */
    public static function json($data) {
        header("Content-Type: application/json; charset=UTF-8");
        echo json_encode($data);
        die;
    }
       

    /**
     * Generates a random string
     * @param int $length
     * @param boolean $small_ltr
     * @param boolean $cap_ltr
     * @param boolean $spec_ltr
     * @param boolean $numbers
     * @return string returns generated string
     */
    public static function randomString($length = 6, $small_ltr = false, $cap_ltr = false, $spec_ltr = false, $numbers = true) {
        $characters = '';
        if ($numbers) {
            $characters .= '0123456789';
        }
        if ($small_ltr) {
            $characters .= 'abcdefghijklmnopqrstuvwxyz';
        }
        if ($cap_ltr) {
            $characters .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }
        if ($spec_ltr) {
            $characters .= '~`!@#$%^&*()_+-[]{}|;:.,<>?';
        }

        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     * Validates a method whether a method is allowed to route
     * @param \ultraphp\core\Request $request
     * @param string $method
     * @return boolean returns true if validated else false
     */
    public static function validateMethod(Request $request, $method) {
        $inputMethod = $request->input('_method', false);
        if (isset($_POST) && !empty($_POST) && $inputMethod == strtolower($method)) {
            return true;
        }
        return false;
    }

    /**
     * Validates a csrf token
     * @param \ultraphp\core\Request $request
     * @return boolean returns true if validated else false;
     */
    public static function validateCSRF(Request $request) {
        $csrf_token = $request->input('_csrf', false);
        if ($csrf_token!=false) {            
            return CSRF::validate($csrf_token);
        }
        return false;
    }
    
    /**
     * Returns old request value against a key,
     * Returns default if key not found
     * @param string $key
     * @param type $default
     * @return type
     */
    public static function old($key, $default){
        $old = Request::get()->getOld();
        if(isset($old[$key])){
            return $old[$key];
        }
        return $default;
    }
}

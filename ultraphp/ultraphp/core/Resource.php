<?php

namespace ultraphp\core;
/**
 * Resource class
 * Manages resources
 * @author Neeraj Mourya <neeraj@egrapes.in>, <neerajmorya@gmail.com>
 * @copyright (c) 2018, Neeraj Mourya
 * @license https://opensource.org/licenses/MIT MIT
 * @since 2.0.0
 */
class Resource {   
    /**
     * Retrieve a value against key from a resource
     * @param string $resourceName Resource Name
     * @param string $key 
     * @param multiple $params can send multiple parameters...
     * @return type
     */
    public static function getValue($resourceName, $key, ...$params){
        $lang = Config::get("app", "APP_LANG");
        return self::getLocaleValue($lang, $resourceName, $key, ...$params);
    }

    /**
     * Retrieve a value against key from a locale resource
     * @param string $lang language key
     * @param string $resourceName Resource Name
     * @param string $key 
     * @param multiple $params can send multiple parameters...
     * @return type 
     */
    public static function getLocaleValue($lang, $resourceName, $key, ...$params) {
        $dir = dirname(__FILE__, 3);
        $file = "$dir/app/resources/$lang/$resourceName.php";        
        if (file_exists($file)) {
            $resources = include $file;
//            print_r($resources);
            if (isset($resources[$key]) && !empty($resources[$key])) {
                $value = $resources[$key];
                if(isset($params) && !empty($params)){
                    for($i=0;$i<sizeof($params);$i++){
                        $value = str_replace("{". $i . "}", $params[$i], $value);
                    }
                }
                return $value;
            } 
        }
        
        return false;
    }
    
    /**
     * Retrieves the resource array
     * @param string $resourceName
     * @return array
     */
    public static function getResource($resourceName){
        $lang = Config::get("app", "APP_LANG");
        return self::getLocaleResource($lang, $resourceName);
    }
    
    /**
     * Retrieves the locale resource array
     * @param string $lang
     * @param string $resouceName
     * @return array
     */
    public static function getLocaleResource($lang, $resouceName){
        $dir = dirname(__FILE__, 3);
        $file = "$dir/app/resources/$lang/$resouceName.php";        
        if (file_exists($file)) {
            return include $file;
        }
    }

}

?>
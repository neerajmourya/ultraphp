<?php

namespace ultraphp\core;

/**
 * Config Class
 * Manages Application configurations
 * @author Neeraj Mourya <neeraj@egrapes.in>, <neerajmorya@gmail.com>
 * @copyright (c) 2018, Neeraj Mourya
 * @license https://opensource.org/licenses/MIT MIT
 * @since 2.0.0
 */
class Config {

    /**
     * @var array stores configurations
     */
    private static $config = array();

    /**
     * Initialises configurations, 
     * reads configurations files and stores it into config array
     */
    public static function initialise() {
        $dir =  dirname(__FILE__, 3);
        $files = array_diff(scandir($dir.'/config'), array('.', '..'));
        foreach($files as $file){
            $filename = str_replace(".php", "", $file);
            self::$config[$filename] = include_once $dir.'/config/'.$file;
        }
    }

    /**
     * Retrieve a config value against given key and modname
     * @param string $modname
     * @param string $key
     * @return type
     */
    public static function get($modname, $key) {
        if (isset(self::$config) && isset(self::$config[$modname]) && isset(self::$config[$modname][$key])) {
            return self::$config[$modname][$key];
        }
    }

    /**
     * Set config value against a key and modname
     * @param string $modname
     * @param string $key
     * @param type $value
     */
    public static function set($modname, $key, $value) {
        if (isset(self::$config)) {
            self::$config[$modname][$key] = $value;
        }
    }

}
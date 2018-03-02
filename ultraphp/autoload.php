<?php
/**
 * Register autoload script
 */
spl_autoload_register(function($className) {
    $namespace = str_replace("\\", "/", __NAMESPACE__);
    $className = str_replace("\\", "/", $className);

    $dirs = [
        'core',
        'app'
    ];

    foreach ($dirs as $dir) {
        $class = dirname(__FILE__) . "/" . (empty($namespace) ? "" : $namespace . "/") . "{$className}.php";
        if (file_exists($class)) {
            include_once($class);
            break;
        }
    }
});

//Instantiates ultraphp
$ultraPHP = new ultraphp\core\UltraPHP();
?>

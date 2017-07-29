<?php

/**
 * UltrapPHP Class
 *
 * Handles the core and most common functionalities of the application
 *
 * @author  Neeraj Mourya <neeraj@egrapes.in>
 * @link    http://neeraj.egrapes.in/
 */
class UltraPHP {

    /**
     * @var object
     */
    private static $ultraPhp;

    /**
     * @var object
     */
    public $controller;

    /**
     * @var string
     */
    public $subdir;

    /**
     * @var array
     */
    public static $requestData;

    /**
     * Contains all the views to include in the template
     * @var array
     */
    public static $views;

    /**
     * contains all the scripts to include in the webpage
     * @var array
     */
    public static $scripts;

    /**
     * contains all the stylesheets to include in the webpage
     * @var array
     */
    public static $styles;

    /**
     * Constructor
     * 
     * @var string
     */
    private function __construct() {
        $this->subdir = "";
    }

    /**
     * Setup and initialise static variables
     * Returns self instance if exists else returns new instance
     * 
     * @return UltraPhp
     */
    public static function get() {
        UltraPHP::$views = array();
        UltraPHP::$scripts = array();
        UltraPHP::$styles = array();

        if (!isset(UltraPHP::$ultraPhp) || !is_object(UltraPHP::$ultraPhp)) {
            UltraPHP::$ultraPhp = new UltraPHP();
        }
        return UltraPHP::$ultraPhp;
    }

    /**
     * Calculates which controller and method to execute as per the routes configurations and default configurations.
     * Instantiates the specific Controller class and executes it's specific method.
     * 
     * @param string $uri
     * @param array $routes
     * @return void
     */
    public function executeController($uri, $routes) {
        $uriBlocks = explode('/', $uri);
        UltraPHP::$requestData = $uriBlocks;

        if (isset($routes[$uri])) {
            if (isset($routes[$uri][2]) && !empty($routes[$uri][2]) && is_int($routes[$uri][2])) {
                $routes[$uri][2] = $this->getMethodNameFromSlug($uri, $routes[$uri][2]);
            }
            $this->instController($routes[$uri]);
            return;
        } else {
            $uriBlocksLength = sizeof($uriBlocks);
            while (sizeof($uriBlocks) > 0) {
                for ($i = $uriBlocksLength - 1; $i >= 0; $i--) {
                    $chkBlocks = $uriBlocks;
                    $chkBlocks[$i] = '*';
                    $chkUri = '';
                    foreach ($chkBlocks as $block) {
                        $chkUri .= $block . '/';
                    }
                    $chkUri = rtrim($chkUri, "/");
//                    echo $chkUri . '<br>';
                    if (isset($routes[$chkUri])) {
                        if (isset($routes[$chkUri][0]) && !empty($routes[$chkUri][0]) && substr($routes[$chkUri][0], -1) === '/') {
                            $this->subdir = $routes[$chkUri][0];
                            $uriBlocks = array();
                            break;
                        }
                        if (isset($routes[$chkUri][2]) && !empty($routes[$chkUri][2]) && is_int($routes[$chkUri][2])) {
                            $routes[$chkUri][2] = $this->getMethodNameFromSlug($chkUri, $routes[$chkUri][2]);
                        }
                        $this->instController($routes[$chkUri]);
                        return;
                    }
                }
                unset($uriBlocks[sizeof($uriBlocks) - 1]);
            }
        }

        
        if (DEFAULT_ROUTES) {
            if (!empty($this->subdir)) {
                $uri = str_replace($this->subdir, "", $uri);
            }
            $uriBlocks = explode('/', $uri);
            $filename = $uriBlocks[0];
            $classname = $this->getClassNameFromRequestSlug($uriBlocks[0]);
            $methodName = (isset($uriBlocks[1]) && !empty($uriBlocks[1])) ? $uriBlocks[1] : '';
            $args = array($filename, $classname, $methodName);
            $this->instController($args);
            return;
        }
    }

    /**
     * Instantiates the controller and executes its method as per the arguments passed.
     * 
     * @param array $args
     * @return void
     */
    public function instController($args) {
        require APP_DIR . 'controllers/' . $this->subdir . $args[0] . '.php';
        $this->controller = new $args[1];

        if (isset($args[2]) && !empty($args[2])) {
            if (method_exists($this->controller, $args[2]) && is_callable(array($this->controller, $args[2]))) {
                $this->controller->{$args[2]}();
                return;
            }
        }

        $this->controller->{'execute'}();
    }

    /**
     * Get the Class Name from Request uri slug
     * 
     * @param string $slug
     * @return string
     */
    public function getClassNameFromRequestSlug($slug) {
        $nameBlocks = explode('-', $slug);
        $className = '';
        foreach ($nameBlocks as $block) {
            $className .= ucwords($block);
        }
        return $className;
    }

    
    /**
     * Get the Method Name from Request uri slug
     * 
     * @param string $uri Request Uri
     * @param integer $position Position of the method name in request uri
     * @return string
     */
    public function getMethodNameFromSlug($uri, $position) {
        $uriBlocks = explode('/', $uri);
        return $uriBlocks[$position - 1];
    }

    /**
     * Redirect to url
     * 
     * @param string $url 
     */
    public static function redirect($url) {
        header("Location: " . $url);
        die();
    }

    /**
     * Sets the template to use.
     * 
     * @param type $template name or sub path of the template.
     * @param array $data Data array to pass to template
     */
    public static function useTemplate($template, $data = array()) {
        require_once APP_DIR . 'views/templates/' . $template . '.php';
    }

    /**
     * includes the file from includes folder
     * 
     * @param string $file Filename without .php in the end
     */
    public static function includeFile($file) {
        require_once PLUGINS_DIR . $file . '.php';
    }

    /**
     * includes the view file from views folder
     * 
     * @param string $view Filename without .php in the end
     */
    public static function includeView($view) {
        require APP_DIR . 'views/' . $view . '.php';
    }

    /**
     * includes the file from models folder
     * 
     * @param string $model Model Filename without .php in the end
     */
    public static function includeModel($model) {
        require_once APP_DIR . 'models/' . $model . '.php';
    }

    /**
     * Adds the view path to the views array to be added in the template
     * 
     * @param string $view
     */
    public static function addView($view) {
        $view = APP_DIR . 'views/' . $view . '.php';
        array_push(UltraPHP::$views, $view);
    }

    /**
     * Adds the script path to the scripts array to be added in the template
     * 
     * @param string $script
     */
    public static function addScript($script) {
        array_push(UltraPHP::$scripts, ASSETS_ADMIN_URL . $script . '.js');
    }

    /**
     * Adds the style path to the styles array to be added in the template
     * 
     * @param string $style
     */
    public static function addStyle($style) {
        array_push(UltraPHP::$styles, ASSETS_ADMIN_URL . $style . '.css');
    }

}

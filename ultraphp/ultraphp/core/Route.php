<?php

namespace ultraphp\core;

/**
 * Route class
 * Manages routing of the application
 * @author Neeraj Mourya <neeraj@egrapes.in>, <neerajmorya@gmail.com>
 * @since 2.0.0
 */
class Route {

    /**
     *
     * @var array stores application routes 
     */
    private static $routes = array();

    /**
     * Adds a route with arguments
     * @param string $route
     * @param array $args
     * @param string $method Route method
     */
    private static function add($route, $args, $method = 'get') {
        //setting method
        $args['method'] = $method;
        //setting default csrf option to true
        if (!isset($args['csrf'])) {
            $args['csrf'] = true;
        }
        self::$routes[$route] = $args;
    }

    /**
     * Adds route with arguments for get method
     * @param string $route
     * @param array $args
     */
    public static function get($route, $args) {
        self::add($route, $args, 'get');
    }

    /**
     * Adds route with arguments for post method
     * @param string $route
     * @param array $args
     */
    public static function post($route, $args) {
        self::add($route, $args, 'post');
    }

    /**
     * Adds route with arguments for put method
     * @param string $route
     * @param array $args
     */
    public static function put($route, $args) {
        self::add($route, $args, 'put');
    }

    /**
     * Adds route with arguments for delete method
     * @param string $route
     * @param array $args
     */
    public static function delete($route, $args) {
        self::add($route, $args, 'delete');
    }

    /**
     * Adds a resource to route
     * @param string $route
     * @param string $controller
     */
    public static function resource($route, $controller) {
        $routeName = strtolower(implode(".", explode("\\", $controller)));
        self::get($route . "/{id}/show", ['as' => $routeName . ".show", 'uses' => $controller . "@show"]);
        self::get($route, ['as' => $routeName . ".index", 'uses' => $controller . "@index"]);
        self::get($route . "/create", ['as' => $routeName . ".create", 'uses' => $controller . "@create"]);
        self::get($route . "/{id}/edit", ['as' => $routeName . ".edit", 'uses' => $controller . "@edit"]);
        self::post($route . "/store", ['as' => $routeName . ".store", 'uses' => $controller . "@store"]);
        self::put($route . "/{id}/udpate", ['as' => $routeName . ".update", 'uses' => $controller . "@udpate"]);
        self::delete($route . "/{id}/destroy", ['as' => $routeName . ".destroy", 'uses' => $controller . "@destroy"]);
    }

    /**
     * Executes the routing on request
     */
    public static function execute() {
        $base_url = Config::get('app', 'APP_URL');
        $actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $request_uri = str_replace($base_url, "", $actual_link);

        //trim request uri
        $request_uri = explode("?", $request_uri)[0];
        $request_uri = trim($request_uri, "/");

        $route = array();
        $data = array();

        if (isset(self::$routes[$request_uri])) {
            $route = self::$routes[$request_uri];
//            self::executeMethod($route);
        } else {
            $uriFacts = explode("/", $request_uri);

            foreach (self::$routes as $key => $value) {
                $routeFacts = explode("/", $key);
                $found = true;
                for ($i = 0; $i < sizeof($uriFacts); $i++) {
                    if (isset($routeFacts[$i]) && ($routeFacts[$i] == $uriFacts[$i] || strpos($routeFacts[$i], '{') === 0)) {
                        continue;
                    } else {
                        $found = false;
                        break;
                    }
                }

                if ($found) {
                    for ($i = 0; $i < sizeof($routeFacts); $i++) {
                        if (strpos($routeFacts[$i], '{') === 0) {
                            $variable_key = trim($routeFacts[$i], "{}");
                            $data[$variable_key] = $uriFacts[$i];
                        }
                    }
                    $route = $value;
//                    self::executeMethod($value, $data);
                }
            }
        }

        if (sizeof($route) > 0) {
            self::executeMethod($route, $data);
        } else {
            trigger_error("Route Not Found");
        }

        die;
    }

    /**
     * Executes the method of the eligible controller
     * @param array $route
     * @param array $data
     */
    public static function executeMethod($route, $data = array()) {
        $request = Request::get();
        $params = ['request' => $request];
        $method = $route['method'];

        $method = strtolower($method);
        //Validating request
        if ($method == 'get') {
            
        } elseif (Helper::validateMethod($request, $method)) {
            //validating CSRF Token
            if ($route['csrf']) {
                if (!Helper::validateCSRF($request)) {
                    trigger_error("Invalid CSRF Token");
                    die;
                } else {
                    if (Session::has('old')) {
                        $request->setOld(Session::get('old'));
                    }
                }
            }
        } else {
            trigger_error("Method not allowed");
            die;
        }

        $params = array_merge($params, $data);

        $uses = explode("@", $route['uses']);
        $methodName = $uses[1];
        $controllerClass = 'app\controllers\\' . $uses[0];

        $controller = new $controllerClass;

        if (isset($methodName) && !empty($methodName)) {
            if (method_exists($controller, $methodName) && is_callable(array($controller, $methodName))) {
                call_user_func_array(array($controller, $methodName), $params);
            }
        }
    }

}

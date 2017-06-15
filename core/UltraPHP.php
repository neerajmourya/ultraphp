<?php

class UltraPHP {

    private static $ultraPhp;
    public $controller;
    public $subdir;
    
    private function __construct() {
        $this->subdir = "";
    }

    public static function get() {
        if (!isset($ultraPhp) || !is_object($ultraPhp)) {
            $ultraPhp = new UltraPHP();
        }
        return $ultraPhp;
    }

    public function executeController($uri, $routes) {
        $uriBlocks = explode('/', $uri);        
        
        echo $uri . '<br>';
        
        if(isset($routes[$uri])){           
            if(isset($routes[$uri][2]) && !empty($routes[$uri][2]) && is_int($routes[$uri][2])){
                $routes[$uri][2] = $this->getMethodNameFromSlug($uri, $routes[$uri][2]);
            }
            $this->instController($routes[$uri]);
            return;
        }else{            
            $uriBlocksLength = sizeof($uriBlocks);
            while(sizeof($uriBlocks)>0){
                for ($i = $uriBlocksLength - 1; $i >= 0; $i--) {
                    $chkBlocks = $uriBlocks;
                    $chkBlocks[$i] = '*';
                    $chkUri = '';
                    foreach($chkBlocks as $block){
                        $chkUri .= $block . '/';
                    }
                    $chkUri = rtrim($chkUri, "/");
//                    echo $chkUri . '<br>';
                    if(isset($routes[$chkUri])){    
                        if(isset($routes[$chkUri][0]) && !empty($routes[$chkUri][0]) && substr($routes[$chkUri][0], -1)==='/'){
                            $this->subdir = $routes[$chkUri][0];
                            $uriBlocks = array();
                            break;
                        }
                        if(isset($routes[$chkUri][2]) && !empty($routes[$chkUri][2]) && is_int($routes[$chkUri][2])){
                            $routes[$chkUri][2] = $this->getMethodNameFromSlug($chkUri, $routes[$chkUri][2]);
                        }
                        $this->instController($routes[$chkUri]);
                        return;
                    }
                }
                unset($uriBlocks[sizeof($uriBlocks)-1]);
            }
            
        }
        
        if(DEFAULT_ROUTES){
            if(!empty($this->subdir)){
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
    
    public function instController($args){        
        require APP_DIR.'controllers/'.$this->subdir.$args[0].'.php';
        $this->controller = new $args[1];        
        
        if(isset($args[2]) && !empty($args[2])){            
            $this->controller->{$args[2]}();
        }else{
            $this->controller->{'execute'}();
        }
    }
    
    public function getClassNameFromRequestSlug($slug){        
        $nameBlocks = explode('-',$slug);
        $className = '';
        foreach ($nameBlocks as $block){
            $className .= ucwords($block);
        }
        return $className;
    }
    
    public function getMethodNameFromSlug($uri,$position){
        $uriBlocks = explode('/', $uri);
        return $uriBlocks[$position-1];
    }
    
    
    public static function model($model){
        require_once APP_DIR.'models/'. $model . '.php';        
    }
    public static function view($view){
        require APP_DIR.'views/'. $view . '.php';        
    }

}

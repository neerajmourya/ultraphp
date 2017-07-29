<?php
class UltraController{
    var $errors;
    public function __construct() {
        $this->errors = array();
    }
    
    public function execute(){
        
    }
    
    public function handleRequest(){
        if($_SERVER['REQUEST_METHOD']==='POST'){
            $this->submit(); 
            return true;
        }   
        return false;
    }
    
    public function submit(){
        
    }
    
    
}

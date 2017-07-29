<?php

class Home extends UltraController{
    public function execute() {
        parent::execute();
        $data['title'] = "UltraPHP";
        $data['page-title'] = "UltraPHP";
        
        //Adding View
        UltraPHP::addView('home');
        //User Template
        UltraPHP::useTemplate('frontend/default', $data);    
    }
}
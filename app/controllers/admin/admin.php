<?php

class Admin extends UltraController{
    public function execute() {
        parent::execute();
        $data['title'] = "UltraPHP";
        $data['page-title'] = "UltraPHP";
        
        //Adding View
        UltraPHP::addView('admin/admin');
        //User Template
        UltraPHP::useTemplate('admin/default', $data);    
    }
}
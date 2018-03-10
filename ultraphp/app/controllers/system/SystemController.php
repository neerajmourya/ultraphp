<?php
namespace app\controllers\system;

use ultraphp\core\DBManager as DB;
use ultraphp\core\Request;
use ultraphp\core\Controller;
use ultraphp\core\Helper;

class SystemController{
    public function install(Request $request){
        //run install script
        echo 'Installing<br>';
        $dir =  dirname(__FILE__, 3);
        $file = "$dir/resources/ultraphp.sql";
        DB::get_query()->import($file);
        echo "installation done";
    }
    
    public function uninstall(Request $request){
        //run uninstall script
    }
}
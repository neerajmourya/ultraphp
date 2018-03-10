<?php
namespace app\models;
use ultraphp\core\Model;
use ultraphp\core\DBManager as DB;

class Role extends Model {
    const TABLE_NAME = "roles";
    
    public static function getRole($roleName){
        return DB::get_query(Role::class)
                ->where('role',$roleName)
                ->first();
    }
}
<?php
namespace app\models;
use ultraphp\core\Model;

class User extends Model{
    const TABLE_NAME = "users";
    
    public function roles(){
        return \ultraphp\core\DBManager::get_query(Role::class)
                ->columns(Role::TABLE_NAME.".*")
                ->from(Role::TABLE_NAME, UserRole::TABLE_NAME)
                ->where(Role::TABLE_NAME.".id",'=' ,UserRole::TABLE_NAME.".role_id",'AND',true)
                ->where(UserRole::TABLE_NAME.".user_id",  $this->id)
                ->get();   
    }
}
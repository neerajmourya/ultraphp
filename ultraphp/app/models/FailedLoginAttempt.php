<?php
namespace app\models;
use ultraphp\core\Model;
use ultraphp\core\Session;
use DateTime;

class FailedLoginAttempt extends Model {
    const TABLE_NAME = "failed_login_attempts";
    
    public static function logAttempt($username){
        if(isset($username) && !empty($username)){
            $user = User::where("username",$username)
                    ->first();
            if($user){
                self::insert([
                    "user_id" => $user->id,
                    "attempted_on" => date('Y-m-d H:i:s',(new DateTime())->getTimestamp()),
                    "ip" => Session::get(Session::USER_IP),
                    "user_agent" => Session::get(Session::USER_AGENT)                    
                ]);
            }
        }
    }
}
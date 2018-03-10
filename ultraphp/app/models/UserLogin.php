<?php
namespace app\models;
use ultraphp\core\Model;
use ultraphp\core\Session;
use DateTime;

class UserLogin extends Model{
    const TABLE_NAME = "user_logins";
    
    public static function login($user){
        self::insert([
            "user_id" => $user->id,
            "login_at" => date('Y-m-d H:i:s',(new DateTime())->getTimestamp()),
            "ip" => Session::get("USER_IP"),
            "user_agent" => Session::get("USER_AGENT"),
        ]);
    }
    
    public static function logout($userid){
        $userLogin = self::where("user_id",$userid)
                ->where('ip',  Session::get(Session::USER_IP))
                ->where('user_agent', Session::get(Session::USER_AGENT))
                ->orderBy("id", "DESC")
                ->first();
        if($userLogin){
            $userLogin->logout_at = date('Y-m-d H:i:s',(new DateTime())->getTimestamp());
            $userLogin->save();
        }
    }
}

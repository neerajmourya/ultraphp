<?php
class SessionManager{
    public static function setAuth($userid){
        if(isset($userid) && !empty($userid)){
                        
            $session_id = SessionManager::getEncryptedId($userid);            
            $_SESSION['user_id'] = $userid;
            $_SESSION['session_id'] = $session_id;
            
//            $_SESSION['userinfo'] = $$userid;
            return true;
        }        
        return false;
    }    
    
    public static function authenticateSession(){
        if(isset($_SESSION['session_id']) && !empty($_SESSION['session_id'])){
            $session_id = $_SESSION['session_id'];
            $userid = $_SESSION['user_id'];
            if(isset($userid) && !empty($userid)){
                $gen_session_id = SessionManager::getEncryptedId($userid);
                if($session_id === $gen_session_id){
                    return true;
                }
            }
        }
        return false;        
    }
    
    
    public static function getEncryptedId($userid){
        if(isset($userid) && !empty($userid)){
            $ipAddress = $_SERVER['REMOTE_ADDR'];
            if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
                $ipAddress = array_pop(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']));
            }
            
            $session_array = array(
                'userid' => $userid,
                'browser' => $_SERVER['HTTP_USER_AGENT'],
                'ip' => $ipAddress
            );
            
            //serializing data
            $session_id = serialize($session_array);
            
            //Encrypting data
            $session_id = UltraEncryption::encrypt($session_id);
            return $session_id;
        }        
        return false;
    }
    
    public static function invalidate(){
        session_destroy();
    }
}

<?php
namespace app\controllers\user;
use ultraphp\core\Controller;
use ultraphp\core\Request;
use ultraphp\core\Helper;
use ultraphp\core\Session;
use ultraphp\core\Auth;
use app\models\User;

class AuthController extends Controller{
        
    public function displayLogin(Request $request){
        Helper::view('auth.login');
    }
    
    public function authenticate(Request $request){
        $this->validate($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);
        
        
        $username = $request->input('username');
        $password = $request->input('password');
        
        if(Auth::authenticate($username, $password)){
            Helper::redirect("/");
        }else{
            Helper::redirect("/login");
        }
        
    }
    
    public function logout(Request $request){
        Auth::logout();
        Helper::redirect("/");
    }
}

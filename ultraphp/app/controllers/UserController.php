<?php
namespace app\controllers;

use ultraphp\core\Request;
use ultraphp\core\Controller;
use ultraphp\core\Helper;

class UserController extends Controller{
    public function create(Request $request){
        Helper::view("users.create");
    }
    
    public function store(Request $request){
        $this->validate($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|int',
            'amount' => 'required|float'
        ]);
    }
    
    public function edit(Request $request, $id){
        echo "from Edit $id";
        die;
    }

}

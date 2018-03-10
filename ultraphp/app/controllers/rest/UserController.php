<?php

namespace app\controllers\rest;

use ultraphp\core\Request;
use ultraphp\core\Controller;
use ultraphp\core\Helper;

use app\models\User;

class UserController extends Controller{
    public function index(Request $request){
        $users = User::all();
        $output = [
            "status" => \ultraphp\core\HttpStatus::BAD_REQUEST,
            "status_message" => "BAD REQUEST",
            "data" => $users
        ];
        Helper::json(\ultraphp\core\HttpStatus::BAD_GATEWAY, $output);
    }

}

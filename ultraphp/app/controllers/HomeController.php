<?php

namespace app\controllers;

use ultraphp\core\Request;
use ultraphp\core\Controller;
use ultraphp\core\Helper;

class HomeController extends Controller {

    public function index() {        
        Helper::view("home");        
    }

}

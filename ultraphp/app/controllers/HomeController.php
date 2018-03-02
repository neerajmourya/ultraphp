<?php

namespace app\controllers;

use ultraphp\core\Request;
use ultraphp\core\Controller;
use ultraphp\core\Helper;

/**
 * HomeController class
 * Controls the home page requests
 */
class HomeController extends Controller {

    /**
     * index for the home
     */
    public function index() {        
        Helper::view("home");        
    }

}

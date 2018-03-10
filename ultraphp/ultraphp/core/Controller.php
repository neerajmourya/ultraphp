<?php

namespace ultraphp\core;

/**
 * Controller class
 * Core controller class provides core functionality,
 * Needs to extended by controllers.
 * 
 * @author Neeraj Mourya <neeraj@egrapes.in>, <neerajmorya@gmail.com>
 * @copyright (c) 2018, Neeraj Mourya
 * @license https://opensource.org/licenses/MIT MIT
 * @since 2.0.0
 */
class Controller {

    /**
     * Constructs the Controller
     * @author Neeraj Mourya <neeraj@egrapes.in>, <neerajmorya@gmail.com>
     * @since 2.0.0
     */
    function __construct() {
        
    }

    /**
     * Validates the data redirect back with errors if validation fails.
     * 
     * @param array $data Data array to be validated in key and value pair
     * @param array $validtionsArr Validations array key and value pair
     * @param array $attributes Attributes array key and value pair
     */
    public function validate($data = array(), $validtionsArr = array(), $attributes = array()) {
        $messageBox = Validator::validate($data, $validtionsArr, $attributes);
        if (!$messageBox->isEmpty()) {
            MessageBoxes::putBox('register-errors', $messageBox);
            Session::put(Session::MESSAGE_BOXES, MessageBoxes::getBoxes());
            Request::get()->toSession();
            Helper::redirectBack();
        }
    }

}

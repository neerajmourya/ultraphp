<?php

namespace ultraphp\core;
/**
 * UltraPHP Class
 * Manages and initialises application
 * Front or Base Controller
 * @author Neeraj Mourya <neeraj@egrapes.in>, <neerajmorya@gmail.com>
 * @copyright (c) 2018, Neeraj Mourya
 * @license https://opensource.org/licenses/MIT MIT
 * @since 2.0.0
 */
class UltraPHP {

    /**
     * Constructs the ultraphp object
     */
    public function __construct() {       
        //Initialising configurations;
        Config::initialise();

        // Turn off error reporting
        error_reporting(0);

        //Setting Error Handler
        set_error_handler(array(Error::class, "errorHandler"));
        register_shutdown_function(array(Error::class, "shutdownHandler"));

        //Initialsing Encryption
        UltraEncryption::initialiseKeys(Config::get('app', 'ENC_KEY'), Config::get('app', 'ENC_IV'));

        //Setting timezone
        date_default_timezone_set(Config::get('app', 'TIMEZONE'));
        
        //Initialising CSRF
        CSRF::initialise();
        
        //Setting Session MessageBoxes        
        if(Session::has(Session::MESSAGE_BOXES)){
            $messageBoxes = Session::pull(Session::MESSAGE_BOXES);
            MessageBoxes::setBoxes($messageBoxes);
        }

        //initialising routes
        $dir = dirname(__FILE__, 3);
        include_once $dir . '/routes/routes.php';

        //Executing Controller
        Route::execute();
    }

}

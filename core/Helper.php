<?php

class Helper{
    public static function redirect($location){
        header("Location: ".$location);
        die();
    }
}

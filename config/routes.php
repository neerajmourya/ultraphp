<?php

$request_uri = $_SERVER['REQUEST_URI'];
if (defined('SUB_FOLDER')) {
    if (0 == strpos($request_uri, SUB_FOLDER)) {
        $request_uri = str_replace(SUB_FOLDER, '', $request_uri);
        $request_uri = rtrim($request_uri,"/");
    }
}

$routes = array();
$routes[''] = array('test','Test');
//$routes['admin/*'] = array('admin/');
//$routes['user/login/add'] = array('user/LoginController','LoginController');
//$routes['user/add/*'] = array('user/login');
//$routes['user/register'] = array('user','LoginRegister');

UltraPHP::get()->executeController($request_uri, $routes);

<?php
use ultraphp\core\Route;

//System routes
Route::get('system/install',['as' => 'install', 'uses' => 'system\SystemController@install']);
Route::get('system/uninstall',['as' => 'uninstall', 'uses' => 'system\SystemController@uninstall']);

Route::get('/', ['as' => 'home', 'uses' => 'HomeController@index']);

Route::get('login',['as' => 'login', 'uses' => 'user\AuthController@displayLogin']);
Route::get('logout',['as' => 'logout', 'uses' => 'user\AuthController@logout']);
Route::post('authenticate',['as' => 'authenticate', 'uses' => 'user\AuthController@authenticate']);


Route::get('rest/users/list',['as' => 'rest.users.list', 'uses' => 'rest\UserController@index']);

//Route::get('user/create', ['as' => 'user.create', 'uses' => 'UserController@create']);
//Route::post('user/store', ['as' => 'user.store', 'uses' => 'UserController@store']);
//Route::resource('user', 'UserController');
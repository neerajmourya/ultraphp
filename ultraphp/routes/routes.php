<?php

use ultraphp\core\Route;

Route::get('/', ['as' => 'home', 'uses' => 'HomeController@index']);

//Route::get('user/create', ['as' => 'user.create', 'uses' => 'UserController@create']);
//Route::post('user/store', ['as' => 'user.store', 'uses' => 'UserController@store']);
//Route::resource('user', 'UserController');
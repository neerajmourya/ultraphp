<?php

use ultraphp\core\Route;

Route::get('home', ['as' => 'home', 'uses' => 'HomeController@index']);
Route::get('home/{id}/edit', ['as' => 'home.edit', 'uses' => 'HomeController@edit']);

Route::get('user/create', ['as' => 'user.create', 'uses' => 'UserController@create']);
Route::post('user/store', ['as' => 'user.store', 'uses' => 'UserController@store']);
//Route::resource('user', 'UserController');
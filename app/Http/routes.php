<?php

/**
* The Home Page
**/
Route::get('/', 'PagesController@home');

Route::get('home', 'HomeController@index');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

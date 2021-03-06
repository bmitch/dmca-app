<?php

/**
* The Home Page
**/
Route::get('/', 'PagesController@home');

/**
* Notices
**/
Route:get('notices/create/conform', 'NoticesController@confirm');
Route::resource('notices', 'NoticesController');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

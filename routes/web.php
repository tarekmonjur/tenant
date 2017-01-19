<?php


Auth::routes();

Route::get('/config', 'Setup\ConfigController@index');
Route::post('/config', 'Setup\ConfigController@config');

Route::get('/home', 'HomeController@index');

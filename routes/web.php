<?php


Route::get('/', 'Setup\ConfigController@index');
Route::post('/config', 'Setup\ConfigController@config');

Auth::routes();

Route::get('/home', 'HomeController@index');

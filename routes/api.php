<?php 

// Users API
Route::post('/register', 'AuthController@register');
Route::post('/login', 'AuthController@login');
Route::post('/logout', 'AuthController@logout');

// Queries API
Route::post('/query', 'QueryController@index');
Route::post('/raw', 'QueryController@raw');
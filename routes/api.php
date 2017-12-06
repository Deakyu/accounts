<?php 

// Users API
Route::post('/register', 'AuthController@register');
Route::post('/login', 'AuthController@login');
Route::post('/logout', 'AuthController@logout');
Route::post('/change', 'AuthController@changePassword');

// Queries API
Route::post('/query', 'QueryController@index');
Route::post('/raw', 'QueryController@raw');
Route::post('/accounts', 'QueryController@accounts');
Route::post('/transactions', 'QueryController@transactions');
Route::post('/accounts-by-name', 'QueryController@accountsByFirstName');
Route::post('/make-transaction', 'QueryController@makeTransaction');
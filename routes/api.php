<?php 

// Users API
Route::post('/register', 'AuthController@register');
Route::post('/login', 'AuthController@login');
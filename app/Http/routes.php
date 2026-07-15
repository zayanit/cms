<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Home page for the website.
Route::get('/', 'PublicWebController@home');
Route::get('/home', 'UserWebController@home');

Route::get('/client', 'ClientWebController@home');

Route::get('client/profile', 'ClientWebController@getProfile');
Route::post('client/profile', 'ClientWebController@postProfile');
Route::get('client/password', 'ClientWebController@getPassword');
Route::post('client/password', 'ClientWebController@postPassword');

Auth::routes();

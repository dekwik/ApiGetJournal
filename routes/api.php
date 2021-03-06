<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//user
Route::post('login', 'Api\AuthController@login');
Route::post('register', 'Api\AuthController@register');
Route::get('logout', 'Api\AuthController@logout');
Route::post('save_user_info', 'Api\AuthController@saveUserInfo');

Route::post('userRead','Api\UserController@read');
Route::post('userEdit','Api\UserController@edit');
Route::post('userEditPass','Api\UserController@editPass');

//jurnal
Route::post('posts/create','Api\PostsController@create');
Route::post('posts/update','Api\PostsController@update');
Route::post('posts/delete','Api\PostsController@delete');
Route::post('posts','Api\PostsController@post');
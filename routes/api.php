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

Route::post('login', 'AccessTokenController@issueToken');

//Route::post('oauth/token', 'AccessTokenController@issueToken');

Route::group(['middleware' => 'auth:api'], function() {
       Route::get('logout', 'UserController@logout');
       Route::get('user', 'UserController@user');
       Route::post('refresh', '\App\Http\Controllers\AccessTokenController@issueToken');
       // Route::get('company_dashboard', 'UserController@user')->middleware('checkRole:1');
       // Route::get('bank_dashboard', 'UserController@user')->middleware('checkRole:2');
});

Route::get('search', 'StaffController@search');



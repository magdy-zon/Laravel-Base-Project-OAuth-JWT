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

Route::post('login', 'AuthController@login');
Route::get('login',['as'   => 'login',
                    'uses' => 'AuthController@no_login']);

Route::post('signup', 'RegisterController@signup');

Route::group([ 'middleware' => 'auth:api' ], function() {

    Route::post('logout', 'AuthController@logout');
    Route::resource('user', 'UserController');

});

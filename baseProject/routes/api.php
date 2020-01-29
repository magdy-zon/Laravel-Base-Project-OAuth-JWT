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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

//
// Route::get('login', function() {
//     echo 'login';
// });
//
// Route::middleware(['auth:api'])->group(function () {
//     Route::get('show_users', 'UsersController@login');
// });



Route::group([ 'prefix' => 'auth' ], function () {
    Route::post('login', 'AuthController@login');
    Route::post('signup', 'RegisterController@signup');

    Route::group([ 'middleware' => 'auth:api' ], function() {
        Route::post('logout', 'AuthController@logout');

        Route::get('user', 'AuthController@user');
    });
});

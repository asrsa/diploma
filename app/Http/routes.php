<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('account/activate/{activationCode}', 'Auth\AuthController@activate');

Route::auth();

Route::group(['middlewareGroups' => ['web']], function() {

    Route::get('/', function () {
        return view('welcome');
    });

    Route::get('/home', 'HomeController@index');

    //user account routes
    Route::get('/account', 'AccountController@index')->name('account');
    Route::post('/avatar/change', 'AccountController@avatarChange');
    Route::get('/account/password', 'AccountController@showReset')->name('resetPassword');
    Route::post('/account/password/reset', 'AccountController@resetPassword');
});
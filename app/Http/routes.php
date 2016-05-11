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

//user activate
Route::get('account/activate/{activationCode}', 'Auth\AuthController@activate');

//author activate
Route::get('/author/activate/{activationCode}', 'Auth\AuthController@activateAuthor');
Route::post('/author/activate/{activationCode}', 'Auth\Authcontroller@postActivateAuthor');

Route::auth();

Route::group(['middlewareGroups' => ['web']], function() {

    Route::get('/', 'NewsController@index')->name('index');
    Route::get('/home', 'HomeController@index')->name('home');


    //news
    Route::get('/news/{id}', 'NewsController@showNews')->name('individualNews');


    //user account routes
    Route::get('/account', 'AccountController@index')->name('account');
    Route::post('/avatar/change', 'AccountController@avatarChange');
    Route::get('/account/password', 'AccountController@showReset')->name('resetPassword');
    Route::post('/account/password/reset', 'AccountController@resetPassword');

    //admin account routes
    Route::get('/admin', 'AdminController@index')->name('adminIndex');
    Route::get('/admin/author/add', 'AdminController@addAuthor')->name('addAuthor');
    Route::post('/admin/author/add', 'AdminController@registerAuthor')->name('registerAuthor');

    //author account routes
    Route::get('/news/create', 'AuthorController@createNewsGet')->name('createNews');
    Route::post('/news/create', 'AuthorController@createNewsPost');
    Route::post('/image/upload', 'AuthorController@uploadImage');
});

//AJAX routes
Route::get('/ajax/subcategory', 'AuthorController@getSubcategories');
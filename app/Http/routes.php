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
    Route::get('/news/view/{id}', 'NewsController@showNews')->name('individualNews');
    Route::get('/comment/delete', 'NewsController@deleteComment')->name('deleteComment')->middleware('auth');
    Route::get('/comment/like', 'NewsController@likeComment')->name('likeComment')->middleware('auth');
    Route::get('news/search', 'NewsController@searchNews')->name('searchNews');

    //categories
    Route::get('/cat/{catName}', 'NewsController@showCategory');

    //subcategories
    Route::get('/subcat/{subcatName}', 'NewsController@showSubcategory')->name('subcategory');


    //all users routes
    Route::get('/password/change', 'UserController@changePasswordGet')->name('changePasswordGet');
    Route::post('/password/change', 'UserController@changePasswordPost')->name('changePasswordPost');

    //user account routes
    //Route::get('/account', 'AccountController@index')->name('account');
    Route::get('/avatar/change', 'AccountController@avatarChangeGet')->name('changeAvatar');
    Route::post('/avatar/change', 'AccountController@avatarChange');
//    Route::get('/account/password', 'AccountController@showReset')->name('resetPassword');
//    Route::post('/account/password/reset', 'AccountController@resetPassword');


    //admin account routes
    Route::get('/admin', 'AdminController@index')->name('adminIndex');
    Route::get('/admin/author/add', 'AdminController@addAuthor')->name('addAuthor');
    Route::post('/admin/author/add', 'AdminController@registerAuthor')->name('registerAuthor');

    //author account routes
    Route::get('/news/create', 'AuthorController@createNewsGet')->name('createNews');
    Route::post('/news/create', 'AuthorController@createNewsPost');
    Route::post('/image/upload', 'AuthorController@uploadImage');
    Route::get('/author/news', 'AuthorController@showNews')->name('authorNews');
    Route::get('/news/edit/{id}', 'AuthorController@editNews')->name('editNews');
    Route::post('/news/edit/{id}', 'AuthorController@editNewsPost')->name('editNewsPost');
    Route::get('/news/delete/{id}', 'AuthorController@deleteNews')->name('deleteNews');
});

//AJAX routes
Route::get('/ajax/subcategory', 'AuthorController@getSubcategories');
Route::post('/ajax/post/comment', 'AccountController@postComment')->name('postComment');
Route::get('/ajax/search/news', 'AuthorController@ajaxSearchNews');
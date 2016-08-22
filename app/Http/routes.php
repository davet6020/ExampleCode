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

Route::get('/', 'Controller@index');

Route::get('/logs', 'Admin\AdminController@quickQuoteExport');
Route::put('/qqdef/update', 'QuickQuoteDefaultsController@apiUpdate');
Route::post('/qqdef/list', 'QuickQuoteDefaultsController@apiList');


Route::get('guz', 'OctopartController@guzzle');
Route::get('octoread', 'OctopartController@octoRead');
Route::get('test', 'OctopartController@srchParts');

// Route::post('api/logout', 'UsersController@token');
Route::get('register/activation/{id}/{code}', ['uses' =>'RegisterController@activationClick']);
// Route::get('user/activation/{id}/{code}', ['uses' =>'UsersController@activationClick']);

Route::group(['prefix' => 'api'], function () {
    Route::post('login', 'UsersController@login');
    Route::post('register', 'RegisterController@register');
    Route::post('password/forgot', 'UsersController@forgotPassword');
    Route::post('password/change', 'UsersController@changePassword');

    // Quick Quote information
    Route::group(['prefix' => 'quote', 'middleware' => 'apiDetectUser'], function () {
        Route::get('quick', 'QuickQuoteController@apiDefaults');
        Route::post('quick', 'QuickQuoteController@apiCalculate');
    });

    Route::group(['middleware' => 'apiAuth'], function () {
        Route::get('test', function () {
            return \Response::json(['test' => \Request::get('active_user')->toArray()], 200);
        });

        Route::get('logout', 'UsersController@apiLogout');

        Route::group(['prefix' => 'user'], function () {
            Route::get('/{id?}', 'UsersController@apiFind');
            Route::post('/', 'UsersController@apiCreate');
            Route::delete('/{id}', 'UsersController@apiDelete');
            Route::put('/{id}', 'UsersController@apiUpdate');
        });

        Route::group(['prefix' => 'project'], function () {
            Route::get('/{id?}', 'ProjectsController@apiList');
            Route::put('/{id}', 'ProjectsController@apiUpdate');
            Route::post('/', 'ProjectsController@apiCreate');
            Route::delete('/{id}', 'ProjectsController@apiDelete');
            Route::post('{id}/upload', 'ProjectsController@apiFileUpload');
            Route::get('{id}/bom', 'ProjectsController@apiParseCSV');
            Route::get('{id}/prices', 'ProjectsController@getPrices');
            Route::post('/{id}/file/delete', 'ProjectsController@apiDeleteFile');
        });
    });
});

// OLD STUFF
// Route::get('importFile', 'CsvController@importFile');
// Route::post('importExcel', 'CsvController@importExcel');
// Route::post('importWhat', 'CsvController@importWhat');

// Route::get('viewTar', 'CsvController@viewTar');
// Route::get('viewZip', 'CsvController@viewZip');

// Route::group(['middleware' => 'guest'], function () {
//     Route::get('register/login', 'RegisterController@loginForm');
//     Route::post('register/login', 'RegisterController@login');
//     Route::get('register/activation/{id}/{code}', ['uses' =>'RegisterController@activationClick']);

//     Route::get('user/login', 'UsersController@loginForm');
//     Route::get('user/login', 'UsersController@loginForm');
//     Route::get('login', 'UsersController@loginTest');
// });

// Route::get('register/pwforgot', function () {
//     return response()->view('register/pwforgot');
// });
// Route::post('register/pwforgot', 'RegisterController@pwForgot');
// Route::get('register/pwreset/{id}/{code}', ['uses' =>'RegisterController@pwReset']);
// Route::get('register/pwsetnew/{id}/{code}', ['uses' =>'RegisterController@pwReset']);
// Route::post('register/pwsetnew', 'RegisterController@pwSetNew');

// Route::group(['middleware' => 'auth'], function () {
//     Route::post('user/logout', 'UsersController@logout');
//     Route::get('register/logout', 'RegisterController@logout');
//     Route::get('logout', 'RegisterController@logout');
// });

// Route::resource('user', 'UsersController');

Route::group(['middleware' => 'admin'], function () {
    // Route::get('user/find', 'UsersController@find');
    // Route::post('user/find', 'UsersController@findByName');
    Route::resource('admin/settings', 'Admin\AdminSettingsController', ['names' => [
        'index'     => 'admin.settings.index',
        'create'    => 'admin.settings.create',
        'store'     => 'admin.settings.store',
        'edit'      => 'admin.settings.edit',
        'update'    => 'admin.settings.update'
    ],
        'only'      => ['index', 'create', 'store', 'edit', 'update']
    ]);
    // Route::get('admin', ['as' => 'admin.index', 'uses' => 'Admin\AdminController@index']);
});



// Route::resource('register', 'RegisterController');
Route::post('quote/quick', 'QuickQuoteController@input');
Route::get('quote/quick', 'QuickQuoteController@index');

// END OLD STUFF

// CATCH ALL ROUTE =============================
// all routes that are not home or api will be redirected to the frontend
// this allows angular to route them
// This is done through the exception handler for Not Found Exceptions.

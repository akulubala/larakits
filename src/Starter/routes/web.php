<?php
Route::get('/', ['uses' => 'Web\HomeController@index', 'as' => 'web.home']);

/*
|--------------------------------------------------------------------------
| Login/ Logout/ Password
|--------------------------------------------------------------------------
*/
Auth::routes();
/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => 'auth'], function(){
    /*
    |--------------------------------------------------------------------------
    | Admin
    |--------------------------------------------------------------------------
    */

    Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => 'admin'], function(){

        /*
        |--------------------------------------------------------------------------
        | Dashboard
        |--------------------------------------------------------------------------
        */

        Route::get('/dashboard', ['uses' => 'PagesController@dashboard', 'as' => 'admin.dashboard']);
        /*
        |--------------------------------------------------------------------------
        | Users
        |--------------------------------------------------------------------------
        */
        Route::resource('users', 'UserController', ['except' => ['show'], 'as' => 'admin']);
        Route::get('users/search', ['uses' => 'UserController@index', 'as' => 'admin.users.search.get']);
        Route::get('users/switch/{id}', ['uses' => 'UserController@switchToUser', 'as' => 'user_switch.get']);
        Route::get('/users/switch-back', ['uses' => 'UserController@switchUserBack', 'as' => 'user_switch_back.get']);

        /*
        |--------------------------------------------------------------------------
        | User Account Setting
        |--------------------------------------------------------------------------
        */

        Route::group(['prefix' => 'user', 'namespace' => 'User'], function(){
            Route::get('settings', ['uses' => 'SettingsController@settings', 'as' => 'user_settings.get']);
            Route::post('settings', ['uses' => 'SettingsController@update', 'as' => 'user_settings.post']);
            Route::get('password', ['uses' => 'PasswordController@password', 'as' => 'password_reset.get']);
            Route::post('password', ['uses' => 'PasswordController@update', 'as' => 'password_reset.post']);
        });

        /*
        |--------------------------------------------------------------------------
        | Roles
        |--------------------------------------------------------------------------
        */
        Route::resource('roles', 'RoleController', ['except' => ['show'], 'as' => 'admin']);
        Route::get('roles/search', ['uses' => 'RoleController@index', 'as' => 'admin.roles.search.get']);

       
    });
});
<?php
Route::get('/', ['uses' => 'Web\IndexController@index', 'as' => 'web.index']);

/*
|--------------------------------------------------------------------------
| Login/ Logout/ Password
|--------------------------------------------------------------------------
*/
Route::get('login', ['uses' => 'Auth\AuthController@getLogin', 'as' => 'login.get']);
Route::post('login', ['uses' => 'Auth\AuthController@postLogin', 'as' => 'login.post']);
Route::get('logout', ['uses' => 'Auth\AuthController@getLogout', 'as' => 'login_out.get']);

Route::get('password/email', ['uses' => 'Auth\PasswordController@getEmail', 'as' => 'password_email.get']);
Route::post('password/email', ['uses' => 'Auth\PasswordController@postEmail', 'as' => 'password_email.post']);

Route::get('password/reset/{token}', ['uses' => 'Auth\PasswordController@getReset', 'as' => 'password_reset.get']);
Route::post('password/reset', ['uses' => 'Auth\PasswordController@postReset', 'as' => 'password_reset.post']);

/*
|--------------------------------------------------------------------------
| Registration
|--------------------------------------------------------------------------
*/
Route::get('register', ['uses' => 'Auth\AuthController@getRegister', 'as' => 'register.get']);
Route::post('register', ['uses' => 'Auth\AuthController@postRegister', 'as' => 'register.post']);

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
        Route::resource('users', 'UserController', ['except' => ['show']]);
        Route::post('users/search', ['uses' => 'UserController@search', 'as' => 'users_search.post']);
        Route::get('users/search', ['uses' => 'UserController@index', 'as' => 'users_search.get']);
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
        Route::resource('roles', 'RoleController', ['except' => ['show']]);
        Route::post('roles/search', ['uses' => 'RoleController@search', 'as' => 'roles_search.post']);
        Route::get('roles/search', ['uses' => 'RoleController@index', 'as' => 'roles_search.get']);

        /*
        |--------------------------------------------------------------------------
        | Team Routes
        |--------------------------------------------------------------------------
        */
        Route::resource('teams', 'TeamController', ['except' => ['show']]);
        Route::get('team/{name}', ['uses' => 'TeamController@showByName', 'as' => 'team_show_by_name.get']);
        Route::post('teams/search', ['uses' => 'TeamController@search', 'as' => 'team_search.get']);
        Route::post('teams/{id}/invite', ['uses' => 'TeamController@inviteMember', 'as' => 'team_invite.post']);
        Route::get('teams/{id}/remove/{userId}', ['uses' => 'TeamController@removeMember', 'as' => 'team_remove_member.get']);
    });
});
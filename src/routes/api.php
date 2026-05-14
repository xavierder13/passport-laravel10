<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// used namespace to avoid declaring every controller controller
Route::namespace('App\Http\Controllers\API')->group(function () {
        
    // Auth routes
    Route::prefix('auth')->group(function () {
        // Public routes (no auth)
        Route::post('/login', 'AuthController@login');
        Route::post('/register', 'AuthController@register');

        // Protected routes (require access token)
        Route::middleware('auth:api')->group(function () {
            Route::get('/me', 'AuthController@me');
            Route::post('/logout', 'AuthController@logout');
        });
    });

    Route::middleware('auth:api')->group(function () {
        
        // User Resource
        Route::apiResource('users', 'UserController')->middleware('user.maintenance');

        // Role Resource
        Route::apiResource('roles', 'RoleController')->middleware('role.maintenance');

        // Permission Resource
        // Route::apiResource('permissions', 'PermissionController')->middleware('permission.maintenance');\
        
        Route::prefix('permissions')->group(function () {
            Route::get('/', 'PermissionController@index')
                 ->middleware('permission.maintenance:permission-list');
        });
    });

    // test for centralized auth server
    Route::middleware('remote.auth')->group(function () {
        
        //KPI Template Resource 
        Route::apiResource('kpi-templates', 'KPITemplateController')
             ->middleware('require.permission:kpi-template-list');
    });

    Route::get('/sales', 'SqlController@sales');
});







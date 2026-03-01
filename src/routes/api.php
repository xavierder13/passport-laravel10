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

        // Pe Resource
        Route::apiResource('permissions', 'PermissionController')->middleware('permission.maintenance');
    });
});







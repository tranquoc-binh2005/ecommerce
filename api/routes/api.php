<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\User\UserCatalogueController;
use App\Http\Controllers\Api\V1\User\UserController;
use App\Http\Controllers\Api\V1\Permission\PermissionController;

Route::group(['prefix' => 'v1/auth'], function(){
    Route::post('/authenticate', [AuthController::class, 'authenticate']);
    Route::post('/refresh', [AuthController::class, 'refresh']);

    Route::middleware(['jwt:api'])->group(function(){
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });
    
});


Route::group(['prefix' => 'v1'], function(){ 
    Route::middleware(['jwt:api', 'convertRequestKey', 'checkApiPermission'])->group(function(){
        Route::post('user_catalogues/{id}/attach', [UserCatalogueController::class, 'attach']);
        Route::delete('user_catalogues/{id}/detach', [UserCatalogueController::class, 'detach']);
        Route::delete('user_catalogues', [UserCatalogueController::class, 'bulkDelete']);
        Route::resource('user_catalogues', UserCatalogueController::class)->except(['edit', 'create']);

        /** USER */
        Route::post('users/{id}/attach', [UserController::class, 'attach']);
        Route::delete('users/{id}/detach', [UserController::class, 'detach']);
        Route::delete('users', [UserController::class, 'bulkDelete']);
        Route::resource('users', UserController::class)->except(['edit', 'create']);

        /** PERMISSION */
        Route::post('permissions/{id}/attach', [PermissionController::class, 'attach']);
        Route::delete('permissions/{id}/detach', [PermissionController::class, 'detach']);
        Route::delete('permissions', [PermissionController::class, 'bulkDelete']);
        Route::resource('permissions', PermissionController::class)->except(['edit', 'create']);
    });
});
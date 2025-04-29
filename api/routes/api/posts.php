<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Post\PostController;

Route::group(['prefix' => 'v1'], function(){
    Route::middleware(['jwt:api', 'convertRequestKey', 'checkApiPermission'])->group(function(){
        Route::post('posts/{id}/attach', [PostController::class, 'attach']);
        Route::delete('posts/{id}/detach', [PostController::class, 'detach']);
        Route::delete('posts', [PostController::class, 'bulkDelete']);
        Route::resource('posts', PostController::class)->except(['edit', 'create']);
    });
});

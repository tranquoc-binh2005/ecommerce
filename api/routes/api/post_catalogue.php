<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Post\PostCatalogueController;

Route::group(['prefix' => 'v1'], function(){
    Route::middleware(['jwt:api', 'convertRequestKey', 'checkApiPermission'])->group(function(){
        Route::post('post_catalogues/{id}/attach', [PostCatalogueController::class, 'attach']);
        Route::delete('post_catalogues/{id}/detach', [PostCatalogueController::class, 'detach']);
        Route::delete('post_catalogues', [PostCatalogueController::class, 'bulkDelete']);
        Route::resource('post_catalogues', PostCatalogueController::class)->except(['edit', 'create']);
    });
});

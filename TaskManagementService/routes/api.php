<?php

use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\TaskController;
use Illuminate\Support\Facades\Route;

Route::prefix('V1')->group(function () {
    Route::prefix('tasks')->controller(TaskController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('/{id}', 'show');
        Route::patch('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
    });
    Route::prefix('categories')->controller(CategoryController::class)->group(function () {

        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('/{id}', 'show');
        Route::patch('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
    });

});

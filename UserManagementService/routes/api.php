<?php

use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('V1')->group(function () {
    Route::controller(UserController::class)->prefix('/users')->group(function () {
        Route::get('/', 'index');
    });
});

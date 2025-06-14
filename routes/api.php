<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WordController;
use App\Http\Middleware\AuthMiddleware;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/signup', [UserController::class, 'create']);
    Route::post('/signin', [AuthController::class, 'login']);
});

Route::prefix('entries')->group(function () {
    Route::get('/en', [WordController::class, 'list'])->middleware(AuthMiddleware::class);
});
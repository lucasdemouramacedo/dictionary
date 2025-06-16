<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DefaultController;
use App\Http\Controllers\FavoriteWordsController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WordController;
use App\Http\Middleware\AuthMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', DefaultController::class);

Route::prefix('auth')->group(function () {
    Route::post('/signup', [UserController::class, 'create']);
    Route::post('/signin', [AuthController::class, 'login']);
});

Route::prefix('entries')->group(function () {
    Route::get('/en', [WordController::class, 'list'])->middleware(AuthMiddleware::class);
    Route::get('/en/{word}', [SearchController::class, 'search'])->middleware(AuthMiddleware::class);
    Route::post('/en/{word}/favorite', [FavoriteWordsController::class, 'create'])->middleware(AuthMiddleware::class);
    Route::delete('/en/{word}/unfavorite', [FavoriteWordsController::class, 'delete'])->middleware(AuthMiddleware::class);
});

Route::prefix('user')->group(function () {
    Route::get('/me', [UserController::class, 'profile'])->middleware(AuthMiddleware::class);
    Route::get('/me/history', [HistoryController::class, 'list'])->middleware(AuthMiddleware::class);
    Route::get('/me/favorites', [FavoriteWordsController::class, 'list'])->middleware(AuthMiddleware::class);
});

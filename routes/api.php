<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ArticleController;

Route::prefix('/v1')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/articles', [ArticleController::class, 'index']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', [AuthController::class, 'user']);
        Route::get('/logout', [AuthController::class, 'logout']);

        // Articles
        Route:;apiResource('articles', ArticleController::class)->except('index');
        // Article search
        Route::get('/articles/search/{title}', [ArticleController::class, 'search']);
        Route::post('/articles/vote/{article}', [ArticleController::class, 'vote']);
    });

});

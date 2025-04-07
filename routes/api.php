<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('/auth/login', [AuthController::class, 'login']);

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('/user', function (Request $request) {
            return $request->user();
        });

        Route::get('/auth/logout', [AuthController::class, 'logout']);

        Route::apiResource('/articles', ArticleController::class);
        Route::apiResource('/articles/{articles}/comment', CommentController::class);
        Route::get('/my-articles', [ArticleController::class, 'myArticle']);
    });
});

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\IsAllowPost;
use App\Http\Controllers\Api\PostController;

Route::middleware([IsAllowPost::class])->group(function () {
    Route::post('/posts', [PostController::class, 'store']);
    Route::put('/posts/{uuid}', [PostController::class, 'update']);
    Route::delete('/posts/{uuid}', [PostController::class, 'destroy']);
});

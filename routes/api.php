<?php

use App\Http\Controllers\Api\PostController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| The task specification defines routes at /api/posts (no version prefix).
*/

Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/{slug}', [PostController::class, 'show']);
Route::post('/posts/{slug}/comments', [\App\Http\Controllers\Api\CommentController::class, 'store']);

<?php

use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Frontend\CommentController as FrontendCommentController;
use App\Http\Controllers\Frontend\PostController as FrontendPostController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [FrontendPostController::class, 'index'])->name('home');
Route::get('/posts/{slug}', [FrontendPostController::class, 'show'])->name('posts.show');
Route::post('/posts/{post:slug}/comments', [FrontendCommentController::class, 'store'])
    ->name('comments.store');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('posts', AdminPostController::class)->except(['show']);
    });

/*
|--------------------------------------------------------------------------
| Auth (Breeze)
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', fn() => redirect()->route('admin.posts.index'))
    ->middleware('auth')
    ->name('dashboard');

require __DIR__ . '/auth.php';

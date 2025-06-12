<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\TimerController;
use Illuminate\Support\Facades\Auth;

// Auth routes (login, register, password reset, etc.)
require __DIR__.'/auth.php';
Route::get('auth/google', [\App\Http\Controllers\Auth\SocialiteController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [\App\Http\Controllers\Auth\SocialiteController::class, 'handleGoogleCallback']);

// Timer management routes (accessible to authenticated users even if locked out)
Route::middleware('auth')->group(function () {
    Route::get('/timer/set', [TimerController::class, 'showSetForm'])->name('timer.set.view');
    Route::post('/timer/set', [TimerController::class, 'set'])->name('timer.set');
    Route::get('/timer/expired', [TimerController::class, 'expired'])->name('timer.expired');
});

// All other routes: must be authenticated AND within timer limit
Route::middleware(['auth', 'timer.active'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['verified'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/community', [PostController::class, 'community'])->name('posts.community');
    Route::get('/communities/create', [CommunityController::class, 'create'])->name('communities.create');
    Route::post('/communities', [CommunityController::class, 'store'])->name('communities.store');
    Route::get('/communities/search', [CommunityController::class, 'search'])->name('communities.search');
    Route::post('/communities/{community}/join', [CommunityController::class, 'join'])->name('communities.join');
    Route::post('/communities/{community}/leave', [CommunityController::class, 'leave'])->name('communities.leave');

    Route::resource('posts', PostController::class);
    Route::resource('communities', CommunityController::class);

    // Komentāri
    /*Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::get('/comments/{comment}/edit', [CommentController::class, 'edit'])->name('comments.edit');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::get('/posts/{post}/comments', [CommentController::class, 'comments'])->name('posts.comments');*/
    Route::get('/posts/{post}/comments', [CommentController::class, 'comments'])->name('posts.comments');
    Route::get('/search', [App\Http\Controllers\SearchController::class, 'index'])->name('search');
});

Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('posts.index')
        : redirect()->route('login');
});
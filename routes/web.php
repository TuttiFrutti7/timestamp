<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

Route::get('/', [PostController::class, 'index'])->name('home');

/*Route::middleware(['auth', 'timer.active'])->group(function () {
    Route::resource('posts', PostController::class)->except(['index']);
});*/

Route::resource('posts', PostController::class);//->auth(['index']);
Route::redirect('/posts', '/');
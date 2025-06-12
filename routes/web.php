<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommunityController;

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/community', [PostController::class, 'community'])->name('posts.community');
    Route::get('/communities/create', [CommunityController::class, 'create'])->name('communities.create');
    Route::post('/communities', [CommunityController::class, 'store'])->name('communities.store');
    Route::get('/communities/search', [CommunityController::class, 'search'])->name('communities.search');
    Route::post('/communities/{community}/join', [CommunityController::class, 'join'])->name('communities.join');
    Route::post('/communities/{community}/leave', [CommunityController::class, 'leave'])->name('communities.leave');
});

Route::resource('posts', PostController::class);//->auth(['index']);

Route::get('/', function () {
    return redirect()->route('posts.index');
});
/*Route::middleware(['auth', 'timer.active'])->group(function () {
    Route::resource('posts', PostController::class)->except(['index']);
});*/
Route::middleware(['auth'])->group(function () {
    Route::resource('posts', PostController::class);
    Route::resource('communities', CommunityController::class);
});

require __DIR__.'/auth.php';
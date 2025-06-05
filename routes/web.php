<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
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
});



require __DIR__.'/auth.php';
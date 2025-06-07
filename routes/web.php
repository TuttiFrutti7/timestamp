<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;

// TODO: Fix dashbord thingy and clean up this file
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});





//Route::resource('posts', PostController::class);//->auth(['index']);
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');

Route::get('/', function () {
    return redirect()->route('posts.index');
});
/*Route::middleware(['auth', 'timer.active'])->group(function () {
    Route::resource('posts', PostController::class)->except(['index']);
});*/
Route::middleware(['auth'])->group(function () {
    Route::resource('posts', PostController::class);
});

// Komentāri
Route::middleware('auth')->group(function () {
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::get('/comments/{comment}/edit', [CommentController::class, 'edit'])->name('comments.edit');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
});
// ielādē daļu komentārus route???
//Route::get('/posts/{post}/comments', [CommentController::class, 'comments']);
Route::get('/posts/{post}/comments', [CommentController::class, 'comments'])->name('posts.comments');



require __DIR__.'/auth.php';
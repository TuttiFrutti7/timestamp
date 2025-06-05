<?php

use Illuminate\Support\Facades\Route;
use app\Http\Controllers\PostController;
use app\Http\Controllers\MediaController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'timer.active'])->group(function () {
    Route::resource('posts', PostController::class);
});

Route::resource('posts', PostController::class);
<?php

use App\Http\Controllers\PhotoController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

//Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
//Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
//Route::get('/posts/show', [PostController::class, 'show'])->name('posts.show');

Route::resource('posts', PostController::class);
Route::resource('photos', PhotoController::class);
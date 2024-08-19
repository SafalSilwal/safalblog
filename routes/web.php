<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Auth\RegisterController;

// Home Route
Route::get('/', function () {
    return view('frontpage'); // Replace 'home' with the actual view you want to load
})->name('frontpage');

// Public Blog Routes for Users and Authors
Route::prefix('posts')->name('posts.')->group(function () {
    Route::get('/', [BlogController::class, 'index'])->name('index');
    Route::get('create', [BlogController::class, 'create'])->name('create');
    Route::post('store', [BlogController::class, 'store'])->name('store');
    Route::get('{id}/edit', [BlogController::class, 'edit'])->name('edit');
    Route::put('{id}/update', [BlogController::class, 'update'])->name('update');
    Route::delete('{id}/destroy', [BlogController::class, 'destroy'])->name('destroy');
});

// Authentication Routes
Auth::routes();
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::get('admin/register', [RegisterController::class, 'showAdminRegistrationForm'])->name('admin.register');

// Admin Routes with Middleware and Prefix
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

     // Update view path as needed

    // Admin User Management Routes

    Route::resource('users', UserController::class);

    // Admin Blog Management Routes
    Route::resource('users', PostController::class);
   
});

// Author Routes (Reuse Admin Routes)
Route::middleware(['auth', 'author'])->prefix('author')->name('author.')->group(function () {
    Route::get('dashboard', fn() => view('home'))->name('dashboard'); // Update view path as needed

    // Author Blog Management Routes (similar to Admin)
    Route::prefix('posts')->name('posts.')->group(function () {
        Route::get('/', [PostController::class, 'index'])->name('index');
        Route::get('create', [PostController::class, 'create'])->name('create');
        Route::post('/', [PostController::class, 'store'])->name('store');
        Route::get('{post}/edit', [PostController::class, 'edit'])->name('edit');
        Route::put('{post}', [PostController::class, 'update'])->name('update');
        Route::delete('{post}', [PostController::class, 'destroy'])->name('destroy');
    });
});
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('auth', 'admin');;

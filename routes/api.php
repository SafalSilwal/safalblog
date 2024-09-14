<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\PostController;
use App\Http\Controllers\API\RegisterController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get('/', function () {
    return 'API'; // Replace 'home' with the actual view you want to load
});

Route::controller(RegisterController::class)->group(function(){
    Route::post('register', 'register');
    Route::post('login', 'login');
});

// Route::get('/blog-posts', [BlogPostController::class, 'index']);
// Route::get('/blog-posts/{id}', [BlogPostController::class, 'show']);

Route::middleware('auth:sanctum')->group( function () {
    Route::resource('/blog-posts', PostController::class);
    Route::get('/blog-posts/{id}', [BlogPostController::class, 'show']);
});
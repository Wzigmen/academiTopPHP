<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CommentController; // Added this line

Route::get('/', function () {
    return view('welcome');
});

Route::get('register', [AuthController::class, 'registerView'])->name('register.view');
Route::post('register', [AuthController::class, 'register'])->name('register');

Route::get('login', [AuthController::class, 'loginView'])->name('login');
Route::post('login', [AuthController::class, 'login']);

Route::post('logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [PostController::class, 'index'])->name('dashboard');
    Route::resource('posts', PostController::class);

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::post('/posts/{post}/like', [PostController::class, 'like'])->name('posts.like');
    Route::post('/posts/{post}/unlike', [PostController::class, 'unlike'])->name('posts.unlike');

    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
});

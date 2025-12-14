<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Admin\AdminController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/search', [\App\Http\Controllers\MovieController::class, 'search'])->name('search');

Route::get('/users/{user}', [\App\Http\Controllers\UserController::class, 'show'])->name('users.show');
Route::get('/users/{user}/watched', [\App\Http\Controllers\UserController::class, 'watched'])->name('users.watched');

Route::get('register', [AuthController::class, 'registerView'])->name('register.view');
Route::post('register', [AuthController::class, 'register'])->name('register');

Route::get('login', [AuthController::class, 'loginView'])->name('login');
Route::post('login', [AuthController::class, 'login']);

Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Password Reset Routes
Route::get('forgot-password', [PasswordResetController::class, 'showEmailForm'])->name('password.request');
Route::post('forgot-password', [PasswordResetController::class, 'sendResetCode'])->name('password.email');
Route::get('verify-code', [PasswordResetController::class, 'showCodeForm'])->name('password.code.form');
Route::post('verify-code', [PasswordResetController::class, 'verifyCode'])->name('password.code.verify');
Route::get('reset-password', [PasswordResetController::class, 'showPasswordForm'])->name('password.reset');
Route::post('reset-password', [PasswordResetController::class, 'resetPassword'])->name('password.update');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [PostController::class, 'index'])->name('dashboard');
    Route::resource('posts', PostController::class);

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::post('/posts/{post}/like', [PostController::class, 'like'])->name('posts.like');
    Route::post('/posts/{post}/unlike', [PostController::class, 'unlike'])->name('posts.unlike');

    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    Route::post('/friends/send/{user}', [\App\Http\Controllers\FriendController::class, 'sendRequest'])->name('friends.send');
    Route::post('/friends/accept/{user}', [\App\Http\Controllers\FriendController::class, 'acceptRequest'])->name('friends.accept');
    Route::post('/friends/remove/{user}', [\App\Http\Controllers\FriendController::class, 'removeFriend'])->name('friends.remove');
    Route::get('/users/{user}/friends', [\App\Http\Controllers\FriendController::class, 'index'])->name('friends.index');

    Route::get('/messages', [\App\Http\Controllers\MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{user}', [\App\Http\Controllers\MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{user}', [\App\Http\Controllers\MessageController::class, 'store'])->name('messages.store');

    Route::post('/movies/{movie}/rate', [\App\Http\Controllers\MovieController::class, 'rate'])->name('movies.rate');
    Route::get('/notifications/friend-requests', [\App\Http\Controllers\FriendController::class, 'pendingRequests'])->name('notifications.friend-requests');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('users', [AdminController::class, 'usersIndex'])->name('users.index');
    Route::post('users/{user}/toggle-admin', [AdminController::class, 'toggleAdmin'])->name('users.toggleAdmin');
    Route::delete('users/{user}', [AdminController::class, 'destroy'])->name('users.destroy');
});

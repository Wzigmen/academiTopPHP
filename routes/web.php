<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('auth')->group( function (){
    Route::get('register', [AuthController::class, 'registerView'])->name('register.view');
    Route::post('register', [AuthController::class, 'register'])->name('register');
});

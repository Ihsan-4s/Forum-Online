<?php

use App\Http\Controllers\ThreadController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Home → feed (threads)
Route::get('/', [ThreadController::class, 'index'])->name('home');

// Admin
Route::get('/admin', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');

Route::get('/account', function(){
    return view('account');
})->name('account');

// Threads
Route::prefix('/threads')->name('threads.')->group(function () {
    Route::get('/', [ThreadController::class, 'index'])->name('index');
    Route::get('/create', [ThreadController::class, 'create'])->name('create');
    Route::post('/store', [ThreadController::class, 'store'])->name('store');

});

// Auth
Route::get('/login', function () {
    return view('auth.login');
})->name('login.form');

Route::post('/login', [UserController::class, 'login'])->name('login');
Route::post('/register', [UserController::class, 'register'])->name('register');
Route::get('/logout', [UserController::class, 'logout'])->name('logout');

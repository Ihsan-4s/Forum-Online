<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/login', function () {
    return view('auth.login');
})->name('login.form'); // jelas: ini buat tampilan form

route::get('/admin', function(){
    return view('admin.dashboard');
})->name('admin.dashboard');

Route::get('/logout', [UserController::class, 'logout'])->name('logout');

Route::post('/login', [UserController::class, 'login'])->name('login');
Route::post('/register', [UserController::class, 'register'])->name('register');


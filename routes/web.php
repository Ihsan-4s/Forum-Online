<?php

use App\Http\Controllers\ThreadController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;

// Home → feed (threads)
Route::get('/', [ThreadController::class, 'index'])->name('index');

Route::prefix('/threads')->name('threads.')->group(function(){
    route::get('create', [ThreadController::class, 'create'])->name('create');
    route::post('store', [ThreadController::class, 'store'])->name('store');
    route::get('/threads/{thread}',[ThreadController::class,'show'])->name('show');
    route::post('/threads/{thread}/comments', [CommentController::class, 'store'])->name('threads.comments.store');
});


Route::prefix('/drafts')->name('drafts.')->group(function(){
    route::get('/', [UserController::class, 'Index'])->name('account.index');
    route::post('store',[ThreadController::class, 'draftStore'])->name('store');
    route::get('/edit/{id}',[ThreadController::class, 'draftEdit'])->name('edit');
    Route::put('/update/{id}', [ThreadController::class, 'draftUpdate'])->name('update');
});

Route::prefix('/urThread')->name('urThreads.')->group(function(){
    route::get('/' , [ThreadController::class, 'urThreadIndex'])->name('index');
});

// Admin
Route::get('/admin', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');

// account
Route::get('/account', [UserController::class, 'index'])->name('account');

// Auth
Route::get('/login', function () {
    return view('auth.login');
})->name('login.form');
Route::post('/login', [UserController::class, 'login'])->name('login');
Route::post('/register', [UserController::class, 'register'])->name('register');
Route::get('/logout', [UserController::class, 'logout'])->name('logout');

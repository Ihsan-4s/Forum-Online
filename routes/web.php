<?php

use App\Http\Controllers\ThreadController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use Illuminate\Support\Facades\Route;

// Home → feed (threads)
Route::get('/', [ThreadController::class, 'index'])->name('index');

Route::prefix('/threads')->name('threads.')->group(function(){
    Route::get('create', [ThreadController::class, 'create'])->name('create');
    Route::post('store', [ThreadController::class, 'store'])->name('store');
    Route::get('{thread}', [ThreadController::class, 'show'])->name('show');
    Route::delete('{thread}', [ThreadController::class, 'destroy'])->name('destroy');
    Route::post('{thread}/comments', [CommentController::class, 'store'])->name('comments.store');
});

Route::middleware('IsAdmin')->prefix('/admin')->name('admin.')->group(function (){
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
});

Route::get('/tags/{tag}', [ThreadController::class, 'filterByTag'])->name('tags.show');

Route::post('/like/toggle', [LikeController::class, 'toggle'])->middleware('auth')->name('like.toggle');




Route::prefix('/drafts')->name('drafts.')->group(function(){
    route::get('/', [UserController::class, 'Index'])->name('account.index');
    route::post('store',[ThreadController::class, 'draftStore'])->name('store');
    route::get('/edit/{id}',[ThreadController::class, 'draftEdit'])->name('edit');
    Route::put('/update/{id}', [ThreadController::class, 'draftUpdate'])->name('update');
    Route::delete('/destroy/{id}', [ThreadController::class, 'draftDestroy'])->name('destroy');
    route::put('publish/{id}', [ThreadController::class, 'draftPublish'])->name('publish');
});

Route::prefix('/urThread')->name('urThreads.')->group(function(){
    route::get('/' , [ThreadController::class, 'urThreadIndex'])->name('index');
});

// account
Route::prefix('/account')->name('account.')->group(function () {
    route::get('/', [UserController::class, 'index'])->name('index');
    route::post('/upload', [UserController::class, 'updateProfilePicture'])->name('upload');
});



// Auth
Route::get('/login', function () {
    return view('auth.login');
})->name('login.form');
Route::post('/login', [UserController::class, 'login'])->name('login');
Route::post('/register', [UserController::class, 'register'])->name('register');
Route::get('/logout', [UserController::class, 'logout'])->name('logout');

<?php

use App\Http\Controllers\ThreadController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use Illuminate\Support\Facades\Route;

// Home → feed (threads)
Route::get('/', [ThreadController::class, 'index'])->name('index');

Route::prefix('/threads')->name('threads.')->group(function(){
    Route::get('trash', [ThreadController::class, 'trash'])->name('trash');
    Route::get('/{thread}/exportPDF', [ThreadController::class, 'exportPDF'])->name('exportPDF');
    Route::get('create', [ThreadController::class, 'create'])->name('create');
    Route::post('store', [ThreadController::class, 'store'])->name('store');
    Route::get('/explore', [ThreadController::class, 'explore'])->name('explore');
    Route::get('{thread}', [ThreadController::class, 'show'])->name('show');
    Route::delete('{thread}', [ThreadController::class, 'destroy'])->name('destroy');
    Route::post('{thread}/report', [ThreadController::class, 'report'])->middleware('auth')->name('report');
    Route::patch('/restore/{id}', [ThreadController::class, 'restore'])->name('restore');
    Route::delete('/delete-permanent/{id}', [ThreadController::class, 'deletePermanent'])->name('deletePermanent');
    Route::post('{thread}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('{thread}/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::post('{thread}/comments/{comment}/report', [CommentController::class, 'report'])->middleware('auth')->name('comments.report');
});

Route::middleware('IsAdmin')->prefix('/admin')->name('admin.')->group(function (){
    route::get('/user/chart', [UserController::class, 'userChart'])->name('userChart');
    route::get('/get' , [UserController::class, 'getAllUsers'])->name('getAllUsers');
    route::get('/dataTable', [UserController::class, 'dataTable'])->name('dataTable');
    route::patch('/active/{id}' , [UserController::class, 'active'])->name('active');
    route::patch('/deactive/{id}' , [UserController::class, 'deactive'])->name('deactive');

    Route::prefix('/threads')->name('threads.')->group(function(){
        route::get('/reported/chart', [ThreadController::class, 'reportedChart'])->name('reportedChart');
        route::get('/' , [ThreadController::class, 'adminThreads'])->name('index');
        route::get('/data', [ThreadController::class, 'threadsData'])->name('data');
        Route::get('/export' , [ThreadController::class, 'exportReportedThreads'])->name('export');
    });

    Route::prefix('/comments')->name('comments.')->group(function(){
        route::get('/', [CommentController::class, 'adminComments'])->name('index');
        route::get('/data', [CommentController::class, 'commentData'])->name('data');
        Route::delete('/{comment}', [CommentController::class, 'adminDestroy'])->name('destroy');
        Route::get('/export', [CommentController::class, 'exportReportedComments'])->name('export');
    });
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

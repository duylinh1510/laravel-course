<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicProfileController;
use App\Models\Follower;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FollowerController;
use App\Http\Controllers\ClapController;
use App\Http\Controllers\CommentController; 

Route::get('/', function () {
    return view('welcome');
});

// Mặc định Laravel sẽ sử dụng primary key của User để để lấy ra nên parhi thêm username
Route::get('/@{user:username}', [PublicProfileController::class, 'show'])
    ->name('profile.show');

Route::get('/@{username}/{post:slug}', [PostController::class, 'show'])
    ->name('post.show');

Route::get('/', [PostController::class, 'index'])
    ->name('dashboard');

Route::get('/category/{category}', [PostController::class, 'category'])
    ->name('post.byCategory');

Route::middleware(['auth','verified'])->group(function(){

    Route::get('/post/create', [PostController::class, 'create'])
    ->name('post.create');

    Route::post('/post', [PostController::class, 'store'])
    ->name('post.store');


    Route::post('/follow/{user:username}', [FollowerController::class, 'followUnfollow'])
    ->name('follow');

    Route::post('/clap/{post}', [ClapController::class, 'clap'])
    ->name('clap');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/my-posts', [PostController::class, 'myPosts'])->name('my.posts');

    Route::get('/post/{post}/edit', [PostController::class, 'edit'])->name('post.edit');
    Route::put('/post/{post}', [PostController::class, 'update'])->name('post.update');
    Route::delete('/post/{post}', [PostController::class, 'destroy'])->name('post.destroy');

    Route::post('/post/{post}/comment', [CommentController::class, 'store'])->name('comment.store');
    Route::delete('/comment/{comment}', [CommentController::class, 'destroy'])->name('comment.destroy');
});

require __DIR__.'/auth.php';

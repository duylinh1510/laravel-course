<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicProfileController;
use App\Models\Follower;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FollowerController;

Route::get('/', function () {
    return view('welcome');
});

// Mặc định Laravel sẽ sử dụng primary key của User để để lấy ra nên parhi thêm username
Route::get('/@{user:username}', [PublicProfileController::class, 'show'])->name('profile.show');


Route::middleware(['auth','verified'])->group(function(){
    Route::get('/', [PostController::class, 'index'])
    ->name('dashboard');

    Route::get('/post/create', [PostController::class, 'create'])
    ->name('post.create');

    Route::post('/post', [PostController::class, 'store'])
    ->name('post.store');

    Route::get('/@{username}/{post:slug}', [PostController::class, 'show'])
    ->name('post.show');

    Route::post('/follow/{user:username}', [FollowerController::class, 'followUnfollow'])
    ->name('follow');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

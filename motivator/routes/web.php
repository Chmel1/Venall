<?php

use App\Http\Controllers\Blog\CommentController;
use App\Http\Controllers\Blog\PostController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Habbit\HabitController;
use App\Http\Controllers\Habbit\HabitLogController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


require __DIR__.'/auth.php';

Auth::routes();
//Главная страница
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
//Посты
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
//Дешбордо
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::middleware('auth')->group(function () {
    //Пути связанные с задачами и наградами
    Route::resource('tasks', \App\Http\Controllers\TaskController::class);
    Route::resource('rewards', \App\Http\Controllers\RewardController::class);

    Route::patch('/tasks/{task}/complete', [\App\Http\Controllers\TaskController::class, 'complete'])->name('tasks.complete');
    Route::patch('/rewards/{reward}/use', [\App\Http\Controllers\RewardController::class, 'use'])->name('rewards.use');


    //Пути связанные с постами
    Route::get('/posts/create',[PostController::class, 'create'])->name('posts.create');
    Route::post('/posts',[PostController::class, 'store'])->name('posts.store');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::post('/posts/{post}/like', [PostController::class, 'like'])->name('posts.like');

    //Пути связанные с комментариями(для постов)
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');

    //Привычками
    Route::resource('habits', HabitController::class)->except(['show']);

    //Отметка выполнения
    Route::post('/habits/{habit}/complete', [HabitLogController::class, 'store'])->name('habits.complete');
});
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;

Route::get('/', function () {
    return view('auth.login');
});


Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});
Route::post('/logout', [LogoutController::class, 'logout'])->middleware('auth')->name('logout');

Route::controller(ProjectController::class)->middleware(['auth'])->prefix('projects')->name('projects.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/create', 'create')->name('create');
    Route::post('/', 'store')->name('store');
    Route::get('/{project}', 'show')->name('show');
    Route::get('/{project}/edit', 'edit')->name('edit');
    Route::put('/{project}', 'update')->name('update');
    Route::delete('/{project}', 'destroy')->name('destroy');
});

Route::controller(TaskController::class)->middleware(['auth'])->prefix('tasks')->name('tasks.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/create', 'create')->name('create');
    Route::post('/', 'store')->name('store');
    Route::get('/{task}', 'show')->name('show');
    Route::get('/{task}/edit', 'edit')->name('edit');
    Route::put('/{task}', 'update')->name('update');
    Route::delete('/{task}', 'destroy')->name('destroy');
});

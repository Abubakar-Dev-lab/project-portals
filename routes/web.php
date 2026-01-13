<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;

// Set a homepage
Route::get('/', function () {
    return redirect()->route('projects.index');
});

Route::controller(ProjectController::class)->prefix('projects')->name('projects.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/create', 'create')->name('create');
    Route::post('/', 'store')->name('store');
    Route::get('/{project}', 'show')->name('show');
    Route::get('/{project}/edit', 'edit')->name('edit');
    Route::put('/{project}', 'update')->name('update');
    Route::delete('/{project}', 'destroy')->name('destroy');
});

Route::controller(TaskController::class)->prefix('tasks')->name('tasks.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/create', 'create')->name('create');
    Route::post('/', 'store')->name('store');
    Route::get('/{task}', 'show')->name('show');
    Route::get('/{task}/edit', 'edit')->name('edit');
    Route::put('/{task}', 'update')->name('update');
    Route::delete('/{task}', 'destroy')->name('destroy');
});

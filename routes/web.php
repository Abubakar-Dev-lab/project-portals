<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;

Route::resource('projects', ProjectController::class);
Route::resource('tasks', TaskController::class);

Route::post('projects/store',[ProjectController::class,'store']);
// Set a homepage
Route::get('/', function () {
    return redirect()->route('projects.index');
});

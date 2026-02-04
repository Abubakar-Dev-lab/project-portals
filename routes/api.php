<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ProjectController;
use App\Http\Controllers\Api\V1\TaskController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\Admin\TrashController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - V1
|--------------------------------------------------------------------------
|
| RESTful API with versioning (v1), Sanctum authentication, and role-based
| authorization. All endpoints return standardized JSON responses.
|
*/

Route::prefix('v1')->name('api.v1.')->group(function () {

    // =====================================================================
    // PUBLIC AUTHENTICATION ROUTES (No Authentication Required)
    // =====================================================================
    Route::prefix('auth')->name('auth.')->group(function () {
        Route::post('login', [AuthController::class, 'login'])
            ->name('login');

        Route::post('register', [AuthController::class, 'register'])
            ->name('register');
    });

    // =====================================================================
    // PROTECTED ROUTES (Requires Sanctum Authentication)
    // =====================================================================
    Route::middleware('auth:sanctum')->group(function () {

        // Authentication Routes
        Route::prefix('auth')->name('auth.')->group(function () {
            Route::post('logout', [AuthController::class, 'logout'])
                ->name('logout');

            Route::get('profile', [AuthController::class, 'profile'])
                ->name('profile');
            Route::patch('profile', [AuthController::class, 'updateProfile'])
                ->name('update-profile');
        });

        // =====================================================================
        // PROJECT MANAGEMENT - RESTful Resource Routes
        // =====================================================================
        Route::apiResource('projects', ProjectController::class);

        // =====================================================================
        // TASK MANAGEMENT - RESTful Resource Routes
        // =====================================================================
        Route::apiResource('tasks', TaskController::class);

        // =====================================================================
        // USER MANAGEMENT - Admin Only Routes
        // =====================================================================
        Route::prefix('admin')->name('admin.')->group(function () {

            // User Management Endpoints
            Route::prefix('users')->name('users.')->group(function () {
                Route::get('/', [UserController::class, 'index'])
                    ->name('index');

                Route::get('roles', [UserController::class, 'getRoles'])
                    ->name('roles');

                Route::get('{user}', [UserController::class, 'show'])
                    ->name('show');

                Route::patch('{user}', [UserController::class, 'update'])
                    ->name('update');

                Route::delete('{user}', [UserController::class, 'destroy'])
                    ->name('destroy');

                Route::patch('{user}/activate', [UserController::class, 'activate'])
                    ->name('activate');
            });

            // =====================================================================
            // TRASH MANAGEMENT - Admin Only Routes
            // =====================================================================
            Route::prefix('trash')->name('trash.')->group(function () {
                Route::get('/', [TrashController::class, 'index'])
                    ->name('index');

                // Project Restoration & Deletion
                Route::patch('projects/{id}/restore', [TrashController::class, 'restoreProject'])
                    ->name('restore-project');

                Route::delete('projects/{id}', [TrashController::class, 'wipeProject'])
                    ->name('wipe-project');

                // Task Restoration & Deletion
                Route::patch('tasks/{id}/restore', [TrashController::class, 'restoreTask'])
                    ->name('restore-task');

                Route::delete('tasks/{id}', [TrashController::class, 'wipeTask'])
                    ->name('wipe-task');
            });
        });
    });
});

/*
|--------------------------------------------------------------------------
| API Documentation & Version Info (Optional)
|--------------------------------------------------------------------------
|
| Return API metadata for client applications to discover endpoints
|
*/
Route::get('/', function () {
    return response()->json([
        'application' => config('app.name'),
        'version' => '1.0.0',
        'api' => [
            'v1' => route('api.v1.auth.login'),
        ],
        'documentation' => 'See API routes at /api/v1',
    ]);
})->name('api.info');

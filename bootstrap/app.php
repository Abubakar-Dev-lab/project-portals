<?php

use App\Http\Middleware\CheckIsAdmin;
use App\Http\Middleware\CheckIfActive;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Database\Eloquent\ModelNotFoundException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        channels: __DIR__ . '/../routes/channels.php',
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => CheckIsAdmin::class,
        ]);
        // To add a global middleware to the 'web' group:
        $middleware->web(append: [
            CheckIfActive::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // FIX: Ensure it only intercepts API requests
        $exceptions->render(function (ModelNotFoundException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Record not found.'
                ], 404);
            }
        });
    })->create();

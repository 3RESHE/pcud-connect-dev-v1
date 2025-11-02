<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\EnsurePasswordChanged;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\EnsureUserIsActive;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Register route middleware
        $middleware->alias([
            'password.changed' => EnsurePasswordChanged::class,
            'role' => RoleMiddleware::class,
            'active' => EnsureUserIsActive::class,
        ]);

        // Apply globally to web routes
        $middleware->web(
            append: [
                EnsurePasswordChanged::class,
                EnsureUserIsActive::class,
            ]
        );
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();

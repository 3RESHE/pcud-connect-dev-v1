<?php

use App\Http\Middleware\AlumniProfileComplete;
use App\Http\Middleware\EnsurePasswordChanged;
use App\Http\Middleware\EnsureUserIsActive;
use App\Http\Middleware\PartnerProfileComplete;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\StudentProfileComplete;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;




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
            'alumni.profile.complete' => AlumniProfileComplete::class,
            'partner.profile.complete' => PartnerProfileComplete::class,
            'student.profile.complete' => StudentProfileComplete::class,
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

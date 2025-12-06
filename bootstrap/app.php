<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\EnsureTokenIsValid;
use App\Http\Middleware\LogRequest;

// ⭐ Agregamos tu RoleMiddleware
use App\Http\Middleware\RoleMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        // Middleware global
        $middleware->append(EnsureTokenIsValid::class);

        // ⭐ Registrar alias para tu middleware "role"
        $middleware->alias([
            'role' => RoleMiddleware::class,
        ]);

        // Aquí puedes poner más middlewares si luego necesitas
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

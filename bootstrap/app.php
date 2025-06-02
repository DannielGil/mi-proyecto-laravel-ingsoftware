<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\CheckUserRole; // <-- ¡CORRECCIÓN AQUÍ! Importa desde el namespace correcto

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            // ... otros aliases que ya tengas (ej. 'auth' de Laravel)
            'role' => CheckUserRole::class, // <-- ¡CORRECCIÓN AQUÍ! Usa el alias 'role' para que coincida con tu routes/web.php
        ]);

        // Si necesitas aplicar middlewares a grupos de rutas web o api globales,
        // lo harías aquí. Para tu caso, usar el alias en Route::middleware() es suficiente.
        // $middleware->web(append: [
        //     // \App\Http\Middleware\CheckUserRole::class, // Esto lo aplicarías globalmente a todas las rutas web
        // ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
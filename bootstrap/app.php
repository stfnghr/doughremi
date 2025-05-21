<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware; // Make sure this is imported if not already

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Example: If you have other aliases, they might look like this
        // $middleware->alias([
        //     'auth' => \App\Http\Middleware\Authenticate::class,
        //     'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        //     // ... other default or custom aliases
        // ]);

        // Add your admin middleware alias
        // If the ->alias() call already exists, add your 'admin' entry to that array.
        // If it doesn't exist, you can create it.
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            // If there were pre-existing aliases, keep them:
            // 'auth' => \App\Http\Middleware\Authenticate::class, // for example
        ]);

        // Alternatively, if $middleware->alias() is already called for other aliases,
        // you can just add your 'admin' entry into that existing array.
        // For example, if it was:
        // $middleware->alias([
        //     'auth' => \App\Http\Middleware\Authenticate::class,
        // ]);
        // You would change it to:
        // $middleware->alias([
        //     'auth' => \App\Http\Middleware\Authenticate::class,
        //     'admin' => \App\Http\Middleware\AdminMiddleware::class, // <-- Add it here
        // ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        // ...
    })->create();
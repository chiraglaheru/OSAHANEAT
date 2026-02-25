<?php

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
        // Register middleware aliases
        $middleware->alias([
            'admin' => App\Http\Middleware\Admin::class,
            'client' => App\Http\Middleware\Client::class,
            'status' => App\Http\Middleware\StatusMiddleware::class, // Added status middleware
        ]);

        
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Custom exception handling can be added here
        // Example:
        // $exceptions->report(function (CustomException $e) {
        //     // Handle the exception
        // });
    })
    ->create();

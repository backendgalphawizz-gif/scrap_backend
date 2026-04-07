<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )

    ->withMiddleware(function (Middleware $middleware) {

        $middleware->append(\Illuminate\Http\Middleware\HandleCors::class);
        // ✅ Register alias
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);


        // ✅ VERY IMPORTANT: Disable login redirect
        $middleware->redirectGuestsTo(function () {
            return null; // prevents Route [login] error
        });

    })

    ->withExceptions(function (Exceptions $exceptions) {

        // ✅ Unauthenticated
        $exceptions->render(function (AuthenticationException $e, $request) {
            return response()->json([
                'status'  => false,
                'message' => 'Token missing or invalid.',
            ], 401);
        });

        // ✅ Validation errors
        $exceptions->render(function (ValidationException $e, $request) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation error',
                'errors'  => $e->errors(),
            ], 422);
        });

    })
    ->create();
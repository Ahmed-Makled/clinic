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
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Handle 419 CSRF token errors by redirecting to login
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\HttpException $e, $request) {
            if ($e->getStatusCode() === 419) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => 'CSRF token mismatch. Please refresh and try again.',
                        'redirect' => route('login')
                    ], 419);
                }

                return redirect()->route('login')
                    ->with('error', 'انتهت صلاحية الجلسة. يرجى تسجيل الدخول مرة أخرى.');
            }
        });

        // Also handle TokenMismatchException directly
        $exceptions->render(function (\Illuminate\Session\TokenMismatchException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'CSRF token mismatch. Please refresh and try again.',
                    'redirect' => route('login')
                ], 419);
            }

            return redirect()->route('login')
                ->with('error', 'انتهت صلاحية الجلسة. يرجى تسجيل الدخول مرة أخرى.');
        });
    })->create();

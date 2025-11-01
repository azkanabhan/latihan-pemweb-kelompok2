<?php

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\EnsureRole::class,
        ]);
        $middleware->validateCsrfTokens(except: [
            'webhook/*',
            '/webhook/payment',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Handle database connection errors
        $exceptions->render(function (QueryException $e, $request) {
            // Check if it's a database connection error
            $errorCode = $e->getCode();
            $errorMessage = $e->getMessage();
            
            // Common database connection error codes:
            // 2002: Connection refused (MySQL)
            // 1045: Access denied (MySQL)
            // SQLSTATE[HY000] [2002]: Connection refused
            // SQLSTATE[HY000] [1045]: Access denied
            
            if (
                $errorCode === 2002 || 
                $errorCode === 1045 ||
                str_contains($errorMessage, 'Connection refused') ||
                str_contains($errorMessage, 'Access denied') ||
                str_contains($errorMessage, 'SQLSTATE[HY000]') ||
                str_contains($errorMessage, 'No connection could be made') ||
                str_contains($errorMessage, 'Connection timed out')
            ) {
                // Return custom database error view
                return response()->view('errors.database', [], 500);
            }
            
            // For other QueryExceptions, let Laravel handle them normally
            return null;
        });
    })->create();

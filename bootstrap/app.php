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
    ->withMiddleware(function (Middleware $middleware): void {
        // SECURITY: Add security headers to all responses
        $middleware->append(\App\Http\Middleware\SecurityHeaders::class);
        
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'operator' => \App\Http\Middleware\OperatorMiddleware::class,
            'participant' => \App\Http\Middleware\ParticipantMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Handle session expired (419 - Token Mismatch) errors
        $exceptions->render(function (\Illuminate\Session\TokenMismatchException $e, $request) {
            // Determine redirect based on the request path
            if ($request->is('admin/*') || $request->is('admin')) {
                return redirect()->route('admin.login')
                    ->with('error', 'Sesi Anda telah habis. Silakan login kembali.');
            }
            
            if ($request->is('participant/*') || $request->is('participant')) {
                return redirect()->route('participant.login')
                    ->with('error', 'Sesi Anda telah habis. Silakan login kembali.');
            }
            
            // Default to participant login for other paths
            return redirect()->route('participant.login')
                ->with('error', 'Sesi Anda telah habis. Silakan login kembali.');
        });
    })->create();

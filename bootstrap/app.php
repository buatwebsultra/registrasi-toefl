<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
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
        // Handle File/Post Too Large errors (413)
        $exceptions->render(function (\Illuminate\Http\Exceptions\PostTooLargeException $e, $request) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ukuran file yang Anda kirimkan terlalu besar. Silakan kompres file Anda menjadi maksimal 2MB per file.');
        });
    })->create();

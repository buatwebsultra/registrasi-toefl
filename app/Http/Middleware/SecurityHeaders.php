<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request and add security headers.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Generate a nonce for this request
        $nonce = base64_encode(random_bytes(16));
        $request->attributes->set('csp_nonce', $nonce);
        if (app()->bound('view')) {
            \View::share('csp_nonce', $nonce);
        }

        $response = $next($request);

        // Prevent clickjacking attacks
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Prevent MIME type sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Enable XSS protection (legacy, but still useful for older browsers)
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Force HTTPS for all future requests (1 year)
        // HSTS header should always be set in production
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');

        // Control referrer information
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Cross-Origin Isolation Headers
        $response->headers->set('Cross-Origin-Opener-Policy', 'same-origin');
        $response->headers->set('Cross-Origin-Resource-Policy', 'same-origin');
        $response->headers->set('Cross-Origin-Embedder-Policy', 'require-corp');

        // Content Security Policy
        // - Allow Bootstrap CDN (jsdelivr)
        // - Allow FontAwesome (cdnjs)
        // - Allow Google Fonts
        // - Allow Vite dev server
        // - USE NONCE for inline scripts/styles to resolve "unsafe-inline"
        $csp = "default-src 'self'; " .
            "script-src 'self' 'nonce-{$nonce}' https://cdn.jsdelivr.net https://unpkg.com http://127.0.0.1:5173 http://localhost:5173; " .
            "style-src 'self' 'unsafe-inline' 'nonce-{$nonce}' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://fonts.googleapis.com https://unpkg.com http://127.0.0.1:5173 http://localhost:5173; " .
            "img-src 'self' data: https:; " .
            "font-src 'self' data: https://cdnjs.cloudflare.com https://fonts.gstatic.com; " .
            "connect-src 'self' http://127.0.0.1:5173 http://localhost:5173 ws://127.0.0.1:5173 ws://localhost:5173;";

        $response->headers->set('Content-Security-Policy', $csp);

        // Permissions Policy
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');

        // Advanced Hardening & Fingerprint Removal
        $response->headers->remove('X-Powered-By');
        $response->headers->remove('Server');
        $response->headers->set('X-Download-Options', 'noopen');
        $response->headers->set('X-Permitted-Cross-Domain-Policies', 'none');

        // BREACH Mitigation: Add random padding to HTML responses
        if ($response instanceof \Illuminate\Http\Response && str_contains($response->headers->get('Content-Type'), 'text/html')) {
            $content = $response->getContent();
            $padding = "\n<!-- BREACH-MITIGATION-PADDING: " . base64_encode(random_bytes(rand(32, 128))) . " -->";
            $response->setContent($content . $padding);
        }

        return $response;
    }
}

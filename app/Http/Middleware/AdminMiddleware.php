<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated as admin
        if (!Auth::check()) {
            return redirect('/admin/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();
        
        // Verify the user has admin role
        if (!$user->isAdmin()) {
            Auth::logout();
            return redirect('/admin/login')->with('error', 'Akses ditolak. Anda bukan admin.');
        }

        return $next($request);
    }
}
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class OperatorMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect('/admin/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();
        
        // Verify the user has operator role or higher
        if (!$user->isOperator()) {
            Auth::logout();
            return redirect('/admin/login')->with('error', 'Akses ditolak. Anda tidak memiliki otoritas operator.');
        }

        return $next($request);
    }
}

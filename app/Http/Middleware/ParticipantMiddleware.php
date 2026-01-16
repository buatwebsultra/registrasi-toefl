<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class ParticipantMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Allow staff (admin/operator) to bypass participant authentication
        if (Auth::check() && Auth::user()->isOperator()) {
            return $next($request);
        }

        // Check if participant is authenticated
        if (!$request->session()->has('participant_id')) {
            return redirect('/participant/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Get participant ID from session
        $participantId = $request->session()->get('participant_id');
        
        // Verify the participant still exists in the database
        $participant = \App\Models\Participant::find($participantId);
        if (!$participant) {
            $request->session()->forget('participant_id');
            return redirect('/participant/login')->with('error', 'Sesi Anda telah berakhir. Silakan login kembali.');
        }

        // CRITICAL SECURITY CHECK: Verify ALL route parameters
        // Check all numeric parameters in the route - they should match the logged-in participant
        $routeParameters = $request->route()->parameters();
        
        foreach ($routeParameters as $key => $value) {
            // If it's a numeric ID parameter, verify it matches the session participant
            if (is_numeric($value)) {
                if ((string)$value !== (string)$participantId) {
                    \Log::warning('IDOR attempt detected', [
                        'session_participant_id' => $participantId,
                        'requested_id' => $value,
                        'route' => $request->route()->getName(),
                        'ip' => $request->ip(),
                        'user_agent' => $request->userAgent()
                    ]);
                    
                    abort(403, 'Anda tidak diizinkan mengakses data peserta lain.');
                }
            }
        }

        // Additional security: Add participant info to request for easy access in controllers
        $request->attributes->set('authenticated_participant', $participant);

        return $next($request);
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Participant;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // Show admin login form
    public function showAdminLoginForm()
    {
        return view('auth.admin-login');
    }

    // Admin login
    public function adminLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Check if user has operator role or higher (operator, admin, superadmin)
        $user = User::where('email', $request->email)->first();

        if ($user && $user->isOperator() && Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            // Log activity
            ActivityLogger::log('Login Admin', 'Admin ' . $user->name . ' berhasil masuk ke sistem.');

            if ($user->role === 'prodi') {
                return redirect()->intended('/admin/prodi/dashboard');
            }

            return redirect()->intended('/admin/dashboard');
        }

        // Log failed login attempt
        \Log::warning('Admin login failed', [
            'email' => $request->email,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records or user is not authorized.',
        ])->withInput();
    }

    // Admin logout
    public function adminLogout(Request $request)
    {
        if (Auth::check()) {
            ActivityLogger::log('Logout Admin', 'Admin ' . Auth::user()->name . ' keluar dari sistem.');
        }
        
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin/login');
    }

    // Show participant login form
    // Show participant login form
    public function showParticipantLoginForm()
    {
        return view('auth.participant-login');
    }

    // Participant login
    public function participantLogin(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string|alpha_dash|max:255',
            'password' => 'required',
        ]);

        // Sanitize input
        $username = trim(strip_tags($request->username));

        // SECURITY: Constant-time authentication to prevent username enumeration
        // Fetch the LATEST registration for this username
        $participant = Participant::where('username', $username)->latest('id')->first();
        
        // Always hash the password even if user doesn't exist (prevents timing attack)
        $passwordHash = $participant ? $participant->password : Hash::make('dummy-password-that-will-never-match');
        $passwordValid = Hash::check($request->password, $passwordHash);
        
        if ($participant && $passwordValid) {
            // Store participant ID in session
            session(['participant_id' => $participant->id]);

            // Log activity
            ActivityLogger::log('Login Peserta', 'Peserta ' . $participant->name . ' berhasil masuk ke dashboard.', 'participant', $participant->id);

            return redirect()->intended('/participant/dashboard/' . $participant->id);
        }

        // SECURITY: Add random delay to prevent timing attacks (100-300ms)
        usleep(random_int(100000, 300000));
        
        // Log failed login attempt
        \Log::warning('Participant login failed', [
            'username' => $request->username,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return back()->withErrors([
            'username' => 'Nama pengguna atau kata sandi salah.',
        ])->withInput($request->except('password'));
    }

    // Participant logout
    public function participantLogout(Request $request)
    {
        if (session()->has('participant_id')) {
            $participantId = session('participant_id');
            ActivityLogger::log('Logout Peserta', 'Peserta keluar dari dashboard.', 'participant', $participantId);
        }
        
        $request->session()->forget('participant_id');

        return redirect('/');
    }

    // Show change password form for admin
    public function showChangePasswordForm()
    {
        return view('auth.change-password');
    }

    // Change admin password
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string|min:8',
            'new_password' => 'required|string|min:8|confirmed|different:current_password',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            \Log::warning('Password change failed - current password incorrect', [
                'user_id' => $user->id,
                'ip' => $request->ip()
            ]);
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        // Log password change
        \Log::info('Admin password changed', [
            'user_id' => $user->id,
            'ip' => $request->ip()
        ]);

        return redirect()->back()->with('success', 'Password changed successfully.');
    }

    // Reset participant password (admin function) - moved to AdminController
    // This method is no longer needed here
}

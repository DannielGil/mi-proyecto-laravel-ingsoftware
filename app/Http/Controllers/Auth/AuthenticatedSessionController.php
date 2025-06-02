<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
// Make sure to import your Usuario model
use App\Models\Usuario; // <--- ADD THIS LINE

class AuthenticatedSessionController extends Controller
{
    public function create()
    {
        return view('auth.login'); // Or 'login', depending on your view path
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Attempt to authenticate the user using the 'email' and 'password'
        // Auth::attempt will use your 'usuarios_provider' configured in auth.php
        if (! Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        // If authentication is successful, regenerate the session
        $request->session()->regenerate();

        // --- START OF ROLE-BASED REDIRECTION LOGIC ---
        $user = Auth::user(); // Get the authenticated user (which is your App\Models\Usuario instance)

        if ($user->isAdministrador()) {
            return redirect()->intended(route('admin.dashboard'));
        } elseif ($user->isDocente()) {
            return redirect()->intended(route('docente.dashboard'));
        } elseif ($user->isCoordinador()) {
            return redirect()->intended(route('coordinador.dashboard'));
        } elseif ($user->isRector()) {
            return redirect()->intended(route('rector.dashboard'));
        }

        // Fallback redirection if no specific role-based redirection is met
        return redirect()->intended(route('dashboard')); // Generic dashboard if roles don't match
        // --- END OF ROLE-BASED REDIRECTION LOGIC ---
    }

    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
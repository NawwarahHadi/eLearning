<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        // 1. Get the authenticated user
        $user = Auth::user();

        // 2. Check if the status is NOT approved
        if ($user->status !== 'approved') {

            // Log them out immediately
            Auth::guard('web')->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // 3. Define the message based on their status
            $message = $user->status === 'rejected'
                ? 'Maaf, pendaftaran anda telah ditolak.'
                : 'Akaun anda masih dalam proses kelulusan oleh Admin.';

            return redirect()->route('login')->with('status_warning', $message);
        }

        // 4. If approved, proceed as normal
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }
    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}

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

        $user = Auth::user();

        // if ($user->status !== 'approved') {
        //     Auth::guard('web')->logout();
        //     $request->session()->invalidate();
        //     $request->session()->regenerateToken();

        //     $message = $user->status === 'rejected'
        //         ? 'Maaf, pendaftaran anda telah ditolak.'
        //         : 'Akaun anda masih dalam proses kelulusan oleh Admin.';

        //     return redirect()->route('login')->with('status_warning', $message);
        // }

        $request->session()->regenerate();

        return match($user->role) {
            'admin'   => redirect()->route('dashboard.admin'),
            'tutor'   => redirect()->route('dashboard.tutor'),
            'student' => redirect()->route('dashboard.student'),
            default   => redirect()->intended(route('dashboard', absolute: false)),
        };
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

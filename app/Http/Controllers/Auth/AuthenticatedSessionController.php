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

        $request->session()->regenerate();

        // Check if there's a redirect parameter
        $redirectUrl = $request->input('redirect');
        if ($redirectUrl) {
            return redirect($redirectUrl);
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Log for debugging
        \Log::info('Logout called', [
            'redirect' => $request->input('redirect'),
            'user' => auth()->user()?->email ?? 'no user'
        ]);

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        // Check if there's a redirect parameter (usually comes as login?redirect=...)
        $redirectUrl = $request->input('redirect');
        
        if ($redirectUrl) {
            // If redirect contains the full login route, use it directly
            if (str_contains($redirectUrl, 'login')) {
                return redirect($redirectUrl)->with('message', 'You have been logged out. Please login as attendee to continue.');
            }
            
            // Otherwise redirect with message
            return redirect($redirectUrl)->with('message', 'You have been logged out. Please login as attendee to continue.');
        }

        return redirect('/');
    }
}

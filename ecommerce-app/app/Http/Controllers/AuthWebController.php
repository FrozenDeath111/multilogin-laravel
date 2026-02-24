<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthWebController extends Controller
{
    /**
     * Show the login form.
     */
    public function show()
    {
        return view('login');
    }

    /**
     * Handle the authentication attempt.
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->has('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        throw ValidationException::withMessages([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * Log the user out.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function SSOAuthRedirect()
    {
        $user = Auth::user();

        $bridgeToken = Str::random(64);
        $cacheKey = "sso_token_" . $bridgeToken;

        Cache::put($cacheKey, [
            'email' => $user->email,
            'name' => $user->name,
            'app_name' => config('app.name')
        ], 60);

        return redirect('http://127.0.0.1:7080/auth-bridge?token=' . $bridgeToken);
    }
}
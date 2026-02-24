<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
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

    public function authSSO(Request $request)
    {
        $token = $request->query('token');

        $response = Http::get(config('app.ecom.base_url') . '/api/sso-verify/' . $token);

        if ($response->failed()) {
            return redirect()->route('login')->with('error', 'SSO Handshake Failed');
        }

        $data = $response->json();

        $user = User::updateOrCreate(
            ['email' => $data['email']],
            [
                'name' => $data['name'],
                'password' => Str::random(24),
                'app_name' => $data['app_name'],
            ]
        );

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
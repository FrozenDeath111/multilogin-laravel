<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class SSOController extends Controller
{
    // Redirect the user to Ecommerce Login
    public function redirect()
    {
        return Socialite::driver('ecom_sso')->stateless()->with(['client_id' => config('services.ecom_sso.client_id')])->redirect();
    }

    // Handle the response from Ecommerce
    public function callback()
    {
        $ssoUser = Socialite::driver('ecom_sso')->stateless()->user();

        // Create or find the user in Foodpanda's local DB
        $user = User::updateOrCreate([
            'email' => $ssoUser->getEmail(),
        ], [
            'name' => $ssoUser->getName(),
            // Set a random password since login is managed by Ecommerce
            'password' => bcrypt(Str::random(24)),
        ]);

        Auth::login($user);

        return redirect('/dashboard');
    }
}
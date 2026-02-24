<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class SSOAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        $ecomAuthUrl = config('app.ecom.base_url') . '/api/get-user';

        $response = Http::withToken($token)->get($ecomAuthUrl);

        if ($response->getStatusCode() != 200) {
            return response()->json([
                'message' => 'Unauthenticated Access',
                'error' => 'Unauthenticated',
                'status' => 'error'
            ], 401);
        }

        $userData = ($response->json())['data'];

        $userInfo = [
            'name' => $userData['name'],
            'email' => $userData['email'],
            'password' => Str::random(24),
            'app_name' => $userData['app_name']
        ];

        $user = User::firstOrCreate(['email' => $userInfo['email']], $userInfo);
        Auth::login($user);

        return $next($request);
    }
}

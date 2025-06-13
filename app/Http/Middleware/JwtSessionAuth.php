<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class JwtSessionAuth
{
    public function handle(Request $request, Closure $next)
    {
        $token = session('access_token');

        if (!$token) {
            return redirect('/login')->withErrors(['token' => 'No access token. Please log in.']);
        }

        try {
            JWTAuth::setToken($token)->authenticate();
        } catch (JWTException $e) {
            return redirect('/refresh-token');
        }

        return $next($request);
    }
}

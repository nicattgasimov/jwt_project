<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class GoogleController extends Controller
{
    public function create($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function store($provider)
    {
        $socialiteUser = Socialite::driver($provider)->stateless()->user();

            $user = User::firstOrCreate(
            ['email' => $socialiteUser->getEmail()],
            [
                'name' => $socialiteUser->getName(),
                'password' => bcrypt(bin2hex(random_bytes(16))),
                'socialite_id' => $socialiteUser->getId(),
                'socialite_token' => $socialiteUser->token,
                'avatar' => $socialiteUser->avatar,
            ]
        );

        $user->update([
            'socialite_id' => $socialiteUser->getId(),
            'socialite_token' => $socialiteUser->token,
            'avatar' => $socialiteUser->avatar,
        ]);

        $token = JWTAuth::fromUser($user);

        $refreshToken = bin2hex(random_bytes(64));
        DB::table('refresh_tokens')->insert([
            'user_id' => $user->id,
            'token' => $refreshToken,
            'expires_at' => now()->addDays(7),
        ]);

        session([
            'access_token' => $token,
            'refresh_token' => $refreshToken,
        ]);

        return redirect('/dashboard');
    }
}

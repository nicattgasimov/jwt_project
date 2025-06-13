<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function showLogin() {
        return view('auth.login');
    }

    public function showRegister() {
        return view('auth.register');
    }

    public function register(Request $request) {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return redirect('/login');
    }

    public function login(Request $request) {
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return back()->withErrors(['email' => 'Invalid credentials']);
        }

        $refreshToken = bin2hex(random_bytes(64));

        DB::table('refresh_tokens')->insert([
            'user_id' => auth()->id(),
            'token' => $refreshToken,
            'expires_at' => now()->addDays(7),
        ]);

        session([
            'access_token' => $token,
            'refresh_token' => $refreshToken,
        ]);

        return redirect('/dashboard');
    }

    public function logout() {
        session()->flush();
        auth()->logout();
        return redirect('/login');
    }

    public function refresh() {
        $refresh = session('refresh_token');

        $record = DB::table('refresh_tokens')
            ->where('token', $refresh)
            ->where('expires_at', '>', now())
            ->first();

        if ($record) {
            $user = User::find($record->user_id);
            $newToken = JWTAuth::fromUser($user);
            session(['access_token' => $newToken]);
            return redirect('/dashboard');
        }

        return redirect('/login')->withErrors(['token' => 'Session expired. Please login again.']);
    }
}

<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GoogleController;
Route::get('/',function (){
    return redirect()->route('dashboard');
});
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/refresh-token', [AuthController::class, 'refresh'])->name('refresh');

Route::middleware(['web'])->group(function () {
    Route::get('auth/{provider}/redirect', [GoogleController::class, 'create'])->where('provider', 'google|microsoft');
    Route::get('auth/{provider}/callback', [GoogleController::class, 'store'])->where('provider', 'google|microsoft');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('jwt.session')->name('dashboard');



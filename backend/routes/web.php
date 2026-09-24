<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required', 'string'],
    ]);

    if (! Auth::attempt($credentials)) {
        return response()->json([
            'message' => 'Kredensial tidak valid.',
        ], 401);
    }

    $request->session()->regenerate();

    return response()->json([
        'data' => [
            'id' => $request->user()->id,
            'name' => $request->user()->name,
        ],
    ]);
})->middleware('throttle:api-login')->name('login');

Route::post('/logout', function (Request $request) {
    Auth::guard('web')->logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return response()->noContent();
})->middleware('auth')->name('logout');



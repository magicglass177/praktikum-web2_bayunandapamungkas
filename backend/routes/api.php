<?php

use Illuminate\Support\Facades\Route;

Route::get('/status', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'Laravel API berjalan',
        'framework' => 'Laravel'
    ]);
});
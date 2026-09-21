<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
Route::get('/', function () {
    return view('welcome');
});

Route::get('/tickets', [TicketController::class, 'index'])
    ->name('tickets.index');

Route::get('/tickets/{ticket}', [TicketController::class, 'show'])
    ->whereNumber('ticket')
    ->name('tickets.show');

Route::get('/api/tickets/{ticket}', [TicketController::class, 'showJson'])
    ->whereNumber('ticket')
    ->name('tickets.show-json');

Route::pattern('ticket', '[0-9]+');

Route::resource('tickets', TicketController::class);

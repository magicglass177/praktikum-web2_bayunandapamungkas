<?php

use App\Http\Controllers\Api\V1\{AuthController, TicketController};
use App\Models\{Category, Ticket};
use Illuminate\Support\Facades\{Gate, Route};

Route::pattern('ticket', '[0-9]+');

Route::prefix('v1')->name('api.v1.')->group(function () {
    Route::middleware(['auth:sanctum', 'throttle:api-v1'])->group(function () {
        Route::get('me', [AuthController::class, 'me'])
            ->name('me');

        Route::get('categories', function () {
            return response()->json([
                'data' => Category::orderBy('name')
                    ->get(['id', 'name']),
            ]);
        })->name('categories.index');

        Route::get('reports/summary', function () {
            Gate::authorize('view-ticket-summary');

            return response()->json([
                'data' => [
                    'ticket_count' => Ticket::count(),
                ],
            ]);
        })->name('reports.summary');

        Route::apiResource('tickets', TicketController::class);
    });
});
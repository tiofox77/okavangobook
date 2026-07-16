<?php

use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\HotelController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\WebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — OkavangoBook v1
|--------------------------------------------------------------------------
| Endpoints REST em JSON para integração com sistemas externos.
| Leitura (GET) é pública (com rate limiting); escrita (POST/DELETE) exige
| a API key no cabeçalho `X-API-Key`.
*/

Route::get('/user', fn (Request $request) => $request->user())->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {

    // ---- Público (leitura) ----
    Route::middleware('throttle:120,1')->group(function () {
        Route::get('/hotels', [HotelController::class, 'index'])->name('api.hotels.index');
        Route::get('/hotels/{slug}', [HotelController::class, 'show'])->name('api.hotels.show');
        Route::get('/locations', [LocationController::class, 'index'])->name('api.locations.index');
        Route::get('/status', fn () => response()->json(['status' => 'ok', 'version' => 'v1', 'time' => now()->toIso8601String()]));
    });

    // ---- Protegido por API key (escrita / integração) ----
    Route::middleware(['api.key', 'throttle:60,1'])->group(function () {
        Route::post('/bookings', [BookingController::class, 'store'])->name('api.bookings.store');
        Route::get('/bookings/{code}', [BookingController::class, 'show'])->name('api.bookings.show');
        Route::post('/bookings/{code}/cancel', [BookingController::class, 'cancel'])->name('api.bookings.cancel');

        // Webhooks (registar endpoints que recebem eventos)
        Route::get('/webhooks', [WebhookController::class, 'index'])->name('api.webhooks.index');
        Route::post('/webhooks', [WebhookController::class, 'store'])->name('api.webhooks.store');
        Route::delete('/webhooks/{id}', [WebhookController::class, 'destroy'])->name('api.webhooks.destroy');
    });
});

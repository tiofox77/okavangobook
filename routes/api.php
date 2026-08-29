<?php

use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\HotelController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\WebhookController;
use App\Http\Controllers\Api\Agent\AgentController;
use App\Http\Controllers\Api\Agent\LogController as AgentLogController;
use App\Http\Controllers\Api\Agent\MediaController as AgentMediaController;
use App\Http\Controllers\Api\Agent\PageController as AgentPageController;
use App\Http\Controllers\Api\Agent\PropertyController as AgentPropertyController;
use App\Http\Controllers\Api\Agent\PropertyMediaController as AgentPropertyMediaController;
use App\Http\Controllers\Api\Agent\PropertyRoomTypeController as AgentPropertyRoomTypeController;
use App\Http\Controllers\Api\Agent\SiteController as AgentSiteController;
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

/*
|--------------------------------------------------------------------------
| Agent API v1 — Laravel + React
|--------------------------------------------------------------------------
| Bearer kstay__; tokens com escopos, expiração e allowlist de IP.
| Toda escrita passa por agent.write (X-Reason + Idempotency-Key).
*/
Route::prefix('agent/v1')->middleware(['agent.auth', 'throttle:agent-api'])->group(function () {
    Route::get('/me', [AgentController::class, 'me']);

    Route::get('/site/status', [AgentSiteController::class, 'status'])->middleware('agent.scope:system:read');
    Route::get('/site/settings', [AgentSiteController::class, 'settings'])->middleware('agent.scope:site:read');
    Route::patch('/site/settings', [AgentSiteController::class, 'updateSettings'])
        ->middleware(['agent.scope:site:write', 'agent.write']);

    Route::get('/pages', [AgentPageController::class, 'index'])->middleware('agent.scope:pages:read');
    Route::get('/pages/{slug}', [AgentPageController::class, 'show'])->middleware('agent.scope:pages:read');
    Route::post('/pages', [AgentPageController::class, 'store'])->middleware(['agent.scope:pages:write', 'agent.write']);
    Route::patch('/pages/{slug}', [AgentPageController::class, 'update'])->middleware(['agent.scope:pages:write', 'agent.write']);
    Route::post('/pages/{slug}/preview', [AgentPageController::class, 'preview'])->middleware(['agent.scope:pages:write', 'agent.write']);
    Route::post('/pages/{slug}/publish', [AgentPageController::class, 'publish'])->middleware(['agent.scope:pages:write', 'agent.write']);
    Route::post('/pages/{slug}/archive', [AgentPageController::class, 'archive'])->middleware(['agent.scope:pages:write', 'agent.write']);

    Route::get('/properties', [AgentPropertyController::class, 'index'])->middleware('agent.scope:properties:read');
    Route::post('/properties', [AgentPropertyController::class, 'store'])->middleware(['agent.scope:properties:write', 'agent.write']);
    Route::get('/properties/{id}', [AgentPropertyController::class, 'show'])->middleware('agent.scope:properties:read');
    Route::patch('/properties/{id}', [AgentPropertyController::class, 'update'])->middleware(['agent.scope:properties:write', 'agent.write']);

    Route::get('/properties/{id}/room-types', [AgentPropertyRoomTypeController::class, 'index'])->middleware('agent.scope:properties:read');
    Route::post('/properties/{id}/room-types', [AgentPropertyRoomTypeController::class, 'store'])->middleware(['agent.scope:properties:write', 'agent.write']);
    Route::patch('/properties/{id}/room-types/{roomTypeId}', [AgentPropertyRoomTypeController::class, 'update'])->middleware(['agent.scope:properties:write', 'agent.write']);
    Route::delete('/properties/{id}/room-types/{roomTypeId}', [AgentPropertyRoomTypeController::class, 'destroy'])->middleware(['agent.scope:properties:write', 'agent.write']);
    Route::post('/properties/{id}/room-types/reorder', [AgentPropertyRoomTypeController::class, 'reorder'])->middleware(['agent.scope:properties:write', 'agent.write']);

    Route::get('/properties/{id}/media', [AgentPropertyMediaController::class, 'index'])->middleware('agent.scope:media:read');
    Route::post('/properties/{id}/media', [AgentPropertyMediaController::class, 'store'])->middleware(['agent.scope:media:write', 'agent.write']);
    Route::patch('/properties/{id}/media/{mediaId}', [AgentPropertyMediaController::class, 'update'])->middleware(['agent.scope:media:write', 'agent.write']);
    Route::delete('/properties/{id}/media/{mediaId}', [AgentPropertyMediaController::class, 'destroy'])->middleware(['agent.scope:media:write', 'agent.write']);
    Route::post('/properties/{id}/media/reorder', [AgentPropertyMediaController::class, 'reorder'])->middleware(['agent.scope:media:write', 'agent.write']);

    Route::post('/media', [AgentMediaController::class, 'store'])->middleware(['agent.scope:media:write', 'agent.write']);
    Route::get('/logs/agent', [AgentLogController::class, 'index'])->middleware('agent.scope:logs:read');
});

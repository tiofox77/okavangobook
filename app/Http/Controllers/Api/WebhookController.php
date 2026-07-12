<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Webhook;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    /**
     * Eventos disponíveis para subscrição.
     */
    public const EVENTS = [
        'reservation.created',
        'reservation.status_changed',
        'reservation.cancelled',
    ];

    /**
     * GET /api/v1/webhooks
     */
    public function index()
    {
        return response()->json([
            'available_events' => self::EVENTS,
            'data' => Webhook::orderByDesc('id')->get(),
        ]);
    }

    /**
     * POST /api/v1/webhooks
     * Regista um endpoint que recebe eventos. Devolve o secret UMA vez.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'url' => ['required', 'url', 'max:2048'],
            'events' => ['required', 'array', 'min:1'],
            'events.*' => ['string', 'in:' . implode(',', array_merge(self::EVENTS, ['*']))],
            'name' => ['nullable', 'string', 'max:255'],
        ]);

        $webhook = Webhook::create($data);

        return response()->json([
            'message' => 'Webhook registado. Guarde o secret — não voltará a ser mostrado.',
            'id' => $webhook->id,
            'url' => $webhook->url,
            'events' => $webhook->events,
            'secret' => $webhook->secret,
        ], 201);
    }

    /**
     * DELETE /api/v1/webhooks/{id}
     */
    public function destroy(int $id)
    {
        Webhook::findOrFail($id)->delete();

        return response()->json(['message' => 'Webhook removido.']);
    }
}

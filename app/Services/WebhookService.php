<?php

namespace App\Services;

use App\Models\Webhook;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WebhookService
{
    /**
     * Envia um evento para todos os webhooks subscritos.
     * O payload é assinado com HMAC-SHA256 usando o secret de cada webhook,
     * para que o recetor possa verificar a autenticidade.
     */
    public static function dispatch(string $event, array $data): void
    {
        $webhooks = Webhook::forEvent($event);

        if ($webhooks->isEmpty()) {
            return;
        }

        $body = json_encode([
            'event' => $event,
            'timestamp' => now()->toIso8601String(),
            'data' => $data,
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        foreach ($webhooks as $webhook) {
            self::send($webhook, $event, $body);
        }
    }

    protected static function send(Webhook $webhook, string $event, string $body): void
    {
        $signature = hash_hmac('sha256', $body, $webhook->secret);

        try {
            $response = Http::timeout(5)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'X-Webhook-Event' => $event,
                    'X-Webhook-Signature' => 'sha256=' . $signature,
                ])
                ->withBody($body, 'application/json')
                ->post($webhook->url);

            if ($response->successful()) {
                $webhook->forceFill([
                    'last_triggered_at' => now(),
                    'failure_count' => 0,
                ])->save();
            } else {
                self::registerFailure($webhook, 'HTTP ' . $response->status());
            }
        } catch (\Throwable $e) {
            self::registerFailure($webhook, $e->getMessage());
        }
    }

    protected static function registerFailure(Webhook $webhook, string $reason): void
    {
        $webhook->increment('failure_count');
        $webhook->forceFill(['last_triggered_at' => now()])->save();

        // Desativa automaticamente após muitas falhas consecutivas.
        if ($webhook->failure_count >= 10) {
            $webhook->forceFill(['is_active' => false])->save();
        }

        Log::warning("Webhook falhou (#{$webhook->id} -> {$webhook->url}): {$reason}");
    }
}

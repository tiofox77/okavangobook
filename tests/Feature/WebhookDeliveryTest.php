<?php

namespace Tests\Feature;

use App\Models\Webhook;
use App\Services\WebhookService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class WebhookDeliveryTest extends TestCase
{
    use DatabaseTransactions;

    public function test_dispatch_sends_signed_payload_to_subscribed_webhook(): void
    {
        Http::fake(['*' => Http::response('', 200)]);

        $webhook = Webhook::create([
            'url' => 'https://exemplo.com/hook',
            'events' => ['reservation.created'],
            'is_active' => true,
        ]);

        WebhookService::dispatch('reservation.created', ['id' => 99, 'status' => 'pending']);

        Http::assertSent(function ($request) use ($webhook) {
            $body = $request->body();
            $expected = 'sha256=' . hash_hmac('sha256', $body, $webhook->secret);

            return $request->url() === 'https://exemplo.com/hook'
                && $request->header('X-Webhook-Event')[0] === 'reservation.created'
                && $request->header('X-Webhook-Signature')[0] === $expected
                && str_contains($body, '"event":"reservation.created"');
        });

        $this->assertNotNull($webhook->fresh()->last_triggered_at);
    }

    public function test_dispatch_ignores_webhooks_not_subscribed_to_event(): void
    {
        Http::fake();

        Webhook::create([
            'url' => 'https://exemplo.com/other',
            'events' => ['reservation.cancelled'],
            'is_active' => true,
        ]);

        WebhookService::dispatch('reservation.created', ['id' => 1]);

        Http::assertNothingSent();
    }

    public function test_failed_delivery_increments_failure_count(): void
    {
        Http::fake(['*' => Http::response('erro', 500)]);

        $webhook = Webhook::create([
            'url' => 'https://exemplo.com/fail',
            'events' => ['*'],
            'is_active' => true,
        ]);

        WebhookService::dispatch('reservation.created', ['id' => 1]);

        $this->assertEquals(1, $webhook->fresh()->failure_count);
    }
}

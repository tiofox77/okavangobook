<?php

namespace Tests\Feature;

use App\Models\AgentToken;
use App\Models\Hotel;
use App\Models\Location;
use Database\Seeders\AgentApiSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AgentApiTest extends TestCase
{
    use DatabaseTransactions;

    private string $plainToken;

    protected function setUp(): void
    {
        parent::setUp();

        [, $this->plainToken] = AgentToken::issue([
            'name' => 'phpunit-agent',
            'scopes' => [
                'system:read', 'site:read', 'pages:read', 'pages:write', 'logs:read',
                'properties:read', 'properties:write', 'properties:publish', 'media:read', 'media:write',
                'pricing:write',
            ],
            'allowed_ips' => ['127.0.0.1'],
            'expires_at' => now()->addDay(),
        ]);
    }

    public function test_agent_requires_valid_bearer_token(): void
    {
        $this->getJson('/api/agent/v1/me')->assertUnauthorized();

        $this->withToken($this->plainToken)
            ->getJson('/api/agent/v1/me')
            ->assertOk()
            ->assertJsonPath('data.name', 'phpunit-agent');
    }

    public function test_write_requires_reason_and_idempotency_key(): void
    {
        $this->withToken($this->plainToken)
            ->postJson('/api/agent/v1/pages', ['title' => 'X', 'blocks' => []])
            ->assertUnprocessable()
            ->assertJsonPath('message', 'X-Reason obrigatório (mínimo 5 caracteres).');
    }

    public function test_page_write_is_idempotent_and_publish_requires_preview(): void
    {
        $payload = [
            'title' => 'Página PHPUnit',
            'slug' => 'pagina-phpunit',
            'blocks' => [['type' => 'hero', 'props' => ['headline' => 'Teste']]],
            'seo' => ['meta_title' => 'Teste Agent API'],
        ];
        $headers = $this->writeHeaders('create-page-phpunit');

        $this->withToken($this->plainToken)->postJson('/api/agent/v1/pages', $payload, $headers)->assertCreated();
        $this->withToken($this->plainToken)->postJson('/api/agent/v1/pages', $payload, $headers)
            ->assertCreated()->assertHeader('Idempotency-Replayed', 'true');

        $this->withToken($this->plainToken)
            ->postJson('/api/agent/v1/pages/pagina-phpunit/publish', ['preview_token' => 'invalid'], $this->writeHeaders('publish-invalid-phpunit'))
            ->assertStatus(409);

        $preview = $this->withToken($this->plainToken)
            ->postJson('/api/agent/v1/pages/pagina-phpunit/preview', [], $this->writeHeaders('preview-page-phpunit'))
            ->assertOk();

        $this->withToken($this->plainToken)
            ->postJson('/api/agent/v1/pages/pagina-phpunit/publish', [
                'preview_token' => $preview->json('data.preview_token'),
            ], $this->writeHeaders('publish-valid-phpunit'))
            ->assertOk()
            ->assertJsonPath('data.status', 'published');

        $this->assertDatabaseHas('agent_audit_logs', [
            'actor' => 'phpunit-agent',
            'idempotency_key' => 'publish-valid-phpunit',
            'status_code' => 200,
        ]);
    }

    public function test_agent_token_command_supports_one_year_tokens(): void
    {
        $this->seed(AgentApiSeeder::class);

        $this->artisan('agent:token', [
            'name' => 'OpenClaw SOS Reclame',
            '--scopes' => ['*'],
            '--days' => 365,
        ])->assertSuccessful();

        $token = AgentToken::where('name', 'OpenClaw SOS Reclame')->latest('id')->firstOrFail();

        $this->assertSame(['*'], $token->scopes);
        $this->assertTrue($token->expires_at->between(now()->addDays(364), now()->addDays(366)));
    }

    public function test_property_keeps_external_thumbnail_and_images_contract_is_json(): void
    {
        $hotel = $this->createHotel();
        $external = 'https://www.sanahotels.com/media/example/epic-sana.jpg';

        $this->withToken($this->plainToken)->patchJson('/api/agent/v1/properties/'.$hotel->id, [
            'thumbnail' => $external,
            'images' => [$external],
        ], $this->writeHeaders('property-images-valid'))->assertOk();

        $this->withToken($this->plainToken)
            ->getJson('/api/agent/v1/properties/'.$hotel->id)
            ->assertOk()
            ->assertJsonPath('data.thumbnail', $external)
            ->assertJsonPath('data.images.0', $external);

        $this->withToken($this->plainToken)->patchJson('/api/agent/v1/properties/'.$hotel->id, [
            'images' => [['url' => $external]],
        ], $this->writeHeaders('property-images-invalid'))
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['images.0']);
    }

    public function test_property_media_can_be_uploaded_attached_and_used_as_cover(): void
    {
        Storage::fake('agent_media');
        $hotel = $this->createHotel();

        $response = $this->withToken($this->plainToken)->post('/api/agent/v1/properties/'.$hotel->id.'/media', [
            'file' => UploadedFile::fake()->image('epic-sana.jpg', 1200, 800),
            'alt_text' => 'Exterior do EPIC SANA Luanda',
            'is_cover' => true,
        ], $this->writeHeaders('property-media-upload'));

        $response->assertCreated()->assertJsonPath('data.hotel_id', $hotel->id);
        $this->assertSame($response->json('data.url'), $hotel->fresh()->thumbnail);
        $this->assertContains($response->json('data.url'), $hotel->fresh()->images);

        $this->withToken($this->plainToken)
            ->getJson('/api/agent/v1/properties/'.$hotel->id.'/media')
            ->assertOk()
            ->assertJsonPath('data.0.is_cover', true);
    }

    public function test_property_room_types_support_crud_reorder_and_detailed_contract(): void
    {
        $hotel = $this->createHotel();
        $source = 'https://www.sanahotels.com/en/hotel/epic-sana-luanda/';

        $standard = $this->withToken($this->plainToken)->postJson('/api/agent/v1/properties/'.$hotel->id.'/room-types', [
            'name' => 'Standard Rooms',
            'description' => 'Quarto oficial do hotel.',
            'adult_capacity' => 2,
            'children_capacity' => 0,
            'beds' => 1,
            'bed_type' => 'King ou Twin',
            'size' => 32,
            'base_price' => 50000,
            'rooms_count' => 219,
            'images' => ['https://example.com/standard.jpg'],
            'source_url' => $source,
        ], $this->writeHeaders('room-type-standard'))->assertCreated();

        $suite = $this->withToken($this->plainToken)->postJson('/api/agent/v1/properties/'.$hotel->id.'/room-types', [
            'name' => 'Premier Plus Suite T1',
            'adult_capacity' => 2,
            'children_capacity' => 2,
            'size' => 71,
            'base_price' => 100000,
            'source_url' => $source,
        ], $this->writeHeaders('room-type-suite'))->assertCreated();

        $standardId = $standard->json('data.id');
        $suiteId = $suite->json('data.id');
        $this->assertSame(4, $suite->json('data.capacity'));

        $this->withToken($this->plainToken)
            ->getJson('/api/agent/v1/properties/'.$hotel->id.'/room-types')
            ->assertOk()
            ->assertJsonPath('data.0.size_unit', 'm2')
            ->assertJsonPath('data.1.children_capacity', 2);

        $this->withToken($this->plainToken)->patchJson('/api/agent/v1/properties/'.$hotel->id.'/room-types/'.$standardId, [
            'size' => 33,
        ], $this->writeHeaders('room-type-update'))->assertOk()->assertJsonPath('data.size', 33);

        $this->withToken($this->plainToken)->postJson('/api/agent/v1/properties/'.$hotel->id.'/room-types/reorder', [
            'room_type_ids' => [$suiteId, $standardId],
        ], $this->writeHeaders('room-type-reorder'))->assertOk()->assertJsonPath('data.0.id', $suiteId);

        $this->withToken($this->plainToken)->deleteJson('/api/agent/v1/properties/'.$hotel->id.'/room-types/'.$standardId, [
            'dry_run' => true,
        ], $this->writeHeaders('room-type-delete-preview'))->assertOk()->assertJsonPath('deleted', false);

        $this->withToken($this->plainToken)->deleteJson('/api/agent/v1/properties/'.$hotel->id.'/room-types/'.$standardId, [], array_merge(
            $this->writeHeaders('room-type-delete-final'), ['X-Confirm-Critical' => 'true']
        ))->assertOk()->assertJsonPath('deleted', true);

        $this->assertDatabaseHas('agent_audit_logs', ['event' => 'property.room_type.reordered']);
    }

    public function test_property_patch_rejects_room_types_instead_of_ignoring_them(): void
    {
        $hotel = $this->createHotel();

        $this->withToken($this->plainToken)->patchJson('/api/agent/v1/properties/'.$hotel->id, [
            'room_types' => [['name' => 'Não deve ser ignorado']],
        ], $this->writeHeaders('property-room-types-invalid'))
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['room_types'])
            ->assertJsonPath('errors.room_types.0', 'Use /properties/{id}/room-types para gerir tipos de quarto.');
    }

    private function writeHeaders(string $key): array
    {
        return [
            'X-Reason' => 'Teste automatizado da Agent API',
            'Idempotency-Key' => $key,
        ];
    }

    private function createHotel(): Hotel
    {
        $location = Location::firstOrCreate(
            ['slug' => 'luanda-agent-test'],
            ['name' => 'Luanda Agent Test', 'province' => 'luanda']
        );

        return Hotel::create([
            'name' => 'Hotel Agent '.uniqid(),
            'description' => 'Hotel criado para teste da Agent API.',
            'address' => 'Rua de Teste, Luanda',
            'location_id' => $location->id,
            'stars' => 5,
            'slug' => 'hotel-agent-'.uniqid(),
            'is_active' => true,
        ]);
    }
}

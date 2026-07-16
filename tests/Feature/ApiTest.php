<?php

namespace Tests\Feature;

use App\Models\Hotel;
use App\Models\Location;
use App\Models\Reservation;
use App\Models\RoomType;
use App\Models\Setting;
use App\Models\Webhook;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ApiTest extends TestCase
{
    use DatabaseTransactions;

    protected string $apiKey = 'okb_test_key_1234567890';

    protected function setUp(): void
    {
        parent::setUp();

        // Roles necessárias (o HotelObserver notifica admins).
        foreach (['Admin', 'Propriedade', 'User'] as $role) {
            \Spatie\Permission\Models\Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        Setting::set('api_key', $this->apiKey, 'general', 'string', 'API Key', false);
        Setting::clearCache();
    }

    private function makeHotel(array $attrs = []): Hotel
    {
        $location = Location::create([
            'name' => 'Luanda',
            'province' => 'luanda',
            'slug' => 'luanda',
            'is_active' => true,
        ]);

        $hotel = Hotel::create(array_merge([
            'name' => 'Hotel Teste',
            'slug' => 'hotel-teste',
            'address' => 'Rua de Teste, Luanda',
            'location_id' => $location->id,
            'property_type' => 'hotel',
            'stars' => 4,
            'rating' => 4.5,
            'min_price' => 20000,
            'is_active' => true,
        ], $attrs));

        RoomType::create([
            'hotel_id' => $hotel->id,
            'name' => 'Duplo',
            'capacity' => 2,
            'base_price' => 20000,
            'is_available' => true,
        ]);

        return $hotel;
    }

    public function test_status_endpoint_is_public(): void
    {
        $this->getJson('/api/v1/status')
            ->assertOk()
            ->assertJson(['status' => 'ok', 'version' => 'v1']);
    }

    public function test_hotels_list_returns_data(): void
    {
        $this->makeHotel();

        $this->getJson('/api/v1/hotels')
            ->assertOk()
            ->assertJsonStructure(['data' => [['id', 'name', 'slug', 'rating', 'min_price', 'location']]])
            ->assertJsonFragment(['slug' => 'hotel-teste']);
    }

    public function test_hotels_can_be_filtered_by_province(): void
    {
        $this->makeHotel();

        $this->getJson('/api/v1/hotels?province=luanda')->assertOk()->assertJsonFragment(['name' => 'Hotel Teste']);
        $this->getJson('/api/v1/hotels?province=benguela')->assertOk()->assertJsonCount(0, 'data');
    }

    public function test_hotel_show_returns_room_types(): void
    {
        $this->makeHotel();

        $this->getJson('/api/v1/hotels/hotel-teste')
            ->assertOk()
            ->assertJsonPath('data.slug', 'hotel-teste')
            ->assertJsonStructure(['data' => ['room_types' => [['id', 'name', 'base_price']]]]);
    }

    public function test_rating_is_on_0_to_5_scale(): void
    {
        $this->makeHotel(['rating' => 4.5]);

        $this->getJson('/api/v1/hotels/hotel-teste')
            ->assertJsonPath('data.rating', 4.5);
    }

    public function test_booking_requires_api_key(): void
    {
        $this->postJson('/api/v1/bookings', [])->assertStatus(401);
    }

    public function test_booking_can_be_created_with_api_key(): void
    {
        $hotel = $this->makeHotel();
        $roomType = $hotel->roomTypes()->first();

        $response = $this->withHeaders(['X-API-Key' => $this->apiKey])
            ->postJson('/api/v1/bookings', [
                'hotel_id' => $hotel->id,
                'room_type_id' => $roomType->id,
                'check_in' => now()->addDays(5)->toDateString(),
                'check_out' => now()->addDays(7)->toDateString(),
                'guests' => 2,
                'customer_name' => 'Ana Silva',
                'customer_email' => 'ana@exemplo.com',
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['data' => ['id', 'confirmation_code', 'status']]);

        $this->assertDatabaseHas('reservations', [
            'hotel_id' => $hotel->id,
            'status' => 'pending',
        ]);
    }

    public function test_booking_validation_fails_with_bad_dates(): void
    {
        $hotel = $this->makeHotel();
        $roomType = $hotel->roomTypes()->first();

        $this->withHeaders(['X-API-Key' => $this->apiKey])
            ->postJson('/api/v1/bookings', [
                'hotel_id' => $hotel->id,
                'room_type_id' => $roomType->id,
                'check_in' => now()->addDays(7)->toDateString(),
                'check_out' => now()->addDays(5)->toDateString(), // antes do check-in
                'guests' => 2,
                'customer_name' => 'Ana',
                'customer_email' => 'ana@exemplo.com',
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors('check_out');
    }

    public function test_webhook_can_be_registered(): void
    {
        $this->withHeaders(['X-API-Key' => $this->apiKey])
            ->postJson('/api/v1/webhooks', [
                'url' => 'https://exemplo.com/hook',
                'events' => ['reservation.created'],
                'name' => 'Teste',
            ])
            ->assertStatus(201)
            ->assertJsonStructure(['id', 'secret', 'events']);

        $this->assertDatabaseHas('webhooks', ['url' => 'https://exemplo.com/hook']);
    }

    public function test_webhook_rejects_invalid_event(): void
    {
        $this->withHeaders(['X-API-Key' => $this->apiKey])
            ->postJson('/api/v1/webhooks', [
                'url' => 'https://exemplo.com/hook',
                'events' => ['evento.invalido'],
            ])
            ->assertStatus(422);
    }

    public function test_booking_can_be_cancelled(): void
    {
        $hotel = $this->makeHotel();
        $roomType = $hotel->roomTypes()->first();

        $code = $this->withHeaders(['X-API-Key' => $this->apiKey])
            ->postJson('/api/v1/bookings', [
                'hotel_id' => $hotel->id,
                'room_type_id' => $roomType->id,
                'check_in' => now()->addDays(5)->toDateString(),
                'check_out' => now()->addDays(7)->toDateString(),
                'guests' => 2,
                'customer_name' => 'Ana',
                'customer_email' => 'ana@exemplo.com',
            ])->json('data.confirmation_code');

        $this->withHeaders(['X-API-Key' => $this->apiKey])
            ->postJson("/api/v1/bookings/{$code}/cancel", ['reason' => 'teste'])
            ->assertOk()
            ->assertJsonPath('data.status', 'cancelled');

        // Segunda tentativa é rejeitada
        $this->withHeaders(['X-API-Key' => $this->apiKey])
            ->postJson("/api/v1/bookings/{$code}/cancel")
            ->assertStatus(422);
    }
}

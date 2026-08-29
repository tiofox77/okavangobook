<?php

namespace Tests\Feature;

use App\Events\ReservationStatusChanged;
use App\Livewire\Admin\ReservationCreation;
use App\Models\Hotel;
use App\Models\Location;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\User;
use App\Models\Webhook;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ReservationFlowTest extends TestCase
{
    use DatabaseTransactions;

    private User $customer;
    private Hotel $hotel;
    private RoomType $roomType;
    private Room $room;

    protected function setUp(): void
    {
        parent::setUp();
        foreach (['Admin', 'Propriedade', 'User'] as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $admin = User::create(['name' => 'Admin', 'email' => 'a-' . uniqid() . '@ex.com', 'password' => bcrypt('x')]);
        $admin->assignRole('Admin');
        $this->actingAs($admin);

        $this->customer = User::create(['name' => 'Cliente', 'email' => 'c-' . uniqid() . '@ex.com', 'password' => bcrypt('x')]);

        $loc = Location::create(['name' => 'Luanda', 'province' => 'luanda', 'slug' => 'l-' . uniqid(), 'is_active' => true]);
        $this->hotel = Hotel::create(['name' => 'Hotel ' . uniqid(), 'address' => 'A', 'location_id' => $loc->id, 'property_type' => 'hotel', 'is_active' => true]);
        $this->roomType = RoomType::create(['hotel_id' => $this->hotel->id, 'name' => 'Duplo', 'capacity' => 2, 'base_price' => 30000, 'is_available' => true]);
        $this->room = Room::create(['hotel_id' => $this->hotel->id, 'room_number' => '101']);
    }

    public function test_admin_can_create_a_reservation(): void
    {
        // Datas/hóspedes primeiro (mudá-los limpa a seleção de quarto), depois o quarto.
        Livewire::test(ReservationCreation::class)
            ->set('userId', $this->customer->id)
            ->set('hotelId', $this->hotel->id)
            ->set('checkIn', now()->addDays(3)->toDateString())
            ->set('checkOut', now()->addDays(5)->toDateString())
            ->set('guests', 2)
            ->set('roomTypeId', $this->roomType->id)
            ->set('roomId', $this->room->id)
            ->set('totalPrice', 60000)
            ->set('paymentMethod', 'cash')
            ->call('createReservation')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('reservations', [
            'hotel_id' => $this->hotel->id,
            'user_id' => $this->customer->id,
            'room_id' => $this->room->id,
            'guests' => 2,
        ]);

        $reservation = Reservation::where('hotel_id', $this->hotel->id)->first();
        $this->assertNotEmpty($reservation->confirmation_code);
    }

    public function test_reservation_creation_validates_dates(): void
    {
        Livewire::test(ReservationCreation::class)
            ->set('userId', $this->customer->id)
            ->set('hotelId', $this->hotel->id)
            ->set('roomTypeId', $this->roomType->id)
            ->set('roomId', $this->room->id)
            ->set('checkIn', now()->addDays(5)->toDateString())
            ->set('checkOut', now()->addDays(3)->toDateString()) // antes do check-in
            ->set('guests', 2)
            ->set('totalPrice', 60000)
            ->set('paymentMethod', 'cash')
            ->call('createReservation')
            ->assertHasErrors('checkOut');
    }

    public function test_status_change_to_cancelled_fires_webhook(): void
    {
        Http::fake(['*' => Http::response('', 200)]);

        Webhook::create(['url' => 'https://ex.com/hook', 'events' => ['reservation.cancelled'], 'is_active' => true]);

        $reservation = Reservation::create([
            'user_id' => $this->customer->id, 'hotel_id' => $this->hotel->id,
            'room_type_id' => $this->roomType->id, 'check_in' => now()->addDays(3),
            'check_out' => now()->addDays(5), 'guests' => 2, 'total_price' => 60000,
            'status' => 'confirmed', 'payment_status' => 'pending', 'confirmation_code' => 'OKB-XYZ',
        ]);

        event(new ReservationStatusChanged($reservation, 'confirmed', 'cancelled'));

        Http::assertSent(fn ($r) => $r->url() === 'https://ex.com/hook'
            && str_contains($r->body(), 'reservation.cancelled'));
    }

    public function test_confirmation_page_accepts_route_model_binding(): void
    {
        $reservation = Reservation::create([
            'user_id' => $this->customer->id,
            'hotel_id' => $this->hotel->id,
            'room_type_id' => $this->roomType->id,
            'check_in' => now()->addDays(3),
            'check_out' => now()->addDays(5),
            'guests' => 2,
            'total_price' => 60000,
            'status' => 'pending',
            'payment_status' => 'pending',
            'confirmation_code' => 'OKB-CONFIRM',
        ]);

        $this->get(route('booking.confirm', $reservation))
            ->assertSuccessful()
            ->assertSee('OKB-CONFIRM')
            ->assertDontSee('Teste de Debug');
    }

}

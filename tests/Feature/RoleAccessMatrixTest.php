<?php

namespace Tests\Feature;

use App\Livewire\Admin\RoomManagement;
use App\Livewire\Admin\IndividualRoomManagement;
use App\Livewire\Admin\LeisureFacilitiesManagement;
use App\Livewire\Admin\RestaurantManagement;
use App\Models\Hotel;
use App\Models\Location;
use App\Models\RoomType;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RoleAccessMatrixTest extends TestCase
{
    use DatabaseTransactions;

    private const SHARED_ROUTES = [
        'admin.dashboard',
        'admin.hotels',
        'admin.rooms',
        'admin.individual-rooms',
        'admin.amenities',
        'admin.restaurant',
        'admin.leisure',
        'admin.reservations',
        'admin.reservations.create',
        'admin.notifications',
        'admin.profile',
        'admin.my-subscription',
    ];

    private const ADMIN_ROUTES = [
        'admin.users',
        'admin.locations',
        'admin.coupons',
        'admin.newsletter',
        'admin.newsletter.send',
        'admin.analytics',
        'admin.articles',
        'admin.reports.reservations',
        'admin.settings',
        'admin.updates',
        'admin.plans',
        'admin.payments',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['Admin', 'Propriedade', 'User'] as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }

    private function userWithRole(string $role): User
    {
        $user = User::create([
            'name' => "{$role} Teste",
            'email' => strtolower($role) . '-' . uniqid() . '@example.test',
            'password' => bcrypt('secret123'),
        ]);
        $user->assignRole($role);

        return $user;
    }

    private function hotelFor(User $owner, string $name): Hotel
    {
        $location = Location::create([
            'name' => 'Luanda',
            'province' => 'Luanda',
            'slug' => 'luanda-' . uniqid(),
            'is_active' => true,
        ]);

        return Hotel::create([
            'user_id' => $owner->id,
            'location_id' => $location->id,
            'name' => $name,
            'address' => 'Luanda',
            'property_type' => 'hotel',
            'is_active' => true,
        ]);
    }

    public function test_guests_are_redirected_from_every_backend_page(): void
    {
        foreach ([...self::SHARED_ROUTES, ...self::ADMIN_ROUTES] as $routeName) {
            $this->get(route($routeName))
                ->assertRedirect(route('login'));
        }
    }

    public function test_normal_users_are_forbidden_from_every_backend_page(): void
    {
        $user = $this->userWithRole('User');

        foreach ([...self::SHARED_ROUTES, ...self::ADMIN_ROUTES] as $routeName) {
            $this->actingAs($user)->get(route($routeName))->assertForbidden();
        }
    }

    public function test_property_role_can_open_shared_pages_but_not_admin_only_pages(): void
    {
        $owner = $this->userWithRole('Propriedade');
        $this->hotelFor($owner, 'Hotel do Proprietário');

        foreach (self::SHARED_ROUTES as $routeName) {
            $this->actingAs($owner)->get(route($routeName))
                ->assertSuccessful();
        }

        foreach (self::ADMIN_ROUTES as $routeName) {
            $this->actingAs($owner)->get(route($routeName))
                ->assertForbidden();
        }
    }

    public function test_admin_can_open_all_backend_pages(): void
    {
        $admin = $this->userWithRole('Admin');

        foreach ([...self::SHARED_ROUTES, ...self::ADMIN_ROUTES] as $routeName) {
            $response = $this->actingAs($admin)->get(route($routeName));
            $this->assertContains(
                $response->status(),
                [200, 302],
                "{$routeName} devolveu {$response->status()}."
            );
        }
    }

    public function test_property_cannot_create_edit_view_or_delete_room_types_of_another_owner(): void
    {
        $owner = $this->userWithRole('Propriedade');
        $otherOwner = $this->userWithRole('Propriedade');
        $this->hotelFor($owner, 'Hotel Próprio');
        $otherHotel = $this->hotelFor($otherOwner, 'Hotel Alheio');
        $otherRoom = RoomType::create([
            'hotel_id' => $otherHotel->id,
            'name' => 'Suite Alheia',
            'capacity' => 2,
            'beds' => 1,
            'base_price' => 50000,
            'rooms_count' => 1,
            'is_available' => true,
        ]);

        $this->actingAs($owner);

        Livewire::test(RoomManagement::class)
            ->set('form_hotel_id', $otherHotel->id)
            ->set('name', 'Quarto Forjado')
            ->set('capacity', 2)
            ->set('beds', 1)
            ->set('base_price', 10000)
            ->set('rooms_count', 1)
            ->call('save')
            ->assertForbidden();

        Livewire::test(RoomManagement::class)->call('view', $otherRoom->id)->assertForbidden();
        Livewire::test(RoomManagement::class)->call('edit', $otherRoom->id)->assertForbidden();
        Livewire::test(RoomManagement::class)->call('delete', $otherRoom->id)->assertForbidden();

        $this->assertDatabaseMissing('room_types', ['name' => 'Quarto Forjado']);
        $this->assertDatabaseHas('room_types', ['id' => $otherRoom->id]);
    }

    public function test_property_cannot_create_operational_data_for_another_hotel(): void
    {
        $owner = $this->userWithRole('Propriedade');
        $otherOwner = $this->userWithRole('Propriedade');
        $this->hotelFor($owner, 'Hotel Próprio');
        $otherHotel = $this->hotelFor($otherOwner, 'Hotel Alheio');
        $otherRoomType = RoomType::create([
            'hotel_id' => $otherHotel->id,
            'name' => 'Standard Alheio',
            'capacity' => 2,
            'beds' => 1,
            'base_price' => 20000,
            'rooms_count' => 2,
            'is_available' => true,
        ]);

        $this->actingAs($owner);

        Livewire::test(IndividualRoomManagement::class)
            ->set('form_hotel_id', $otherHotel->id)
            ->set('form_room_type_id', $otherRoomType->id)
            ->set('room_number', 'X-101')
            ->set('form_status', 'available')
            ->call('save')
            ->assertForbidden();

        Livewire::test(RestaurantManagement::class)
            ->set('hotel_id', $otherHotel->id)
            ->set('name', 'Prato Forjado')
            ->set('category', 'Prato Principal')
            ->set('price', 1000)
            ->call('save')
            ->assertForbidden();

        Livewire::test(LeisureFacilitiesManagement::class)
            ->set('hotel_id', $otherHotel->id)
            ->set('name', 'Piscina Forjada')
            ->set('type', 'piscina')
            ->call('save')
            ->assertForbidden();

        $this->assertDatabaseMissing('rooms', ['room_number' => 'X-101']);
        $this->assertDatabaseMissing('hotel_restaurant_items', ['name' => 'Prato Forjado']);
        $this->assertDatabaseMissing('hotel_leisure_facilities', ['name' => 'Piscina Forjada']);
    }
}

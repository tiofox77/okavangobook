<?php

namespace Tests\Feature;

use App\Livewire\Admin\HotelManagement;
use App\Models\Hotel;
use App\Models\Location;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class HotelCreationTest extends TestCase
{
    use DatabaseTransactions;

    private Location $location;

    protected function setUp(): void
    {
        parent::setUp();
        foreach (['Admin', 'Propriedade', 'User'] as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin-' . uniqid() . '@ex.com',
            'password' => bcrypt('x'),
        ]);
        $admin->assignRole('Admin');
        $this->actingAs($admin);

        $this->location = Location::create([
            'name' => 'Luanda', 'province' => 'luanda', 'slug' => 'luanda-' . uniqid(), 'is_active' => true,
        ]);
    }

    private function createHotel(string $name, string $type = 'hotel')
    {
        return Livewire::test(HotelManagement::class)
            ->set('name', $name)
            ->set('property_type', $type)
            ->set('description', 'Descrição de teste para ' . $name)
            ->set('locationId', $this->location->id)
            ->set('address', 'Rua de Teste')
            ->set('is_active', true)
            ->call('save');
    }

    public function test_admin_can_create_a_hotel(): void
    {
        $this->createHotel('Hotel Alfa')->assertHasNoErrors();

        $hotel = Hotel::where('name', 'Hotel Alfa')->first();
        $this->assertNotNull($hotel);
        $this->assertEquals('hotel-alfa', $hotel->slug);
        $this->assertEquals($this->location->id, $hotel->location_id);
        $this->assertTrue((bool) $hotel->is_active);
    }

    public function test_creating_requires_name_and_description(): void
    {
        Livewire::test(HotelManagement::class)
            ->set('name', '')
            ->set('description', '')
            ->set('locationId', $this->location->id)
            ->call('save')
            ->assertHasErrors(['name', 'description']);
    }

    public function test_duplicate_names_get_unique_slugs(): void
    {
        $this->createHotel('Hotel Repetido')->assertHasNoErrors();
        $this->createHotel('Hotel Repetido')->assertHasNoErrors();
        $this->createHotel('Hotel Repetido')->assertHasNoErrors();

        $slugs = Hotel::where('name', 'Hotel Repetido')->pluck('slug')->toArray();
        $this->assertCount(3, $slugs);
        $this->assertCount(3, array_unique($slugs), 'Os slugs devem ser todos distintos.');
        $this->assertContains('hotel-repetido', $slugs);
    }

    public function test_multiple_hotels_of_all_types_coexist(): void
    {
        $this->createHotel('City Hotel', 'hotel')->assertHasNoErrors();
        $this->createHotel('Beach Resort', 'resort')->assertHasNoErrors();
        $this->createHotel('Casa Familiar', 'hospedaria')->assertHasNoErrors();

        $this->assertDatabaseHas('hotels', ['name' => 'City Hotel', 'property_type' => 'hotel']);
        $this->assertDatabaseHas('hotels', ['name' => 'Beach Resort', 'property_type' => 'resort']);
        $this->assertDatabaseHas('hotels', ['name' => 'Casa Familiar', 'property_type' => 'hospedaria']);

        // Cada tipo é distinguível (usado pelos filtros do site)
        $this->assertEquals(1, Hotel::where('property_type', 'resort')->where('name', 'Beach Resort')->count());
    }

    public function test_created_hotels_are_visible_via_public_api(): void
    {
        $this->createHotel('Hotel Público')->assertHasNoErrors();

        \App\Models\Setting::set('api_key', 'k', 'general', 'string', 'x', false);
        \App\Models\Setting::clearCache();

        $this->getJson('/api/v1/hotels?q=Hotel Público')
            ->assertOk()
            ->assertJsonFragment(['name' => 'Hotel Público']);
    }
}

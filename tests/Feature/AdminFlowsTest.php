<?php

namespace Tests\Feature;

use App\Livewire\Admin\CouponManagement;
use App\Livewire\Admin\RoomManagement;
use App\Models\Coupon;
use App\Models\Hotel;
use App\Models\Location;
use App\Models\RoomType;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminFlowsTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        foreach (['Admin', 'Propriedade', 'User'] as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $admin = User::create(['name' => 'Admin', 'email' => 'admin-' . uniqid() . '@ex.com', 'password' => bcrypt('x')]);
        $admin->assignRole('Admin');
        $this->actingAs($admin);
    }

    private function hotel(): Hotel
    {
        $loc = Location::create(['name' => 'Luanda', 'province' => 'luanda', 'slug' => 'l-' . uniqid(), 'is_active' => true]);
        return Hotel::create([
            'name' => 'Hotel ' . uniqid(), 'address' => 'A', 'location_id' => $loc->id,
            'property_type' => 'hotel', 'is_active' => true,
        ]);
    }

    // ---------- Tipos de quarto ----------

    public function test_admin_can_create_room_type(): void
    {
        $hotel = $this->hotel();

        Livewire::test(RoomManagement::class)
            ->set('form_hotel_id', $hotel->id)
            ->set('name', 'Quarto Duplo')
            ->set('capacity', 2)
            ->set('beds', 1)
            ->set('base_price', 25000)
            ->set('rooms_count', 5)
            ->set('is_available', true)
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('room_types', [
            'hotel_id' => $hotel->id, 'name' => 'Quarto Duplo', 'capacity' => 2, 'base_price' => 25000,
        ]);
    }

    public function test_room_type_requires_hotel_and_name(): void
    {
        Livewire::test(RoomManagement::class)
            ->set('name', '')
            ->call('save')
            ->assertHasErrors(['form_hotel_id', 'name']);
    }

    public function test_hotel_can_have_multiple_room_types(): void
    {
        $hotel = $this->hotel();
        foreach ([['Standard', 15000, 2], ['Suite', 40000, 4], ['Família', 60000, 6]] as [$name, $price, $cap]) {
            Livewire::test(RoomManagement::class)
                ->set('form_hotel_id', $hotel->id)->set('name', $name)
                ->set('capacity', $cap)->set('beds', 2)->set('base_price', $price)->set('rooms_count', 3)
                ->set('is_available', true)->call('save')->assertHasNoErrors();
        }

        $this->assertEquals(3, RoomType::where('hotel_id', $hotel->id)->count());
    }

    // ---------- Cupões ----------

    public function test_admin_can_create_coupon(): void
    {
        Livewire::test(CouponManagement::class)
            ->set('code', 'verao25')
            ->set('type', 'percentage')
            ->set('value', 25)
            ->set('is_active', true)
            ->call('save')
            ->assertHasNoErrors();

        // O código é gravado em maiúsculas
        $this->assertDatabaseHas('coupons', ['code' => 'VERAO25', 'type' => 'percentage', 'value' => 25]);
    }

    public function test_coupon_requires_valid_type(): void
    {
        Livewire::test(CouponManagement::class)
            ->set('code', 'X')
            ->set('type', 'invalido')
            ->set('value', 10)
            ->call('save')
            ->assertHasErrors('type');
    }
}

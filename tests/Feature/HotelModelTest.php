<?php

namespace Tests\Feature;

use App\Models\Hotel;
use App\Models\Location;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class HotelModelTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        foreach (['Admin', 'Propriedade', 'User'] as $role) {
            \Spatie\Permission\Models\Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }
    }

    private function location(): Location
    {
        return Location::create(['name' => 'Luanda', 'province' => 'luanda', 'slug' => 'luanda-' . uniqid(), 'is_active' => true]);
    }

    public function test_slug_is_generated_when_missing(): void
    {
        $hotel = Hotel::create([
            'name' => 'Meu Hotel Teste',
            'address' => 'Rua X',
            'location_id' => $this->location()->id,
        ]);

        $this->assertNotEmpty($hotel->slug);
        $this->assertStringStartsWith('meu-hotel-teste', $hotel->slug);
    }

    public function test_slug_is_unique(): void
    {
        $loc = $this->location();
        $a = Hotel::create(['name' => 'Hotel Repetido', 'address' => 'A', 'location_id' => $loc->id]);
        $b = Hotel::create(['name' => 'Hotel Repetido', 'address' => 'B', 'location_id' => $loc->id]);

        $this->assertNotEquals($a->slug, $b->slug);
    }

    public function test_provided_slug_is_kept(): void
    {
        $hotel = Hotel::create([
            'name' => 'Hotel Com Slug',
            'slug' => 'slug-personalizado',
            'address' => 'A',
            'location_id' => $this->location()->id,
        ]);

        $this->assertEquals('slug-personalizado', $hotel->slug);
    }
}

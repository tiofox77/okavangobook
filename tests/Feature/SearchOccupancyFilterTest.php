<?php

namespace Tests\Feature;

use App\Livewire\SearchForm;
use App\Livewire\SearchResults;
use App\Models\Hotel;
use App\Models\Location;
use App\Models\RoomType;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Tests\TestCase;

class SearchOccupancyFilterTest extends TestCase
{
    use DatabaseTransactions;

    public function test_home_controls_update_guests_and_rooms_within_limits(): void
    {
        Livewire::test(SearchForm::class)
            ->assertSet('guests', 2)
            ->assertSet('rooms', 1)
            ->call('incrementGuests')
            ->call('incrementRooms')
            ->assertSet('guests', 3)
            ->assertSet('rooms', 2)
            ->assertSee('3 hóspedes')
            ->assertSee('2 quartos')
            ->set('guests', 99)
            ->set('rooms', 99)
            ->call('search')
            ->assertSet('guests', 10)
            ->assertSet('rooms', 5);
    }

    public function test_results_only_include_hotels_with_enough_rooms_and_capacity(): void
    {
        $location = Location::create([
            'name' => 'Destino Capacidade ' . uniqid(),
            'province' => 'luanda',
            'slug' => 'capacidade-' . uniqid(),
            'is_active' => true,
        ]);

        $smallHotel = $this->hotel($location, 'Hotel Pequeno ' . uniqid());
        $familyHotel = $this->hotel($location, 'Hotel Familiar ' . uniqid());

        $this->roomType($smallHotel, capacity: 2, roomsCount: 1);
        $this->roomType($familyHotel, capacity: 2, roomsCount: 3);

        Livewire::test(SearchResults::class, [
            'location_id' => $location->id,
            'guests' => 5,
            'rooms' => 2,
        ])->assertViewHas('searchResults', function ($results) use ($smallHotel, $familyHotel) {
            $ids = $results->getCollection()->pluck('id');

            return !$ids->contains($smallHotel->id) && $ids->contains($familyHotel->id);
        });

        Livewire::test(SearchResults::class, [
            'location_id' => $location->id,
            'guests' => 7,
            'rooms' => 2,
        ])->assertViewHas('searchResults', fn ($results) => $results->total() === 0);
    }

    private function hotel(Location $location, string $name): Hotel
    {
        return Hotel::create([
            'name' => $name,
            'address' => 'Rua de teste',
            'location_id' => $location->id,
            'is_active' => true,
        ]);
    }

    private function roomType(Hotel $hotel, int $capacity, int $roomsCount): RoomType
    {
        return RoomType::create([
            'hotel_id' => $hotel->id,
            'name' => 'Quarto ' . uniqid(),
            'capacity' => $capacity,
            'beds' => 1,
            'base_price' => 20000,
            'rooms_count' => $roomsCount,
            'is_available' => true,
        ]);
    }
}

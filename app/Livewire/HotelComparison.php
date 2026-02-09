<?php

namespace App\Livewire;

use App\Models\Hotel;
use Livewire\Component;

class HotelComparison extends Component
{
    public $hotelIds = [];
    public $hotels = [];

    public function mount()
    {
        // Carregar IDs da sessão
        $this->hotelIds = session('compare_hotels', []);
        $this->loadHotels();
    }

    public function loadHotels()
    {
        if (count($this->hotelIds) > 0) {
            $this->hotels = Hotel::with(['location', 'roomTypes'])
                ->whereIn('id', $this->hotelIds)
                ->get()
                ->toArray();
        } else {
            $this->hotels = [];
        }
    }

    public function addHotel($hotelId)
    {
        if (!in_array($hotelId, $this->hotelIds) && count($this->hotelIds) < 4) {
            $this->hotelIds[] = $hotelId;
            session(['compare_hotels' => $this->hotelIds]);
            $this->loadHotels();
            session()->flash('message', 'Hotel adicionado à comparação!');
        } elseif (count($this->hotelIds) >= 4) {
            session()->flash('error', 'Você pode comparar no máximo 4 hotéis de cada vez.');
        }
    }

    public function removeHotel($hotelId)
    {
        $this->hotelIds = array_values(array_filter($this->hotelIds, fn($id) => $id != $hotelId));
        session(['compare_hotels' => $this->hotelIds]);
        $this->loadHotels();
        session()->flash('message', 'Hotel removido da comparação.');
    }

    public function clearAll()
    {
        $this->hotelIds = [];
        $this->hotels = [];
        session()->forget('compare_hotels');
        session()->flash('message', 'Comparação limpa.');
    }

    public function render()
    {
        return view('livewire.hotel-comparison', [
            'hotels' => $this->hotels,
        ])->layout('layouts.app');
    }
}

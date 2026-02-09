<?php

namespace App\Livewire;

use App\Models\PriceAlert;
use App\Models\Hotel;
use App\Models\RoomType;
use Livewire\Component;
use Livewire\WithPagination;

class PriceAlerts extends Component
{
    use WithPagination;

    public $showModal = false;
    public $hotel_id;
    public $room_type_id;
    public $target_price;
    public $check_in;
    public $check_out;
    public $guests = 1;
    
    public $hotels = [];
    public $roomTypes = [];

    protected $rules = [
        'hotel_id' => 'required|exists:hotels,id',
        'room_type_id' => 'nullable|exists:room_types,id',
        'target_price' => 'required|numeric|min:1',
        'check_in' => 'nullable|date',
        'check_out' => 'nullable|date|after:check_in',
        'guests' => 'required|integer|min:1',
    ];

    public function mount()
    {
        $this->hotels = Hotel::select('id', 'name')->orderBy('name')->get();
    }

    public function updatedHotelId($value)
    {
        if ($value) {
            $this->roomTypes = RoomType::where('hotel_id', $value)
                ->select('id', 'name', 'base_price')
                ->orderBy('name')
                ->get();
        } else {
            $this->roomTypes = [];
        }
    }

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        PriceAlert::create([
            'user_id' => auth()->id(),
            'hotel_id' => $this->hotel_id,
            'room_type_id' => $this->room_type_id,
            'target_price' => $this->target_price,
            'check_in' => $this->check_in,
            'check_out' => $this->check_out,
            'guests' => $this->guests,
            'is_active' => true,
        ]);

        session()->flash('message', 'Alerta de preço criado com sucesso!');
        $this->closeModal();
    }

    public function delete($id)
    {
        PriceAlert::where('id', $id)
            ->where('user_id', auth()->id())
            ->delete();
        
        session()->flash('message', 'Alerta removido!');
    }

    public function toggleStatus($id)
    {
        $alert = PriceAlert::where('id', $id)
            ->where('user_id', auth()->id())
            ->first();
        
        if ($alert) {
            $alert->update(['is_active' => !$alert->is_active]);
            session()->flash('message', 'Status atualizado!');
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->hotel_id = null;
        $this->room_type_id = null;
        $this->target_price = null;
        $this->check_in = null;
        $this->check_out = null;
        $this->guests = 1;
        $this->roomTypes = [];
        $this->resetValidation();
    }

    public function render()
    {
        $alerts = PriceAlert::with(['hotel', 'roomType'])
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('livewire.price-alerts', [
            'alerts' => $alerts,
        ])->layout('layouts.app');
    }
}

<?php

namespace App\Livewire\Admin;

use App\Models\Hotel;
use App\Models\HotelLeisureFacility;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class LeisureFacilitiesManagement extends Component
{
    use WithPagination, WithFileUploads;

    public $facilityId = null;
    public $hotel_id = null;
    public $name = '';
    public $type = '';
    public $description = '';
    public $images = [];
    public $imagesUpload = [];
    public $is_available = true;
    public $is_free = true;
    public $price_per_hour = null;
    public $daily_price = null;
    public $opening_time = null;
    public $closing_time = null;
    public $operating_days = [];
    public $capacity = null;
    public $requires_booking = false;
    public $rules = '';
    public $location = '';
    public $display_order = 0;

    public $search = '';
    public $filterHotel = null;
    public $filterType = null;
    public $filterAvailability = null;

    public $showModal = false;
    public $showDeleteModal = false;
    public $facilityToDelete = null;

    protected $facilityTypes = [
        'piscina' => 'Piscina',
        'spa' => 'Spa',
        'ginasio' => 'Ginásio',
        'sauna' => 'Sauna',
        'campo_tenis' => 'Campo de Ténis',
        'sala_jogos' => 'Sala de Jogos',
        'biblioteca' => 'Biblioteca',
        'jardim' => 'Jardim',
        'bar' => 'Bar',
        'sala_conferencias' => 'Sala de Conferências',
        'parque_infantil' => 'Parque Infantil',
        'quadra_esportes' => 'Quadra de Esportes',
    ];

    protected $daysOfWeek = [
        'monday' => 'Segunda',
        'tuesday' => 'Terça',
        'wednesday' => 'Quarta',
        'thursday' => 'Quinta',
        'friday' => 'Sexta',
        'saturday' => 'Sábado',
        'sunday' => 'Domingo',
    ];

    public function mount()
    {
        if (!auth()->check()) {
            abort(401, 'Não autenticado.');
        }

        $user = auth()->user();
        if (!$user->hasRole('Admin') && !$user->managedHotels()->exists()) {
            abort(403, 'Não tem permissão para aceder a esta página.');
        }
    }

    protected function rules()
    {
        return [
            'hotel_id' => 'required|exists:hotels,id',
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'description' => 'nullable|string',
            'imagesUpload.*' => 'nullable|image|max:2048',
            'is_available' => 'boolean',
            'is_free' => 'boolean',
            'price_per_hour' => 'nullable|numeric|min:0',
            'daily_price' => 'nullable|numeric|min:0',
            'opening_time' => 'nullable|date_format:H:i',
            'closing_time' => 'nullable|date_format:H:i',
            'capacity' => 'nullable|integer|min:1',
            'requires_booking' => 'boolean',
            'rules' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'display_order' => 'integer',
        ];
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterHotel()
    {
        $this->resetPage();
    }

    public function updatedFilterType()
    {
        $this->resetPage();
    }

    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit($id)
    {
        $facility = HotelLeisureFacility::findOrFail($id);

        $user = auth()->user();
        if (!$user->hasRole('Admin') && $facility->hotel->user_id !== $user->id) {
            session()->flash('error', 'Não tem permissão para editar esta instalação.');
            return;
        }

        $this->facilityId = $facility->id;
        $this->hotel_id = $facility->hotel_id;
        $this->name = $facility->name;
        $this->type = $facility->type;
        $this->description = $facility->description;
        $this->images = $facility->images ?? [];
        $this->is_available = $facility->is_available;
        $this->is_free = $facility->is_free;
        $this->price_per_hour = $facility->price_per_hour;
        $this->daily_price = $facility->daily_price;
        $this->opening_time = $facility->opening_time ? substr($facility->opening_time, 0, 5) : null;
        $this->closing_time = $facility->closing_time ? substr($facility->closing_time, 0, 5) : null;
        $this->operating_days = $facility->operating_days ?? [];
        $this->capacity = $facility->capacity;
        $this->requires_booking = $facility->requires_booking;
        $this->rules = $facility->rules;
        $this->location = $facility->location;
        $this->display_order = $facility->display_order;

        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $imagePaths = $this->images;

        if (!empty($this->imagesUpload)) {
            foreach ($this->imagesUpload as $image) {
                $imagePaths[] = $image->store('leisure-facilities', 'public');
            }
        }

        $data = [
            'hotel_id' => $this->hotel_id,
            'name' => $this->name,
            'type' => $this->type,
            'description' => $this->description,
            'images' => $imagePaths,
            'is_available' => $this->is_available,
            'is_free' => $this->is_free,
            'price_per_hour' => $this->price_per_hour,
            'daily_price' => $this->daily_price,
            'opening_time' => $this->opening_time,
            'closing_time' => $this->closing_time,
            'operating_days' => $this->operating_days,
            'capacity' => $this->capacity,
            'requires_booking' => $this->requires_booking,
            'rules' => $this->rules,
            'location' => $this->location,
            'display_order' => $this->display_order,
        ];

        if ($this->facilityId) {
            HotelLeisureFacility::find($this->facilityId)->update($data);
            session()->flash('message', 'Instalação atualizada com sucesso!');
        } else {
            HotelLeisureFacility::create($data);
            session()->flash('message', 'Instalação criada com sucesso!');
        }

        $this->closeModal();
    }

    public function removeImage($index)
    {
        if (isset($this->images[$index])) {
            Storage::disk('public')->delete($this->images[$index]);
            unset($this->images[$index]);
            $this->images = array_values($this->images);
        }
    }

    public function confirmDelete($id)
    {
        $this->facilityToDelete = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        if ($this->facilityToDelete) {
            $facility = HotelLeisureFacility::findOrFail($this->facilityToDelete);
            
            $user = auth()->user();
            if (!$user->hasRole('Admin') && $facility->hotel->user_id !== $user->id) {
                session()->flash('error', 'Não tem permissão para eliminar esta instalação.');
                $this->showDeleteModal = false;
                return;
            }

            if (!empty($facility->images)) {
                foreach ($facility->images as $image) {
                    Storage::disk('public')->delete($image);
                }
            }

            $facility->delete();
            session()->flash('message', 'Instalação eliminada com sucesso!');
        }

        $this->showDeleteModal = false;
        $this->facilityToDelete = null;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->facilityId = null;
        $this->hotel_id = null;
        $this->name = '';
        $this->type = '';
        $this->description = '';
        $this->images = [];
        $this->imagesUpload = [];
        $this->is_available = true;
        $this->is_free = true;
        $this->price_per_hour = null;
        $this->daily_price = null;
        $this->opening_time = null;
        $this->closing_time = null;
        $this->operating_days = [];
        $this->capacity = null;
        $this->requires_booking = false;
        $this->rules = '';
        $this->location = '';
        $this->display_order = 0;
        $this->resetErrorBag();
    }

    public function render()
    {
        $user = auth()->user();
        $isAdmin = $user->hasRole('Admin');

        $query = HotelLeisureFacility::with('hotel')
            ->when(!$isAdmin, function ($q) use ($user) {
                $q->whereHas('hotel', function ($hq) use ($user) {
                    $hq->where('user_id', $user->id);
                });
            })
            ->when($this->search, function ($q) {
                $q->where(function ($sq) {
                    $sq->where('name', 'like', '%' . $this->search . '%')
                       ->orWhere('description', 'like', '%' . $this->search . '%')
                       ->orWhereHas('hotel', function ($hq) {
                           $hq->where('name', 'like', '%' . $this->search . '%');
                       });
                });
            })
            ->when($this->filterHotel, function ($q) {
                $q->where('hotel_id', $this->filterHotel);
            })
            ->when($this->filterType, function ($q) {
                $q->where('type', $this->filterType);
            })
            ->when($this->filterAvailability !== null, function ($q) {
                $q->where('is_available', $this->filterAvailability);
            })
            ->orderBy('hotel_id')
            ->orderBy('type')
            ->orderBy('display_order');

        $hotelsQuery = Hotel::orderBy('name');
        if (!$isAdmin) {
            $hotelsQuery->where('user_id', $user->id);
        }

        return view('livewire.admin.leisure-facilities-management', [
            'facilities' => $query->paginate(12),
            'hotels' => $hotelsQuery->get(),
            'facilityTypes' => $this->facilityTypes,
            'daysOfWeek' => $this->daysOfWeek,
        ])->layout('layouts.admin');
    }
}

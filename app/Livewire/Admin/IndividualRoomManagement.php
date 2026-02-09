<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\Hotel;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithPagination;

class IndividualRoomManagement extends Component
{
    use WithPagination;
    
    protected $paginationTheme = 'tailwind';
    
    // Filtros
    public ?int $hotel_id = null;
    public ?int $room_type_id = null;
    public ?string $status = null;
    public ?string $floor = null;
    public string $search = '';
    public bool $availableOnly = false;
    
    // Formulário
    public ?int $room_id = null;
    public ?int $form_hotel_id = null;
    public ?int $form_room_type_id = null;
    public string $room_number = '';
    public string $form_floor = '';
    public bool $is_available = true;
    public bool $is_clean = true;
    public bool $is_maintenance = false;
    public string $form_status = 'available';
    public string $notes = '';
    public ?string $available_from = null;
    
    // Controle de modal
    public bool $showModal = false;
    public bool $showBulkModal = false;
    
    // Criação em lote
    public array $bulkData = [
        'hotel_id' => null,
        'room_type_id' => null,
        'floor' => '',
        'start_number' => 101,
        'end_number' => 110,
        'prefix' => '',
        'status' => 'available',
        'is_available' => true,
        'is_clean' => true,
        'is_maintenance' => false,
        'notes' => '',
        'available_from' => null,
    ];
    public array $bulkPreview = [];
    
    // Propriedades computadas
    protected Collection $hotels;
    protected Collection $roomTypes;
    
    // Reset de paginação
    protected $queryString = [
        'search' => ['except' => ''],
        'hotel_id' => ['except' => null],
        'room_type_id' => ['except' => null],
        'status' => ['except' => null],
        'floor' => ['except' => null],
        'availableOnly' => ['except' => false],
    ];
    
    /**
     * Reset paginação quando filtros são alterados
     */
    public function updatingSearch(): void
    {
        $this->resetPage();
    }
    
    public function updatingHotelId(): void
    {
        $this->resetPage();
        $this->room_type_id = null; // Reset room type quando hotel muda
    }
    
    public function updatingRoomTypeId(): void
    {
        $this->resetPage();
    }
    
    public function updatingStatus(): void
    {
        $this->resetPage();
    }
    
    public function updatingFloor(): void
    {
        $this->resetPage();
    }
    
    public function updatingAvailableOnly(): void
    {
        $this->resetPage();
    }
    
    /**
     * Abrir modal para criar/editar quarto
     */
    public function openModal(?int $roomId = null): void
    {
        $this->resetForm();
        
        if ($roomId) {
            $room = Room::with('roomType.hotel')->find($roomId);
            if ($room) {
                $this->room_id = $room->id;
                $this->form_hotel_id = $room->hotel_id;
                $this->form_room_type_id = $room->room_type_id;
                $this->room_number = $room->room_number;
                $this->form_floor = $room->floor ?? '';
                $this->is_available = $room->is_available;
                $this->is_clean = $room->is_clean;
                $this->is_maintenance = $room->is_maintenance;
                $this->form_status = $room->status;
                $this->notes = $room->notes ?? '';
                $this->available_from = $room->available_from?->format('Y-m-d');
            }
        }
        
        $this->showModal = true;
    }
    
    /**
     * Quando o hotel é alterado na modal, resetar o tipo de quarto
     */
    public function updatedFormHotelId(): void
    {
        $this->form_room_type_id = null;
    }
    
    /**
     * Quando o hotel é alterado na modal de criação em lote
     */
    public function updatedBulkDataHotelId(): void
    {
        $this->bulkData['room_type_id'] = null;
    }
    
    /**
     * Gerar pré-visualização dos números de quartos para criação em lote
     */
    public function getBulkPreviewProperty(): array
    {
        $startNumber = $this->bulkData['start_number'] ?? 0;
        $endNumber = $this->bulkData['end_number'] ?? 0;
        $prefix = $this->bulkData['prefix'] ?? '';
        
        if ($startNumber <= 0 || $endNumber <= 0 || $startNumber > $endNumber) {
            return [];
        }
        
        $preview = [];
        for ($i = $startNumber; $i <= $endNumber; $i++) {
            $roomNumber = $prefix . str_pad((string)$i, 2, '0', STR_PAD_LEFT);
            $preview[] = $roomNumber;
        }
        
        return $preview;
    }
    
    /**
     * Abrir modal para criação em lote
     */
    public function openBulkModal(): void
    {
        $this->resetBulkForm();
        $this->showBulkModal = true;
    }
    
    /**
     * Fechar modais
     */
    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }
    
    public function closeBulkModal(): void
    {
        $this->showBulkModal = false;
        $this->resetBulkForm();
    }
    
    /**
     * Salvar quarto individual
     */
    public function save(): void
    {
        $this->validate([
            'form_hotel_id' => 'required|exists:hotels,id',
            'form_room_type_id' => 'required|exists:room_types,id',
            'room_number' => [
                'required',
                'string',
                'max:20',
                function ($attribute, $value, $fail) {
                    $query = Room::where('hotel_id', $this->form_hotel_id)
                                 ->where('room_number', $value);
                    
                    if ($this->room_id) {
                        $query->where('id', '!=', $this->room_id);
                    }
                    
                    if ($query->exists()) {
                        $fail('Este número de quarto já existe neste hotel.');
                    }
                },
            ],
            'form_floor' => 'nullable|string|max:50',
            'form_status' => 'required|in:available,occupied,maintenance,cleaning,out_of_order',
            'notes' => 'nullable|string|max:1000',
            'available_from' => 'nullable|date|after_or_equal:today',
        ]);
        
        $data = [
            'hotel_id' => $this->form_hotel_id,
            'room_type_id' => $this->form_room_type_id,
            'room_number' => $this->room_number,
            'floor' => $this->form_floor ?: null,
            'is_available' => $this->is_available,
            'is_clean' => $this->is_clean,
            'is_maintenance' => $this->is_maintenance,
            'status' => $this->form_status,
            'notes' => $this->notes ?: null,
            'available_from' => $this->available_from ? \Carbon\Carbon::parse($this->available_from) : null,
        ];
        
        if ($this->room_id) {
            Room::find($this->room_id)->update($data);
            session()->flash('message', 'Quarto atualizado com sucesso!');
        } else {
            Room::create($data);
            session()->flash('message', 'Quarto criado com sucesso!');
        }
        
        $this->closeModal();
    }
    
    /**
     * Criar quartos em lote
     */
    public function saveBulkRooms(): void
    {
        $this->validate([
            'bulkData.hotel_id' => 'required|exists:hotels,id',
            'bulkData.room_type_id' => 'required|exists:room_types,id',
            'bulkData.floor' => 'required|string|max:50',
            'bulkData.start_number' => 'required|integer|min:1',
            'bulkData.end_number' => 'required|integer|min:1|gte:bulkData.start_number',
            'bulkData.prefix' => 'nullable|string|max:5',
        ]);
        
        $createdCount = 0;
        $skippedCount = 0;
        
        for ($i = $this->bulkData['start_number']; $i <= $this->bulkData['end_number']; $i++) {
            $roomNumber = $this->bulkData['prefix'] . str_pad((string)$i, 2, '0', STR_PAD_LEFT);
            
            // Verificar se já existe
            if (Room::where('hotel_id', $this->bulkData['hotel_id'])
                    ->where('room_number', $roomNumber)
                    ->exists()) {
                $skippedCount++;
                continue;
            }
            
            Room::create([
                'hotel_id' => $this->bulkData['hotel_id'],
                'room_type_id' => $this->bulkData['room_type_id'],
                'room_number' => $roomNumber,
                'floor' => $this->bulkData['floor'],
                'is_available' => true,
                'is_clean' => true,
                'is_maintenance' => false,
                'status' => 'available',
                'notes' => "Criado em lote - {$this->bulkData['floor']}",
            ]);
            
            $createdCount++;
        }
        
        session()->flash('message', 
            "{$createdCount} quartos criados com sucesso!" . 
            ($skippedCount > 0 ? " ({$skippedCount} foram ignorados por já existirem)" : "")
        );
        
        $this->closeBulkModal();
    }
    
    /**
     * Alias para saveBulkRooms (usado na modal)
     */
    public function bulkCreate(): void
    {
        $this->saveBulkRooms();
    }
    
    /**
     * Excluir quarto
     */
    public function delete(int $roomId): void
    {
        $room = Room::find($roomId);
        if ($room) {
            // Verificar se tem reservas ativas
            if ($room->reservations()->whereIn('status', ['confirmed', 'checked_in'])->exists()) {
                session()->flash('error', 'Não é possível eliminar um quarto com reservas ativas.');
                return;
            }
            
            $room->delete();
            session()->flash('message', 'Quarto eliminado com sucesso!');
        }
    }
    
    /**
     * Reset do formulário
     */
    private function resetForm(): void
    {
        $this->room_id = null;
        $this->form_hotel_id = null;
        $this->form_room_type_id = null;
        $this->room_number = '';
        $this->form_floor = '';
        $this->is_available = true;
        $this->is_clean = true;
        $this->is_maintenance = false;
        $this->form_status = 'available';
        $this->notes = '';
        $this->available_from = null;
    }
    
    /**
     * Reset do formulário de criação em lote
     */
    private function resetBulkForm(): void
    {
        $this->bulkData = [
            'hotel_id' => null,
            'room_type_id' => null,
            'floor' => '',
            'start_number' => 101,
            'end_number' => 110,
            'prefix' => '',
            'status' => 'available',
            'is_available' => true,
            'is_clean' => true,
            'is_maintenance' => false,
            'notes' => '',
            'available_from' => null,
        ];
    }
    
    /**
     * Obter lista de hotéis
     */
    public function getHotelsProperty(): Collection
    {
        $user = auth()->user();
        $query = Hotel::select('id', 'name');
        
        // Se não for Admin, mostrar apenas hotéis do usuário
        if (!$user->hasRole('Admin')) {
            $query->where('user_id', $user->id);
        }
        
        return $query->orderBy('name')->get();
    }
    
    /**
     * Obter lista de tipos de quarto baseado no hotel selecionado
     */
    public function getRoomTypesProperty(): Collection
    {
        $hotelId = null;
        
        // Priorizar hotel da modal individual
        if ($this->form_hotel_id) {
            $hotelId = $this->form_hotel_id;
        }
        // Depois hotel da modal de criação em lote
        elseif ($this->bulkData['hotel_id']) {
            $hotelId = $this->bulkData['hotel_id'];
        }
        // Por último, hotel dos filtros
        elseif ($this->hotel_id) {
            $hotelId = $this->hotel_id;
        }
        
        if (!$hotelId) {
            return collect();
        }
        
        return RoomType::where('hotel_id', $hotelId)
                      ->select('id', 'name')
                      ->orderBy('name')
                      ->get();
    }
    
    /**
     * Obter lista de andares únicos
     */
    public function getFloorsProperty(): Collection
    {
        $query = Room::select('floor')
                    ->whereNotNull('floor')
                    ->distinct()
                    ->orderBy('floor');
        
        if ($this->hotel_id) {
            $query->where('hotel_id', $this->hotel_id);
        }
        
        return $query->pluck('floor');
    }
    
    /**
     * Obter quartos com filtros aplicados
     */
    public function getRoomsProperty()
    {
        $user = auth()->user();
        $isAdmin = $user->hasRole('Admin');
        
        $query = Room::with(['hotel:id,name', 'roomType:id,name,base_price'])
                    // Se não for Admin, mostrar apenas quartos dos hotéis do usuário
                    ->when(!$isAdmin, function (Builder $query) use ($user) {
                        $query->whereHas('hotel', function ($q) use ($user) {
                            $q->where('user_id', $user->id);
                        });
                    })
                    ->when($this->search, function (Builder $query, string $search) {
                        $query->where(function (Builder $q) use ($search) {
                            $q->where('room_number', 'like', "%{$search}%")
                              ->orWhere('floor', 'like', "%{$search}%")
                              ->orWhere('notes', 'like', "%{$search}%");
                        });
                    })
                    ->when($this->hotel_id, fn (Builder $query, int $hotelId) => $query->where('hotel_id', $hotelId))
                    ->when($this->room_type_id, fn (Builder $query, int $roomTypeId) => $query->where('room_type_id', $roomTypeId))
                    ->when($this->status, fn (Builder $query, string $status) => $query->where('status', $status))
                    ->when($this->floor, fn (Builder $query, string $floor) => $query->where('floor', $floor))
                    ->when($this->availableOnly, fn (Builder $query) => $query->where('is_available', true))
                    ->orderBy('hotel_id')
                    ->orderBy('floor')
                    ->orderBy('room_number');
        
        return $query->paginate(15);
    }
    
    /**
     * Render do componente
     */
    public function render()
    {
        return view('livewire.admin.individual-room-management', [
            'rooms' => $this->getRoomsProperty(),
            'hotels' => $this->getHotelsProperty(),
            'roomTypes' => $this->getRoomTypesProperty(),
            'floors' => $this->getFloorsProperty(),
            'statusOptions' => [
                'available' => 'Disponível',
                'occupied' => 'Ocupado',
                'maintenance' => 'Manutenção',
                'cleaning' => 'Limpeza',
                'out_of_order' => 'Fora de Serviço',
            ],
            'room_id' => $this->room_id,
            'bulkPreview' => $this->getBulkPreviewProperty(),
        
            ])->layout('layouts.admin');
    }
}

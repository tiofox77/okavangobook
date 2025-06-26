<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\Hotel;
use App\Models\Location;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class ReservationCreation extends Component
{
    // Dados da reserva
    public ?int $userId = null;
    public ?int $hotelId = null;
    public ?int $roomTypeId = null;
    public ?int $roomId = null;
    public string $checkIn = '';
    public string $checkOut = '';
    public int $guests = 1;
    public ?float $totalPrice = null;
    public string $status = 'confirmed';
    public string $paymentStatus = 'pending';
    public string $paymentMethod = 'cash';
    public ?string $specialRequests = null;
    public ?string $transactionId = null;
    public bool $isRefundable = true;
    public ?string $notes = null;
    
    // Dados auxiliares
    public Collection $locations;
    public Collection $hotels;
    public Collection $roomTypes;
    public Collection $rooms;
    public Collection $availableRooms;
    public Collection $users;
    
    // Estados da interface
    public string $currentStep = 'location';
    public bool $showUserModal = false;
    public bool $showConfirmModal = false;
    public bool $showCreateUserModal = false;
    
    // Filtros
    public string $locationFilter = '';
    public string $hotelFilter = '';
    public string $roomFilter = '';
    
    // Propriedades para seleção
    public ?int $selectedLocationId = null;
    public ?int $selectedHotelId = null;
    public ?int $selectedRoomTypeId = null;
    public ?int $selectedRoomId = null;
    
    // Estados da interface
    public ?int $nights = null;
    public ?array $selectedHotel = null;
    public ?array $selectedRoomType = null;
    public ?array $selectedRoom = null;
    public ?array $selectedUser = null;
    
    // Pesquisa
    public string $searchUser = '';
    public string $searchHotel = '';
    
    // Novo utilizador (criação manual)
    public string $newUserName = '';
    public string $newUserEmail = '';
    public string $newUserPhone = '';
    public string $newUserDocument = '';
    public string $newUserDocumentType = 'bi';
    public ?string $newUserBirthdate = null;
    public string $newUserNationality = 'Angola';
    
    // Opções de métodos de pagamento
    public array $paymentMethods = [
        'cash' => 'Dinheiro (Offline)',
        'bank_transfer' => 'Transferência Bancária',
        'credit_card' => 'Cartão de Crédito',
        'debit_card' => 'Cartão de Débito',
        'mobile_payment' => 'Pagamento Móvel',
    ];

    // Opções de status de pagamento
    public array $paymentStatusOptions = [
        'pending' => 'Pendente',
        'paid' => 'Pago',
        'failed' => 'Falhado',
        'refunded' => 'Reembolsado',
        'partial' => 'Parcial',
    ];

    // Opções de status da reserva
    public array $statusOptions = [
        'pending' => 'Pendente',
        'confirmed' => 'Confirmada',
        'checked_in' => 'Check-in Realizado',
        'checked_out' => 'Check-out Realizado',
        'cancelled' => 'Cancelada',
        'no_show' => 'Não Compareceu'
    ];
    
    public array $documentTypes = [
        'bi' => 'Bilhete de Identidade',
        'passaporte' => 'Passaporte',
        'guia_conducao' => 'Guia de Condução',
        'outro' => 'Outro'
    ];
    
    /**
     * Verifica se uma coluna existe numa tabela antes de aplicar filtro
     */
    private function applyColumnFilter($query, string $table, string $column, $value = true): void
    {
        if (Schema::hasColumn($table, $column)) {
            $query->where($column, $value);
        }
    }
    
    public function mount(): void
    {
        $this->checkIn = now()->addDay()->format('Y-m-d');
        $this->checkOut = now()->addDays(2)->format('Y-m-d');
        $this->guests = 1;
        $this->status = 'confirmed';
        $this->paymentStatus = 'pending';
        $this->paymentMethod = 'cash';
        
        $this->loadLocations();
        $this->hotels = collect();
        $this->roomTypes = collect();
        $this->rooms = collect();
        $this->availableRooms = collect();
        $this->users = collect();
        
        $this->calculateNights();
    }
    
    /**
     * Carrega as localizações filtradas
     */
    public function loadLocations(): void
    {
        $query = Location::query();
        
        // Verifica se a coluna is_active existe antes de aplicar o filtro
        $this->applyColumnFilter($query, 'locations', 'is_active', true);
        
        if (!empty($this->locationFilter)) {
            $query->where('name', 'like', '%' . $this->locationFilter . '%');
        }
        
        $this->locations = $query->orderBy('name')->get();
    }
    
    /**
     * Carrega os hotéis baseado na localização e permissões do usuário
     */
    public function loadHotels(): void
    {
        if (!$this->selectedLocationId) {
            $this->hotels = collect();
            return;
        }
        
        $query = Hotel::query()
            ->where('location_id', $this->selectedLocationId);
        
        // Verifica se a coluna is_active existe antes de aplicar o filtro
        $this->applyColumnFilter($query, 'hotels', 'is_active', true);
        
        // Verificação mais robusta de admin - incluir diferentes variações de role
        $user = auth()->user();
        $isAdmin = $user->hasRole('Admin') || 
                   $user->hasRole('admin') || 
                   $user->hasRole('ADMIN') ||
                   $user->roles->contains('name', 'Admin') ||
                   $user->roles->contains('name', 'admin') ||
                   $user->roles->contains('name', 'ADMIN');
        
        // Se não é admin, mostrar apenas hotéis do usuário logado OU hotéis sem proprietário
        if (!$isAdmin) {
            $query->where(function($q) {
                $q->where('user_id', auth()->id())
                  ->orWhereNull('user_id');
            });
        }
        
        if (!empty($this->hotelFilter)) {
            $query->where('name', 'like', '%' . $this->hotelFilter . '%');
        }
        
        $this->hotels = $query->orderBy('name')->get();
    }
    
    /**
     * Selecciona um hotel e carrega os tipos de quarto
     */
    public function selectHotel(int $hotelId): void
    {
        $hotel = $this->hotels->firstWhere('id', $hotelId);
        if (!$hotel) {
            return;
        }
        
        $this->selectedHotelId = $hotelId;
        $this->hotelId = $hotelId;
        $this->selectedHotel = $hotel->toArray();
        
        // Limpar seleções de quarto quando hotel muda
        $this->selectedRoomTypeId = null;
        $this->roomTypeId = null;
        $this->selectedRoomType = null;
        $this->selectedRoomId = null;
        $this->roomId = null;
        $this->selectedRoom = null;
        $this->availableRooms = collect();
        
        // Carregar tipos de quarto do hotel seleccionado
        $this->loadRoomTypes();
        
        // Avançar para o próximo passo
        $this->currentStep = 'room';
    }
    
    /**
     * Carrega os tipos de quarto baseado no hotel selecionado
     */
    public function loadRoomTypes(): void
    {
        if (!$this->selectedHotelId) {
            $this->roomTypes = collect();
            return;
        }
        
        $query = RoomType::query()
            ->where('hotel_id', $this->selectedHotelId);
        
        // Verifica se a coluna is_available existe antes de aplicar o filtro
        $this->applyColumnFilter($query, 'room_types', 'is_available', true);
        
        // Filtrar por capacidade de hóspedes
        if ($this->guests > 0 && Schema::hasColumn('room_types', 'capacity')) {
            $query->where('capacity', '>=', $this->guests);
        }
        
        if (!empty($this->roomFilter)) {
            $query->where('name', 'like', '%' . $this->roomFilter . '%');
        }
        
        $this->roomTypes = $query->orderBy('name')->get();
    }
    
    /**
     * Atualiza o filtro de localização
     */
    public function updatedLocationFilter(): void
    {
        $this->loadLocations();
    }
    
    /**
     * Atualiza o filtro de hotel
     */
    public function updatedHotelFilter(): void
    {
        $this->loadHotels();
    }
    
    /**
     * Atualiza o filtro de quarto
     */
    public function updatedRoomFilter(): void
    {
        $this->loadRoomTypes();
    }
    
    public function calculateNights(): void
    {
        $checkIn = Carbon::parse($this->checkIn);
        $checkOut = Carbon::parse($this->checkOut);
        $this->nights = $checkOut->diffInDays($checkIn);
    }
    
    public function updatedCheckIn(): void
    {
        $this->calculateNights();
        $this->clearRoomSelection();
        // Se já há um tipo de quarto selecionado, recarrega os quartos disponíveis
        if ($this->selectedRoomTypeId) {
            $this->loadAvailableRooms();
        }
    }
    
    public function updatedCheckOut(): void
    {
        $this->calculateNights();
        $this->clearRoomSelection();
        // Se já há um tipo de quarto selecionado, recarrega os quartos disponíveis
        if ($this->selectedRoomTypeId) {
            $this->loadAvailableRooms();
        }
    }
    
    public function updatedGuests(): void
    {
        $this->clearRoomSelection();
        // Recarregar tipos de quartos com novo filtro de capacidade
        $this->loadRoomTypes();
        // Se já há um tipo de quarto selecionado, recarrega os quartos disponíveis
        if ($this->selectedRoomTypeId) {
            $this->loadAvailableRooms();
        }
    }
    
    public function selectLocation(int $locationId): void
    {
        $this->selectedLocationId = $locationId;
        $this->loadHotels();
        $this->currentStep = 'hotel';
        $this->clearHotelSelection();
    }
    
    public function selectRoomType(int $roomTypeId): void
    {
        $roomType = $this->roomTypes->firstWhere('id', $roomTypeId);
        if (!$roomType) {
            return;
        }
        
        $this->selectedRoomTypeId = $roomTypeId;
        $this->roomTypeId = $roomTypeId;
        $this->selectedRoomType = $roomType->toArray();
        
        // Limpar seleção de quarto específico
        $this->selectedRoomId = null;
        $this->roomId = null;
        $this->selectedRoom = null;
        
        $this->loadAvailableRooms();
        $this->calculatePrice();
    }
    
    public function loadAvailableRooms(): void
    {
        $this->availableRooms = Room::where('room_type_id', $this->roomTypeId)
            ->where('is_available', true)
            ->whereDoesntHave('reservations', function($query) {
                $query->where(function($q) {
                    $q->whereBetween('check_in', [$this->checkIn, $this->checkOut])
                      ->orWhereBetween('check_out', [$this->checkIn, $this->checkOut])
                      ->orWhere(function($q2) {
                          $q2->where('check_in', '<=', $this->checkIn)
                             ->where('check_out', '>=', $this->checkOut);
                      });
                })
                ->whereIn('status', ['confirmed', 'checked_in']);
            })
            ->get();
    }
    
    public function selectRoom(int $roomId): void
    {
        $this->roomId = $roomId;
        $room = Room::find($roomId);
        $this->selectedRoom = $room->toArray();
        $this->currentStep = 'guest';
    }
    
    public function calculatePrice(): void
    {
        if ($this->selectedRoomType && $this->nights) {
            $this->totalPrice = $this->selectedRoomType['base_price'] * $this->nights;
        }
    }
    
    public function searchUsers(): void
    {
        if (strlen($this->searchUser) >= 2) {
            $this->users = User::where(function($query) {
                    $query->where('name', 'like', '%' . $this->searchUser . '%')
                          ->orWhere('email', 'like', '%' . $this->searchUser . '%');
                })
                ->limit(10)
                ->get();
        } else {
            $this->users = collect();
        }
    }
    
    public function selectUser(int $userId): void
    {
        $user = User::find($userId);
        if (!$user) {
            session()->flash('error', 'Utilizador não encontrado.');
            return;
        }

        $this->userId = $user->id;
        $this->selectedUser = $user->toArray();
        $this->closeUserModal();
        
        session()->flash('message', 'Hóspede selecionado com sucesso!');
    }

    public function openUserModal(): void
    {
        $this->showUserModal = true;
        $this->searchUser = '';
        $this->searchUsers();
    }
    
    public function closeUserModal(): void
    {
        $this->showUserModal = false;
        $this->searchUser = '';
        $this->users = collect();
    }
    
    public function openCreateUserModal(): void
    {
        $this->showCreateUserModal = true;
    }
    
    public function closeCreateUserModal(): void
    {
        $this->showCreateUserModal = false;
        $this->resetNewUserForm();
    }

    public function createUser(): void
    {
        $this->validate([
            'newUserName' => 'required|string|min:2|max:255',
            'newUserEmail' => 'required|email|unique:users,email',
            'newUserPhone' => 'required|string|min:9|max:20',
            'newUserDocument' => 'required|string|min:5|max:20',
            'newUserDocumentType' => 'required|in:bi,passaporte,guia_conducao,outro',
            'newUserBirthdate' => 'nullable|date|before:today',
            'newUserNationality' => 'required|string|min:2|max:255',
        ]);

        try {
            $user = User::create([
                'name' => $this->newUserName,
                'email' => $this->newUserEmail,
                'phone' => $this->newUserPhone,
                'document' => $this->newUserDocument,
                'document_type' => $this->newUserDocumentType,
                'birthdate' => $this->newUserBirthdate ?: null,
                'nationality' => $this->newUserNationality,
                'password' => Hash::make(Str::random(12)), // password temporária
                'role' => 'client',
                'active' => true,
            ]);

            // Seleccionar o utilizador criado automaticamente
            $this->selectUser($user->id);
            $this->closeCreateUserModal();
            
            // Limpar campos do formulário
            $this->resetNewUserForm();

            session()->flash('message', 'Hóspede criado e selecionado com sucesso!');
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao criar hóspede: ' . $e->getMessage());
        }
    }

    public function resetNewUserForm(): void
    {
        $this->newUserName = null;
        $this->newUserEmail = null;
        $this->newUserPhone = null;
        $this->newUserDocument = null;
        $this->newUserDocumentType = 'bi';
        $this->newUserBirthdate = null;
        $this->newUserNationality = null;
    }

    public function createReservation(): void
    {
        $this->validate([
            'userId' => 'required|exists:users,id',
            'hotelId' => 'required|exists:hotels,id',
            'roomTypeId' => 'required|exists:room_types,id',
            'roomId' => 'required|exists:rooms,id',
            'checkIn' => 'required|date|after_or_equal:today',
            'checkOut' => 'required|date|after:checkIn',
            'guests' => 'required|integer|min:1',
            'totalPrice' => 'required|numeric|min:0',
            'paymentMethod' => 'required|in:cash,bank_transfer,credit_card,debit_card,mobile_payment',
        ]);

        DB::transaction(function () {
            $reservation = Reservation::create([
                'user_id' => $this->userId,
                'hotel_id' => $this->hotelId,
                'room_type_id' => $this->roomTypeId,
                'room_id' => $this->roomId,
                'check_in' => $this->checkIn,
                'check_out' => $this->checkOut,
                'guests' => $this->guests,
                'total_price' => $this->totalPrice,
                'status' => $this->status,
                'payment_status' => $this->paymentStatus,
                'payment_method' => $this->paymentMethod,
                'special_requests' => $this->specialRequests,
                'transaction_id' => $this->transactionId,
                'is_refundable' => $this->isRefundable,
                'confirmation_code' => strtoupper(Str::random(8)),
            ]);

            session()->flash('success', 'Reserva criada com sucesso! Código: ' . $reservation->confirmation_code);
        });

        $this->showConfirmModal = true;
    }
    
    public function confirmReservation(): void
    {
        $this->status = 'confirmed';
        $this->createReservation();
    }

    public function saveAsDraft(): void
    {
        $this->status = 'pending';
        $this->paymentStatus = 'pending';
        $this->createReservation();
    }
    
    public function closeConfirmModal(): void
    {
        $this->showConfirmModal = false;
    }
    
    public function resetForm(): void
    {
        $this->userId = null;
        $this->hotelId = null;
        $this->roomTypeId = null;
        $this->roomId = null;
        $this->selectedLocationId = null;
        $this->totalPrice = null;
        $this->specialRequests = null;
        $this->transactionId = null;

        $this->selectedHotel = null;
        $this->selectedRoomType = null;
        $this->selectedRoom = null;
        $this->selectedUser = null;

        $this->currentStep = 'location';
        $this->hotels = collect();
        $this->roomTypes = collect();
        $this->rooms = collect();
        $this->availableRooms = collect();
        $this->users = collect();

        $this->checkIn = now()->addDay()->format('Y-m-d');
        $this->checkOut = now()->addDays(2)->format('Y-m-d');
        $this->calculateNights();
    }

    public function goToStep(string $step): void
    {
        $this->currentStep = $step;
    }

    public function goToPreviousStep(): void
    {
        $steps = ['location', 'hotel', 'room', 'guest', 'confirm'];
        $currentIndex = array_search($this->currentStep, $steps);

        if ($currentIndex > 0) {
            $this->currentStep = $steps[$currentIndex - 1];
        }
    }

    public function clearHotelSelection(): void
    {
        $this->hotelId = null;
        $this->selectedHotel = null;
        $this->clearRoomSelection();
    }

    public function clearRoomSelection(): void
    {
        $this->roomTypeId = null;
        $this->roomId = null;
        $this->selectedRoomType = null;
        $this->selectedRoom = null;
        $this->totalPrice = null;
        // Não limpar $this->roomTypes aqui pois isso faz os quartos desaparecerem
        $this->availableRooms = collect();
    }

    public function render()
    {
        return view('livewire.admin.reservation-creation', [
            'paymentMethods' => $this->paymentMethods,
            'statusOptions' => $this->statusOptions,
            'paymentStatusOptions' => $this->paymentStatusOptions,
            'documentTypes' => $this->documentTypes,
        ])->layout('layouts.admin');
    }
}

<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\Hotel;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithPagination;

class ReservationManagement extends Component
{
    use WithPagination;
    
    // Propriedades do filtro
    public string $search = '';
    public string $status = '';
    public string $dateFilter = '';
    public string $paymentStatus = '';
    public string $hotelFilter = '';
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';
    public int $perPage = 10;
    
    // Propriedades do modal
    public bool $showModal = false;
    public bool $showDeleteModal = false;
    public bool $showAssignRoomModal = false;
    public ?int $editingReservationId = null;
    public ?string $paymentMethod = null;
    public ?Reservation $selectedReservation = null;
    
    // Propriedades para a atribuição de quartos
    public ?int $selectedRoomId = null;
    public Collection $availableRooms;
    
    // Propriedades da reserva em edição
    public ?string $confirmationCode = null;
    public ?string $checkIn = null;
    public ?string $checkOut = null;
    public ?int $guests = null;
    public ?string $specialRequests = null;
    
    // Propriedade para mensagens de feedback
    public array $errors = [];
    
    /**
     * Regras de validação para as propriedades
     *
     * @return array<string, array<int, string>>
     */
    protected function rules(): array
    {
        return [
            'paymentMethod' => ['nullable', 'string'],
            'selectedRoomId' => ['nullable', 'integer', 'exists:rooms,id'],
            'checkIn' => ['required', 'date', 'after_or_equal:today'],
            'checkOut' => ['required', 'date', 'after:checkIn'],
            'guests' => ['required', 'integer', 'min:1'],
            'specialRequests' => ['nullable', 'string']
        ];
    }
    
    /**
     * Inicializar o componente
     *
     * @return void
     */
    public function mount(): void
    {
        $this->availableRooms = collect();
    }
    
    /**
     * Método executado em cada atualização de propriedade
     *
     * @return void
     */
    public function updated(string $propertyName): void
    {
        if (in_array($propertyName, ['search', 'status', 'dateFilter', 'paymentStatus', 'hotelFilter'])) {
            $this->resetPage();
        }
    }
    
    /**
     * Ordenar os resultados por um campo específico
     *
     * @param string $field
     * @return void
     */
    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }
    
    /**
     * Abrir o modal de edição de uma reserva
     *
     * @param int $reservationId
     * @return void
     */
    public function editReservation(int $reservationId): void
    {
        $this->editingReservationId = $reservationId;
        $reservation = Reservation::findOrFail($reservationId);
        
        $this->confirmationCode = $reservation->confirmation_code;
        $this->checkIn = $reservation->check_in?->format('Y-m-d');
        $this->checkOut = $reservation->check_out?->format('Y-m-d');
        $this->guests = $reservation->guests;
        $this->specialRequests = $reservation->special_requests;
        $this->paymentMethod = $reservation->payment_method;
        
        $this->showModal = true;
    }
    
    /**
     * Fechar o modal e limpar os campos
     *
     * @return void
     */
    public function closeModal(): void
    {
        $this->showModal = false;
        $this->showDeleteModal = false;
        $this->showAssignRoomModal = false;
        $this->editingReservationId = null;
        $this->selectedRoomId = null;
        $this->paymentMethod = null;
        $this->availableRooms = collect();
        $this->selectedReservation = null;
        
        $this->confirmationCode = null;
        $this->checkIn = null;
        $this->checkOut = null;
        $this->guests = null;
        $this->specialRequests = null;
    }
    
    /**
     * Salvar as alterações na reserva
     *
     * @return void
     */
    public function saveReservation(): void
    {
        if (!$this->editingReservationId) {
            return;
        }
        
        $this->validate();
        
        $reservation = Reservation::findOrFail($this->editingReservationId);
        
        $reservation->check_in = $this->checkIn;
        $reservation->check_out = $this->checkOut;
        $reservation->guests = $this->guests;
        $reservation->special_requests = $this->specialRequests;
        
        $reservation->save();
        
        $this->closeModal();
        session()->flash('message', 'Reserva atualizada com sucesso!');
    }
    
    /**
     * Abrir o modal de confirmação
     *
     * @param int $reservationId
     * @return void
     */
    public function openConfirmModal(int $reservationId): void
    {
        $this->selectedReservation = Reservation::with('user', 'hotel', 'roomType')->findOrFail($reservationId);
        $this->dispatch('open-confirm-modal');
    }

    /**
     * Abrir o modal de check-in
     *
     * @param int $reservationId
     * @return void
     */
    public function openCheckInModal(int $reservationId): void
    {
        $this->selectedReservation = Reservation::with('user', 'hotel', 'roomType', 'room')->findOrFail($reservationId);
        $this->dispatch('open-checkin-modal');
    }

    /**
     * Abrir o modal de check-out
     *
     * @param int $reservationId
     * @return void
     */
    public function openCheckOutModal(int $reservationId): void
    {
        $this->selectedReservation = Reservation::with('user', 'hotel', 'roomType', 'room')->findOrFail($reservationId);
        $this->dispatch('open-checkout-modal');
    }

    /**
     * Abrir o modal de cancelamento
     *
     * @param int $reservationId
     * @return void
     */
    public function openCancelModal(int $reservationId): void
    {
        $this->selectedReservation = Reservation::with('user', 'hotel', 'roomType')->findOrFail($reservationId);
        $this->dispatch('open-cancel-modal');
    }
    
    /**
     * Abrir o modal de visualização detalhada
     *
     * @param int $reservationId
     * @return void
     */
    public function openViewModal(int $reservationId): void
    {
        $this->selectedReservation = Reservation::with('user', 'hotel', 'roomType', 'room')->findOrFail($reservationId);
        $this->dispatch('open-view-modal');
    }
    
    /**
     * Abrir o modal de edição
     *
     * @param int $reservationId
     * @return void
     */
    public function openEditModal(int $reservationId): void
    {
        $this->editingReservationId = $reservationId;
        $this->selectedReservation = Reservation::findOrFail($reservationId);
        
        $this->confirmationCode = $this->selectedReservation->confirmation_code;
        $this->checkIn = $this->selectedReservation->check_in?->format('Y-m-d');
        $this->checkOut = $this->selectedReservation->check_out?->format('Y-m-d');
        $this->guests = $this->selectedReservation->guests;
        $this->specialRequests = $this->selectedReservation->special_requests;
        $this->paymentMethod = $this->selectedReservation->payment_method;
        
        $this->dispatch('open-edit-modal');
    }
    
    /**
     * Confirmar uma reserva e atribuir um quarto
     *
     * @param int $reservationId
     * @return void
     */
    public function confirmReservation(int $reservationId = null): void
    {
        if ($reservationId !== null) {
            $this->editingReservationId = $reservationId;
            $this->selectedReservation = Reservation::findOrFail($reservationId);
        }
        
        if (!$this->selectedReservation) {
            session()->flash('error', 'Nenhuma reserva selecionada para confirmar.');
            return;
        }
        
        $this->editingReservationId = $this->selectedReservation->id;
        
        // Busca quartos disponíveis para esta reserva
        $this->loadAvailableRooms($this->selectedReservation);
        
        // Mostra o modal para selecionar o quarto e o método de pagamento
        $this->showAssignRoomModal = true;
    }
    
    /**
     * Carregar quartos disponíveis para uma reserva
     *
     * @param Reservation $reservation
     * @return void
     */
    public function loadAvailableRooms(Reservation $reservation): void
    {
        $this->availableRooms = Room::where('hotel_id', $reservation->hotel_id)
            ->where('room_type_id', $reservation->room_type_id)
            ->where('is_available', true)
            ->where('is_maintenance', false)
            ->where('status', 'available')
            ->whereNotExists(function ($query) use ($reservation) {
                $query->select(\DB::raw(1))
                    ->from('reservations')
                    ->whereRaw('reservations.room_id = rooms.id')
                    ->where('status', '!=', Reservation::STATUS_CANCELLED)
                    ->where('id', '!=', $reservation->id)
                    ->where(function ($q) use ($reservation) {
                        $q->where(function ($sq) use ($reservation) {
                            $sq->where('check_in', '<=', $reservation->check_in)
                              ->where('check_out', '>', $reservation->check_in);
                        })->orWhere(function ($sq) use ($reservation) {
                            $sq->where('check_in', '<', $reservation->check_out)
                              ->where('check_out', '>=', $reservation->check_out);
                        })->orWhere(function ($sq) use ($reservation) {
                            $sq->where('check_in', '>=', $reservation->check_in)
                              ->where('check_out', '<=', $reservation->check_out);
                        });
                    });
            })
            ->get();
    }
    
    /**
     * Processar a confirmação da reserva com o quarto selecionado
     *
     * @return void
     */
    public function processConfirmation(): void
    {
        $this->validate([
            'selectedRoomId' => 'required|exists:rooms,id',
            'paymentMethod' => 'required|string',
        ]);
        
        $reservation = Reservation::findOrFail($this->editingReservationId);
        $reservation->room_id = $this->selectedRoomId;
        
        // Confirma a reserva e marca o quarto como reservado
        if ($reservation->confirm($this->paymentMethod)) {
            session()->flash('message', "Reserva #{$reservation->id} confirmada com sucesso! Quarto atribuído: {$reservation->room->room_number}");
        } else {
            session()->flash('error', 'Não foi possível confirmar a reserva. Por favor, tente novamente.');
        }
        
        $this->closeModal();
    }
    
    /**
     * Cancelar uma reserva
     *
     * @param int $reservationId
     * @param string|null $reason
     * @param bool $refund
     * @return void
     */
    public function cancelReservation(int $reservationId, ?string $reason = null, bool $refund = false): void
    {
        $reservation = Reservation::findOrFail($reservationId);
        
        if ($reservation->cancel($reason, $refund)) {
            session()->flash('message', "Reserva #{$reservation->id} cancelada com sucesso!");
        } else {
            session()->flash('error', 'Não foi possível cancelar esta reserva. Verifique se o status atual permite cancelamento.');
        }
    }
    
    /**
     * Registrar check-in para uma reserva
     *
     * @param int $reservationId
     * @return void
     */
    public function checkIn(int $reservationId): void
    {
        $reservation = Reservation::findOrFail($reservationId);
        
        if ($reservation->checkIn()) {
            session()->flash('message', "Check-in para reserva #{$reservation->id} realizado com sucesso! Quarto {$reservation->room->room_number} marcado como ocupado.");
        } else {
            session()->flash('error', 'Não foi possível realizar o check-in. Verifique se a reserva está confirmada.');
        }
    }
    
    /**
     * Registrar check-out para uma reserva
     *
     * @param int $reservationId
     * @return void
     */
    public function checkOut(int $reservationId): void
    {
        $reservation = Reservation::findOrFail($reservationId);
        
        if ($reservation->checkOut()) {
            session()->flash('message', "Check-out para reserva #{$reservation->id} realizado com sucesso! Quarto {$reservation->room->room_number} marcado para limpeza.");
        } else {
            session()->flash('error', 'Não foi possível realizar o check-out. Verifique se o check-in foi realizado.');
        }
    }
    
    /**
     * Renderizar o componente
     *
     * @return View
     */
    public function render(): View
    {
        $query = Reservation::with(['user', 'hotel', 'roomType', 'room'])
            ->when($this->search, function ($q) {
                return $q->where(function ($sq) {
                    $sq->where('confirmation_code', 'like', '%' . $this->search . '%')
                       ->orWhereHas('user', function ($uq) {
                           $uq->where('name', 'like', '%' . $this->search . '%')
                             ->orWhere('email', 'like', '%' . $this->search . '%');
                       })
                       ->orWhereHas('hotel', function ($hq) {
                           $hq->where('name', 'like', '%' . $this->search . '%');
                       });
                });
            })
            ->when($this->status, function ($q) {
                return $q->where('status', $this->status);
            })
            ->when($this->paymentStatus, function ($q) {
                return $q->where('payment_status', $this->paymentStatus);
            })
            ->when($this->hotelFilter, function ($q) {
                return $q->where('hotel_id', $this->hotelFilter);
            })
            ->when($this->dateFilter, function ($q) {
                $today = Carbon::today();
                
                return match ($this->dateFilter) {
                    'today' => $q->where('check_in', $today),
                    'tomorrow' => $q->where('check_in', $today->copy()->addDay()),
                    'this_week' => $q->whereBetween('check_in', [
                        $today, 
                        $today->copy()->endOfWeek()
                    ]),
                    'next_week' => $q->whereBetween('check_in', [
                        $today->copy()->startOfWeek()->addWeek(),
                        $today->copy()->endOfWeek()->addWeek()
                    ]),
                    'current' => $q->current(),
                    'future' => $q->future(),
                    default => $q,
                };
            })
            ->orderBy($this->sortField, $this->sortDirection);
        
        // Estatísticas
        $stats = [
            'total' => Reservation::count(),
            'confirmed' => Reservation::where('status', Reservation::STATUS_CONFIRMED)->count(),
            'checked_in' => Reservation::where('status', Reservation::STATUS_CHECKED_IN)->count(),
            'today' => Reservation::where('check_in', Carbon::today())->count(),
        ];
        
        // Hotels para o filtro
        $hotels = Hotel::orderBy('name')->get();
        
        return view('livewire.admin.reservation-management', [
            'reservations' => $query->paginate($this->perPage),
            'stats' => $stats,
            'hotels' => $hotels,
            'statusOptions' => [
                Reservation::STATUS_PENDING => 'Pendente',
                Reservation::STATUS_CONFIRMED => 'Confirmada',
                Reservation::STATUS_CHECKED_IN => 'Check-in',
                Reservation::STATUS_CHECKED_OUT => 'Check-out',
                Reservation::STATUS_CANCELLED => 'Cancelada',
                Reservation::STATUS_NO_SHOW => 'No-show'
            ],
            'paymentStatusOptions' => [
                Reservation::PAYMENT_PENDING => 'Pendente',
                Reservation::PAYMENT_PAID => 'Pago',
                Reservation::PAYMENT_REFUNDED => 'Reembolsado',
                Reservation::PAYMENT_FAILED => 'Falhou',
                Reservation::PAYMENT_PARTIAL => 'Parcial'
            ],
            'paymentMethods' => [
                'credit_card' => 'Cartão de Crédito',
                'debit_card' => 'Cartão de Débito',
                'bank_transfer' => 'Transferência Bancária',
                'paypal' => 'PayPal',
                'mbway' => 'MB Way',
                'cash' => 'Dinheiro',
                'hotel_credit' => 'Crédito no Hotel',
            ],
            'dateFilterOptions' => [
                'today' => 'Hoje',
                'tomorrow' => 'Amanhã',
                'this_week' => 'Esta Semana',
                'next_week' => 'Próxima Semana',
                'current' => 'Estadias Atuais',
                'future' => 'Futuras'
            ]
        ])->layout('layouts.admin');
    }
}

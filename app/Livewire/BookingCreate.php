<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Hotel;
use App\Models\Location;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;

class BookingCreate extends Component
{
    // Dados da reserva
    public ?int $hotel_id = null;
    public ?int $room_type_id = null;
    public ?int $room_id = null;
    public string $check_in = '';
    public string $check_out = '';
    public int $guests = 1;
    public ?float $total_price = null;
    public string $payment_method = 'cash';
    public ?string $special_requests = null;
    
    // Dados do utilizador (para não logados)
    public string $guest_name = '';
    public string $guest_email = '';
    public string $guest_phone = '';
    
    // Dados auxiliares
    public ?Hotel $selectedHotel = null;
    public ?RoomType $selectedRoomType = null;
    public ?Room $selectedRoom = null;
    public Collection $availableRooms;
    public int $nights = 1;
    
    // Estados da interface
    public string $currentStep = 'details';
    public bool $isLoggedIn = false;
    public bool $agreedToTerms = false;
    
    /**
     * Regras de validação
     */
    protected function rules(): array
    {
        $rules = [
            'hotel_id' => ['required', 'integer', 'exists:hotels,id'],
            'room_type_id' => ['required', 'integer', 'exists:room_types,id'],
            'room_id' => ['nullable', 'integer', 'exists:rooms,id'],
            'check_in' => ['required', 'date', 'after_or_equal:today'],
            'check_out' => ['required', 'date', 'after:check_in'],
            'guests' => ['required', 'integer', 'min:1'],
            'payment_method' => ['required', 'string', 'in:cash,card,transfer,mobile_money'],
            'special_requests' => ['nullable', 'string', 'max:500'],
            'agreedToTerms' => ['required', 'accepted'],
        ];
        
        // Se não estiver logado, requer dados do hóspede
        if (!$this->isLoggedIn) {
            $rules['guest_name'] = ['required', 'string', 'max:255'];
            $rules['guest_email'] = ['required', 'email', 'max:255'];
            $rules['guest_phone'] = ['nullable', 'string', 'max:20'];
        }
        
        return $rules;
    }
    
    /**
     * Inicializar componente
     */
    public function mount(): void
    {
        $this->isLoggedIn = Auth::check();
        $this->availableRooms = collect();
        
        // Receber parâmetros da URL com casting correto
        $this->hotel_id = request()->get('hotel_id') ? (int) request()->get('hotel_id') : null;
        $this->room_type_id = request()->get('room_id') ? (int) request()->get('room_id') : null; // Na verdade é room_type_id
        $this->check_in = request()->get('check_in', '');
        $this->check_out = request()->get('check_out', '');
        $this->guests = (int) request()->get('guests', 1);
        $this->nights = (int) request()->get('nights', 1);
        $this->total_price = (float) request()->get('total', 0);
        
        // Carregar dados se foram fornecidos
        if ($this->hotel_id) {
            $this->loadHotelData();
        }
        
        if ($this->room_type_id) {
            $this->loadRoomTypeData();
        }
    }
    
    /**
     * Carregar dados do hotel
     */
    private function loadHotelData(): void
    {
        $this->selectedHotel = Hotel::find($this->hotel_id);
    }
    
    /**
     * Carregar dados do tipo de quarto
     */
    private function loadRoomTypeData(): void
    {
        $this->selectedRoomType = RoomType::find($this->room_type_id);
        $this->loadAvailableRooms();
    }
    
    /**
     * Carregar quartos disponíveis
     */
    private function loadAvailableRooms(): void
    {
        if (!$this->hotel_id || !$this->room_type_id) {
            $this->availableRooms = collect();
            return;
        }
        
        $this->availableRooms = Room::where('hotel_id', $this->hotel_id)
            ->where('room_type_id', $this->room_type_id)
            ->where('is_available', true)
            ->where('status', 'available')
            ->get();
    }
    
    /**
     * Avançar para próximo step
     */
    public function nextStep(): void
    {
        if ($this->currentStep === 'details') {
            $this->currentStep = 'confirmation';
        }
    }
    
    /**
     * Voltar para step anterior
     */
    public function previousStep(): void
    {
        if ($this->currentStep === 'confirmation') {
            $this->currentStep = 'details';
        }
    }
    
    /**
     * Confirmar reserva
     */
    public function confirmBooking(): void
    {
        $this->validate();
        
        try {
            DB::beginTransaction();
            
            // Criar ou encontrar utilizador
            $user = $this->getOrCreateUser();
            
            // Criar reserva
            $reservation = Reservation::create([
                'user_id' => $user->id,
                'hotel_id' => $this->hotel_id,
                'room_type_id' => $this->room_type_id,
                'room_id' => $this->room_id,
                'check_in' => $this->check_in,
                'check_out' => $this->check_out,
                'guests' => $this->guests,
                'nights' => $this->nights,
                'total_price' => $this->total_price,
                'status' => 'pending',
                'payment_status' => 'pending',
                'payment_method' => $this->payment_method,
                'special_requests' => $this->special_requests,
                'confirmation_code' => $this->generateConfirmationCode(),
                'is_refundable' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            // Atualizar disponibilidade do quarto se selecionado
            if ($this->room_id) {
                Room::where('id', $this->room_id)
                    ->update(['is_available' => false]);
            }
            
            DB::commit();
            
            // Redirecionar para página de sucesso
            redirect()->route('booking.success', ['booking' => $reservation->id]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Erro ao criar reserva. Tente novamente.');
        }
    }
    
    /**
     * Obter ou criar utilizador
     */
    private function getOrCreateUser(): User
    {
        if ($this->isLoggedIn) {
            return Auth::user();
        }
        
        // Procurar utilizador existente pelo email
        $user = User::where('email', $this->guest_email)->first();
        
        if (!$user) {
            // Criar novo utilizador
            $user = User::create([
                'name' => $this->guest_name,
                'email' => $this->guest_email,
                'phone' => $this->guest_phone,
                'password' => bcrypt(Str::random(16)), // Password temporária
                'email_verified_at' => now(),
            ]);
        }
        
        return $user;
    }
    
    /**
     * Gerar código de confirmação
     */
    private function generateConfirmationCode(): string
    {
        return 'OB' . strtoupper(Str::random(8));
    }
    
    /**
     * Render do componente
     */
    public function render(): View
    {
        return view('livewire.booking-create')
            ->layout('layouts.app');
    }
}

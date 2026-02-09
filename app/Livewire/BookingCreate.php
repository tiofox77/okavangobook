<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Coupon;
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
    public ?float $price_per_night = null; // Preço por noite recebido da URL
    public string $payment_method = '';
    public array $availablePaymentMethods = [];
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
    public bool $continueAsGuest = false;
    
    // Cupom de desconto
    public string $couponCode = '';
    public ?object $appliedCoupon = null;
    public ?float $discount = null;
    public ?float $finalPrice = null;
    
    /**
     * Observer para recarregar métodos de pagamento quando hotel muda
     */
    public function updatedHotelId($value)
    {
        $this->loadHotelData();
    }
    
    /**
     * Regras de validação
     */
    protected function rules(): array
    {
        // Obter apenas os valores dos métodos disponíveis
        $availableMethods = array_column($this->availablePaymentMethods, 'value');
        $allowedMethods = !empty($availableMethods) ? implode(',', $availableMethods) : 'cash';
        
        $rules = [
            'hotel_id' => ['required', 'integer', 'exists:hotels,id'],
            'room_type_id' => ['required', 'integer', 'exists:room_types,id'],
            'room_id' => ['nullable', 'integer', 'exists:rooms,id'],
            'check_in' => ['required', 'date', 'after_or_equal:today'],
            'check_out' => ['required', 'date', 'after:check_in'],
            'guests' => ['required', 'integer', 'min:1'],
            'payment_method' => ['required', 'string', 'in:' . $allowedMethods],
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
        $this->room_type_id = request()->get('room_type_id') 
            ? (int) request()->get('room_type_id') 
            : (request()->get('room_id') ? (int) request()->get('room_id') : null);
        $this->check_in = request()->get('check_in', '');
        $this->check_out = request()->get('check_out', '');
        $this->guests = (int) request()->get('guests', 1);
        
        // Receber preço por noite da URL (se disponível)
        $this->price_per_night = request()->get('price_per_night') 
            ? (float) request()->get('price_per_night') 
            : null;
        
        // Carregar dados se foram fornecidos
        if ($this->hotel_id) {
            $this->loadHotelData();
        }
        
        if ($this->room_type_id) {
            $this->loadRoomTypeData();
        }
        
        // Garantir que noites e preço sejam calculados no início
        if (!empty($this->check_in) && !empty($this->check_out)) {
            $this->calculateNights();
            $this->calculateTotalPrice();
        }
    }
    
    /**
     * Atualizar noites quando check_in mudar
     */
    public function updatedCheckIn()
    {
        $this->calculateNights();
        $this->calculateTotalPrice();
    }
    
    /**
     * Atualizar noites quando check_out mudar
     */
    public function updatedCheckOut()
    {
        $this->calculateNights();
        $this->calculateTotalPrice();
    }
    
    /**
     * Validar capacidade quando guests mudar
     */
    public function updatedGuests()
    {
        if ($this->selectedRoomType && $this->guests > $this->selectedRoomType->capacity) {
            // Reverter para a capacidade máxima
            $this->guests = $this->selectedRoomType->capacity;
            
            // Enviar notificação para o frontend
            $this->dispatch('show-toast', [
                'type' => 'error',
                'message' => "A capacidade máxima deste quarto é de {$this->selectedRoomType->capacity} pessoas."
            ]);
        }
    }
    
    /**
     * Calcular número de noites
     */
    private function calculateNights(): void
    {
        if (!empty($this->check_in) && !empty($this->check_out)) {
            try {
                $checkIn = Carbon::parse($this->check_in);
                $checkOut = Carbon::parse($this->check_out);
                $this->nights = max(1, $checkOut->diffInDays($checkIn));
            } catch (\Exception $e) {
                $this->nights = 1;
            }
        }
    }
    
    /**
     * Calcular preço total baseado nas noites
     */
    private function calculateTotalPrice(): void
    {
        if ($this->nights > 0) {
            // Usar preço por noite da URL se disponível, senão usar base_price do room type
            $pricePerNight = $this->price_per_night ?? ($this->selectedRoomType->base_price ?? 0);
            $this->total_price = $pricePerNight * $this->nights;
        }
    }
    
    /**
     * Carregar dados do hotel
     */
    private function loadHotelData(): void
    {
        if ($this->hotel_id) {
            $this->selectedHotel = Hotel::find($this->hotel_id);
            $this->loadAvailablePaymentMethods();
        }
    }
    
    private function loadAvailablePaymentMethods()
    {
        $this->availablePaymentMethods = [];
        
        if ($this->selectedHotel) {
            $this->availablePaymentMethods[] = ['value' => 'cash', 'label' => 'Dinheiro (Cash)'];
            
            if ($this->selectedHotel->accept_transfer) {
                $this->availablePaymentMethods[] = ['value' => 'transfer', 'label' => 'Transferência Bancária'];
            }
            
            if ($this->selectedHotel->accept_tpa_onsite) {
                $this->availablePaymentMethods[] = ['value' => 'tpa_onsite', 'label' => 'TPA no Local (Cartão na Chegada)'];
            }
            
            if (empty($this->payment_method) && !empty($this->availablePaymentMethods)) {
                $this->payment_method = $this->availablePaymentMethods[0]['value'];
            }
        }
    }
    
    /**
     * Carregar dados do tipo de quarto
     */
    private function loadRoomTypeData(): void
    {
        $this->selectedRoomType = RoomType::find($this->room_type_id);
        $this->loadAvailableRooms();
        
        // Sempre recalcular noites e preço após carregar room type
        if (!empty($this->check_in) && !empty($this->check_out)) {
            $this->calculateNights();
        }
        
        // Calcular preço baseado nas noites
        $this->calculateTotalPrice();
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
     * Aplicar cupom de desconto
     */
    public function applyCoupon()
    {
        if (empty($this->couponCode)) {
            session()->flash('coupon_error', 'Por favor, insira um código de cupom.');
            return;
        }

        $coupon = Coupon::where('code', strtoupper($this->couponCode))->active()->first();

        if (!$coupon) {
            session()->flash('coupon_error', 'Cupom inválido ou expirado.');
            $this->appliedCoupon = null;
            $this->discount = null;
            return;
        }

        if (!$coupon->isValid($this->total_price)) {
            if ($coupon->min_amount && $this->total_price < $coupon->min_amount) {
                session()->flash('coupon_error', 'Valor mínimo de reserva: ' . number_format($coupon->min_amount, 2) . ' Kz');
            } else {
                session()->flash('coupon_error', 'Cupom não disponível.');
            }
            $this->appliedCoupon = null;
            $this->discount = null;
            return;
        }

        $this->appliedCoupon = $coupon;
        $this->discount = $coupon->calculateDiscount($this->total_price);
        $this->finalPrice = $this->total_price - $this->discount;
        
        session()->flash('coupon_success', 'Cupom aplicado com sucesso!');
        session()->forget('coupon_error');
    }

    /**
     * Remover cupom aplicado
     */
    public function removeCoupon()
    {
        $this->couponCode = '';
        $this->appliedCoupon = null;
        $this->discount = null;
        $this->finalPrice = null;
        session()->forget(['coupon_success', 'coupon_error']);
    }

    /**
     * Renderizar componente
     */
    public function render(): View
    {
        return view('livewire.booking-create')
            ->layout('layouts.app');
    }
}

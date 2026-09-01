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
        if ($this->nights <= 0) {
            $this->total_price = 0;
            return;
        }

        $this->total_price = $this->resolvePricePerNight() * $this->nights;
    }

    /**
     * Preço por noite com cadeia de fallbacks — antes bastava faltar o
     * price_per_night na URL e o tipo de quarto não estar carregado para o
     * total ficar a 0 (e a reserva ser criada a 0 Kz).
     */
    private function resolvePricePerNight(): float
    {
        if ($this->price_per_night > 0) {
            return (float) $this->price_per_night;
        }

        if ($this->selectedRoomType?->base_price > 0) {
            return (float) $this->selectedRoomType->base_price;
        }

        // Recarrega o tipo de quarto se o estado se perdeu
        if ($this->room_type_id) {
            $base = (float) (RoomType::whereKey($this->room_type_id)->value('base_price') ?? 0);
            if ($base > 0) {
                return $base;
            }
        }

        // Último recurso: preço do hotel ou quarto mais barato disponível
        if ($this->hotel_id) {
            $min = (float) (Hotel::whereKey($this->hotel_id)->value('min_price') ?? 0);
            if ($min > 0) {
                return $min;
            }

            $cheapest = (float) (RoomType::where('hotel_id', $this->hotel_id)
                ->where('is_available', true)->where('base_price', '>', 0)
                ->min('base_price') ?? 0);
            if ($cheapest > 0) {
                return $cheapest;
            }
        }

        return 0.0;
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
     * Mensagens de validação em português (sem isto apareciam chaves cruas
     * como "validation.accepted" na confirmação).
     */
    protected function messages(): array
    {
        return [
            'guest_name.required' => 'Indique o nome completo do hóspede.',
            'guest_email.required' => 'Indique o email para receber a confirmação.',
            'guest_email.email' => 'O email não parece válido.',
            'check_in.required' => 'Escolha a data de check-in.',
            'check_in.after_or_equal' => 'O check-in não pode ser no passado.',
            'check_out.required' => 'Escolha a data de check-out.',
            'check_out.after' => 'O check-out tem de ser depois do check-in.',
            'guests.required' => 'Indique o número de hóspedes.',
            'agreedToTerms.accepted' => 'Tem de aceitar os termos e condições para reservar.',
            'agreedToTerms.required' => 'Tem de aceitar os termos e condições para reservar.',
            'payment_method.required' => 'Escolha o método de pagamento.',
        ];
    }

    /**
     * Avançar para próximo step — valida os campos DESTE passo primeiro
     * (antes avançava com o formulário do hóspede vazio e os erros ficavam
     * escondidos no passo anterior).
     */
    public function nextStep(): void
    {
        if ($this->currentStep === 'details') {
            $fields = ['check_in', 'check_out', 'guests'];
            if (! $this->isLoggedIn) {
                array_push($fields, 'guest_name', 'guest_email', 'guest_phone');
            }

            try {
                $this->validate(collect($this->rules())->only($fields)->all());
            } catch (\Illuminate\Validation\ValidationException $e) {
                $this->dispatch('show-toast', type: 'error', message: 'Verifique os campos assinalados antes de continuar.');
                throw $e;
            }

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
        try {
            $this->validate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Feedback visível (toast) além das mensagens inline — antes o clique
            // parecia "não fazer nada" quando o erro pertencia a outro passo.
            $this->dispatch('show-toast', type: 'error', message: 'Não foi possível confirmar: verifique os campos assinalados.');
            throw $e;
        }

        // Guarda de segurança no caminho do dinheiro: recalcula e recusa criar
        // uma reserva sem preço (era possível gravar total_price = 0).
        $this->calculateNights();
        $this->calculateTotalPrice();

        if ($this->nights <= 0) {
            $this->dispatch('show-toast', type: 'error', message: 'As datas selecionadas são inválidas. Escolha um check-out posterior ao check-in.');
            return;
        }

        if ((float) $this->total_price <= 0) {
            $this->dispatch('show-toast', type: 'error', message: 'Não foi possível calcular o preço desta reserva. Volte à página do alojamento e escolha o quarto novamente.');
            return;
        }

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

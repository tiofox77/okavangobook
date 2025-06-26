<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Reservation extends Model
{
    use HasFactory;
    
    /**
     * Os atributos que são atribuíveis em massa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'hotel_id',
        'room_type_id',
        'room_id',
        'check_in',
        'check_out',
        'guests',
        'total_price',
        'status',
        'payment_status',
        'payment_method',
        'transaction_id',
        'payment_details',
        'special_requests',
        'confirmed_at',
        'cancelled_at',
        'completed_at',
        'cancellation_reason',
        'confirmation_code',
        'is_refundable'
    ];
    
    /**
     * Os atributos que devem ser convertidos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'check_in' => 'date',
        'check_out' => 'date',
        'guests' => 'integer',
        'total_price' => 'decimal:2',
        'confirmed_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'completed_at' => 'datetime',
        'is_refundable' => 'boolean'
    ];
    
    /**
     * Os status possíveis de uma reserva.
     */
    public const STATUS_PENDING = 'pending';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_CHECKED_IN = 'checked_in';
    public const STATUS_CHECKED_OUT = 'checked_out';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_NO_SHOW = 'no_show';
    
    /**
     * Os status possíveis de pagamento.
     */
    public const PAYMENT_PENDING = 'pending';
    public const PAYMENT_PAID = 'paid';
    public const PAYMENT_REFUNDED = 'refunded';
    public const PAYMENT_FAILED = 'failed';
    public const PAYMENT_PARTIAL = 'partial';
    
    /**
     * Obter o utilizador que fez esta reserva.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Obter o hotel desta reserva.
     */
    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }
    
    /**
     * Obter o tipo de quarto desta reserva.
     */
    public function roomType(): BelongsTo
    {
        return $this->belongsTo(RoomType::class);
    }
    
    /**
     * Obter o quarto específico desta reserva.
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
    
    /**
     * Calcular o número de noites desta reserva.
     *
     * @return int
     */
    public function getNightsAttribute(): int
    {
        return $this->check_in->diffInDays($this->check_out);
    }
    
    /**
     * Calcular o preço total da reserva.
     *
     * @return float
     */
    public function calculateTotalPrice(): float
    {
        $roomType = $this->roomType;
        $nights = $this->nights;
        
        // Preço base por noite
        $basePrice = $roomType->base_price;
        
        // Preço total
        $total = $basePrice * $nights;
        
        return (float) $total;
    }
    
    /**
     * Confirmar a reserva e atribuir um quarto se disponível.
     *
     * @param string|null $paymentMethod
     * @param string|null $transactionId
     * @param array|null $paymentDetails
     * @return bool
     */
    public function confirm(?string $paymentMethod = null, ?string $transactionId = null, ?array $paymentDetails = null): bool
    {
        // Verifica se a reserva já está confirmada
        if ($this->status === self::STATUS_CONFIRMED) {
            return true;
        }
        
        // Atribui um quarto disponível se não tiver sido atribuído ainda
        if (!$this->room_id) {
            $this->assignRoom();
        }
        
        // Se não conseguiu atribuir um quarto, não pode confirmar
        if (!$this->room_id) {
            return false;
        }
        
        // Atualiza o status da reserva
        $this->status = self::STATUS_CONFIRMED;
        $this->confirmed_at = now();
        
        // Se um método de pagamento for fornecido, atualiza o status do pagamento
        if ($paymentMethod) {
            $this->payment_method = $paymentMethod;
            $this->payment_status = self::PAYMENT_PAID;
            
            if ($transactionId) {
                $this->transaction_id = $transactionId;
            }
            
            if ($paymentDetails) {
                $this->payment_details = json_encode($paymentDetails);
            }
        }
        
        // Gera um código de confirmação único
        if (!$this->confirmation_code) {
            $this->confirmation_code = strtoupper(Str::random(8));
        }
        
        // Marca o quarto como reservado/ocupado
        if ($this->room) {
            $this->room->markAsReserved();
        }
        
        return $this->save();
    }
    
    /**
     * Cancelar a reserva.
     *
     * @param string|null $reason
     * @param bool $refund
     * @return bool
     */
    public function cancel(?string $reason = null, bool $refund = false): bool
    {
        // Apenas reservas confirmadas ou pendentes podem ser canceladas
        if (!in_array($this->status, [self::STATUS_CONFIRMED, self::STATUS_PENDING])) {
            return false;
        }
        
        // Atualiza o status da reserva
        $this->status = self::STATUS_CANCELLED;
        $this->cancelled_at = now();
        
        if ($reason) {
            $this->cancellation_reason = $reason;
        }
        
        // Se deve reembolsar
        if ($refund && $this->payment_status === self::PAYMENT_PAID) {
            $this->payment_status = self::PAYMENT_REFUNDED;
        }
        
        // Libera o quarto
        if ($this->room) {
            $this->room->markAsAvailable();
        }
        
        return $this->save();
    }
    
    /**
     * Registrar check-in para a reserva.
     *
     * @return bool
     */
    public function checkIn(): bool
    {
        // Apenas reservas confirmadas podem fazer check-in
        if ($this->status !== self::STATUS_CONFIRMED) {
            return false;
        }
        
        // Atualiza o status da reserva
        $this->status = self::STATUS_CHECKED_IN;
        
        // Marca o quarto como ocupado
        if ($this->room) {
            $this->room->markAsOccupied();
        }
        
        return $this->save();
    }
    
    /**
     * Registrar check-out para a reserva.
     *
     * @return bool
     */
    public function checkOut(): bool
    {
        // Apenas reservas com check-in podem fazer check-out
        if ($this->status !== self::STATUS_CHECKED_IN) {
            return false;
        }
        
        // Atualiza o status da reserva
        $this->status = self::STATUS_CHECKED_OUT;
        $this->completed_at = now();
        
        // Marca o quarto como disponível mas precisando de limpeza
        if ($this->room) {
            $this->room->status = 'cleaning';
            $this->room->is_clean = false;
            $this->room->save();
        }
        
        return $this->save();
    }
    
    /**
     * Atribuir um quarto disponível para a reserva.
     *
     * @return bool
     */
    public function assignRoom(): bool
    {
        // Busca quartos disponíveis do tipo selecionado
        $availableRoom = Room::where('hotel_id', $this->hotel_id)
            ->where('room_type_id', $this->room_type_id)
            ->where('is_available', true)
            ->where('is_maintenance', false)
            ->whereNotExists(function ($query) {
                $query->select('\DB::raw(1)')
                    ->from('reservations')
                    ->whereRaw('reservations.room_id = rooms.id')
                    ->where('status', '!=', self::STATUS_CANCELLED)
                    ->where(function ($q) {
                        $q->where(function ($sq) {
                            $sq->where('check_in', '<=', $this->check_in)
                              ->where('check_out', '>', $this->check_in);
                        })->orWhere(function ($sq) {
                            $sq->where('check_in', '<', $this->check_out)
                              ->where('check_out', '>=', $this->check_out);
                        })->orWhere(function ($sq) {
                            $sq->where('check_in', '>=', $this->check_in)
                              ->where('check_out', '<=', $this->check_out);
                        });
                    });
            })
            ->first();
        
        // Se encontrou um quarto disponível, associa-o à reserva
        if ($availableRoom) {
            $this->room_id = $availableRoom->id;
            return true;
        }
        
        return false;
    }
    
    /**
     * Scope para filtrar reservas pendentes.
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }
    
    /**
     * Scope para filtrar reservas confirmadas.
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', self::STATUS_CONFIRMED);
    }
    
    /**
     * Scope para filtrar reservas canceladas.
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', self::STATUS_CANCELLED);
    }
    
    /**
     * Scope para filtrar reservas com check-in.
     */
    public function scopeCheckedIn($query)
    {
        return $query->where('status', self::STATUS_CHECKED_IN);
    }
    
    /**
     * Scope para filtrar reservas com check-out.
     */
    public function scopeCheckedOut($query)
    {
        return $query->where('status', self::STATUS_CHECKED_OUT);
    }
    
    /**
     * Scope para filtrar reservas atuais (hoje está entre check-in e check-out).
     */
    public function scopeCurrent($query)
    {
        $today = Carbon::today();
        return $query->where('status', '!=', self::STATUS_CANCELLED)
                    ->where('check_in', '<=', $today)
                    ->where('check_out', '>', $today);
    }
    
    /**
     * Scope para filtrar reservas futuras.
     */
    public function scopeFuture($query)
    {
        return $query->where('status', '!=', self::STATUS_CANCELLED)
                    ->where('check_in', '>', Carbon::today());
    }
}

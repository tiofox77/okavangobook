<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Price extends Model
{
    use HasFactory;
    
    /**
     * Os atributos que são atribuíveis em massa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'hotel_id',
        'room_type_id',
        'provider',
        'price',
        'currency',
        'original_price',
        'discount_percentage',
        'link',
        'check_in',
        'check_out',
        'breakfast_included',
        'free_cancellation',
        'pay_at_hotel',
        'cancellation_policy',
        'taxes_fees',
        'last_updated',
        'is_available'
    ];
    
    /**
     * Os atributos que devem ser convertidos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2',
        'original_price' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'check_in' => 'date',
        'check_out' => 'date',
        'breakfast_included' => 'boolean',
        'free_cancellation' => 'boolean',
        'pay_at_hotel' => 'boolean',
        'taxes_fees' => 'array',
        'last_updated' => 'datetime',
        'is_available' => 'boolean',
    ];
    
    /**
     * Obter o hotel associado a este preço.
     */
    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }
    
    /**
     * Obter o tipo de quarto associado a este preço.
     */
    public function roomType(): BelongsTo
    {
        return $this->belongsTo(RoomType::class);
    }
    
    /**
     * Scope para filtrar preços disponíveis.
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }
    
    /**
     * Scope para filtrar preços por provedor.
     */
    public function scopeByProvider($query, $provider)
    {
        return $query->where('provider', $provider);
    }
    
    /**
     * Scope para filtrar preços por datas de check-in e check-out.
     */
    public function scopeByDates($query, $checkIn, $checkOut)
    {
        return $query->where('check_in', '<=', $checkIn)
                    ->where('check_out', '>=', $checkOut);
    }
    
    /**
     * Scope para filtrar preços por valor máximo.
     */
    public function scopeMaxPrice($query, $maxPrice)
    {
        return $query->where('price', '<=', $maxPrice);
    }
    
    /**
     * Obter a duração da estadia em noites.
     */
    public function getDurationAttribute()
    {
        return $this->check_in->diffInDays($this->check_out);
    }
    
    /**
     * Obter o preço total para toda a estadia.
     */
    public function getTotalPriceAttribute()
    {
        return $this->price * $this->duration;
    }
}

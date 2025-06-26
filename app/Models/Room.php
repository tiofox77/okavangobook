<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
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
        'room_number',
        'floor',
        'is_available',
        'is_clean',
        'is_maintenance',
        'status',
        'notes',
        'available_from'
    ];
    
    /**
     * Os atributos que devem ser convertidos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_available' => 'boolean',
        'is_clean' => 'boolean',
        'is_maintenance' => 'boolean',
        'available_from' => 'date'
    ];
    
    /**
     * Obter o hotel a que este quarto pertence.
     */
    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }
    
    /**
     * Obter o tipo de quarto deste quarto.
     */
    public function roomType(): BelongsTo
    {
        return $this->belongsTo(RoomType::class);
    }
    
    /**
     * Obter todas as reservas para este quarto.
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }
    
    /**
     * Verificar se o quarto está disponível para reserva em determinadas datas.
     *
     * @param string|\DateTime $checkIn
     * @param string|\DateTime $checkOut
     * @return bool
     */
    public function isAvailable($checkIn, $checkOut): bool
    {
        if (!$this->is_available || $this->is_maintenance) {
            return false;
        }
        
        $reservationExists = $this->reservations()
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($checkIn, $checkOut) {
                $query->where(function ($q) use ($checkIn, $checkOut) {
                    $q->where('check_in', '<=', $checkIn)
                      ->where('check_out', '>', $checkIn);
                })->orWhere(function ($q) use ($checkIn, $checkOut) {
                    $q->where('check_in', '<', $checkOut)
                      ->where('check_out', '>=', $checkOut);
                })->orWhere(function ($q) use ($checkIn, $checkOut) {
                    $q->where('check_in', '>=', $checkIn)
                      ->where('check_out', '<=', $checkOut);
                });
            })
            ->exists();
            
        return !$reservationExists;
    }
    
    /**
     * Marcar quarto como ocupado.
     *
     * @return bool
     */
    public function markAsOccupied(): bool
    {
        $this->status = 'occupied';
        $this->is_available = false;
        return $this->save();
    }
    
    /**
     * Marcar quarto como disponível.
     *
     * @return bool
     */
    public function markAsAvailable(): bool
    {
        $this->status = 'available';
        $this->is_available = true;
        return $this->save();
    }
    
    /**
     * Marcar quarto como reservado.
     *
     * @return bool
     */
    public function markAsReserved(): bool
    {
        $this->status = 'reserved';
        $this->is_available = false;
        return $this->save();
    }
    
    /**
     * Marcar quarto como em manutenção.
     *
     * @param string|null $notes
     * @return bool
     */
    public function markAsMaintenance(?string $notes = null): bool
    {
        $this->status = 'maintenance';
        $this->is_maintenance = true;
        $this->is_available = false;
        
        if ($notes) {
            $this->notes = $notes;
        }
        
        return $this->save();
    }
    
    /**
     * Scope para filtrar quartos disponíveis.
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true)
                     ->where('status', 'available')
                     ->where('is_maintenance', false);
    }
    
    /**
     * Scope para filtrar quartos ocupados.
     */
    public function scopeOccupied($query)
    {
        return $query->where('status', 'occupied');
    }
    
    /**
     * Scope para filtrar quartos reservados.
     */
    public function scopeReserved($query)
    {
        return $query->where('status', 'reserved');
    }
    
    /**
     * Scope para filtrar quartos em manutenção.
     */
    public function scopeInMaintenance($query)
    {
        return $query->where('is_maintenance', true);
    }
}

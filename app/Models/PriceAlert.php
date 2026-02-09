<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceAlert extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'hotel_id',
        'room_type_id',
        'target_price',
        'current_price',
        'check_in',
        'check_out',
        'guests',
        'is_active',
        'notification_sent',
        'last_checked_at',
        'triggered_at',
    ];

    protected $casts = [
        'target_price' => 'decimal:2',
        'current_price' => 'decimal:2',
        'check_in' => 'date',
        'check_out' => 'date',
        'is_active' => 'boolean',
        'notification_sent' => 'boolean',
        'last_checked_at' => 'datetime',
        'triggered_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePending($query)
    {
        return $query->where('is_active', true)
            ->where('notification_sent', false);
    }

    public function checkPrice()
    {
        if (!$this->is_active || $this->notification_sent) {
            return false;
        }

        $currentPrice = $this->getCurrentMarketPrice();
        $this->current_price = $currentPrice;
        $this->last_checked_at = now();

        if ($currentPrice <= $this->target_price) {
            $this->triggered_at = now();
            $this->notification_sent = true;
            $this->save();

            Notification::createForUser(
                $this->user_id,
                'price_alert',
                'Alerta de Preço Atingido!',
                "O preço do {$this->hotel->name} baixou para {$currentPrice} Kz",
                'fas fa-tag',
                route('hotel.details', $this->hotel_id)
            );

            if ($this->user && $this->user->email) {
                \Illuminate\Support\Facades\Mail::to($this->user->email)
                    ->queue(new \App\Mail\PriceAlertTriggered($this));
            }

            return true;
        }

        $this->save();
        return false;
    }

    private function getCurrentMarketPrice()
    {
        if ($this->room_type_id) {
            return $this->roomType->base_price ?? 0;
        }
        
        return $this->hotel->roomTypes()->min('base_price') ?? 0;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HotelLeisureFacility extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id',
        'name',
        'type',
        'description',
        'images',
        'is_available',
        'is_free',
        'price_per_hour',
        'daily_price',
        'opening_time',
        'closing_time',
        'operating_days',
        'capacity',
        'requires_booking',
        'rules',
        'location',
        'display_order',
    ];

    protected $casts = [
        'images' => 'array',
        'is_available' => 'boolean',
        'is_free' => 'boolean',
        'price_per_hour' => 'decimal:2',
        'daily_price' => 'decimal:2',
        'operating_days' => 'array',
        'capacity' => 'integer',
        'requires_booking' => 'boolean',
        'display_order' => 'integer',
    ];

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }
}

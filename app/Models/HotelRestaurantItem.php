<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HotelRestaurantItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id',
        'name',
        'description',
        'category',
        'price',
        'image',
        'is_available',
        'is_vegetarian',
        'is_vegan',
        'is_gluten_free',
        'allergens',
        'preparation_time',
        'is_spicy',
        'display_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_available' => 'boolean',
        'is_vegetarian' => 'boolean',
        'is_vegan' => 'boolean',
        'is_gluten_free' => 'boolean',
        'is_spicy' => 'boolean',
        'allergens' => 'array',
        'display_order' => 'integer',
        'preparation_time' => 'integer',
    ];

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }
}

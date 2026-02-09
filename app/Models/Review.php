<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id',
        'user_id',
        'reservation_id',
        'rating',
        'title',
        'comment',
        'photos',
        'is_verified',
        'is_approved',
        'response',
        'responded_at',
    ];

    protected $casts = [
        'photos' => 'array',
        'is_verified' => 'boolean',
        'is_approved' => 'boolean',
        'responded_at' => 'datetime',
    ];

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SearchHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'search_type',
        'search_query',
        'filters',
        'hotel_id',
        'location',
        'check_in',
        'check_out',
        'guests',
        'price_min',
        'price_max',
    ];

    protected $casts = [
        'filters' => 'array',
        'check_in' => 'date',
        'check_out' => 'date',
        'price_min' => 'decimal:2',
        'price_max' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public static function track($data)
    {
        return static::create([
            'user_id' => auth()->id(),
            'session_id' => session()->getId(),
            'search_type' => $data['type'] ?? 'general',
            'search_query' => $data['query'] ?? null,
            'filters' => $data['filters'] ?? null,
            'hotel_id' => $data['hotel_id'] ?? null,
            'location' => $data['location'] ?? null,
            'check_in' => $data['check_in'] ?? null,
            'check_out' => $data['check_out'] ?? null,
            'guests' => $data['guests'] ?? null,
            'price_min' => $data['price_min'] ?? null,
            'price_max' => $data['price_max'] ?? null,
        ]);
    }
}

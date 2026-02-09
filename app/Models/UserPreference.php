<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'preferred_locations',
        'preferred_amenities',
        'preferred_stars',
        'budget_min',
        'budget_max',
        'travel_style',
        'interests',
        'receive_recommendations',
    ];

    protected $casts = [
        'preferred_locations' => 'array',
        'preferred_amenities' => 'array',
        'interests' => 'array',
        'budget_min' => 'decimal:2',
        'budget_max' => 'decimal:2',
        'receive_recommendations' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

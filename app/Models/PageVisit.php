<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageVisit extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id', 'user_id', 'path', 'url', 'method', 'referrer', 'referrer_host',
        'ip', 'country', 'country_code', 'city', 'device_type', 'browser', 'platform',
        'language', 'is_bot', 'user_agent',
    ];

    protected $casts = [
        'is_bot' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Apenas tráfego humano (exclui bots/crawlers). */
    public function scopeHumans(Builder $query): Builder
    {
        return $query->where('is_bot', false);
    }

    /** Visitas dentro de um intervalo [start, end]. */
    public function scopeBetweenDates(Builder $query, $start, $end): Builder
    {
        return $query->whereBetween('created_at', [$start, $end]);
    }
}

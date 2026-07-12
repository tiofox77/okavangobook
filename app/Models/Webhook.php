<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Webhook extends Model
{
    protected $fillable = [
        'name', 'url', 'events', 'secret', 'is_active', 'last_triggered_at', 'failure_count',
    ];

    protected $casts = [
        'events' => 'array',
        'is_active' => 'boolean',
        'last_triggered_at' => 'datetime',
    ];

    protected $hidden = ['secret'];

    protected static function booted(): void
    {
        static::creating(function (Webhook $webhook) {
            if (empty($webhook->secret)) {
                $webhook->secret = Str::random(48);
            }
        });
    }

    /**
     * Webhooks activos subscritos a um dado evento.
     */
    public static function forEvent(string $event)
    {
        return static::where('is_active', true)
            ->get()
            ->filter(fn (Webhook $w) => in_array($event, $w->events ?? [], true) || in_array('*', $w->events ?? [], true));
    }
}

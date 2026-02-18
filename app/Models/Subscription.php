<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plan_id',
        'billing_cycle',
        'status',
        'amount_paid',
        'currency',
        'payment_method',
        'transaction_id',
        'starts_at',
        'ends_at',
        'trial_ends_at',
        'cancelled_at',
        'cancellation_reason',
        'auto_renew',
        'metadata',
    ];

    protected $casts = [
        'amount_paid' => 'decimal:2',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'trial_ends_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'auto_renew' => 'boolean',
        'metadata' => 'array',
    ];

    const STATUS_ACTIVE = 'active';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_EXPIRED = 'expired';
    const STATUS_SUSPENDED = 'suspended';
    const STATUS_PENDING = 'pending';

    const CYCLE_MONTHLY = 'monthly';
    const CYCLE_YEARLY = 'yearly';
    const CYCLE_TRIAL = 'trial';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE)
                     ->where('ends_at', '>', now());
    }

    public function scopeExpired($query)
    {
        return $query->where('ends_at', '<=', now());
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE && $this->ends_at->isFuture();
    }

    public function isTrial(): bool
    {
        return $this->billing_cycle === self::CYCLE_TRIAL;
    }

    public function isExpired(): bool
    {
        return $this->ends_at->isPast();
    }

    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function daysRemaining(): int
    {
        if ($this->ends_at->isPast()) return 0;
        return (int) now()->diffInDays($this->ends_at, false);
    }

    public function cancel(?string $reason = null): void
    {
        $this->update([
            'status' => self::STATUS_CANCELLED,
            'cancelled_at' => now(),
            'cancellation_reason' => $reason,
            'auto_renew' => false,
        ]);
    }

    public function suspend(): void
    {
        $this->update(['status' => self::STATUS_SUSPENDED]);
    }

    public function reactivate(): void
    {
        $this->update([
            'status' => self::STATUS_ACTIVE,
            'cancelled_at' => null,
            'cancellation_reason' => null,
        ]);
    }

    public function expire(): void
    {
        $this->update(['status' => self::STATUS_EXPIRED]);
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function paymentTransactions()
    {
        return $this->hasMany(PaymentTransaction::class);
    }

    public static function expireOverdue(): int
    {
        $count = static::where('status', self::STATUS_ACTIVE)
            ->where('ends_at', '<=', now())
            ->update(['status' => self::STATUS_EXPIRED]);

        // Also expire cancelled subs that reached their end date
        $count += static::where('status', self::STATUS_CANCELLED)
            ->where('ends_at', '<=', now())
            ->update(['status' => self::STATUS_EXPIRED]);

        return $count;
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            self::STATUS_ACTIVE => $this->ends_at->isFuture() ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800',
            self::STATUS_CANCELLED => 'bg-red-100 text-red-800',
            self::STATUS_EXPIRED => 'bg-gray-100 text-gray-800',
            self::STATUS_SUSPENDED => 'bg-orange-100 text-orange-800',
            self::STATUS_PENDING => 'bg-blue-100 text-blue-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            self::STATUS_ACTIVE => $this->ends_at->isFuture() ? 'Activa' : 'A Expirar',
            self::STATUS_CANCELLED => 'Cancelada',
            self::STATUS_EXPIRED => 'Expirada',
            self::STATUS_SUSPENDED => 'Suspensa',
            self::STATUS_PENDING => 'Pendente',
            default => $this->status,
        };
    }
}

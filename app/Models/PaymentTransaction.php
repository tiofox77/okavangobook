<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class PaymentTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subscription_id',
        'plan_id',
        'billing_cycle',
        'amount',
        'currency',
        'payment_method',
        'status',
        'reference_code',
        'bank_name',
        'account_holder',
        'transfer_reference',
        'transfer_date',
        'proof_file',
        'user_notes',
        'admin_notes',
        'approved_by',
        'approved_at',
        'rejected_at',
        'rejection_reason',
        'expires_at',
        'metadata',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'transfer_date' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'expires_at' => 'datetime',
        'metadata' => 'array',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_EXPIRED = 'expired';

    const METHOD_BANK_TRANSFER = 'bank_transfer';
    const METHOD_MULTICAIXA = 'multicaixa';
    const METHOD_REFERENCE = 'reference';

    // ── Relationships ──

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function approvedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // ── Scopes ──

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    // ── Helpers ──

    public static function generateReferenceCode(): string
    {
        do {
            $code = 'PAY-' . strtoupper(Str::random(4)) . '-' . now()->format('ymd') . '-' . rand(1000, 9999);
        } while (static::where('reference_code', $code)->exists());

        return $code;
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function approve(int $adminId, ?string $notes = null): void
    {
        $this->update([
            'status' => self::STATUS_APPROVED,
            'approved_by' => $adminId,
            'approved_at' => now(),
            'admin_notes' => $notes,
        ]);

        // Activar a subscrição associada
        $user = $this->user;
        $plan = $this->plan;

        // Cancelar subscrição activa anterior
        $activeSub = $user->activeSubscription;
        if ($activeSub) {
            $activeSub->cancel('Upgrade via pagamento #' . $this->reference_code);
        }

        $startsAt = now();
        if ($this->billing_cycle === 'yearly') {
            $endsAt = $startsAt->copy()->addYear();
        } else {
            $endsAt = $startsAt->copy()->addMonth();
        }

        $subscription = Subscription::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'billing_cycle' => $this->billing_cycle,
            'status' => Subscription::STATUS_ACTIVE,
            'amount_paid' => $this->amount,
            'currency' => $this->currency,
            'payment_method' => $this->payment_method,
            'transaction_id' => $this->reference_code,
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
        ]);

        $this->update(['subscription_id' => $subscription->id]);

        // Atribuir role Propriedade se não tiver
        if (!$user->hasRole('Propriedade') && !$user->hasRole('Admin')) {
            $user->assignRole('Propriedade');
        }
    }

    public function reject(int $adminId, string $reason, ?string $notes = null): void
    {
        $this->update([
            'status' => self::STATUS_REJECTED,
            'approved_by' => $adminId,
            'rejected_at' => now(),
            'rejection_reason' => $reason,
            'admin_notes' => $notes,
        ]);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'bg-yellow-100 text-yellow-800',
            self::STATUS_APPROVED => 'bg-green-100 text-green-800',
            self::STATUS_REJECTED => 'bg-red-100 text-red-800',
            self::STATUS_EXPIRED => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'Pendente',
            self::STATUS_APPROVED => 'Aprovado',
            self::STATUS_REJECTED => 'Rejeitado',
            self::STATUS_EXPIRED => 'Expirado',
            default => $this->status,
        };
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        return match($this->payment_method) {
            self::METHOD_BANK_TRANSFER => 'Transferência Bancária',
            self::METHOD_MULTICAIXA => 'Multicaixa Express',
            self::METHOD_REFERENCE => 'Referência de Pagamento',
            default => $this->payment_method,
        };
    }
}

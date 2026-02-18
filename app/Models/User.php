<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    
    /**
     * Hotéis geridos por este utilizador.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function managedHotels()
    {
        return $this->hasMany(Hotel::class, 'user_id');
    }
    
    /**
     * Reservas feitas por este utilizador.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'user_id');
    }
    
    /**
     * Avaliações escritas por este utilizador.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    
    /**
     * Hotéis favoritos deste utilizador.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }
    
    /**
     * Hotéis favoritos (através de BelongsToMany).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function favoriteHotels()
    {
        return $this->belongsToMany(Hotel::class, 'favorites')->withTimestamps();
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function unreadNotifications()
    {
        return $this->notifications()->unread();
    }

    // ── Subscriptions ──

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function activeSubscription()
    {
        return $this->hasOne(Subscription::class)
                    ->where('status', 'active')
                    ->where('ends_at', '>', now())
                    ->latest('starts_at');
    }

    public function currentPlan(): ?Plan
    {
        $sub = $this->activeSubscription;
        return $sub?->plan;
    }

    public function hasActiveSubscription(): bool
    {
        return $this->activeSubscription()->exists();
    }

    public function isOnFreePlan(): bool
    {
        $plan = $this->currentPlan();
        return $plan?->is_free ?? false;
    }

    public function subscriptionDaysRemaining(): int
    {
        $sub = $this->activeSubscription;
        return $sub ? $sub->daysRemaining() : 0;
    }

    public function canAddHotel(): bool
    {
        $plan = $this->currentPlan();
        if (!$plan) return false;
        if ($plan->max_hotels >= 999) return true;
        return $this->managedHotels()->count() < $plan->max_hotels;
    }

    public function canUseFeature(string $feature): bool
    {
        $plan = $this->currentPlan();
        if (!$plan) return false;
        if (property_exists($plan, $feature) || isset($plan->$feature)) {
            return (bool) $plan->$feature;
        }
        return false;
    }

    public function subscribeToPlan(Plan $plan, string $cycle = 'monthly', ?float $amount = null): Subscription
    {
        // Cancelar subscrição activa anterior
        $activeSub = $this->activeSubscription;
        if ($activeSub) {
            $activeSub->cancel('Upgrade/mudança de plano');
        }

        $startsAt = now();

        if ($plan->is_free) {
            $endsAt = $startsAt->copy()->addYear();
            $cycle = 'trial';
            $amount = 0;
        } elseif ($cycle === 'yearly') {
            $endsAt = $startsAt->copy()->addYear();
            $amount = $amount ?? $plan->price_yearly;
        } else {
            $endsAt = $startsAt->copy()->addMonth();
            $amount = $amount ?? $plan->price_monthly;
        }

        return Subscription::create([
            'user_id' => $this->id,
            'plan_id' => $plan->id,
            'billing_cycle' => $cycle,
            'status' => Subscription::STATUS_ACTIVE,
            'amount_paid' => $amount,
            'currency' => $plan->currency,
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'trial_ends_at' => $plan->is_free ? $endsAt : null,
        ]);
    }

    public function paymentTransactions()
    {
        return $this->hasMany(PaymentTransaction::class);
    }

    public function hasPendingPayment(): bool
    {
        return $this->paymentTransactions()
            ->where('status', PaymentTransaction::STATUS_PENDING)
            ->exists();
    }

    public function pendingPayment()
    {
        return $this->paymentTransactions()
            ->where('status', PaymentTransaction::STATUS_PENDING)
            ->latest()
            ->first();
    }

    public function createPaymentRequest(Plan $plan, string $cycle, array $transferData = []): PaymentTransaction
    {
        // Expirar pedidos pendentes anteriores do mesmo utilizador
        $this->paymentTransactions()
            ->where('status', PaymentTransaction::STATUS_PENDING)
            ->update(['status' => PaymentTransaction::STATUS_EXPIRED]);

        $amount = $cycle === 'yearly' ? $plan->price_yearly : $plan->price_monthly;

        return PaymentTransaction::create([
            'user_id' => $this->id,
            'plan_id' => $plan->id,
            'billing_cycle' => $cycle,
            'amount' => $amount,
            'currency' => $plan->currency,
            'payment_method' => $transferData['payment_method'] ?? 'bank_transfer',
            'status' => PaymentTransaction::STATUS_PENDING,
            'reference_code' => PaymentTransaction::generateReferenceCode(),
            'bank_name' => $transferData['bank_name'] ?? null,
            'account_holder' => $transferData['account_holder'] ?? null,
            'transfer_reference' => $transferData['transfer_reference'] ?? null,
            'transfer_date' => $transferData['transfer_date'] ?? null,
            'proof_file' => $transferData['proof_file'] ?? null,
            'user_notes' => $transferData['user_notes'] ?? null,
            'expires_at' => now()->addHours(72),
        ]);
    }
}


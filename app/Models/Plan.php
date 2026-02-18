<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'badge_color',
        'icon',
        'price_monthly',
        'price_yearly',
        'currency',
        'max_hotels',
        'max_room_types_per_hotel',
        'max_images_per_hotel',
        'max_images_per_room',
        'featured_listing',
        'priority_support',
        'advanced_analytics',
        'review_responses',
        'restaurant_management',
        'leisure_management',
        'custom_branding',
        'api_access',
        'priority_search',
        'promotions',
        'export_reports',
        'trial_days',
        'sort_order',
        'is_active',
        'is_popular',
        'is_free',
        'extra_features',
    ];

    protected $casts = [
        'price_monthly' => 'decimal:2',
        'price_yearly' => 'decimal:2',
        'max_hotels' => 'integer',
        'max_room_types_per_hotel' => 'integer',
        'max_images_per_hotel' => 'integer',
        'max_images_per_room' => 'integer',
        'featured_listing' => 'boolean',
        'priority_support' => 'boolean',
        'advanced_analytics' => 'boolean',
        'review_responses' => 'boolean',
        'restaurant_management' => 'boolean',
        'leisure_management' => 'boolean',
        'custom_branding' => 'boolean',
        'api_access' => 'boolean',
        'priority_search' => 'boolean',
        'promotions' => 'boolean',
        'export_reports' => 'boolean',
        'trial_days' => 'integer',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
        'is_popular' => 'boolean',
        'is_free' => 'boolean',
        'extra_features' => 'array',
    ];

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function activeSubscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class)->where('status', 'active');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('price_monthly');
    }

    public function scopeFree($query)
    {
        return $query->where('is_free', true);
    }

    public function getYearlySavingsAttribute(): float
    {
        if ($this->price_monthly <= 0) return 0;
        $yearlyIfMonthly = $this->price_monthly * 12;
        return $yearlyIfMonthly - $this->price_yearly;
    }

    public function getYearlySavingsPercentAttribute(): int
    {
        if ($this->price_monthly <= 0) return 0;
        $yearlyIfMonthly = $this->price_monthly * 12;
        if ($yearlyIfMonthly <= 0) return 0;
        return (int) round(($this->yearly_savings / $yearlyIfMonthly) * 100);
    }

    public function getFormattedMonthlyPriceAttribute(): string
    {
        if ($this->is_free) return 'Grátis';
        return number_format($this->price_monthly, 0, ',', '.') . ' ' . $this->currency;
    }

    public function getFormattedYearlyPriceAttribute(): string
    {
        if ($this->is_free) return 'Grátis';
        return number_format($this->price_yearly, 0, ',', '.') . ' ' . $this->currency;
    }

    public function getFeaturesListAttribute(): array
    {
        $features = [];

        // Limites
        $maxHotels = $this->max_hotels >= 999 ? 'Ilimitados' : $this->max_hotels;
        $features[] = ['text' => "Até {$maxHotels} " . ($this->max_hotels == 1 ? 'propriedade' : 'propriedades'), 'included' => true];

        $maxRooms = $this->max_room_types_per_hotel >= 999 ? 'Ilimitados' : $this->max_room_types_per_hotel;
        $features[] = ['text' => "{$maxRooms} tipos de quarto por hotel", 'included' => true];

        $maxImages = $this->max_images_per_hotel >= 999 ? 'Ilimitadas' : $this->max_images_per_hotel;
        $features[] = ['text' => "{$maxImages} fotos por hotel", 'included' => true];

        // Funcionalidades booleanas
        $features[] = ['text' => 'Listagem em destaque', 'included' => $this->featured_listing];
        $features[] = ['text' => 'Prioridade na pesquisa', 'included' => $this->priority_search];
        $features[] = ['text' => 'Analytics avançados', 'included' => $this->advanced_analytics];
        $features[] = ['text' => 'Responder avaliações', 'included' => $this->review_responses];
        $features[] = ['text' => 'Gestão de restaurante', 'included' => $this->restaurant_management];
        $features[] = ['text' => 'Gestão de lazer', 'included' => $this->leisure_management];
        $features[] = ['text' => 'Promoções e cupões', 'included' => $this->promotions];
        $features[] = ['text' => 'Exportar relatórios', 'included' => $this->export_reports];
        $features[] = ['text' => 'Suporte prioritário', 'included' => $this->priority_support];
        $features[] = ['text' => 'Marca personalizada', 'included' => $this->custom_branding];
        $features[] = ['text' => 'Acesso à API', 'included' => $this->api_access];

        return $features;
    }
}

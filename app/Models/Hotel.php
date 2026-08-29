<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Hotel extends Model
{
    use HasFactory;

    /**
     * Garante que todo hotel tenha um slug único.
     * Protege as rotas /hotel/{slug} contra slugs nulos vindos de
     * seeders, factories ou criação programática.
     */
    protected static function booted(): void
    {
        static::saving(function (Hotel $hotel) {
            if (empty($hotel->slug) && !empty($hotel->name)) {
                $hotel->slug = static::generateUniqueSlug($hotel->name, $hotel->id);
            }
        });
    }

    /**
     * Gera um slug único a partir de um texto base, evitando colisões.
     */
    public static function generateUniqueSlug(string $base, $ignoreId = null): string
    {
        $slug = Str::slug($base) ?: 'hotel';
        $original = $slug;
        $suffix = 2;

        while (static::where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $original . '-' . $suffix++;
        }

        return $slug;
    }
    
    /**
     * Os atributos que são atribuíveis em massa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'property_type',
        'description',
        'address',
        'location_id',
        'user_id',
        'stars',
        'thumbnail',
        'images',
        'latitude',
        'longitude',
        'amenities',
        'check_in_time',
        'check_out_time',
        'phone',
        'email',
        'website',
        'min_price',
        'rating',
        'reviews_count',
        'is_featured',
        'is_active',
        'slug',
        'featured_image',
        'policies',
        'accept_transfer',
        'accept_tpa_onsite',
        'transfer_instructions',
        'bank_name',
        'account_number',
        'iban',
    ];
    
    /**
     * Os atributos que devem ser convertidos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'images' => 'array',
        'amenities' => 'array',
        'stars' => 'integer',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'min_price' => 'decimal:2',
        'rating' => 'decimal:2',
        'reviews_count' => 'integer',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'accept_transfer' => 'boolean',
        'accept_tpa_onsite' => 'boolean',
    ];
    
    /**
     * Accessor para obter a imagem em destaque do hotel
     * Retorna a thumbnail ou uma imagem padrão caso não exista
     *
     * @return string
     */
    public function getFeaturedImageAttribute()
    {
        // Usar ImageHelper para validar a imagem se estiver disponível
        if (class_exists('\App\Helpers\ImageHelper')) {
            return \App\Helpers\ImageHelper::getValidImage($this->thumbnail, 'hotel');
        }
        
        // Verificação básica caso o ImageHelper não esteja disponível
        if (!empty($this->thumbnail) && filter_var($this->thumbnail, FILTER_VALIDATE_URL)) {
            return $this->thumbnail;
        }
        
        return \App\Helpers\ImageHelper::generateDefaultSvg('hotel');
    }
    
    /**
     * Obter a localização associada ao hotel
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }
    
    /**
     * Obter o usuário responsável pelo hotel
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Obter o utilizador que gere este hotel.
     */
    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    /**
     * Obter todos os tipos de quarto disponíveis neste hotel.
     */
    public function roomTypes(): HasMany
    {
        return $this->hasMany(RoomType::class)->orderBy('position')->orderBy('id');
    }
    
    /**
     * Obter todos os quartos disponíveis neste hotel.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }
    
    /**
     * Obter todos os preços disponíveis para este hotel.
     */
    public function prices(): HasMany
    {
        return $this->hasMany(Price::class);
    }
    
    /**
     * Obter todos os itens do restaurante deste hotel.
     */
    public function restaurantItems(): HasMany
    {
        return $this->hasMany(HotelRestaurantItem::class)->orderBy('display_order');
    }
    
    /**
     * Obter todas as instalações de lazer deste hotel.
     */
    public function leisureFacilities(): HasMany
    {
        return $this->hasMany(HotelLeisureFacility::class)->orderBy('display_order');
    }
    
    /**
     * Obter todas as reservas deste hotel.
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Obter todas as avaliações deste hotel.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function agentMedia(): HasMany
    {
        return $this->hasMany(AgentMedia::class)->orderBy('position')->orderBy('id');
    }
    
    /**
     * Obter avaliações aprovadas deste hotel.
     */
    public function approvedReviews(): HasMany
    {
        return $this->hasMany(Review::class)->where('is_approved', true)->orderBy('created_at', 'desc');
    }
    
    /**
     * Utilizadores que favoritaram este hotel.
     */
    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }
    
    /**
     * Scope para filtrar hotéis em destaque.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
    
    /**
     * Scope para filtrar hotéis ativos.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    /**
     * Scope para filtrar hotéis por número de estrelas.
     */
    public function scopeByStars($query, $stars)
    {
        return $query->where('stars', $stars);
    }
    
    /**
     * Scope para filtrar hotéis por avaliação mínima.
     */
    public function scopeMinRating($query, $rating)
    {
        return $query->where('rating', '>=', $rating);
    }
    
    /**
     * Scope para filtrar hotéis por localização.
     */
    public function scopeByLocation($query, $locationId)
    {
        return $query->where('location_id', $locationId);
    }
}

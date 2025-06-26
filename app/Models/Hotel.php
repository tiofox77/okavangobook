<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Hotel extends Model
{
    use HasFactory;
    
    /**
     * Os atributos que são atribuíveis em massa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
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
        'slug'
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
        
        // Retornar uma imagem padrão confiável
        return 'https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80';
    }
    
    /**
     * Obter a localização a que este hotel pertence.
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
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
        return $this->hasMany(RoomType::class);
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

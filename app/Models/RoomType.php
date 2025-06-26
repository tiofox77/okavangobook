<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RoomType extends Model
{
    use HasFactory;
    
    /**
     * Os atributos que são atribuíveis em massa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'hotel_id',
        'name',
        'description',
        'capacity',
        'beds',
        'bed_type',
        'size',
        'amenities',
        'images',
        'is_available',
        'base_price',
        'rooms_count',
        'is_featured'
    ];
    
    /**
     * Os atributos que devem ser convertidos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amenities' => 'array',
        'images' => 'array',
        'capacity' => 'integer',
        'beds' => 'integer',
        'size' => 'integer',
        'is_available' => 'boolean',
        'base_price' => 'decimal:2',
        'rooms_count' => 'integer',
        'is_featured' => 'boolean',
    ];
    
    /**
     * Obter o hotel a que este tipo de quarto pertence.
     */
    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }
    
    /**
     * Obter todos os preços disponíveis para este tipo de quarto.
     */
    public function prices(): HasMany
    {
        return $this->hasMany(Price::class);
    }
    
    /**
     * Scope para filtrar tipos de quarto disponíveis.
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }
    
    /**
     * Scope para filtrar tipos de quarto em destaque.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
    
    /**
     * Scope para filtrar tipos de quarto por capacidade mínima.
     */
    public function scopeMinCapacity($query, $capacity)
    {
        return $query->where('capacity', '>=', $capacity);
    }
}

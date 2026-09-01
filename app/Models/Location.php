<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    use HasFactory;

    /**
     * Slug único garantido em qualquer criação (admin, seeders, Agent API).
     * A coluna é UNIQUE NOT NULL, mas o formulário do admin não a preenchia.
     */
    protected static function booted(): void
    {
        static::saving(function (Location $location) {
            if (empty($location->slug) && !empty($location->name)) {
                $location->slug = static::generateUniqueSlug($location->name, $location->id);
            }
        });
    }

    public static function generateUniqueSlug(string $base, $ignoreId = null): string
    {
        $slug = \Illuminate\Support\Str::slug($base) ?: 'destino';
        $original = $slug;
        $suffix = 2;

        while (static::where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $original . '-' . $suffix++;
        }

        return $slug;
    }

    public const PROVINCE_NAMES = [
        'bengo' => 'Bengo',
        'benguela' => 'Benguela',
        'bie' => 'Bié',
        'cabinda' => 'Cabinda',
        'cuando-cubango' => 'Cuando Cubango',
        'cuanza-norte' => 'Cuanza Norte',
        'cuanza-sul' => 'Cuanza Sul',
        'cunene' => 'Cunene',
        'huambo' => 'Huambo',
        'huila' => 'Huíla',
        'luanda' => 'Luanda',
        'lunda-norte' => 'Lunda Norte',
        'lunda-sul' => 'Lunda Sul',
        'malanje' => 'Malanje',
        'moxico' => 'Moxico',
        'namibe' => 'Namibe',
        'uige' => 'Uíge',
        'zaire' => 'Zaire',
    ];
    
    /**
     * Os atributos que são atribuíveis em massa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'province',
        'description',
        'image',
        'slug',
        'latitude',
        'longitude',
        'is_featured',
        'is_active',
        'hotels_count',
        'population',
        'capital'
    ];
    
    /**
     * Os atributos que devem ser convertidos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'hotels_count' => 'integer',
        'population' => 'integer',
    ];
    
    /**
     * Obter todos os hotéis associados a esta localização.
     */
    public function hotels(): HasMany
    {
        return $this->hasMany(Hotel::class);
    }

    /**
     * Galeria multimédia do destino (imagens e vídeos, ordenados).
     */
    public function media(): HasMany
    {
        return $this->hasMany(LocationMedia::class)->orderBy('position')->orderBy('id');
    }
    
    /**
     * Obter as buscas feitas para esta localização.
     */
    public function searches(): HasMany
    {
        return $this->hasMany(Search::class);
    }
    
    /**
     * Acessor para obter o nome da localização formatado com a província.
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->name}, " . self::provinceName($this->province);
    }

    public static function provinceName(?string $province): string
    {
        if (!$province) {
            return '';
        }

        return self::PROVINCE_NAMES[$province]
            ?? \Illuminate\Support\Str::title(str_replace('-', ' ', $province));
    }
}

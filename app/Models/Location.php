<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    use HasFactory;

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

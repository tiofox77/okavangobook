<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Search extends Model
{
    use HasFactory;
    
    /**
     * Os atributos que são atribuíveis em massa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'location_id',
        'location_text',
        'check_in',
        'check_out',
        'guests',
        'rooms',
        'filters',
        'sort_by',
        'ip_address',
        'user_agent',
        'results_count',
        'is_saved'
    ];
    
    /**
     * Os atributos que devem ser convertidos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'check_in' => 'date',
        'check_out' => 'date',
        'guests' => 'integer',
        'rooms' => 'integer',
        'filters' => 'array',
        'results_count' => 'integer',
        'is_saved' => 'boolean',
    ];
    
    /**
     * Obter o usuário que fez esta busca (se estiver logado).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Obter a localização relacionada a esta busca.
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }
    
    /**
     * Scope para filtrar buscas salvas.
     */
    public function scopeSaved($query)
    {
        return $query->where('is_saved', true);
    }
    
    /**
     * Scope para filtrar buscas por usuário.
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
    
    /**
     * Scope para filtrar buscas por localização.
     */
    public function scopeByLocation($query, $locationId)
    {
        return $query->where('location_id', $locationId);
    }
    
    /**
     * Obter a duração da estadia em noites.
     */
    public function getDurationAttribute()
    {
        return $this->check_in->diffInDays($this->check_out);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgentMedia extends Model
{
    protected $table = 'agent_media';

    protected $fillable = [
        'disk', 'path', 'url', 'mime_type', 'size', 'width', 'height',
        'alt_text', 'title', 'uploaded_by_token_id', 'hotel_id', 'position', 'is_cover',
    ];

    protected $casts = [
        'position' => 'integer',
        'is_cover' => 'boolean',
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }
}

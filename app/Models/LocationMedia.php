<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LocationMedia extends Model
{
    use HasFactory;

    protected $table = 'location_media';

    protected $fillable = ['location_id', 'type', 'url', 'title', 'position'];

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    /** É um vídeo do YouTube? (para embed em iframe) */
    public function youtubeId(): ?string
    {
        if ($this->type !== 'video') {
            return null;
        }
        if (preg_match('~(?:youtube\.com/(?:watch\?v=|embed/|shorts/)|youtu\.be/)([\w-]{6,20})~i', $this->url, $m)) {
            return $m[1];
        }
        return null;
    }

    /** É um vídeo do Vimeo? */
    public function vimeoId(): ?string
    {
        if ($this->type !== 'video') {
            return null;
        }
        if (preg_match('~vimeo\.com/(?:video/)?(\d{6,12})~i', $this->url, $m)) {
            return $m[1];
        }
        return null;
    }
}

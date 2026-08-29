<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgentPage extends Model
{
    protected $fillable = [
        'title', 'slug', 'status', 'blocks', 'seo', 'preview_token',
        'published_at', 'created_by', 'updated_by',
    ];

    protected $hidden = ['preview_token'];

    protected $casts = [
        'blocks' => 'array',
        'seo' => 'array',
        'published_at' => 'datetime',
    ];
}

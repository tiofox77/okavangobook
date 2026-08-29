<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgentIdempotencyKey extends Model
{
    protected $fillable = [
        'agent_token_id', 'key', 'method', 'path', 'request_hash',
        'response_status', 'response_body', 'completed_at',
    ];

    protected $casts = ['completed_at' => 'datetime'];
}

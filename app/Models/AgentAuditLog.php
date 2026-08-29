<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgentAuditLog extends Model
{
    protected $fillable = [
        'agent_token_id', 'actor', 'event', 'method', 'route', 'ip', 'reason',
        'idempotency_key', 'subject_type', 'subject_id', 'before', 'after',
        'status_code', 'dry_run',
    ];

    protected $casts = [
        'before' => 'array',
        'after' => 'array',
        'dry_run' => 'boolean',
    ];
}

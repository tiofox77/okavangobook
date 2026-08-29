<?php

namespace App\Services;

use App\Models\AgentAuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class AgentAuditService
{
    public function record(
        Request $request,
        string $event,
        Model|string|null $subject,
        mixed $before,
        mixed $after,
        int $statusCode = 200,
        bool $dryRun = false,
    ): AgentAuditLog {
        $token = $request->attributes->get('agentToken');

        return AgentAuditLog::create([
            'agent_token_id' => $token?->id,
            'actor' => $token?->name ?? 'unknown-agent',
            'event' => $event,
            'method' => $request->method(),
            'route' => '/'.$request->path(),
            'ip' => $request->ip(),
            'reason' => $request->header('X-Reason'),
            'idempotency_key' => $request->header('Idempotency-Key'),
            'subject_type' => $subject instanceof Model ? $subject::class : $subject,
            'subject_id' => $subject instanceof Model ? (string) $subject->getKey() : null,
            'before' => $this->normalise($before),
            'after' => $this->normalise($after),
            'status_code' => $statusCode,
            'dry_run' => $dryRun,
        ]);
    }

    private function normalise(mixed $value): mixed
    {
        if ($value instanceof Model) {
            return $value->toArray();
        }

        return $value;
    }
}

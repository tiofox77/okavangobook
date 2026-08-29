<?php

namespace App\Http\Middleware;

use App\Models\AgentIdempotencyKey;
use App\Models\AgentAuditLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\Response;

class RequireAgentWriteContext
{
    public function handle(Request $request, Closure $next): Response
    {
        if (in_array($request->method(), ['GET', 'HEAD', 'OPTIONS'], true)) {
            return $next($request);
        }

        $reason = trim((string) $request->header('X-Reason'));
        $key = trim((string) $request->header('Idempotency-Key'));

        if ($reason === '' || mb_strlen($reason) < 5) {
            return response()->json(['message' => 'X-Reason obrigatório (mínimo 5 caracteres).'], 422);
        }

        if ($key === '' || mb_strlen($key) > 128) {
            return response()->json(['message' => 'Idempotency-Key obrigatório (máximo 128 caracteres).'], 422);
        }

        $token = $request->attributes->get('agentToken');
        $hash = $this->requestHash($request);
        $record = AgentIdempotencyKey::where('agent_token_id', $token->id)->where('key', $key)->first();

        if ($record) {
            if (!hash_equals($record->request_hash, $hash)) {
                return response()->json(['message' => 'Idempotency-Key já usada com outro payload.'], 409);
            }

            if ($record->completed_at) {
                return response($record->response_body, $record->response_status)
                    ->header('Content-Type', 'application/json')
                    ->header('Idempotency-Replayed', 'true');
            }

            return response()->json(['message' => 'Pedido idempotente ainda em processamento.'], 409);
        }

        $record = AgentIdempotencyKey::create([
            'agent_token_id' => $token->id,
            'key' => $key,
            'method' => $request->method(),
            'path' => '/'.$request->path(),
            'request_hash' => $hash,
        ]);

        $request->attributes->set('agentIdempotency', $record);
        $response = $next($request);

        $record->update([
            'response_status' => $response->getStatusCode(),
            'response_body' => $response->getContent(),
            'completed_at' => now(),
        ]);

        $alreadyAudited = AgentAuditLog::where('agent_token_id', $token->id)
            ->where('idempotency_key', $key)
            ->exists();

        if (!$alreadyAudited) {
            AgentAuditLog::create([
                'agent_token_id' => $token->id,
                'actor' => $token->name,
                'method' => $request->method(),
                'route' => '/'.$request->path(),
                'ip' => $request->ip(),
                'reason' => $reason,
                'idempotency_key' => $key,
                'before' => null,
                'after' => ['result' => $response->isSuccessful() ? 'completed' : 'rejected'],
                'status_code' => $response->getStatusCode(),
                'dry_run' => $request->boolean('dry_run'),
            ]);
        }

        return $response;
    }

    private function requestHash(Request $request): string
    {
        $payload = $request->all();
        $payload['_files'] = $this->fileFingerprints($request->allFiles());

        return hash('sha256', json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }

    private function fileFingerprints(array $files): array
    {
        foreach ($files as $key => $file) {
            if (is_array($file)) {
                $files[$key] = $this->fileFingerprints($file);
                continue;
            }

            if ($file instanceof UploadedFile) {
                $files[$key] = [
                    'name' => $file->getClientOriginalName(),
                    'mime' => $file->getClientMimeType(),
                    'size' => $file->getSize(),
                    'sha256' => hash_file('sha256', $file->getRealPath()),
                ];
            }
        }

        return $files;
    }
}

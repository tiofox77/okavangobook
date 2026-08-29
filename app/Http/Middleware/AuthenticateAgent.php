<?php

namespace App\Http\Middleware;

use App\Models\AgentToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateAgent
{
    public function handle(Request $request, Closure $next): Response
    {
        $plain = $request->bearerToken();

        if (!$plain || !str_starts_with($plain, AgentToken::PREFIX)) {
            return response()->json(['message' => 'Bearer token kstay__ obrigatório.'], 401);
        }

        $token = AgentToken::where('token_hash', hash('sha256', $plain))->first();

        if (!$token || !$token->isUsableFrom($request->ip())) {
            return response()->json(['message' => 'Token inválido, expirado, revogado ou IP não autorizado.'], 401);
        }

        $token->forceFill(['last_used_at' => now()])->saveQuietly();
        $request->attributes->set('agentToken', $token);

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;

class VerifyApiKey
{
    /**
     * Valida a API key enviada em `X-API-Key` (ou Bearer) contra a chave
     * configurada no admin. Usada para proteger endpoints de escrita.
     */
    public function handle(Request $request, Closure $next)
    {
        $configured = Setting::get('api_key', config('services.api.key'));

        if (empty($configured)) {
            return response()->json([
                'message' => 'A API não está configurada. Gere uma API key no painel de administração.',
            ], 503);
        }

        $provided = $request->header('X-API-Key') ?: $request->bearerToken();

        if (!$provided || !hash_equals((string) $configured, (string) $provided)) {
            return response()->json(['message' => 'API key inválida ou em falta.'], 401);
        }

        return $next($request);
    }
}

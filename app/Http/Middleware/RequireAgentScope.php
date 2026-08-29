<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireAgentScope
{
    public function handle(Request $request, Closure $next, string ...$scopes): Response
    {
        $token = $request->attributes->get('agentToken');

        foreach ($scopes as $scope) {
            if (!$token?->hasScope($scope)) {
                return response()->json([
                    'message' => 'Escopo insuficiente.',
                    'required_scope' => $scope,
                ], 403);
            }
        }

        return $next($request);
    }
}

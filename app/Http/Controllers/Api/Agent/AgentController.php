<?php

namespace App\Http\Controllers\Api\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    public function me(Request $request)
    {
        $token = $request->attributes->get('agentToken');

        return response()->json(['data' => [
            'id' => $token->id,
            'name' => $token->name,
            'scopes' => $token->scopes,
            'allowed_ips' => $token->allowed_ips ?: ['any'],
            'expires_at' => $token->expires_at->toIso8601String(),
            'last_used_at' => $token->last_used_at?->toIso8601String(),
            'api_version' => config('agent_api.version', 'v1'),
            'capabilities' => config('agent_api.modules', []),
            'react_block_types' => config('agent_api.react_block_types', []),
        ]]);
    }
}

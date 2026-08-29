<?php

namespace App\Console\Commands;

use App\Models\AgentToken;
use Illuminate\Console\Command;

class CreateAgentToken extends Command
{
    protected $signature = 'agent-token:create
        {name : Nome do agente/integrador}
        {--scope=* : Escopos permitidos}
        {--ip=* : IPs autorizados; vazio permite qualquer IP}
        {--days=30 : Validade em dias}';

    protected $description = 'Emite um Bearer token kstay__ para a Agent API';

    public function handle(): int
    {
        $maxDays = max(1, (int) config('agent_api.token_max_days', 365));
        $days = max(1, min($maxDays, (int) $this->option('days')));
        $scopes = array_values(array_unique(array_filter($this->option('scope'))));

        if ($scopes === []) {
            $this->error('Informe pelo menos um --scope. Use --scope=* apenas para um agente totalmente confiável.');
            return self::FAILURE;
        }

        $allowedScopes = config('agent_api.scopes', []);
        $unknown = array_diff($scopes, array_merge($allowedScopes, ['*']));
        if ($unknown !== []) {
            $this->error('Escopos desconhecidos: '.implode(', ', $unknown));
            return self::FAILURE;
        }

        [$token, $plain] = AgentToken::issue([
            'name' => $this->argument('name'),
            'scopes' => $scopes,
            'allowed_ips' => array_values(array_unique(array_filter($this->option('ip')))),
            'expires_at' => now()->addDays($days),
            'created_by' => auth()->id(),
        ]);

        $this->warn('Guarde este token agora; ele não voltará a ser mostrado:');
        $this->line($plain);
        $this->newLine();
        $this->table(['ID', 'Nome', 'Escopos', 'IPs', 'Expira'], [[
            $token->id,
            $token->name,
            implode(', ', $token->scopes),
            implode(', ', $token->allowed_ips ?: ['qualquer']),
            $token->expires_at->toIso8601String(),
        ]]);

        return self::SUCCESS;
    }
}

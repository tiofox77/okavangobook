<?php

namespace App\Console\Commands;

use App\Models\AgentToken as AgentTokenModel;
use Illuminate\Console\Command;

class AgentToken extends Command
{
    protected $signature = 'agent:token
        {name : Nome do agente ou integração}
        {--scopes=* : Escopos permitidos; use * apenas para agente confiável}
        {--ip=* : IPs autorizados; vazio permite qualquer IP}
        {--days=90 : Validade em dias}';

    protected $description = 'Emite um token Bearer kstay__ para a Agent API';

    public function handle(): int
    {
        $maxDays = max(1, (int) config('agent_api.token_max_days', 365));
        $requestedDays = (int) $this->option('days');

        if ($requestedDays < 1 || $requestedDays > $maxDays) {
            $this->error("A validade deve estar entre 1 e {$maxDays} dias.");

            return self::FAILURE;
        }

        $scopes = array_values(array_unique(array_filter($this->option('scopes'))));
        if ($scopes === []) {
            $this->error("Informe pelo menos um --scopes. Use --scopes='*' apenas para acesso total.");

            return self::FAILURE;
        }

        $allowedScopes = config('agent_api.scopes', []);
        $unknown = array_diff($scopes, array_merge($allowedScopes, ['*']));
        if ($unknown !== []) {
            $this->error('Escopos desconhecidos: '.implode(', ', $unknown));

            return self::FAILURE;
        }

        [$token, $plain] = AgentTokenModel::issue([
            'name' => $this->argument('name'),
            'scopes' => $scopes,
            'allowed_ips' => array_values(array_unique(array_filter($this->option('ip')))),
            'expires_at' => now()->addDays($requestedDays),
            'created_by' => null,
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

        if ($requestedDays > 90) {
            $this->warn('Token de longa duração: restrinja por --ip e guarde-o num gestor de segredos.');
        }

        return self::SUCCESS;
    }
}

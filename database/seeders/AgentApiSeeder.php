<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use RuntimeException;

class AgentApiSeeder extends Seeder
{
    public function run(): void
    {
        $requiredTables = [
            'agent_tokens',
            'agent_pages',
            'agent_media',
            'agent_idempotency_keys',
            'agent_audit_logs',
        ];

        $missing = array_values(array_filter(
            $requiredTables,
            fn (string $table): bool => !Schema::hasTable($table)
        ));

        if ($missing !== []) {
            throw new RuntimeException(
                'Agent API não preparada. Execute primeiro php artisan migrate --force. Tabelas ausentes: '.implode(', ', $missing)
            );
        }

        $this->command?->info('Agent API v1 preparada; nenhum token previsível foi criado pelo seeder.');
    }
}

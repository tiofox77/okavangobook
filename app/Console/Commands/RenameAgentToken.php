<?php

namespace App\Console\Commands;

use App\Models\AgentToken;
use Illuminate\Console\Command;

class RenameAgentToken extends Command
{
    protected $signature = 'agent:token:rename {current : Nome atual} {new : Novo nome}';

    protected $description = 'Altera o nome operacional de tokens da Agent API sem trocar o segredo';

    public function handle(): int
    {
        $tokens = AgentToken::where('name', $this->argument('current'))->get();
        if ($tokens->isEmpty()) {
            $this->error('Nenhum token encontrado com o nome informado.');
            return self::FAILURE;
        }

        AgentToken::whereKey($tokens->pluck('id'))->update(['name' => $this->argument('new')]);
        $this->info($tokens->count().' token(s) renomeado(s) para '.$this->argument('new').'.');

        return self::SUCCESS;
    }
}

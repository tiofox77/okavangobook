<?php

namespace App\Livewire\Admin;

use App\Models\AgentToken;
use Livewire\Component;

/**
 * Gestão dos tokens da Agent API (Bearer kstay__).
 * Permite ver tokens, editar escopos, criar novos e revogar/reativar —
 * tudo pelo painel (sem SSH). O valor do token só é mostrado à criação.
 */
class AgentTokenManagement extends Component
{
    // Edição de escopos de um token existente
    public ?int $editingId = null;
    public array $editScopes = [];

    // Formulário de criação
    public bool $showCreate = false;
    public string $newName = '';
    public array $newScopes = [];
    public int $newDays = 90;
    public string $newIps = '';

    // Token recém-criado (mostrado uma única vez)
    public ?string $plainToken = null;
    public ?string $plainTokenName = null;

    public function render()
    {
        return view('livewire.admin.agent-token-management', [
            'tokens' => AgentToken::orderByDesc('id')->get(),
            'availableScopes' => config('agent_api.scopes', []),
        ])->layout('layouts.admin');
    }

    /** Abre o editor de escopos para um token. (nome != da propriedade $editScopes) */
    public function openScopeEditor(int $id): void
    {
        $token = AgentToken::findOrFail($id);
        $this->editingId = $id;
        $this->editScopes = $token->scopes ?? [];
    }

    public function cancelEdit(): void
    {
        $this->reset(['editingId', 'editScopes']);
    }

    /** Grava os escopos selecionados no token em edição. */
    public function saveScopes(): void
    {
        abort_unless(auth()->user()?->hasRole('Admin'), 403);

        $token = AgentToken::findOrFail($this->editingId);
        $scopes = $this->sanitizeScopes($this->editScopes);

        if ($scopes === []) {
            session()->flash('error', 'Selecione pelo menos um escopo.');
            return;
        }

        $token->update(['scopes' => $scopes]);
        session()->flash('success', "Escopos do token \"{$token->name}\" atualizados.");
        $this->cancelEdit();
    }

    /** Revoga ou reativa um token. */
    public function toggleRevoke(int $id): void
    {
        abort_unless(auth()->user()?->hasRole('Admin'), 403);

        $token = AgentToken::findOrFail($id);
        $wasRevoked = (bool) $token->revoked_at;
        $token->update(['revoked_at' => $wasRevoked ? null : now()]);

        session()->flash('success', $wasRevoked
            ? "Token \"{$token->name}\" reativado."
            : "Token \"{$token->name}\" revogado.");
    }

    /** Emite um novo token e mostra o valor em texto uma única vez. */
    public function createToken(): void
    {
        abort_unless(auth()->user()?->hasRole('Admin'), 403);

        $this->validate([
            'newName' => ['required', 'string', 'max:255'],
            'newScopes' => ['required', 'array', 'min:1'],
            'newDays' => ['required', 'integer', 'min:1', 'max:365'],
            'newIps' => ['nullable', 'string', 'max:1000'],
        ], [], [
            'newName' => 'nome',
            'newScopes' => 'escopos',
            'newDays' => 'validade',
        ]);

        $scopes = $this->sanitizeScopes($this->newScopes);
        if ($scopes === []) {
            session()->flash('error', 'Selecione escopos válidos.');
            return;
        }

        $ips = array_values(array_filter(array_map('trim', explode(',', $this->newIps))));

        [$token, $plain] = AgentToken::issue([
            'name' => $this->newName,
            'scopes' => $scopes,
            'allowed_ips' => $ips,
            'expires_at' => now()->addDays($this->newDays),
            'created_by' => auth()->id(),
        ]);

        $this->plainToken = $plain;
        $this->plainTokenName = $token->name;
        $this->reset(['newName', 'newScopes', 'newIps', 'showCreate']);
        $this->newDays = 90;
        session()->flash('success', 'Token criado. Copie-o agora — não voltará a ser mostrado.');
    }

    public function dismissPlainToken(): void
    {
        $this->reset(['plainToken', 'plainTokenName']);
    }

    /** Mantém apenas escopos válidos (do allowlist da config) ou '*'. */
    private function sanitizeScopes(array $scopes): array
    {
        $allowed = array_merge(config('agent_api.scopes', []), ['*']);

        return array_values(array_unique(array_filter(
            $scopes,
            fn ($s) => is_string($s) && in_array($s, $allowed, true)
        )));
    }
}

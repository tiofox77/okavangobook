<div class="p-4 sm:p-6">
    {{-- Cabeçalho --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                <i class="fas fa-key text-indigo-500 mr-2"></i>Tokens da Agent API
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Bearer <code>kstay__</code> — gere escopos, cria e revoga tokens de integração.</p>
        </div>
        <button wire:click="$toggle('showCreate')"
                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium">
            <i class="fas fa-plus mr-1"></i> Criar token
        </button>
    </div>

    {{-- Flash --}}
    @if(session('success'))
        <div class="mb-4 bg-green-50 dark:bg-green-900/20 border border-green-300 dark:border-green-700 text-green-800 dark:text-green-300 px-4 py-3 rounded-lg text-sm">
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 bg-red-50 dark:bg-red-900/20 border border-red-300 dark:border-red-700 text-red-800 dark:text-red-300 px-4 py-3 rounded-lg text-sm">
            <i class="fas fa-exclamation-circle mr-1"></i> {{ session('error') }}
        </div>
    @endif

    {{-- Token recém-criado (mostrado uma única vez) --}}
    @if($plainToken)
        <div class="mb-6 bg-amber-50 dark:bg-amber-900/20 border-2 border-amber-300 dark:border-amber-700 rounded-xl p-5">
            <div class="flex items-start justify-between gap-3">
                <div class="min-w-0 flex-1">
                    <h3 class="font-bold text-amber-900 dark:text-amber-200"><i class="fas fa-triangle-exclamation mr-1"></i> Copie o token agora — não volta a ser mostrado</h3>
                    <p class="text-sm text-amber-800 dark:text-amber-300 mt-1">Token de "{{ $plainTokenName }}":</p>
                    <div class="mt-2 flex items-center gap-2">
                        <code id="new-token-value" class="flex-1 bg-gray-900 text-green-300 text-xs sm:text-sm rounded-lg p-3 overflow-x-auto break-all">{{ $plainToken }}</code>
                        <button onclick="navigator.clipboard.writeText(document.getElementById('new-token-value').textContent).then(()=>{this.innerHTML='<i class=\'fas fa-check\'></i>'})"
                                class="flex-shrink-0 px-3 py-3 bg-amber-600 hover:bg-amber-700 text-white rounded-lg text-sm" title="Copiar">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                </div>
                <button wire:click="dismissPlainToken" class="flex-shrink-0 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200" title="Fechar">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
        </div>
    @endif

    {{-- Formulário de criação --}}
    @if($showCreate)
        <div class="mb-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
            <h3 class="font-bold text-gray-800 dark:text-white mb-4">Novo token</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Nome do agente/integração</label>
                    <input type="text" wire:model="newName" placeholder="ex.: codex-agent"
                           class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 dark:text-white">
                    @error('newName')<span class="text-xs text-red-500">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Validade (dias)</label>
                    <input type="number" wire:model="newDays" min="1" max="365"
                           class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 dark:text-white">
                    @error('newDays')<span class="text-xs text-red-500">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">IPs permitidos (opcional, vírgulas)</label>
                    <input type="text" wire:model="newIps" placeholder="vazio = qualquer IP"
                           class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 dark:text-white">
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-2">Escopos</label>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-2">
                    @foreach($availableScopes as $scope)
                        <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-200 bg-gray-50 dark:bg-gray-700/50 rounded-lg px-2 py-1.5 cursor-pointer">
                            <input type="checkbox" wire:model="newScopes" value="{{ $scope }}" class="rounded text-indigo-600">
                            <span class="truncate font-mono text-xs">{{ $scope }}</span>
                        </label>
                    @endforeach
                </div>
                @error('newScopes')<span class="text-xs text-red-500">{{ $message }}</span>@enderror
            </div>
            <div class="flex gap-2">
                <button wire:click="createToken" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium">
                    <i class="fas fa-key mr-1"></i> Emitir token
                </button>
                <button wire:click="$set('showCreate', false)" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg text-sm font-medium">
                    Cancelar
                </button>
            </div>
        </div>
    @endif

    {{-- Lista de tokens --}}
    <div class="space-y-4">
        @forelse($tokens as $token)
            @php
                $isRevoked = (bool) $token->revoked_at;
                $isExpired = $token->expires_at && $token->expires_at->isPast();
                $canWriteProps = in_array('*', $token->scopes ?? [], true) || in_array('properties:write', $token->scopes ?? [], true);
            @endphp
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2 flex-wrap">
                            <h3 class="font-bold text-gray-800 dark:text-white">{{ $token->name }}</h3>
                            @if($isRevoked)
                                <span class="text-xs bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-300 px-2 py-0.5 rounded-full">Revogado</span>
                            @elseif($isExpired)
                                <span class="text-xs bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 px-2 py-0.5 rounded-full">Expirado</span>
                            @else
                                <span class="text-xs bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-300 px-2 py-0.5 rounded-full">Ativo</span>
                            @endif
                            @if($canWriteProps)
                                <span class="text-xs bg-indigo-100 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300 px-2 py-0.5 rounded-full" title="Pode criar/editar propriedades"><i class="fas fa-hotel mr-1"></i>cria propriedades</span>
                            @endif
                        </div>
                        <div class="flex flex-wrap gap-1 mt-2">
                            @forelse($token->scopes ?? [] as $s)
                                <span class="text-xs font-mono bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 px-2 py-0.5 rounded">{{ $s }}</span>
                            @empty
                                <span class="text-xs text-gray-400">sem escopos</span>
                            @endforelse
                        </div>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">
                            IPs: {{ !empty($token->allowed_ips) ? implode(', ', $token->allowed_ips) : 'qualquer' }} ·
                            Expira: {{ optional($token->expires_at)->format('d/m/Y') ?? '—' }} ·
                            Último uso: {{ optional($token->last_used_at)->diffForHumans() ?? 'nunca' }}
                        </p>
                    </div>
                    <div class="flex flex-shrink-0 gap-2">
                        <button wire:click="openScopeEditor({{ $token->id }})"
                                class="px-3 py-1.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg text-sm hover:bg-gray-200 dark:hover:bg-gray-600">
                            <i class="fas fa-sliders-h mr-1"></i> Escopos
                        </button>
                        <button wire:click="toggleRevoke({{ $token->id }})"
                                class="px-3 py-1.5 rounded-lg text-sm {{ $isRevoked ? 'bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-300 hover:bg-green-200' : 'bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-300 hover:bg-red-200' }}">
                            <i class="fas {{ $isRevoked ? 'fa-rotate-left' : 'fa-ban' }} mr-1"></i>{{ $isRevoked ? 'Reativar' : 'Revogar' }}
                        </button>
                    </div>
                </div>

                {{-- Editor de escopos inline --}}
                @if($editingId === $token->id)
                    <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-2">Editar escopos</p>
                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-2 mb-3">
                            @foreach($availableScopes as $scope)
                                <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-200 bg-gray-50 dark:bg-gray-700/50 rounded-lg px-2 py-1.5 cursor-pointer">
                                    <input type="checkbox" wire:model="editScopes" value="{{ $scope }}" class="rounded text-indigo-600">
                                    <span class="truncate font-mono text-xs">{{ $scope }}</span>
                                </label>
                            @endforeach
                        </div>
                        <div class="flex gap-2">
                            <button wire:click="saveScopes" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium">
                                <i class="fas fa-save mr-1"></i> Guardar escopos
                            </button>
                            <button wire:click="cancelEdit" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg text-sm font-medium">
                                Cancelar
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        @empty
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-10 text-center">
                <i class="fas fa-key text-4xl text-gray-300 dark:text-gray-600 mb-3"></i>
                <p class="text-gray-500 dark:text-gray-400">Ainda não há tokens. Clique em "Criar token" para emitir o primeiro.</p>
            </div>
        @endforelse
    </div>
</div>

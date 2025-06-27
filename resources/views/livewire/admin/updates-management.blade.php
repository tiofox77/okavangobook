<div class="p-6">
    <!-- Cabeçalho da página -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                <i class="fas fa-download text-admin-primary mr-3"></i>
                Actualizações do Sistema
            </h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2">
                Verifique e instale actualizações do GitHub
            </p>
        </div>
        
        <button wire:click="checkForUpdates" wire:loading.attr="disabled" 
                class="bg-admin-primary hover:bg-admin-primary-dark text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200 flex items-center">
            <span wire:loading.remove wire:target="checkForUpdates">
                <i class="fas fa-sync-alt mr-2"></i>
                Verificar Actualizações
            </span>
            <span wire:loading wire:target="checkForUpdates">
                <i class="fas fa-spinner fa-spin mr-2"></i>
                A verificar...
            </span>
        </button>
    </div>

    <!-- Mensagens Flash -->
    @if (session()->has('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <i class="fas fa-exclamation-circle mr-2"></i>
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Coluna Principal - Status e Actualizações -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Estado Actual do Sistema -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md">
                <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center">
                        <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                        Estado do Sistema
                    </h2>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Versão Actual</p>
                                    <p class="text-2xl font-bold text-gray-900 dark:text-white">v{{ $currentVersion }}</p>
                                </div>
                                <div class="text-blue-500">
                                    <i class="fas fa-code-branch text-3xl"></i>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Última Versão</p>
                                    <p class="text-2xl font-bold {{ $latestVersion && version_compare($latestVersion, $currentVersion, '>') ? 'text-green-600' : 'text-gray-900 dark:text-white' }}">
                                        @if($latestVersion)
                                            v{{ $latestVersion }}
                                        @else
                                            --
                                        @endif
                                    </p>
                                </div>
                                <div class="{{ $latestVersion && version_compare($latestVersion, $currentVersion, '>') ? 'text-green-500' : 'text-gray-500' }}">
                                    <i class="fas fa-cloud-download-alt text-3xl"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status da Actualização -->
                    <div class="mt-6 p-4 rounded-lg border-l-4 
                        @if($updateStatus === 'checking') border-blue-500 bg-blue-50 dark:bg-blue-900/20
                        @elseif($updateStatus === 'available') border-green-500 bg-green-50 dark:bg-green-900/20
                        @elseif($updateStatus === 'not_available') border-gray-500 bg-gray-50 dark:bg-gray-700
                        @elseif($updateStatus === 'downloading') border-yellow-500 bg-yellow-50 dark:bg-yellow-900/20
                        @elseif($updateStatus === 'installing') border-orange-500 bg-orange-50 dark:bg-orange-900/20
                        @elseif($updateStatus === 'completed') border-green-500 bg-green-50 dark:bg-green-900/20
                        @elseif($updateStatus === 'error') border-red-500 bg-red-50 dark:bg-red-900/20
                        @endif">
                        
                        <div class="flex items-center">
                            @if($updateStatus === 'checking')
                                <i class="fas fa-spinner fa-spin text-blue-500 mr-3"></i>
                            @elseif($updateStatus === 'available')
                                <i class="fas fa-arrow-circle-up text-green-500 mr-3"></i>
                            @elseif($updateStatus === 'not_available')
                                <i class="fas fa-check-circle text-gray-500 mr-3"></i>
                            @elseif($updateStatus === 'downloading')
                                <i class="fas fa-download text-yellow-500 mr-3"></i>
                            @elseif($updateStatus === 'installing')
                                <i class="fas fa-cog fa-spin text-orange-500 mr-3"></i>
                            @elseif($updateStatus === 'completed')
                                <i class="fas fa-check-circle text-green-500 mr-3"></i>
                            @elseif($updateStatus === 'error')
                                <i class="fas fa-exclamation-triangle text-red-500 mr-3"></i>
                            @endif
                            
                            <p class="font-medium 
                                @if($updateStatus === 'checking') text-blue-700 dark:text-blue-300
                                @elseif($updateStatus === 'available') text-green-700 dark:text-green-300
                                @elseif($updateStatus === 'not_available') text-gray-700 dark:text-gray-300
                                @elseif($updateStatus === 'downloading') text-yellow-700 dark:text-yellow-300
                                @elseif($updateStatus === 'installing') text-orange-700 dark:text-orange-300
                                @elseif($updateStatus === 'completed') text-green-700 dark:text-green-300
                                @elseif($updateStatus === 'error') text-red-700 dark:text-red-300
                                @endif">
                                {{ $updateMessage }}
                            </p>
                        </div>

                        <!-- Barra de Progresso -->
                        @if($updateStatus === 'downloading' && $downloadProgress > 0)
                            <div class="mt-3">
                                <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400 mb-1">
                                    <span>Download em progresso</span>
                                    <span>{{ $downloadProgress }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-yellow-500 h-2 rounded-full transition-all duration-300" style="width: {{ $downloadProgress }}%"></div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Botões de Acção -->
                    @if($updateStatus === 'available' && !$confirmUpdate)
                        <div class="mt-6 flex space-x-3">
                            <button wire:click="toggleReleaseNotes" 
                                    class="flex-1 bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center">
                                <i class="fas fa-file-alt mr-2"></i>
                                Ver Notas da Versão
                            </button>
                            
                            <button wire:click="confirmUpdateProcess" 
                                    class="flex-1 bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center">
                                <i class="fas fa-download mr-2"></i>
                                Instalar Actualização
                            </button>
                        </div>
                    @endif

                    <!-- Confirmação de Actualização -->
                    @if($confirmUpdate)
                        <div class="mt-6 p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                            <div class="flex items-center mb-3">
                                <i class="fas fa-exclamation-triangle text-yellow-500 mr-2"></i>
                                <h4 class="font-semibold text-yellow-800 dark:text-yellow-200">Confirmar Actualização</h4>
                            </div>
                            <p class="text-yellow-700 dark:text-yellow-300 text-sm mb-4">
                                Esta operação irá actualizar o sistema para a versão <strong>v{{ $latestVersion }}</strong>. 
                                Um backup será criado automaticamente antes da instalação.
                            </p>
                            <div class="flex space-x-3">
                                <button wire:click="startUpdate" 
                                        class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200 flex items-center">
                                    <i class="fas fa-rocket mr-2"></i>
                                    Confirmar e Instalar
                                </button>
                                <button wire:click="cancelUpdate" 
                                        class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                                    <i class="fas fa-times mr-2"></i>
                                    Cancelar
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Notas da Versão -->
            @if($showReleaseNotes && !empty($releaseNotes))
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md">
                    <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4 flex justify-between items-center">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center">
                            <i class="fas fa-file-alt text-green-500 mr-2"></i>
                            Notas da Versão - {{ $releaseNotes['name'] ?? 'Nova Versão' }}
                        </h2>
                        <button wire:click="toggleReleaseNotes" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    
                    <div class="p-6">
                        <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                            <i class="fas fa-calendar-alt mr-1"></i>
                            Publicado em: {{ isset($releaseNotes['published_at']) ? \Carbon\Carbon::parse($releaseNotes['published_at'])->format('d/m/Y H:i') : 'Data não disponível' }}
                        </div>
                        
                        <div class="prose dark:prose-invert max-w-none">
                            @if(isset($releaseNotes['body']))
                                {!! nl2br(e($releaseNotes['body'])) !!}
                            @else
                                <p class="text-gray-500 dark:text-gray-400 font-style: italic;">Notas da versão não disponíveis.</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Coluna Lateral - Log e Configurações -->
        <div class="space-y-6">
            
            <!-- Configurações do GitHub -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md">
                <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                        <i class="fab fa-github text-gray-700 dark:text-gray-300 mr-2"></i>
                        Configurações do GitHub
                    </h3>
                    <button wire:click="toggleGitHubSettings" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                        <i class="fas {{ $showGitHubSettings ? 'fa-times' : 'fa-edit' }}"></i>
                    </button>
                </div>
                
                <div class="p-6">
                    @if($showGitHubSettings)
                        <!-- Formulário de Configurações -->
                        <form wire:submit.prevent="saveGitHubSettings" class="space-y-4">
                            <div>
                                <label for="githubRepo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Repositório GitHub *
                                </label>
                                <input wire:model="githubRepo" type="text" id="githubRepo" 
                                       placeholder="ex: username/repositorio"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-admin-primary focus:border-admin-primary dark:bg-gray-700 dark:text-white">
                                @error('githubRepo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="githubToken" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Token do GitHub (opcional)
                                </label>
                                <input wire:model="githubToken" type="password" id="githubToken" 
                                       placeholder="ghp_xxxxxxxxxxxxxxxxxxxx"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-admin-primary focus:border-admin-primary dark:bg-gray-700 dark:text-white">
                                @error('githubToken') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    Token para aumentar limite de requests da API do GitHub
                                </p>
                            </div>

                            <div class="flex space-x-3">
                                <button type="submit" wire:loading.attr="disabled" 
                                        class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200 flex items-center">
                                    <span wire:loading.remove wire:target="saveGitHubSettings">
                                        <i class="fas fa-save mr-2"></i>
                                        Salvar
                                    </span>
                                    <span wire:loading wire:target="saveGitHubSettings">
                                        <i class="fas fa-spinner fa-spin mr-2"></i>
                                        A salvar...
                                    </span>
                                </button>
                                
                                <button type="button" wire:click="toggleGitHubSettings" 
                                        class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                                    <i class="fas fa-times mr-2"></i>
                                    Cancelar
                                </button>
                            </div>
                        </form>
                    @else
                        <!-- Visualização das Configurações -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Repositório GitHub
                                </label>
                                <div class="text-sm text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 rounded p-2 flex items-center">
                                    <i class="fab fa-github mr-2"></i>
                                    {{ $githubRepo }}
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Token Configurado
                                </label>
                                <div class="flex items-center text-sm">
                                    @if($githubToken)
                                        <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                        <span class="text-gray-600 dark:text-gray-400">Sim (oculto por segurança)</span>
                                    @else
                                        <div class="w-2 h-2 bg-yellow-500 rounded-full mr-2"></div>
                                        <span class="text-gray-600 dark:text-gray-400">Não configurado</span>
                                    @endif
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Status da API
                                </label>
                                <div class="flex items-center text-sm">
                                    <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                    <span class="text-gray-600 dark:text-gray-400">Conectado</span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Log de Actualização -->
            @if(!empty($updateLog))
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md">
                    <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4 flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                            <i class="fas fa-list-alt text-blue-500 mr-2"></i>
                            Log de Actualização
                        </h3>
                        <button wire:click="clearUpdateLog" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 text-sm">
                            <i class="fas fa-trash mr-1"></i>
                            Limpar
                        </button>
                    </div>
                    
                    <div class="p-6 max-h-96 overflow-y-auto">
                        <div class="space-y-2">
                            @foreach($updateLog as $entry)
                                <div class="flex items-start space-x-2 text-sm">
                                    <span class="text-gray-500 dark:text-gray-400 flex-shrink-0 font-mono">
                                        {{ $entry['timestamp'] }}
                                    </span>
                                    <span class="text-gray-700 dark:text-gray-300">
                                        {{ $entry['message'] }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Informações Técnicas -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md">
                <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                        <i class="fas fa-server text-gray-500 mr-2"></i>
                        Informações do Sistema
                    </h3>
                </div>
                
                <div class="p-6 space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">PHP:</span>
                        <span class="text-gray-900 dark:text-white font-mono">{{ PHP_VERSION }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Laravel:</span>
                        <span class="text-gray-900 dark:text-white font-mono">{{ app()->version() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Espaço Livre:</span>
                        <span class="text-gray-900 dark:text-white font-mono">
                            {{ number_format(disk_free_space('/') / 1024 / 1024 / 1024, 2) }} GB
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Última Verificação:</span>
                        <span class="text-gray-900 dark:text-white font-mono">{{ now()->format('H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

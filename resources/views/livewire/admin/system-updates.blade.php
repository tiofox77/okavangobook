<div class="p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Sistema de Atualizações</h1>
        <p class="mt-2 text-gray-600">Gerir atualizações do sistema e configurações GitHub</p>
    </div>

    <!-- Tab Navigation -->
    <div class="mb-6">
        <nav class="flex space-x-8 border-b border-gray-200">
            <button wire:click="setActiveTab('settings')"
                    class="py-2 px-1 border-b-2 font-medium text-sm transition-colors
                           {{ $activeTab === 'settings' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                <i class="fab fa-github mr-2"></i>Repositório GitHub
            </button>
            <button wire:click="setActiveTab('updates')"
                    class="py-2 px-1 border-b-2 font-medium text-sm transition-colors
                           {{ $activeTab === 'updates' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                <i class="fas fa-download mr-2"></i>Atualizações Disponíveis
            </button>
            <button wire:click="setActiveTab('requirements')" 
                    class="py-2 px-1 border-b-2 font-medium text-sm transition-colors
                           {{ $activeTab === 'requirements' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                <i class="fas fa-check-circle mr-2"></i>Requisitos do Sistema
            </button>
            <button wire:click="setActiveTab('history')" 
                    class="py-2 px-1 border-b-2 font-medium text-sm transition-colors
                           {{ $activeTab === 'history' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                <i class="fas fa-history mr-2"></i>Histórico de Updates
            </button>
        </nav>
    </div>

    <!-- GitHub Settings Tab -->
    @if($activeTab === 'settings')
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">
                            <i class="fab fa-github text-gray-600 mr-2"></i>
                            Configurações do Repositório
                        </h3>
                        <p class="text-sm text-gray-600 mt-1">Configure a URL do repositório GitHub público para atualizações</p>
                    </div>
                </div>

                <form wire:submit.prevent="saveGitHubSettings" class="space-y-6">
                    <!-- Repository URL -->
                    <div>
                        <label for="repositoryUrl" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fab fa-github mr-1"></i>URL do Repositório GitHub
                        </label>
                        <div class="flex space-x-2">
                            <input type="url" 
                                   id="repositoryUrl"
                                   wire:model="repositoryUrl"
                                   placeholder="https://github.com/owner/repository"
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <button type="button" 
                                    wire:click="testRepository"
                                    class="px-4 py-2 bg-gray-600 text-white rounded-md text-sm font-medium hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                                <i class="fas fa-check-circle mr-1"></i>Testar
                            </button>
                        </div>
                        @error('repositoryUrl')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">
                            <i class="fas fa-info-circle mr-1"></i>
                            Digite a URL completa do repositório público do GitHub (ex: https://github.com/owner/repo)
                        </p>
                    </div>

                    <!-- Current Repository Info -->
                    @if($githubRepo)
                        <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                            <div class="flex items-center">
                                <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                                <div>
                                    <p class="text-sm font-medium text-blue-900">Repositório Configurado</p>
                                    <p class="text-sm text-blue-700">{{ $githubRepo }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-3">
                        <button type="button" 
                                wire:click="cancelGitHubEdit"
                                class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                            <i class="fas fa-times mr-2"></i>Cancelar
                        </button>
                        <button type="submit" 
                                wire:loading.attr="disabled"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors disabled:opacity-50">
                            <i class="fas fa-save mr-2" wire:loading.remove></i>
                            <i class="fas fa-spinner fa-spin mr-2" wire:loading></i>
                            Salvar Configurações
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Updates Tab -->
    @if($activeTab === 'updates')
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Version Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                        Informações da Versão
                    </h3>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                            <span class="text-sm font-medium text-gray-700">Versão Atual</span>
                            <span class="text-sm font-mono bg-gray-200 px-2 py-1 rounded">{{ $currentVersion ?: 'Não definida' }}</span>
                        </div>
                        
                        @if($latestVersion)
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                <span class="text-sm font-medium text-gray-700">Última Versão</span>
                                <span class="text-sm font-mono bg-green-200 px-2 py-1 rounded">{{ $latestVersion }}</span>
                            </div>
                        @endif

                        @if($updateAvailable)
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-download text-green-400"></i>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-green-800">Atualização Disponível</h3>
                                        <p class="mt-1 text-sm text-green-700">
                                            Uma nova versão está disponível: {{ $latestVersion }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @elseif($latestVersion)
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-check-circle text-blue-400"></i>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-blue-800">Sistema Atualizado</h3>
                                        <p class="mt-1 text-sm text-blue-700">
                                            Você está usando a versão mais recente.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="mt-6 space-y-3">
                        <button wire:click="checkForUpdates" 
                                class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
                                wire:loading.attr="disabled">
                            <i class="fas fa-sync-alt mr-2" wire:loading.class="fa-spin"></i>
                            Verificar Atualizações
                        </button>

                        @if($updateAvailable && !$isUpdating)
                            <!-- System Requirements Status -->
                            @if(!empty($systemRequirements))
                                @php
                                    $status = is_array($requirementsStatus) ? $requirementsStatus : ['failed' => 0, 'warnings' => 0, 'passed' => 0];
                                    $failed = $status['failed'] ?? 0;
                                    $warnings = $status['warnings'] ?? 0;
                                    $passed = $status['passed'] ?? 0;
                                @endphp
                                
                                @if($failed > 0)
                                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                        <div class="flex">
                                            <div class="flex-shrink-0">
                                                <i class="fas fa-exclamation-triangle text-red-400"></i>
                                            </div>
                                            <div class="ml-3">
                                                <h3 class="text-sm font-medium text-red-800">Requisitos Críticos Falharam</h3>
                                                <p class="mt-1 text-sm text-red-700">
                                                    {{ $failed }} requisito(s) crítico(s) não atendido(s). Corrija antes de atualizar.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @elseif($warnings > 0)
                                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                        <div class="flex">
                                            <div class="flex-shrink-0">
                                                <i class="fas fa-exclamation-circle text-yellow-400"></i>
                                            </div>
                                            <div class="ml-3">
                                                <h3 class="text-sm font-medium text-yellow-800">Avisos nos Requisitos</h3>
                                                <p class="mt-1 text-sm text-yellow-700">
                                                    {{ $warnings }} aviso(s) encontrado(s). A atualização pode prosseguir.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                        <div class="flex">
                                            <div class="flex-shrink-0">
                                                <i class="fas fa-check-circle text-green-400"></i>
                                            </div>
                                            <div class="ml-3">
                                                <h3 class="text-sm font-medium text-green-800">Requisitos Atendidos</h3>
                                                <p class="mt-1 text-sm text-green-700">
                                                    Todos os requisitos foram atendidos. Pronto para atualizar.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @else
                                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-info-circle text-gray-400"></i>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-gray-800">Requisitos Não Verificados</h3>
                                            <p class="mt-1 text-sm text-gray-700">
                                                Clique em "Instalar Atualização" para verificar automaticamente.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            <button wire:click="showConfirmation('startUpdate', 'Tem certeza que deseja iniciar a atualização? O sistema ficará em manutenção durante o processo.')" 
                                    class="w-full bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors">
                                <i class="fas fa-download mr-2"></i>
                                Instalar Atualização
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Update Progress -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-tasks text-green-500 mr-2"></i>
                        Progresso da Atualização
                    </h3>
                    
                    @if($isUpdating)
                        <div class="space-y-4">
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-spinner fa-spin text-yellow-400"></i>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-yellow-800">Atualização em Progresso</h3>
                                        <p class="mt-1 text-sm text-yellow-700">
                                            O sistema está sendo atualizado. Não feche esta página.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Progress Steps -->
                            <div class="space-y-3">
                                @foreach($updateProgress as $step => $status)
                                    <div class="flex items-center space-x-3">
                                        @if($status === 'completed')
                                            <i class="fas fa-check-circle text-green-500"></i>
                                        @elseif($status === 'processing')
                                            <i class="fas fa-spinner fa-spin text-blue-500"></i>
                                        @else
                                            <i class="fas fa-circle text-gray-300"></i>
                                        @endif
                                        <span class="text-sm {{ $status === 'completed' ? 'text-green-700' : ($status === 'processing' ? 'text-blue-700' : 'text-gray-500') }}">
                                            {{ ucfirst(str_replace('_', ' ', $step)) }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-moon text-gray-400 text-3xl mb-3"></i>
                            <p class="text-gray-500">Nenhuma atualização em progresso.</p>
                        </div>
                    @endif

                    <!-- Update Log -->
                    @if(!empty($updateLog) && is_array($updateLog))
                        <div class="mt-6">
                            <h4 class="text-sm font-medium text-gray-900 mb-2">Log da Atualização</h4>
                            <div class="bg-gray-50 rounded-lg p-3 max-h-48 overflow-y-auto">
                                <pre class="text-xs text-gray-700 whitespace-pre-wrap">@foreach($updateLog as $logEntry)@if(is_array($logEntry) && isset($logEntry['message']))[{{ $logEntry['timestamp'] ?? 'N/A' }}] {{ $logEntry['message'] }}@else{{ is_string($logEntry) ? $logEntry : json_encode($logEntry) }}@endif{{ $loop->last ? '' : "\n" }}@endforeach</pre>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Release Notes -->
        @if($latestReleaseData)
            <div class="mt-6 bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-file-alt text-purple-500 mr-2"></i>
                        Notas da Versão {{ $latestVersion }}
                    </h3>
                    
                    <div class="prose prose-sm max-w-none">
                        @if(!empty($latestReleaseData['body']))
                            {!! nl2br(e($latestReleaseData['body'])) !!}
                        @else
                            <p class="text-gray-500">Sem notas de versão disponíveis.</p>
                        @endif
                    </div>

                    @if(!empty($latestReleaseData['html_url']))
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <a href="{{ $latestReleaseData['html_url'] }}" target="_blank" 
                               class="inline-flex items-center text-blue-600 hover:text-blue-800">
                                <i class="fab fa-github mr-2"></i>
                                Ver no GitHub
                                <i class="fas fa-external-link-alt ml-1 text-xs"></i>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    @endif

    <!-- System Requirements Tab -->
    @if($activeTab === 'requirements')
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        Verificação dos Requisitos
                    </h3>
                    <button wire:click="checkSystemRequirements" 
                            class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
                            wire:loading.attr="disabled">
                        <i class="fas fa-sync-alt mr-2" wire:loading.class="fa-spin"></i>
                        Verificar Requisitos
                    </button>
                </div>

                @if($isCheckingRequirements)
                    <div class="flex items-center justify-center py-8">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                        <span class="ml-2 text-gray-600">Verificando requisitos...</span>
                    </div>
                @endif

                @if(!empty($systemRequirements))
                    <!-- Requirements Summary -->
                    <div class="grid grid-cols-3 gap-4 mb-6">
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold text-green-600">{{ $requirementsStatus['passed'] }}</div>
                            <div class="text-sm text-green-600">Aprovados</div>
                        </div>
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold text-yellow-600">{{ $requirementsStatus['warnings'] }}</div>
                            <div class="text-sm text-yellow-600">Avisos</div>
                        </div>
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold text-red-600">{{ $requirementsStatus['failed'] }}</div>
                            <div class="text-sm text-red-600">Falharam</div>
                        </div>
                    </div>

                    <!-- Requirements List -->
                    <div class="space-y-3">
                        @foreach($systemRequirements as $requirement)
                            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                <div class="flex-1">
                                    <h4 class="text-sm font-medium text-gray-900">{{ $requirement['name'] }}</h4>
                                    <p class="text-sm text-gray-500">{{ $requirement['required'] }}</p>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <span class="text-sm text-gray-600">{{ $requirement['current'] }}</span>
                                    @if($requirement['status'] === 'passed')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check mr-1"></i>OK
                                        </span>
                                    @elseif($requirement['status'] === 'warning')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>Aviso
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-times mr-1"></i>Erro
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Update History Tab -->
    @if($activeTab === 'history')
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-history text-blue-500 mr-2"></i>
                    Histórico de Atualizações
                </h3>
                
                <div class="text-center py-8">
                    <i class="fas fa-clock text-gray-400 text-3xl mb-3"></i>
                    <p class="text-gray-500">Funcionalidade em desenvolvimento.</p>
                    <p class="text-sm text-gray-400 mt-2">
                        Em breve você poderá visualizar o histórico completo de atualizações do sistema.
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Confirmation Modal -->
    @if($showConfirmModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100">
                        <i class="fas fa-exclamation-triangle text-yellow-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mt-2">Confirmação de Atualização</h3>
                    <div class="mt-2 px-7 py-3">
                        <p class="text-sm text-gray-500">{{ $confirmMessage }}</p>
                    </div>
                    <div class="items-center px-4 py-3">
                        <button wire:click="executeConfirmedAction" 
                                class="px-4 py-2 bg-green-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-300 mr-2 mb-2">
                            Confirmar Atualização
                        </button>
                        <button wire:click="cancelConfirmation" 
                                class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded z-50" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded z-50" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    @if (session()->has('warning'))
        <div class="fixed top-4 right-4 bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded z-50" role="alert">
            <span class="block sm:inline">{{ session('warning') }}</span>
        </div>
    @endif
</div>

<script>
    // Auto-hide flash messages
    setTimeout(() => {
        const alerts = document.querySelectorAll('[role="alert"]');
        alerts.forEach(alert => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        });
    }, 5000);

    // Refresh page after successful update
    window.addEventListener('update-completed', event => {
        setTimeout(() => {
            window.location.reload();
        }, 3000);
    });
</script>

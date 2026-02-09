<!-- Modal para visualizar localização (somente leitura) -->
<div class="fixed inset-0 overflow-y-auto z-50" x-show="$wire.showViewModal"
     x-transition:enter="ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     style="display: none;">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Overlay -->
        <div class="fixed inset-0 transition-opacity" aria-hidden="true" x-show="$wire.showViewModal">
            <div class="absolute inset-0 bg-gray-500 dark:bg-gray-800 opacity-75"></div>
        </div>

        <!-- Modal centralizado -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <!-- Conteúdo do modal -->
        <div class="relative inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full" 
             x-show="$wire.showViewModal" 
             x-transition:enter="ease-out duration-300" 
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
             x-transition:leave="ease-in duration-200" 
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
            
            <!-- Botão de fechar -->
            <div class="absolute top-0 right-0 pt-4 pr-4 z-10">
                <button wire:click="closeViewModal" type="button" class="bg-white dark:bg-gray-700 rounded-md text-gray-400 hover:text-gray-500 focus:outline-none transition-colors duration-200 shadow-md p-2">
                    <span class="sr-only">Fechar</span>
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            @if($viewingLocation)
                <div class="px-4 pt-5 pb-4 sm:p-6">
                    <!-- Cabeçalho com Imagem -->
                    <div class="mb-6">
                        @if($viewingLocation->image)
                            <div class="relative h-64 rounded-lg overflow-hidden mb-4">
                                <img src="{{ asset('storage/' . $viewingLocation->image) }}" 
                                     alt="{{ $viewingLocation->name }}" 
                                     class="w-full h-full object-cover"
                                     onerror="this.src='https://via.placeholder.com/800x400?text={{ urlencode($viewingLocation->name) }}';">
                                @if($viewingLocation->is_featured)
                                    <div class="absolute top-4 right-4 bg-gradient-to-r from-yellow-400 to-yellow-500 text-white px-4 py-2 rounded-full shadow-lg flex items-center">
                                        <i class="fas fa-star mr-2"></i>
                                        Destaque
                                    </div>
                                @endif
                            </div>
                        @endif
                        
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="text-3xl leading-6 font-bold text-gray-900 dark:text-white mb-2">
                                    {{ $viewingLocation->name }}
                                </h3>
                                <div class="flex items-center text-gray-500 dark:text-gray-400 mb-2">
                                    <i class="fas fa-flag mr-2 text-blue-500"></i>
                                    <span class="text-lg font-medium">{{ $viewingLocation->province }}</span>
                                </div>
                            </div>
                            @if(!$viewingLocation->image && $viewingLocation->is_featured)
                                <span class="px-4 py-2 bg-amber-100 dark:bg-amber-900 text-amber-800 dark:text-amber-300 rounded-full text-sm font-medium">
                                    <i class="fas fa-star mr-1"></i> Destaque
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Grid de informações -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <!-- Capital -->
                        @if($viewingLocation->capital)
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-building text-green-500 mr-2"></i>
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Capital</span>
                                </div>
                                <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $viewingLocation->capital }}</p>
                            </div>
                        @endif

                        <!-- População -->
                        @if($viewingLocation->population)
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-users text-purple-500 mr-2"></i>
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">População</span>
                                </div>
                                <p class="text-xl font-bold text-gray-900 dark:text-white">{{ number_format($viewingLocation->population, 0, ',', '.') }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">habitantes</p>
                            </div>
                        @endif

                        <!-- Hotéis -->
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-hotel text-indigo-500 mr-2"></i>
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Hotéis</span>
                            </div>
                            <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $viewingLocation->hotels_count ?? 0 }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $viewingLocation->hotels_count === 1 ? 'hotel' : 'hotéis' }} registrados</p>
                        </div>
                    </div>

                    <!-- Descrição -->
                    @if($viewingLocation->description)
                        <div class="mb-6">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                                <i class="fas fa-align-left text-gray-500 mr-2"></i>
                                Descrição
                            </h4>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line leading-relaxed">{{ $viewingLocation->description }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Informações Adicionais em Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <!-- Informação Geográfica -->
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900 dark:to-blue-800 rounded-lg p-4 border-l-4 border-blue-500">
                            <h5 class="text-sm font-semibold text-blue-900 dark:text-blue-200 mb-3 flex items-center">
                                <i class="fas fa-globe mr-2"></i>
                                Informação Geográfica
                            </h5>
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-blue-700 dark:text-blue-300">Província:</span>
                                    <span class="text-sm font-medium text-blue-900 dark:text-blue-100">{{ $viewingLocation->province }}</span>
                                </div>
                                @if($viewingLocation->capital)
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-blue-700 dark:text-blue-300">Capital:</span>
                                        <span class="text-sm font-medium text-blue-900 dark:text-blue-100">{{ $viewingLocation->capital }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Estatísticas -->
                        <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900 dark:to-purple-800 rounded-lg p-4 border-l-4 border-purple-500">
                            <h5 class="text-sm font-semibold text-purple-900 dark:text-purple-200 mb-3 flex items-center">
                                <i class="fas fa-chart-bar mr-2"></i>
                                Estatísticas
                            </h5>
                            <div class="space-y-2">
                                @if($viewingLocation->population)
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-purple-700 dark:text-purple-300">População:</span>
                                        <span class="text-sm font-medium text-purple-900 dark:text-purple-100">{{ number_format($viewingLocation->population, 0, ',', '.') }}</span>
                                    </div>
                                @endif
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-purple-700 dark:text-purple-300">Total de Hotéis:</span>
                                    <span class="text-sm font-medium text-purple-900 dark:text-purple-100">{{ $viewingLocation->hotels_count ?? 0 }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-purple-700 dark:text-purple-300">Status:</span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $viewingLocation->is_featured ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200' }}">
                                        <i class="fas {{ $viewingLocation->is_featured ? 'fa-star' : 'fa-circle' }} mr-1"></i>
                                        {{ $viewingLocation->is_featured ? 'Destaque' : 'Normal' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Lista de Hotéis (se houver) -->
                    @if($viewingLocation->hotels_count > 0)
                        <div class="mb-6">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                                <i class="fas fa-hotel text-gray-500 mr-2"></i>
                                Hotéis nesta Localização ({{ $viewingLocation->hotels_count }})
                            </h4>
                            <div class="bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 rounded-lg p-4">
                                <p class="text-sm text-blue-800 dark:text-blue-200 flex items-center">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Esta localização possui {{ $viewingLocation->hotels_count }} {{ $viewingLocation->hotels_count === 1 ? 'hotel registrado' : 'hotéis registrados' }}.
                                </p>
                            </div>
                        </div>
                    @else
                        <div class="mb-6">
                            <div class="bg-yellow-50 dark:bg-yellow-900 border border-yellow-200 dark:border-yellow-700 rounded-lg p-4">
                                <p class="text-sm text-yellow-800 dark:text-yellow-200 flex items-center">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    Nenhum hotel registrado nesta localização ainda.
                                </p>
                            </div>
                        </div>
                    @endif

                    <!-- Metadados -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">ID:</span>
                                <span class="ml-2 font-mono text-gray-900 dark:text-white">#{{ $viewingLocation->id }}</span>
                            </div>
                            @if($viewingLocation->created_at)
                                <div>
                                    <span class="text-gray-500 dark:text-gray-400">Criado em:</span>
                                    <span class="ml-2 text-gray-900 dark:text-white">{{ $viewingLocation->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Footer com ações -->
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-200 dark:border-gray-600">
                    <button wire:click="openModal({{ $viewingLocation->id }})" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                        <i class="fas fa-edit mr-2"></i>
                        Editar Localização
                    </button>
                    <a href="{{ route('location.details', $viewingLocation->province) }}" target="_blank" class="mt-3 w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                        <i class="fas fa-external-link-alt mr-2"></i>
                        Ver no Site
                    </a>
                    <button wire:click="closeViewModal" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm transition-colors">
                        Fechar
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

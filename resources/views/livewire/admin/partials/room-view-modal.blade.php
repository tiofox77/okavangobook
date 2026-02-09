<!-- Modal para visualizar tipo de quarto (somente leitura) -->
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
            
            @if($viewingRoom)
                <div class="px-4 pt-5 pb-4 sm:p-6">
                    <!-- Cabeçalho -->
                    <div class="mb-6 flex items-center border-b border-gray-200 dark:border-gray-700 pb-4">
                        <div class="flex-shrink-0 flex items-center justify-center h-14 w-14 rounded-full bg-green-100 dark:bg-green-900 mr-4">
                            <i class="fas fa-eye text-green-600 dark:text-green-400 text-xl"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-2xl leading-6 font-bold text-gray-900 dark:text-white">
                                {{ $viewingRoom->name }}
                            </h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                {{ $viewingRoom->hotel->name ?? 'N/A' }}
                            </p>
                        </div>
                        @if($viewingRoom->is_featured)
                            <span class="px-3 py-1 bg-amber-100 dark:bg-amber-900 text-amber-800 dark:text-amber-300 rounded-full text-sm font-medium">
                                <i class="fas fa-star mr-1"></i> Destaque
                            </span>
                        @endif
                    </div>

                    <!-- Grid de informações -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                        <!-- Capacidade -->
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-users text-blue-500 mr-2"></i>
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Capacidade</span>
                            </div>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $viewingRoom->capacity }} pessoas</p>
                        </div>

                        <!-- Camas -->
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-bed text-indigo-500 mr-2"></i>
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Camas</span>
                            </div>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $viewingRoom->beds }}</p>
                            @if($viewingRoom->bed_type)
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $viewingRoom->bed_type }}</p>
                            @endif
                        </div>

                        <!-- Tamanho -->
                        @if($viewingRoom->size)
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-ruler-combined text-purple-500 mr-2"></i>
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Tamanho</span>
                                </div>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $viewingRoom->size }} m²</p>
                            </div>
                        @endif

                        <!-- Preço Base -->
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-tag text-yellow-500 mr-2"></i>
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Preço Base</span>
                            </div>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($viewingRoom->base_price, 0, ',', '.') }} KZ</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">por noite</p>
                        </div>

                        <!-- Quantidade de Quartos -->
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-door-closed text-teal-500 mr-2"></i>
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Quartos Disponíveis</span>
                            </div>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $viewingRoom->rooms_count }}</p>
                        </div>

                        <!-- Status -->
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-info-circle text-gray-500 mr-2"></i>
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</span>
                            </div>
                            @if($viewingRoom->is_available)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                    <i class="fas fa-check-circle mr-1"></i> Disponível
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">
                                    <i class="fas fa-times-circle mr-1"></i> Indisponível
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Descrição -->
                    @if($viewingRoom->description)
                        <div class="mb-6">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                                <i class="fas fa-align-left text-gray-500 mr-2"></i>
                                Descrição
                            </h4>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $viewingRoom->description }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Comodidades -->
                    @if(!empty($viewingRoom->amenities) && is_array($viewingRoom->amenities))
                        <div class="mb-6">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                                <i class="fas fa-concierge-bell text-gray-500 mr-2"></i>
                                Comodidades
                            </h4>
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                                @foreach($viewingRoom->amenities as $amenity)
                                    <div class="flex items-center bg-gray-50 dark:bg-gray-700 rounded-lg p-3">
                                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                        <span class="text-sm text-gray-700 dark:text-gray-300">{{ $amenity }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Galeria de Imagens -->
                    @if(!empty($viewingRoom->images))
                        <div class="mb-6">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                                <i class="fas fa-images text-gray-500 mr-2"></i>
                                Galeria de Imagens
                            </h4>
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                @php
                                    $images = is_array($viewingRoom->images) ? $viewingRoom->images : [];
                                    $allImages = [];
                                    
                                    if (isset($images['thumbnail'])) {
                                        $allImages[] = $images['thumbnail'];
                                    }
                                    
                                    if (isset($images['gallery']) && is_array($images['gallery'])) {
                                        $allImages = array_merge($allImages, $images['gallery']);
                                    }
                                @endphp
                                
                                @forelse($allImages as $image)
                                    <div class="relative aspect-video rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow">
                                        <img src="{{ asset('storage/' . ltrim($image, '/storage/')) }}" 
                                             alt="{{ $viewingRoom->name }}" 
                                             class="w-full h-full object-cover"
                                             onerror="this.src='https://via.placeholder.com/400x300?text=Sem+Imagem';">
                                    </div>
                                @empty
                                    <div class="col-span-full text-center py-8 text-gray-500 dark:text-gray-400">
                                        <i class="fas fa-image text-4xl mb-2"></i>
                                        <p>Sem imagens disponíveis</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Footer com ações -->
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-200 dark:border-gray-600">
                    <button wire:click="edit({{ $viewingRoom->id }})" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                        <i class="fas fa-edit mr-2"></i>
                        Editar Quarto
                    </button>
                    @if($viewingRoom->hotel_id)
                        <a href="{{ route('hotel.details', $viewingRoom->hotel_id) }}" target="_blank" class="mt-3 w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            <i class="fas fa-external-link-alt mr-2"></i>
                            Ver Hotel no Site
                        </a>
                    @endif
                    <button wire:click="closeViewModal" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm transition-colors">
                        Fechar
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

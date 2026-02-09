<!-- Modal para visualizar hotel (somente leitura) -->
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
        <div class="relative inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-5xl sm:w-full" 
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
            
            @if($viewingHotel)
                <div class="px-4 pt-5 pb-4 sm:p-6">
                    <!-- Cabeçalho -->
                    <div class="mb-6 border-b border-gray-200 dark:border-gray-700 pb-4">
                        <div class="flex items-start justify-between">
                            <div class="flex items-center flex-1">
                                <div class="flex-shrink-0 flex items-center justify-center h-16 w-16 rounded-full bg-blue-100 dark:bg-blue-900 mr-4">
                                    @if($viewingHotel->thumbnail)
                                        <img src="{{ asset('storage/' . ltrim($viewingHotel->thumbnail, '/storage/')) }}" 
                                             alt="{{ $viewingHotel->name }}" 
                                             class="h-16 w-16 rounded-full object-cover"
                                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div class="hidden h-16 w-16 rounded-full bg-blue-100 dark:bg-blue-900 items-center justify-center">
                                            <i class="fas fa-hotel text-blue-600 dark:text-blue-400 text-2xl"></i>
                                        </div>
                                    @else
                                        <i class="fas fa-hotel text-blue-600 dark:text-blue-400 text-2xl"></i>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-3xl leading-6 font-bold text-gray-900 dark:text-white mb-2">
                                        {{ $viewingHotel->name }}
                                    </h3>
                                    @if($viewingHotel->location)
                                        <p class="text-sm text-gray-500 dark:text-gray-400 flex items-center">
                                            <i class="fas fa-map-marker-alt mr-2"></i>
                                            {{ $viewingHotel->location->name }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                            <div class="flex flex-col items-end space-y-2">
                                @if($viewingHotel->is_featured)
                                    <span class="px-3 py-1 bg-amber-100 dark:bg-amber-900 text-amber-800 dark:text-amber-300 rounded-full text-sm font-medium">
                                        <i class="fas fa-star mr-1"></i> Destaque
                                    </span>
                                @endif
                                @if($viewingHotel->rating)
                                    <div class="flex items-center">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $viewingHotel->rating ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600' }}"></i>
                                        @endfor
                                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">({{ $viewingHotel->rating }} estrelas)</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Grid de informações principais -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                        <!-- Preço -->
                        @if($viewingHotel->price)
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-tag text-green-500 mr-2"></i>
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Preço Base</span>
                                </div>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($viewingHotel->price, 0, ',', '.') }} KZ</p>
                            </div>
                        @endif

                        <!-- Check-in Time -->
                        @if($viewingHotel->check_in_time)
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-clock text-blue-500 mr-2"></i>
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Check-in</span>
                                </div>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $viewingHotel->check_in_time }}</p>
                            </div>
                        @endif

                        <!-- Check-out Time -->
                        @if($viewingHotel->check_out_time)
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-clock text-orange-500 mr-2"></i>
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Check-out</span>
                                </div>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $viewingHotel->check_out_time }}</p>
                            </div>
                        @endif

                        <!-- Status -->
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-info-circle text-gray-500 mr-2"></i>
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</span>
                            </div>
                            @if($viewingHotel->is_active)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                    <i class="fas fa-check-circle mr-1"></i> Ativo
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">
                                    <i class="fas fa-times-circle mr-1"></i> Inativo
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Informações de Contato -->
                    <div class="mb-6">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                            <i class="fas fa-address-book text-gray-500 mr-2"></i>
                            Informações de Contato
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @if($viewingHotel->address)
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3 flex items-center">
                                    <i class="fas fa-map-marker-alt text-red-500 mr-3"></i>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Endereço</p>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $viewingHotel->address }}</p>
                                    </div>
                                </div>
                            @endif

                            @if($viewingHotel->phone)
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3 flex items-center">
                                    <i class="fas fa-phone text-green-500 mr-3"></i>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Telefone</p>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $viewingHotel->phone }}</p>
                                    </div>
                                </div>
                            @endif

                            @if($viewingHotel->email)
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3 flex items-center">
                                    <i class="fas fa-envelope text-blue-500 mr-3"></i>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Email</p>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $viewingHotel->email }}</p>
                                    </div>
                                </div>
                            @endif

                            @if($viewingHotel->website)
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3 flex items-center">
                                    <i class="fas fa-globe text-purple-500 mr-3"></i>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Website</p>
                                        <a href="{{ $viewingHotel->website }}" target="_blank" class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:underline">
                                            Ver Website <i class="fas fa-external-link-alt ml-1 text-xs"></i>
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Descrição -->
                    @if($viewingHotel->description)
                        <div class="mb-6">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                                <i class="fas fa-align-left text-gray-500 mr-2"></i>
                                Descrição
                            </h4>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $viewingHotel->description }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Comodidades -->
                    @if(!empty($viewingHotel->amenities) && is_array($viewingHotel->amenities))
                        <div class="mb-6">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                                <i class="fas fa-concierge-bell text-gray-500 mr-2"></i>
                                Comodidades do Hotel
                            </h4>
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                                @foreach($viewingHotel->amenities as $amenityId)
                                    @php
                                        $amenity = \App\Models\Amenity::find($amenityId);
                                    @endphp
                                    @if($amenity)
                                        <div class="flex items-center bg-gray-50 dark:bg-gray-700 rounded-lg p-3">
                                            <i class="{{ $amenity->icon ?? 'fas fa-check-circle' }} text-green-500 mr-2"></i>
                                            <span class="text-sm text-gray-700 dark:text-gray-300">{{ $amenity->name }}</span>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Coordenadas e Mapa -->
                    @if($viewingHotel->latitude && $viewingHotel->longitude)
                        <div class="mb-6">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                                <i class="fas fa-map text-gray-500 mr-2"></i>
                                Localização Geográfica
                            </h4>
                            <div class="grid grid-cols-2 gap-4 mb-3">
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Latitude</p>
                                    <p class="text-sm font-mono font-medium text-gray-900 dark:text-white">{{ $viewingHotel->latitude }}</p>
                                </div>
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Longitude</p>
                                    <p class="text-sm font-mono font-medium text-gray-900 dark:text-white">{{ $viewingHotel->longitude }}</p>
                                </div>
                            </div>
                            @if($viewingHotel->map_link)
                                <a href="{{ $viewingHotel->map_link }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-map-marked-alt mr-2"></i>
                                    Ver no Mapa
                                    <i class="fas fa-external-link-alt ml-2 text-xs"></i>
                                </a>
                            @endif
                        </div>
                    @endif

                    <!-- Galeria de Imagens -->
                    @if(!empty($viewingHotel->images))
                        <div class="mb-6">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                                <i class="fas fa-images text-gray-500 mr-2"></i>
                                Galeria de Imagens
                            </h4>
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                @php
                                    $images = is_array($viewingHotel->images) ? $viewingHotel->images : json_decode($viewingHotel->images, true) ?? [];
                                @endphp
                                
                                @forelse($images as $image)
                                    <div class="relative aspect-video rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow">
                                        <img src="{{ asset('storage/' . ltrim($image, '/storage/')) }}" 
                                             alt="{{ $viewingHotel->name }}" 
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

                    <!-- Informações Adicionais -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @if($viewingHotel->slug)
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Slug (URL)</p>
                                <p class="text-sm font-mono text-gray-900 dark:text-white">{{ $viewingHotel->slug }}</p>
                            </div>
                        @endif
                        
                        @if($viewingHotel->user)
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Gestor Responsável</p>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $viewingHotel->user->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $viewingHotel->user->email }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Footer com ações -->
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-200 dark:border-gray-600">
                    <button wire:click="openModal({{ $viewingHotel->id }})" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                        <i class="fas fa-edit mr-2"></i>
                        Editar Hotel
                    </button>
                    <a href="{{ route('hotel.details', $viewingHotel->id) }}" target="_blank" class="mt-3 w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
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

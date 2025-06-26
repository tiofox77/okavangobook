<div class="space-y-6">
    <!-- Header Section -->
    <div class="mb-8">
        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
            <i class="fas fa-map-marker-alt text-blue-600 mr-2"></i>
            Selecionar Localização
        </h3>
        <p class="text-gray-600 dark:text-gray-400">
            Escolha a localização onde deseja fazer a reserva
        </p>
    </div>

    <!-- Search Filter -->
    <div class="mb-6">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400"></i>
            </div>
            <input type="text" 
                   wire:model.live.debounce.300ms="locationFilter"
                   placeholder="Pesquisar localização..."
                   class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-all duration-200">
        </div>
    </div>

    <!-- Locations Grid -->
    @if($locations->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($locations as $location)
                <div class="group relative bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden transition-all duration-300 cursor-pointer transform hover:-translate-y-1"
                     wire:click="selectLocation({{ $location->id }})"
                     role="button"
                     tabindex="0">
                    
                    <!-- Location Image -->
                    <div class="relative h-48 bg-gradient-to-br from-blue-400 to-blue-600 overflow-hidden">
                        @php
                            $imageSrc = \App\Helpers\ImageHelper::getValidImage($location->image, 'location');
                        @endphp
                        <img src="{{ $imageSrc }}" 
                             alt="{{ $location->name }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                             onerror="this.onerror=null; this.src='{{ \App\Helpers\ImageHelper::generateDefaultSvg('location', $location->name, 400, 300) }}';">
                        
                        <!-- Overlay -->
                        <div class="absolute inset-0 bg-black/20 group-hover:bg-black/30 transition-all duration-300"></div>
                        
                        <!-- Featured Badge -->
                        @if($location->is_featured)
                            <div class="absolute top-3 right-3">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-yellow-400 to-orange-500 text-white shadow-lg">
                                    <i class="fas fa-star mr-1"></i>
                                    Destaque
                                </span>
                            </div>
                        @endif
                    </div>

                    <!-- Location Info -->
                    <div class="p-4">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                            {{ $location->name }}
                        </h4>
                        
                        @if($location->description)
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3 line-clamp-2">
                                {{ Str::limit($location->description, 120) }}
                            </p>
                        @endif

                        <!-- Location Stats -->
                        <div class="flex justify-between items-center text-sm text-gray-500 dark:text-gray-400">
                            @if($location->province)
                                <span class="flex items-center">
                                    <i class="fas fa-map text-blue-500 mr-1"></i>
                                    {{ $location->province }}
                                </span>
                            @endif
                            
                            @if($location->hotels_count > 0)
                                <span class="flex items-center">
                                    <i class="fas fa-hotel text-indigo-500 mr-1"></i>
                                    {{ $location->hotels_count }} {{ $location->hotels_count == 1 ? 'hotel' : 'hotéis' }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center py-12 bg-gray-50 dark:bg-gray-800 rounded-xl border-2 border-dashed border-gray-300 dark:border-gray-600">
            <div class="mx-auto w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                <i class="fas fa-search text-3xl text-gray-400"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
                Nenhuma localização encontrada
            </h3>
            <p class="text-gray-600 dark:text-gray-400 mb-4">
                Tente ajustar o filtro de pesquisa ou verificar se existem localizações disponíveis
            </p>
            @if(!empty($locationFilter))
                <button wire:click="$set('locationFilter', '')" 
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                    <i class="fas fa-times mr-2"></i>
                    Limpar Filtro
                </button>
            @endif
        </div>
    @endif
</div>

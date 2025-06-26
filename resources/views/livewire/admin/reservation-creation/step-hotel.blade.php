<div class="space-y-6">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                    <i class="fas fa-hotel text-blue-600 mr-2"></i>
                    Selecionar Hotel
                </h3>
                <p class="text-gray-600 dark:text-gray-400">
                    Escolha o hotel para a sua reserva
                </p>
            </div>
            <button wire:click="goToPreviousStep" 
                    class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                Voltar
            </button>
        </div>
    </div>

    <!-- Search Filter -->
    <div class="mb-6">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400"></i>
            </div>
            <input type="text" 
                   wire:model.live.debounce.300ms="hotelFilter"
                   placeholder="Pesquisar hotéis..." 
                   class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200">
        </div>
    </div>

    <!-- Hotels Grid -->
    @if($hotels->count() > 0)
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @foreach($hotels as $hotel)
                <div class="group relative bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden transition-all duration-300 cursor-pointer"
                     wire:click="selectHotel({{ $hotel->id }})"
                     role="button"
                     tabindex="0">
                    
                    <!-- Hotel Image -->
                    <div class="relative h-48 bg-gradient-to-br from-indigo-400 to-purple-600 overflow-hidden">
                        @php
                            $imageSrc = \App\Helpers\ImageHelper::getValidImage($hotel->thumbnail, 'hotel');
                        @endphp
                        <img src="{{ $imageSrc }}" 
                             alt="{{ $hotel->name }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                             onerror="this.onerror=null; this.src='{{ \App\Helpers\ImageHelper::generateDefaultSvg('hotel', $hotel->name, 400, 300) }}';">
                        
                        <!-- Overlay -->
                        <div class="absolute inset-0 bg-black/20 group-hover:bg-black/30 transition-all duration-300"></div>
                        
                        <!-- Hotel Rating -->
                        @if($hotel->rating)
                            <div class="absolute top-3 left-3">
                                <div class="flex items-center bg-white/90 dark:bg-gray-800/90 rounded-full px-3 py-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star text-sm {{ $i <= $hotel->stars ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                    @endfor
                                    <span class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                        {{ number_format($hotel->rating, 1) }}
                                    </span>
                                </div>
                            </div>
                        @endif
                        
                        <!-- Featured Badge -->
                        @if($hotel->is_featured)
                            <div class="absolute top-3 right-3">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-yellow-400 to-orange-500 text-white shadow-lg">
                                    <i class="fas fa-star mr-1"></i>
                                    Destaque
                                </span>
                            </div>
                        @endif
                    </div>

                    <!-- Hotel Info -->
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-3">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                {{ $hotel->name }}
                            </h4>
                            @if($hotel->min_price)
                                <div class="text-right">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">a partir de</p>
                                    <p class="text-lg font-bold text-green-600 dark:text-green-400">
                                        {{ \App\Helpers\CurrencyHelper::formatKwanza($hotel->min_price) }}
                                    </p>
                                </div>
                            @endif
                        </div>
                        
                        @if($hotel->description)
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 line-clamp-2">
                                {{ Str::limit($hotel->description, 150) }}
                            </p>
                        @endif

                        <!-- Hotel Details -->
                        <div class="space-y-2 mb-4">
                            @if($hotel->address)
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                    <i class="fas fa-map-marker-alt mr-2 text-gray-400 w-4"></i>
                                    {{ $hotel->address }}
                                </div>
                            @endif
                            
                            @if($hotel->phone)
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                    <i class="fas fa-phone mr-2 text-gray-400 w-4"></i>
                                    {{ $hotel->phone }}
                                </div>
                            @endif

                            @if($hotel->email)
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                    <i class="fas fa-envelope mr-2 text-gray-400 w-4"></i>
                                    {{ $hotel->email }}
                                </div>
                            @endif

                            @if($hotel->website)
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                    <i class="fas fa-globe mr-2 text-gray-400 w-4"></i>
                                    Website disponível
                                </div>
                            @endif
                        </div>

                        <!-- Hotel Features -->
                        @php
                            $amenities = $hotel->amenities;
                            if (is_string($amenities)) {
                                $amenities = json_decode($amenities, true) ?: [];
                            }
                            $amenities = is_array($amenities) ? $amenities : [];
                        @endphp
                        @if(!empty($amenities))
                            <div class="mb-4">
                                <h5 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Comodidades:</h5>
                                <div class="flex flex-wrap gap-2">
                                    @foreach(array_slice($amenities, 0, 4) as $amenity)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                                            {{ $amenity }}
                                        </span>
                                    @endforeach
                                    
                                    @if(count($amenities) > 4)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                            +{{ count($amenities) - 4 }} mais
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Action Button -->
                        <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                            <button type="button" 
                                    class="w-full inline-flex items-center justify-center px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 group-hover:bg-blue-700">
                                <i class="fas fa-check mr-2"></i>
                                Selecionar Hotel
                                <i class="fas fa-arrow-right ml-2"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center py-12 bg-gray-50 dark:bg-gray-800 rounded-xl border-2 border-dashed border-gray-300 dark:border-gray-600">
            <div class="mx-auto w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                <i class="fas fa-hotel text-3xl text-gray-400"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
                Nenhum hotel encontrado
            </h3>
            <p class="text-gray-600 dark:text-gray-400 mb-4">
                @if(!empty($hotelFilter))
                    Nenhum hotel corresponde ao filtro "{{ $hotelFilter }}". Tente ajustar a pesquisa.
                @else
                    Não há hotéis disponíveis nesta localização ou você pode não ter permissão para visualizá-los.
                @endif
            </p>
            <div class="flex justify-center space-x-3">
                @if(!empty($hotelFilter))
                    <button wire:click="$set('hotelFilter', '')" 
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                        <i class="fas fa-times mr-2"></i>
                        Limpar Filtro
                    </button>
                @endif
                <button wire:click="goToPreviousStep" 
                        class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Escolher Outra Localização
                </button>
            </div>
        </div>
    @endif
</div>

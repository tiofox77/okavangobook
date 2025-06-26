@php
    use Illuminate\Support\Str;
@endphp

<div class="bg-gray-100 min-h-screen relative">
    <!-- Indicador de carregamento elegante para pesquisa principal -->
    <div wire:loading wire:target="search" class="fixed inset-0 bg-black bg-opacity-30 backdrop-blur-sm z-50 flex items-center justify-center transition-all duration-300">
        <div class="bg-white rounded-lg shadow-xl p-6 max-w-md mx-auto transform transition-all scale-90 opacity-0 animate-fadeIn">
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <div class="w-12 h-12 rounded-full border-4 border-gray-200">
                        <div class="w-12 h-12 rounded-full border-4 border-t-primary border-r-transparent border-b-transparent border-l-transparent animate-spin absolute top-0 left-0"></div>
                    </div>
                </div>
                <div class="flex-1">
                    <h3 class="text-gray-800 text-lg font-medium">Buscando hospedagens</h3>
                    <p class="text-gray-500 text-sm">Aguarde enquanto encontramos as melhores opções para você...</p>
                </div>
            </div>
        </div>
    </div>
    
    <style>
        @keyframes fadeIn {
            0% { opacity: 0; transform: scale(0.9); }
            100% { opacity: 1; transform: scale(1); }
        }
        .animate-fadeIn {
            animation: fadeIn 0.3s ease-out forwards;
        }
    </style>
    
    <!-- Indicador de carregamento sutil para filtros -->
    <div wire:loading.flex wire:target="stars, ratings, amenities, selectedProvinces, minPrice, maxPrice, setSorting, applyProvinceFilter, toggleRatingFilter, toggleAmenityFilter" 
         class="hidden fixed bottom-4 right-4 z-40 items-center">
        <div class="bg-white rounded-full shadow-lg py-2 px-4 flex items-center space-x-3 animate-fadeIn">
            <div class="relative">
                <div class="w-6 h-6 rounded-full border-2 border-gray-200">
                    <div class="w-6 h-6 rounded-full border-2 border-t-primary border-r-transparent border-b-transparent border-l-transparent animate-spin absolute top-0 left-0"></div>
                </div>
            </div>
            <p class="text-gray-700 text-sm font-medium">Atualizando resultados...</p>
        </div>
    </div>
    <!-- Barra de pesquisa compacta para refinar a busca -->
    <div class="bg-white shadow-md py-4">
        <div class="container mx-auto px-4">
            <form wire:submit.prevent="search" class="flex flex-wrap items-end gap-2">
                <div class="flex-1 min-w-[200px]">
                    <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Destino</label>
                    <input 
                        type="text" 
                        id="location" 
                        wire:model.defer="location" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary"
                        placeholder="Para onde você vai?"
                    >
                </div>
                
                <div class="flex-1 min-w-[150px]">
                    <label for="check_in" class="block text-sm font-medium text-gray-700 mb-1">Check-in</label>
                    <input 
                        type="date" 
                        id="check_in" 
                        wire:model.defer="checkIn" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary"
                    >
                </div>
                
                <div class="flex-1 min-w-[150px]">
                    <label for="check_out" class="block text-sm font-medium text-gray-700 mb-1">Check-out</label>
                    <input 
                        type="date" 
                        id="check_out" 
                        wire:model.defer="checkOut" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary"
                    >
                </div>
                
                <div class="flex-1 min-w-[100px]">
                    <label for="guests" class="block text-sm font-medium text-gray-700 mb-1">Hóspedes</label>
                    <input 
                        type="number" 
                        id="guests" 
                        wire:model.defer="guests" 
                        min="1" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary"
                    >
                </div>
                
                <div class="flex-1 min-w-[100px]">
                    <label for="rooms" class="block text-sm font-medium text-gray-700 mb-1">Quartos</label>
                    <input 
                        type="number" 
                        id="rooms" 
                        wire:model.defer="rooms" 
                        min="1" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary"
                    >
                </div>
                
                <div>
                    <button 
                        type="submit" 
                        wire:loading.attr="disabled"
                        wire:target="search"
                        class="bg-primary hover:bg-primary-dark text-white font-medium px-4 py-2 rounded-md transition duration-300 flex items-center relative overflow-hidden"
                    >
                        <div class="flex items-center space-x-2 relative">
                            <i class="fas fa-search"></i>
                            <span wire:loading.remove wire:target="search">Buscar</span>
                            <span wire:loading wire:target="search" class="flex items-center space-x-2">
                                <span>Buscando</span>
                                <span class="flex space-x-0.5">
                                    <span class="animate-[bounce_1s_infinite_200ms] inline-block w-1 h-1 bg-white rounded-full"></span>
                                    <span class="animate-[bounce_1s_infinite_400ms] inline-block w-1 h-1 bg-white rounded-full"></span>
                                    <span class="animate-[bounce_1s_infinite_600ms] inline-block w-1 h-1 bg-white rounded-full"></span>
                                </span>
                            </span>
                        </div>
                        
                        <!-- Efeito de carregamento na borda inferior -->
                        <span wire:loading wire:target="search" class="absolute bottom-0 left-0 w-full h-1 bg-white animate-pulse"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Conteúdo principal -->
    <div class="container mx-auto px-4 py-8">
        <!-- Título da página e estatísticas -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">
                @if($searchedLocation)
                    Hotéis em {{ $searchedLocation->name }}, {{ $searchedLocation->province }}
                @else
                    Resultados da sua busca
                @endif
            </h1>
            <p class="text-gray-600">
                {{ $searchResults->total() }} hotéis encontrados
                @if($checkIn && $checkOut)
                    para {{ Carbon\Carbon::parse($checkIn)->format('d/m/Y') }} a {{ Carbon\Carbon::parse($checkOut)->format('d/m/Y') }}
                    ({{ $nights }} {{ $nights == 1 ? 'noite' : 'noites' }})
                @endif
            </p>
        </div>
        
        <div class="flex flex-wrap md:flex-nowrap gap-6" wire:loading.class.delay="opacity-75">
            <!-- Filtros - Coluna esquerda -->
            <div class="w-full md:w-1/4 md:min-w-[280px] md:max-w-[300px] mb-6" wire:loading.class="opacity-75">
                <div class="bg-white rounded-xl shadow-lg p-5 mb-4 border border-gray-100 hover:border-primary/20 transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-bold text-gray-800">Filtros de Busca</h2>
                        <button 
                            wire:click="clearFilters" 
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-50 cursor-wait"
                            class="text-sm text-primary hover:text-primary-dark transition-colors duration-300 flex items-center gap-1">
                            <i class="fas fa-redo-alt" wire:loading.class="animate-spin"></i>
                            <span wire:loading.remove>Limpar</span>
                            <span wire:loading>Limpando...</span>
                    </div>
                    
                    <!-- Filtro de destino -->
                    <div class="mb-6">
                        <h3 class="font-medium text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-map-marker-alt text-primary mr-2"></i> Destino
                        </h3>
                        <div class="relative">
                            <input 
                                type="text" 
                                wire:model.debounce.500ms="location" 
                                placeholder="Digite um destino" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary pl-10"
                            >
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                        </div>
                        
                        @if($popularDestinations && count($popularDestinations) > 0)
                        <div class="mt-2">
                            <p class="text-xs text-gray-500 mb-1">Destinos populares:</p>
                            <div class="flex flex-wrap gap-1">
                                @foreach($popularDestinations as $dest)
                                <button 
                                    wire:click="selectDestination('{{ $dest->id }}', '{{ $dest->name }}')"
                                    class="text-xs bg-gray-100 hover:bg-primary/10 text-gray-700 px-2 py-1 rounded-full transition-colors duration-300"
                                >
                                    {{ $dest->name }}
                                </button>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Filtro de províncias -->
                    <div class="mb-6" x-data="{open: true}">
                        <div class="flex items-center justify-between cursor-pointer" @click="open = !open">
                            <h3 class="font-medium text-gray-700 flex items-center">
                                <i class="fas fa-map text-primary mr-2"></i> Províncias
                                @if(count($selectedProvinces) > 0)
                                    <span class="ml-1 text-xs bg-primary text-white rounded-full px-2 py-0.5">{{ count($selectedProvinces) }}</span>
                                @endif
                            </h3>
                            <i class="fas" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                        </div>
                        
                        <!-- Resumo das províncias selecionadas -->
                        @if(count($selectedProvinces) > 0)
                            <div class="mt-2 mb-2 flex flex-wrap gap-1">
                                @foreach($selectedProvinces as $selectedProvince)
                                    <div class="inline-flex items-center bg-primary/10 text-primary text-xs rounded-full px-2 py-1">
                                        <span>{{ $selectedProvince }}</span>
                                        <button wire:click="toggleProvinceFilter('{{ $selectedProvince }}')" class="ml-1 text-primary hover:text-primary-dark focus:outline-none">
                                            <i class="fas fa-times-circle"></i>
                                        </button>
                                    </div>
                                @endforeach
                                <button 
                                    wire:click="clearProvinceFilters" 
                                    class="text-xs text-gray-500 hover:text-primary underline focus:outline-none ml-1"
                                >
                                    Limpar todos
                                </button>
                            </div>
                        @endif
                        
                        <div x-show="open" class="mt-2 space-y-1">
                            @if(count($provinces) > 0)
                                @foreach($provinces as $province)
                                <div class="flex items-center justify-between py-1 px-2 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                                    <button 
                                        wire:click="toggleProvinceFilter('{{ $province->province }}')" 
                                        class="flex items-center justify-between w-full relative overflow-hidden {{ in_array($province->province, $selectedProvinces) ? 'text-primary font-medium' : 'text-gray-700' }}"
                                    >
                                        <div class="flex items-center">
                                            <div class="w-5 h-5 flex items-center justify-center mr-2 border border-gray-300 rounded {{ in_array($province->province, $selectedProvinces) ? 'bg-primary border-primary' : 'bg-white' }}">
                                                @if(in_array($province->province, $selectedProvinces))
                                                    <i class="fas fa-check text-xs text-white"></i>
                                                @endif
                                            </div>
                                            <span class="text-sm truncate max-w-[150px]">{{ $province->province }}</span>
                                        </div>
                                        
                                        <div class="flex items-center">
                                            @if(isset($provinceCounts[$province->province]))
                                                <span class="text-xs text-gray-500 mr-2">({{ $provinceCounts[$province->province] }})</span>
                                            @endif
                                            
                                            <div wire:loading wire:target="toggleProvinceFilter('{{ $province->province }}')" 
                                                class="w-4 h-4 border-2 border-t-primary border-r-transparent border-b-transparent border-l-transparent rounded-full animate-spin">
                                            </div>
                                        </div>
                                    </button>
                                </div>
                                @endforeach
                            @else
                                <div class="text-sm text-gray-500 py-2">Nenhuma província disponível</div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Filtro de preço -->
                    <div class="mb-6" x-data="{open: true}">
                        <div class="flex items-center justify-between cursor-pointer" @click="open = !open">
                            <h3 class="font-medium text-gray-700 flex items-center">
                                <i class="fas fa-tag text-primary mr-2"></i> Preço por noite
                            </h3>
                            <i class="fas" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                        </div>
                        
                        <div x-show="open" class="mt-3">
                            <!-- Inputs de preço -->
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div class="relative">
                                    <label for="min-price" class="block text-xs text-gray-500 mb-1">Preço mínimo</label>
                                    <div class="relative">
                                        <input type="number" 
                                            id="min-price"
                                            wire:model="minPrice" 
                                            min="0" 
                                            max="1000000" 
                                            step="5000" 
                                            class="w-full px-8 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary"
                                            placeholder="Mínimo"
                                        >
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 text-xs">AKZ</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="relative">
                                    <label for="max-price" class="block text-xs text-gray-500 mb-1">Preço máximo</label>
                                    <div class="relative">
                                        <input type="number" 
                                            id="max-price"
                                            wire:model="maxPrice" 
                                            min="0" 
                                            max="1000000" 
                                            step="5000" 
                                            class="w-full px-8 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary"
                                            placeholder="Máximo"
                                        >
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 text-xs">AKZ</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Preço atual aplicado -->
                            <div class="text-sm text-gray-700 mt-3 mb-3 flex items-center justify-between">
                                <div>Faixa atual:</div>
                                <div class="font-medium">
                                    <span wire:loading.class="opacity-50" wire:target="applyPriceFilter">
                                        AKZ {{ number_format($minPrice, 0, ',', '.') }} - AKZ {{ number_format($maxPrice, 0, ',', '.') }}
                                    </span>
                                    <span wire:loading wire:target="applyPriceFilter" class="ml-2">
                                        <div class="inline-block w-4 h-4 border-2 border-t-primary border-r-transparent border-b-transparent border-l-transparent rounded-full animate-spin"></div>
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Botão para aplicar filtro -->
                            <button 
                                wire:click="applyPriceFilter"
                                wire:loading.attr="disabled"
                                wire:target="applyPriceFilter"
                                class="w-full py-2 mt-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors duration-200 relative overflow-hidden"
                            >
                                <div class="flex items-center justify-center space-x-1">
                                    <i class="fas fa-filter text-primary text-xs"></i>
                                    <span wire:loading.remove wire:target="applyPriceFilter">Aplicar filtro de preço</span>
                                    <span wire:loading wire:target="applyPriceFilter" class="flex items-center space-x-2">
                                        <span>Aplicando...</span>
                                    </span>
                                </div>
                                <!-- Indicador de progresso na borda inferior -->
                                <div wire:loading wire:target="applyPriceFilter" class="absolute bottom-0 left-0 h-1 bg-primary animate-pulse w-full"></div>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Filtro de avaliação -->
                    <div class="mb-6" x-data="{open: true}">
                        <div class="flex items-center justify-between cursor-pointer" @click="open = !open">
                            <h3 class="font-medium text-gray-700 flex items-center">
                                <i class="fas fa-star text-primary mr-2"></i> Classificação
                            </h3>
                            <i class="fas" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                        </div>
                        
                        <div x-show="open" class="mt-2 space-y-2">
                            @for($i = 5; $i >= 1; $i--)
                                <div class="flex items-center justify-between py-1 px-2 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                                    <button 
                                        wire:click="toggleStarFilter({{ $i }})" 
                                        class="flex items-center justify-between w-full relative overflow-hidden {{ in_array($i, $stars) ? 'text-primary font-medium' : 'text-gray-700' }}"
                                    >
                                        <div class="flex items-center">
                                            @for($j = 1; $j <= $i; $j++)
                                                <i class="fas fa-star text-yellow-400 text-sm"></i>
                                            @endfor
                                            @for($j = $i + 1; $j <= 5; $j++)
                                                <i class="far fa-star text-yellow-400 text-sm"></i>
                                            @endfor
                                            <span class="ml-1 text-sm">{{ $i }} {{ $i == 1 ? 'estrela' : 'estrelas' }}</span>
                                        </div>
                                        
                                        <div class="flex items-center">
                                            @if(isset($starCounts[$i]))
                                                <span class="text-xs text-gray-500 mr-2">({{ $starCounts[$i] }})</span>
                                            @endif
                                            
                                            <div wire:loading wire:target="toggleStarFilter({{ $i }})" 
                                                class="w-4 h-4 border-2 border-t-primary border-r-transparent border-b-transparent border-l-transparent rounded-full animate-spin">
                                            </div>
                                            
                                            @if(in_array($i, $stars))
                                                <i class="fas fa-check-circle text-primary ml-1"></i>
                                            @endif
                                        </div>
                                    </button>
                                </div>
                            @endfor
                        </div>
                    </div>
                    
                    <!-- Filtro de avaliação dos hóspedes -->
                    <div class="mb-6" x-data="{open: true}">
                        <div class="flex items-center justify-between cursor-pointer" @click="open = !open">
                            <h3 class="font-medium text-gray-700 flex items-center">
                                <i class="fas fa-thumbs-up text-primary mr-2"></i> Avaliação de Hóspedes
                            </h3>
                            <i class="fas" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                        </div>
                        
                        <div x-show="open" class="mt-2 space-y-2">
                            @foreach([['Excelente', 9], ['Muito Bom', 8], ['Bom', 7], ['Regular', 6], ['Ruim', 0]] as $rating)
                                <div class="flex items-center justify-between py-1 px-2 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                                    <button 
                                        wire:click="toggleRatingFilter({{ $rating[1] }})" 
                                        class="flex items-center justify-between w-full relative overflow-hidden {{ in_array($rating[1], $ratings) ? 'text-primary font-medium' : 'text-gray-700' }}"
                                    >
                                        <div class="flex items-center">
                                            <div class="w-5 h-5 flex items-center justify-center mr-2 border border-gray-300 rounded {{ in_array($rating[1], $ratings) ? 'bg-primary border-primary' : 'bg-white' }}">
                                                @if(in_array($rating[1], $ratings))
                                                    <i class="fas fa-check text-xs text-white"></i>
                                                @endif
                                            </div>
                                            <div class="flex items-center">
                                                <span class="text-sm">{{ $rating[0] }}</span>
                                                @if($rating[1] > 0)
                                                    <div class="ml-2 flex">
                                                        @for($j = 1; $j <= 5; $j++)
                                                            @if($j <= round($rating[1]/2))
                                                                <i class="fas fa-star text-yellow-400 text-xs"></i>
                                                            @else
                                                                <i class="far fa-star text-yellow-400 text-xs"></i>
                                                            @endif
                                                        @endfor
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-center">
                                            @if(isset($ratingCounts[$rating[1]]))
                                                <span class="text-xs text-gray-500 mr-2">({{ $ratingCounts[$rating[1]] }})</span>
                                            @endif
                                            
                                            <div wire:loading wire:target="toggleRatingFilter({{ $rating[1] }})" 
                                                class="w-4 h-4 border-2 border-t-primary border-r-transparent border-b-transparent border-l-transparent rounded-full animate-spin">
                                            </div>
                                        </div>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Filtro de comodidades -->
                    <div class="mb-6" x-data="{open: true}">
                        <div class="flex items-center justify-between cursor-pointer" @click="open = !open">
                            <h3 class="font-medium text-gray-700 flex items-center">
                                <i class="fas fa-concierge-bell text-primary mr-2"></i> Comodidades
                            </h3>
                            <i class="fas" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                        </div>
                        
                        <div x-show="open" class="mt-2 grid grid-cols-1 gap-2">
                            <div class="flex items-center py-1 px-2 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                                <input 
                                    type="checkbox" 
                                    id="amenity-wifi" 
                                    wire:model.live="amenities" 
                                    wire:loading.attr="disabled"
                                    value="wifi" 
                                    class="w-4 h-4 text-primary focus:ring-primary border-gray-300 rounded cursor-pointer"
                                >
                                <label for="amenity-wifi" class="ml-3 flex items-center cursor-pointer">
                                    <i class="fas fa-wifi text-gray-600 mr-2"></i>
                                    <span class="text-sm">Wi-Fi</span>
                                </label>
                                @if(isset($amenityCounts['wifi']))
                                    <span class="ml-auto text-xs text-gray-500">({{ $amenityCounts['wifi'] }})</span>
                                @endif
                            </div>
                            
                            <div class="flex items-center py-1 px-2 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                                <input 
                                    type="checkbox" 
                                    id="amenity-pool" 
                                    wire:model.live="amenities" 
                                    wire:loading.attr="disabled"
                                    value="pool" 
                                    class="w-4 h-4 text-primary focus:ring-primary border-gray-300 rounded cursor-pointer"
                                >
                                <label for="amenity-pool" class="ml-3 flex items-center cursor-pointer">
                                    <i class="fas fa-swimming-pool text-gray-600 mr-2"></i>
                                    <span class="text-sm">Piscina</span>
                                </label>
                                @if(isset($amenityCounts['pool']))
                                    <span class="ml-auto text-xs text-gray-500">({{ $amenityCounts['pool'] }})</span>
                                @endif
                            </div>
                            
                            <div class="flex items-center py-1 px-2 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                                <input 
                                    type="checkbox" 
                                    id="amenity-breakfast" 
                                    wire:model.live="amenities" 
                                    wire:loading.attr="disabled"
                                    value="breakfast" 
                                    class="w-4 h-4 text-primary focus:ring-primary border-gray-300 rounded cursor-pointer"
                                >
                                <label for="amenity-breakfast" class="ml-3 flex items-center cursor-pointer">
                                    <i class="fas fa-coffee text-gray-600 mr-2"></i>
                                    <span class="text-sm">Café da manhã</span>
                                </label>
                                @if(isset($amenityCounts['breakfast']))
                                    <span class="ml-auto text-xs text-gray-500">({{ $amenityCounts['breakfast'] }})</span>
                                @endif
                            </div>
                            
                            <div class="flex items-center py-1 px-2 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                                <input 
                                    type="checkbox" 
                                    id="amenity-parking" 
                                    wire:model.live="amenities" 
                                    wire:loading.attr="disabled"
                                    value="parking" 
                                    class="w-4 h-4 text-primary focus:ring-primary border-gray-300 rounded cursor-pointer"
                                >
                                <label for="amenity-parking" class="ml-3 flex items-center cursor-pointer">
                                    <i class="fas fa-parking text-gray-600 mr-2"></i>
                                    <span class="text-sm">Estacionamento</span>
                                </label>
                                @if(isset($amenityCounts['parking']))
                                    <span class="ml-auto text-xs text-gray-500">({{ $amenityCounts['parking'] }})</span>
                                @endif
                            </div>
                            
                            <div class="flex items-center py-1 px-2 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                                <input 
                                    type="checkbox" 
                                    id="amenity-ac" 
                                    wire:model.live="amenities" 
                                    wire:loading.attr="disabled"
                                    value="air_conditioning" 
                                    class="w-4 h-4 text-primary focus:ring-primary border-gray-300 rounded cursor-pointer"
                                >
                                <label for="amenity-ac" class="flex items-center cursor-pointer">
                                    <i class="fas fa-snowflake text-gray-600 mr-2"></i>
                                    <span>Ar condicionado</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Botão para limpar filtros -->
                    <button 
                        wire:click="clearFilters" 
                        wire:loading.attr="disabled"
                        wire:target="clearFilters"
                        class="w-full py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium rounded transition duration-300 relative overflow-hidden"
                    >
                        <div class="flex items-center justify-center space-x-1">
                            <i class="fas fa-eraser text-gray-600 text-xs"></i>
                            <span wire:loading.remove wire:target="clearFilters">Limpar filtros</span>
                            <span wire:loading wire:target="clearFilters" class="flex items-center space-x-2">
                                <span>Limpando...</span>
                                <div class="w-4 h-4 border-2 border-t-primary border-r-transparent border-b-transparent border-l-transparent rounded-full animate-spin"></div>
                            </span>
                        </div>
                        <!-- Indicador de progresso na borda inferior -->
                        <div wire:loading wire:target="clearFilters" class="absolute bottom-0 left-0 h-1 bg-primary animate-pulse w-full"></div>
                    </button>
                </div>
            </div>
            
            <!-- Resultados da busca - Coluna direita -->
            <div class="w-full md:w-3/4 relative min-h-[500px]">
                <!-- Indicador de carregamento elegante para resultados -->
                <div wire:loading wire:target="stars, ratings, amenities, selectedProvinces, minPrice, maxPrice, setSorting, applyProvinceFilter, toggleRatingFilter, toggleAmenityFilter, perPage" 
                     class="absolute inset-0 bg-white/60 backdrop-blur-[1px] z-10 transition-opacity duration-300">
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="bg-white rounded-lg shadow-md p-4 flex items-center space-x-3 animate-fadeIn">
                            <div class="relative">
                                <div class="w-8 h-8 rounded-full border-3 border-gray-200">
                                    <div class="w-8 h-8 rounded-full border-3 border-t-primary border-r-transparent border-b-transparent border-l-transparent animate-spin absolute top-0 left-0"></div>
                                </div>
                            </div>
                            <p class="text-gray-700 font-medium">Atualizando...</p>
                        </div>
                    </div>
                    <!-- Grade de pulso para os resultados -->
                    <div class="absolute inset-0 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 p-6 opacity-10 pointer-events-none">
                        @for($i = 0; $i < 6; $i++)
                            <div class="bg-white h-48 rounded-lg shadow-sm animate-pulse"></div>
                        @endfor
                    </div>
                </div>
                <!-- Ordenação -->
                <div class="bg-white rounded-lg shadow-md p-4 mb-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
                        <div>
                            <span class="font-medium text-gray-700 block mb-2">Ordenar por:</span>
                            <div class="flex flex-wrap gap-2">
                                <button 
                                    wire:click="setSorting('recommended')" 
                                    wire:loading.attr="disabled"
                                    wire:target="setSorting"
                                    class="px-3 py-1.5 rounded-lg text-sm font-medium transition-colors duration-200 relative overflow-hidden {{ $sortBy == 'recommended' ? 'bg-primary text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-700' }}"
                                >
                                    <div class="flex items-center space-x-1 relative">
                                        <i class="fas fa-thumbs-up {{ $sortBy == 'recommended' ? 'text-white' : 'text-gray-600' }} text-xs"></i>
                                        <span>Recomendados</span>
                                        
                                        <!-- Indicador de carregamento elegante inline -->
                                        <div wire:loading wire:target="setSorting('recommended')" class="absolute right-0 ml-1">
                                            <div class="w-4 h-4 border-2 border-t-primary border-r-transparent border-b-transparent border-l-transparent rounded-full animate-spin"></div>
                                        </div>
                                    </div>
                                </button>
                                <button 
                                    wire:click="setSorting('price_asc')" 
                                    wire:loading.attr="disabled"
                                    wire:target="setSorting"
                                    class="px-3 py-1.5 rounded-lg text-sm font-medium transition-colors duration-200 relative overflow-hidden {{ $sortBy == 'price_asc' ? 'bg-primary text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-700' }}"
                                >
                                    <div class="flex items-center space-x-1 relative">
                                        <i class="fas fa-sort-amount-down-alt {{ $sortBy == 'price_asc' ? 'text-white' : 'text-gray-600' }} text-xs"></i>
                                        <span>Menor preço</span>
                                        
                                        <!-- Indicador de carregamento -->
                                        <div wire:loading wire:target="setSorting('price_asc')" class="absolute right-0 ml-1">
                                            <div class="w-4 h-4 border-2 border-t-primary border-r-transparent border-b-transparent border-l-transparent rounded-full animate-spin"></div>
                                        </div>
                                    </div>
                                </button>
                                <button 
                                    wire:click="setSorting('price_desc')" 
                                    wire:loading.attr="disabled"
                                    wire:target="setSorting"
                                    class="px-3 py-1.5 rounded-lg text-sm font-medium transition-colors duration-200 relative overflow-hidden {{ $sortBy == 'price_desc' ? 'bg-primary text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-700' }}"
                                >
                                    <div class="flex items-center space-x-1 relative">
                                        <i class="fas fa-sort-amount-up {{ $sortBy == 'price_desc' ? 'text-white' : 'text-gray-600' }} text-xs"></i>
                                        <span>Maior preço</span>
                                        
                                        <!-- Indicador de carregamento -->
                                        <div wire:loading wire:target="setSorting('price_desc')" class="absolute right-0 ml-1">
                                            <div class="w-4 h-4 border-2 border-t-primary border-r-transparent border-b-transparent border-l-transparent rounded-full animate-spin"></div>
                                        </div>
                                    </div>
                                </button>
                                <button 
                                    wire:click="setSorting('rating')" 
                                    wire:loading.attr="disabled"
                                    wire:target="setSorting"
                                    class="px-3 py-1.5 rounded-lg text-sm font-medium transition-colors duration-200 relative overflow-hidden {{ $sortBy == 'rating' ? 'bg-primary text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-700' }}"
                                >
                                    <div class="flex items-center space-x-1 relative">
                                        <i class="fas fa-star {{ $sortBy == 'rating' ? 'text-white' : 'text-yellow-500' }} text-xs"></i>
                                        <span>Melhor avaliação</span>
                                        
                                        <!-- Indicador de carregamento -->
                                        <div wire:loading wire:target="setSorting('rating')" class="absolute right-0 ml-1">
                                            <div class="w-4 h-4 border-2 border-t-primary border-r-transparent border-b-transparent border-l-transparent rounded-full animate-spin"></div>
                                        </div>
                                    </div>
                                </button>
                                <button 
                                    wire:click="setSorting('stars_desc')" 
                                    wire:loading.attr="disabled"
                                    wire:target="setSorting"
                                    class="px-3 py-1.5 rounded-lg text-sm font-medium transition-colors duration-200 relative overflow-hidden {{ $sortBy == 'stars_desc' ? 'bg-primary text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-700' }}"
                                >
                                    <div class="flex items-center space-x-1 relative">
                                        <i class="fas fa-star {{ $sortBy == 'stars_desc' ? 'text-white' : 'text-yellow-400' }} text-xs"></i>
                                        <span>Mais estrelas</span>
                                        
                                        <!-- Indicador de carregamento -->
                                        <div wire:loading wire:target="setSorting('stars_desc')" class="absolute right-0 ml-1">
                                            <div class="w-4 h-4 border-2 border-t-primary border-r-transparent border-b-transparent border-l-transparent rounded-full animate-spin"></div>
                                        </div>
                                    </div>
                                </button>
                            </div>
                        </div>
                        
                        <div class="md:flex items-center space-x-2">
                            <span class="text-sm text-gray-700 font-medium">Mostrar:</span>
                            <select 
                                wire:model="perPage" 
                                class="text-sm border rounded-lg px-3 py-1.5 bg-white focus:ring-primary focus:border-primary text-gray-700 cursor-pointer shadow-sm">
                                <option value="10">10 por página</option>
                                <option value="20">20 por página</option>
                                <option value="50">50 por página</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <button
                            wire:click="$set('viewMode', 'list')"
                            class="p-2 rounded-md {{ $viewMode == 'list' ? 'bg-primary/10 text-primary' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }} transition-colors duration-200">
                            <i class="fas fa-list"></i>
                        </button>
                        <button
                            wire:click="$set('viewMode', 'grid')"
                            class="p-2 rounded-md {{ $viewMode == 'grid' ? 'bg-primary/10 text-primary' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }} transition-colors duration-200">
                            <i class="fas fa-th-large"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Resultados da busca -->
                <div class="{{ $viewMode == 'grid' ? 'grid grid-cols-1 md:grid-cols-2 gap-6' : 'space-y-6' }}">
                    @forelse($searchResults as $hotel)
                        <div class="bg-white rounded-xl shadow-md overflow-hidden transition-all duration-300 hover:shadow-xl border border-gray-100 hover:border-primary/20 relative group">
                            <div class="{{ $viewMode == 'grid' ? 'flex flex-col' : 'flex flex-col md:flex-row' }}">
                                <!-- Imagem do hotel -->
                                <div class="{{ $viewMode == 'grid' ? 'w-full h-48' : 'md:w-1/3 h-48 md:h-64' }} relative overflow-hidden">
                                    @php
                                        // Verificar se é uma URL completa ou caminho para storage
                                        $thumbnailUrl = null;
                                        
                                        if ($hotel->thumbnail) {
                                            // Verifica se já é uma URL (começa com http:// ou https://)
                                            if (Str::startsWith($hotel->thumbnail, ['http://', 'https://'])) {
                                                $thumbnailUrl = $hotel->thumbnail;
                                            } else {
                                                // Caminho para storage
                                                $thumbnailUrl = asset('storage/' . $hotel->thumbnail);
                                            }
                                        } else {
                                            // Fallback para o helper
                                            $thumbnailUrl = \App\Helpers\ImageHelper::getValidImage(null, 'hotel');
                                        }
                                    @endphp
                                    
                                    <img 
                                        src="{{ $thumbnailUrl }}" 
                                        alt="{{ $hotel->name }}" 
                                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                        loading="lazy"
                                        onerror="this.onerror=null; this.src='{{ \App\Helpers\ImageHelper::getValidImage(null, 'hotel') }}'"
                                    >
                                    <!-- Badge de classificação -->
                                    <div class="absolute top-0 right-0 bg-white m-3 px-2 py-1 rounded-md flex items-center shadow-md">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $hotel->stars)
                                                <i class="fas fa-star text-yellow-400"></i>
                                            @else
                                                <i class="far fa-star text-yellow-400"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    
                                    <!-- Selo de destaque (se aplicável) -->
                                    @if($hotel->featured)
                                    <div class="absolute top-0 left-0 bg-gradient-to-r from-yellow-500 to-yellow-600 text-white m-3 px-3 py-1 rounded-md flex items-center shadow-md text-xs font-bold">
                                        <i class="fas fa-certificate mr-1"></i> Destaque
                                    </div>
                                    @endif
                                </div>
                                
                                <!-- Informações do hotel -->
                                <div class="{{ $viewMode == 'grid' ? 'w-full' : 'md:w-2/3' }} p-5 flex flex-col h-full">
                                    <!-- Nome, localização e avaliação -->
                                    <div class="mb-3">
                                        <div class="flex justify-between items-start mb-1">
                                            <h3 class="text-xl font-bold text-gray-800 hover:text-primary transition-colors duration-300">{{ $hotel->name }}</h3>
                                            <div class="flex items-center bg-{{ $hotel->rating >= 8 ? 'green' : ($hotel->rating >= 7 ? 'blue' : ($hotel->rating >= 6 ? 'yellow' : 'red')) }}-100 text-{{ $hotel->rating >= 8 ? 'green' : ($hotel->rating >= 7 ? 'blue' : ($hotel->rating >= 6 ? 'yellow' : 'red')) }}-700 font-bold px-2 py-1 rounded-lg">
                                                <span class="text-base">{{ number_format($hotel->rating, 1) }}</span>
                                                <span class="text-xs ml-1">/10</span>
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-center text-sm text-gray-600 mb-2">
                                            <i class="fas fa-map-marker-alt mr-2 text-primary"></i>
                                            <span>{{ $hotel->location->name }}, {{ $hotel->location->province }}</span>
                                        </div>
                                        
                                        <!-- Avaliação simplificada -->
                                        <div class="flex items-center gap-3">
                                            <span class="text-sm font-medium">
                                                @if($hotel->rating >= 9)
                                                    Excelente
                                                @elseif($hotel->rating >= 8)
                                                    Muito Bom
                                                @elseif($hotel->rating >= 7)
                                                    Bom
                                                @elseif($hotel->rating >= 6)
                                                    Regular
                                                @else
                                                    Precisa Melhorar
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                    
                                    @if($viewMode == 'list')
                                    <!-- Descrição rápida -->
                                    <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $hotel->description }}</p>
                                    @endif
                                    
                                    <!-- Comodidades -->
                                    <div class="mb-4 flex flex-wrap gap-2">
                                        @php 
                                            // Converter a string de amenidades em um array se necessário
                                            $amenitiesArray = is_string($hotel->amenities) ? 
                                                (json_decode($hotel->amenities) ?: [$hotel->amenities]) : 
                                                (is_array($hotel->amenities) ? $hotel->amenities : []);
                                            $displayedAmenities = 0; 
                                        @endphp
                                        
                                        @if(count($amenitiesArray) > 0)
                                            @foreach($amenitiesArray as $amenity)
                                                @if($displayedAmenities < ($viewMode == 'grid' ? 4 : 6))
                                                    <span class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded-full flex items-center">
                                                        @if($amenity == 'wifi')
                                                            <i class="fas fa-wifi text-blue-500 mr-1"></i> Wi-Fi
                                                        @elseif($amenity == 'pool')
                                                            <i class="fas fa-swimming-pool text-blue-500 mr-1"></i> Piscina
                                                        @elseif($amenity == 'breakfast')
                                                            <i class="fas fa-coffee text-amber-700 mr-1"></i> Café da manhã
                                                        @elseif($amenity == 'parking')
                                                            <i class="fas fa-parking text-blue-700 mr-1"></i> Estacionamento
                                                        @elseif($amenity == 'air_conditioning')
                                                            <i class="fas fa-snowflake text-blue-400 mr-1"></i> Ar-condicionado
                                                        @elseif($amenity == 'gym')
                                                            <i class="fas fa-dumbbell text-gray-700 mr-1"></i> Academia
                                                        @elseif($amenity == 'spa')
                                                            <i class="fas fa-spa text-pink-500 mr-1"></i> Spa
                                                        @elseif($amenity == 'restaurant')
                                                            <i class="fas fa-utensils text-red-600 mr-1"></i> Restaurante
                                                        @else
                                                            {{ ucfirst(is_string($amenity) ? str_replace('_', ' ', $amenity) : '') }}
                                                        @endif
                                                    </span>
                                                    @php $displayedAmenities++; @endphp
                                                @endif
                                            @endforeach
                                            
                                            @if(count($amenitiesArray) > ($viewMode == 'grid' ? 4 : 6))
                                                <span class="text-xs bg-gray-100 text-primary px-2 py-1 rounded-full">
                                                    +{{ count($amenitiesArray) - ($viewMode == 'grid' ? 4 : 6) }} mais
                                                </span>
                                            @endif
                                        @else
                                            <span class="text-xs text-gray-500">Sem comodidades listadas</span>
                                        @endif
                                    </div>
                                    
                                    <!-- Preço e botão de ação -->
                                    <div class="mt-auto pt-3 border-t border-gray-100 flex {{ $viewMode == 'grid' ? 'flex-col space-y-3' : 'flex-row items-center justify-between' }}">
                                        <div>
                                            @php
                                                // Obter o menor preço disponível para o hotel
                                                $lowestPrice = null;
                                                foreach($hotel->roomTypes as $roomType) {
                                                    foreach($roomType->prices as $price) {
                                                        if ($lowestPrice === null || $price->price < $lowestPrice) {
                                                            $lowestPrice = $price->price;
                                                        }
                                                    }
                                                }
                                                $totalPrice = $lowestPrice * $nights;
                                                
                                                // Calcular desconto se houver
                                                $discount = null;
                                                $originalPrice = null;
                                                if ($hotel->discount_percentage > 0 && $lowestPrice) {
                                                    $originalPrice = $lowestPrice / (1 - ($hotel->discount_percentage / 100));
                                                    $discount = $hotel->discount_percentage;
                                                }
                                            @endphp
                                            
                                            @if($lowestPrice)
                                                <div class="flex items-center gap-2">
                                                    @if($discount)
                                                        <span class="text-sm line-through text-gray-500">AKZ {{ number_format($originalPrice, 0, ',', '.') }}</span>
                                                        <span class="text-xs bg-red-100 text-red-700 px-1.5 py-0.5 rounded font-medium">-{{ $discount }}%</span>
                                                    @endif
                                                </div>
                                                <div class="text-xl font-bold text-primary">AKZ {{ number_format($lowestPrice, 0, ',', '.') }}</div>
                                                <div class="text-xs text-gray-500">AKZ {{ number_format($totalPrice, 0, ',', '.') }} total para {{ $nights }} {{ $nights == 1 ? 'noite' : 'noites' }}</div>
                                            @else
                                                <div class="text-red-600 font-medium">Sem disponibilidade para as datas selecionadas</div>
                                            @endif
                                        </div>
                                        
                                        <a 
                                            href="{{ route('hotel.details', ['id' => $hotel->id, 'check_in' => $checkIn, 'check_out' => $checkOut, 'guests' => $guests, 'rooms' => $rooms]) }}" 
                                            class="inline-flex items-center justify-center bg-primary hover:bg-primary-dark text-white font-medium py-2 px-4 rounded-lg transition-all duration-300 gap-1 hover:gap-2 {{ $viewMode == 'grid' ? 'w-full' : '' }}"
                                        >
                                            <span>Ver detalhes</span>
                                            <i class="fas fa-arrow-right transition-transform group-hover:translate-x-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            
                            @if($hotel->discount_percentage > 0)
                            <!-- Badge de oferta especial -->
                            <div class="absolute top-2 left-2 z-10 bg-gradient-to-r from-red-500 to-red-600 text-white px-3 py-1 rounded-lg shadow-lg transform -rotate-3 text-sm font-bold">
                                Oferta especial
                            </div>
                            @endif
                        </div>
                    @empty
                        <div class="bg-white rounded-lg shadow-md p-8 text-center">
                            <i class="fas fa-search text-5xl text-gray-400 mb-4"></i>
                            <h3 class="text-xl font-bold text-gray-800 mb-2">Nenhum resultado encontrado</h3>
                            <p class="text-gray-600 mb-4">Tente ajustar seus filtros ou escolher datas diferentes.</p>
                            <button 
                                wire:click="clearFilters" 
                                class="bg-primary hover:bg-primary-dark text-white font-bold py-2 px-4 rounded transition duration-300"
                            >
                                Limpar filtros
                            </button>
                        </div>
                    @endforelse
                </div>
                
                <!-- Paginação com indicador de carregamento -->
                <div class="mt-6 relative">
                    <!-- Overlay de carregamento para paginação -->
                    <div wire:loading wire:target="gotoPage, nextPage, previousPage, setPage" 
                         class="absolute inset-0 bg-white/70 backdrop-blur-[1px] rounded-lg flex items-center justify-center z-10 transition-opacity duration-300">
                        <div class="flex items-center space-x-2 bg-white shadow-md px-4 py-2 rounded-full">
                            <div class="w-4 h-4 border-2 border-t-primary border-r-transparent border-b-transparent border-l-transparent rounded-full animate-spin"></div>
                            <span class="text-sm font-medium text-gray-700">Carregando página...</span>
                        </div>
                    </div>
                    
                    <div class="pagination-container" wire:loading.class="opacity-50">
                        {{ $searchResults->links() }}
                    </div>
                    
                    <!-- Estilos customizados para paginação -->
                    <style>
                        .pagination-container nav div:first-child {
                            display: none; /* Remove o texto de resultados para interface mais limpa */
                        }
                        
                        .pagination-container .shadow-sm {
                            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
                        }
                        
                        .pagination-container .relative {
                            position: relative;
                        }
                        
                        .pagination-container .inline-flex {
                            transition: all 0.3s ease;
                        }
                        
                        .pagination-container button:hover:not([disabled]) {
                            transform: translateY(-2px);
                        }
                    </style>
                </div>
            </div>
        </div>
    </div>
</div>

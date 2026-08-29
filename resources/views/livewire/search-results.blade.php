@php
    use Illuminate\Support\Str;
@endphp
@section('title', 'Pesquisar hotéis e acomodações em Angola')
@section('meta_description', 'Compare preços de hotéis, resorts e hospedarias em Angola. Filtre por destino, datas, preço e comodidades e reserve a melhor opção.')

<div class="bg-gray-100 min-h-screen relative" x-data="{ filtersOpen: false }" @keydown.escape.window="filtersOpen = false">
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
                    <h3 class="text-gray-800 text-lg font-medium">{{ __('Buscando hospedagens') }}</h3>
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
        @media (max-width: 767px) {
            .mobile-sort-scroll {
                flex-wrap: nowrap !important;
                overflow-x: auto;
                padding-bottom: .35rem;
                scroll-snap-type: x proximity;
                scrollbar-width: none;
            }
            .mobile-sort-scroll::-webkit-scrollbar {
                display: none;
            }
            .mobile-sort-scroll > button {
                flex: 0 0 auto;
                min-height: 40px;
                scroll-snap-align: start;
            }
            .mobile-filter-open {
                display: flex !important;
                position: fixed;
                inset: 0;
                z-index: 60;
                width: 100%;
                max-width: none;
                padding: 0;
                margin: 0;
                align-items: flex-end;
                background: rgba(15, 23, 42, .55);
            }
            .mobile-filter-open > div {
                width: 100%;
                max-height: 88vh;
                overflow-y: auto;
                margin: 0;
                border-radius: 1.25rem 1.25rem 0 0;
                padding-bottom: calc(1.25rem + env(safe-area-inset-bottom));
            }
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
            <p class="text-gray-700 text-sm font-medium">{{ __('Atualizando resultados...') }}</p>
        </div>
    </div>
    <!-- Barra de pesquisa compacta para refinar a busca -->
    <div class="bg-white shadow-md py-4">
        <div class="container mx-auto px-4">
            <form wire:submit.prevent="search" class="flex flex-wrap items-end gap-2">
                <div class="flex-1 min-w-[200px]">
                    <label for="location" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Destino') }}</label>
                    <input 
                        type="text" 
                        id="location" 
                        wire:model.defer="location" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary"
                        placeholder="Para onde você vai?"
                    >
                </div>
                
                <div class="flex-1 min-w-[150px]">
                    <label for="check_in" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Check-in') }}</label>
                    <input 
                        type="date" 
                        id="check_in" 
                        wire:model.defer="checkIn" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary"
                    >
                </div>
                
                <div class="flex-1 min-w-[150px]">
                    <label for="check_out" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Check-out') }}</label>
                    <input 
                        type="date" 
                        id="check_out" 
                        wire:model.defer="checkOut" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary"
                    >
                </div>
                
                <div class="flex-1 min-w-[100px]">
                    <label for="guests" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Hóspedes') }}</label>
                    <input 
                        type="number" 
                        id="guests" 
                        wire:model.defer="guests" 
                        min="1"
                        max="10"
                        required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary"
                    >
                </div>
                
                <div class="flex-1 min-w-[100px]">
                    <label for="rooms" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Quartos') }}</label>
                    <input 
                        type="number" 
                        id="rooms" 
                        wire:model.defer="rooms" 
                        min="1"
                        max="5"
                        required
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
                            <span wire:loading.remove wire:target="search">{{ __('Buscar') }}</span>
                            <span wire:loading wire:target="search" class="flex items-center space-x-2">
                                <span>{{ __('Buscando') }}</span>
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
                    {{ __('Hotéis em :location, :province', ['location' => $searchedLocation->name, 'province' => $searchedLocation->province]) }}
                @else
                    {{ __('Resultados da sua busca') }}
                @endif
            </h1>
            <p class="text-gray-600">
                {{ trans_choice(':count hotel encontrado|:count hotéis encontrados', $searchResults->total(), ['count' => $searchResults->total()]) }}
                @if($checkIn && $checkOut)
                    {{ __('para :checkin a :checkout', [
                        'checkin' => Carbon\Carbon::parse($checkIn)->format('d/m/Y'),
                        'checkout' => Carbon\Carbon::parse($checkOut)->format('d/m/Y'),
                    ]) }}
                    ({{ trans_choice(':count noite|:count noites', $nights, ['count' => $nights]) }})
                @endif
                <span class="inline-flex items-center gap-1 ml-2 text-primary font-medium">
                    <i class="fas fa-user-friends text-xs"></i>
                    {{ trans_choice(':count hóspede|:count hóspedes', $guests, ['count' => $guests]) }}
                    <span aria-hidden="true">·</span>
                    <i class="fas fa-door-open text-xs"></i>
                    {{ trans_choice(':count quarto|:count quartos', $rooms, ['count' => $rooms]) }}
                </span>
            </p>
        </div>

        <!-- Ações principais no telemóvel: resultados ficam visíveis antes dos filtros -->
        <div class="md:hidden sticky top-2 z-30 mb-4 grid grid-cols-2 gap-2 rounded-xl border border-gray-200 bg-white/95 p-2 shadow-lg backdrop-blur">
            <button type="button"
                    @click="filtersOpen = true"
                    class="flex min-h-[44px] items-center justify-center gap-2 rounded-lg bg-primary px-3 py-2.5 font-semibold text-white">
                <i class="fas fa-sliders-h"></i>
                <span>{{ __('Filtros') }}</span>
                @php
                    $activeFilterCount = count($propertyTypes) + count($selectedProvinces) + count($stars) + count($ratings) + count($amenities);
                @endphp
                @if($activeFilterCount > 0)
                    <span class="rounded-full bg-white px-2 py-0.5 text-xs text-primary">{{ $activeFilterCount }}</span>
                @endif
            </button>
            <button type="button"
                    data-location-button
                    onclick="window.KiandaLocation.request('{{ $_instance->getId() }}')"
                    class="flex min-h-[44px] items-center justify-center gap-1.5 whitespace-nowrap rounded-lg border border-primary bg-white px-2 py-2.5 text-sm font-semibold text-primary disabled:cursor-wait">
                <i class="fas fa-location-crosshairs"></i>
                <span data-location-label>{{ __('Perto de mim') }}</span>
            </button>
            <p data-location-status hidden role="status" aria-live="polite" class="col-span-2 px-2 pb-1 text-xs text-gray-600"></p>
        </div>
        
        <div class="flex flex-wrap md:flex-nowrap gap-6" wire:loading.class.delay="opacity-75">
            <!-- Filtros - Coluna esquerda -->
            <aside class="hidden w-full md:order-1 md:block md:w-1/4 md:min-w-[280px] md:max-w-[300px] md:mb-6"
                   :class="{ 'mobile-filter-open': filtersOpen }"
                   @click.self="filtersOpen = false"
                   aria-label="{{ __('Filtros de pesquisa') }}"
                   wire:loading.class="opacity-75">
                <div class="bg-white rounded-xl shadow-lg p-5 mb-4 border border-gray-100 hover:border-primary/20 transition-all duration-300">
                    <div class="md:hidden mb-4 flex items-center justify-between border-b border-gray-100 pb-3">
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">{{ __('Refinar resultados') }}</p>
                            <h2 class="text-xl font-bold text-gray-900">{{ __('Filtros') }}</h2>
                        </div>
                        <button type="button" @click="filtersOpen = false" aria-label="{{ __('Fechar filtros') }}"
                                class="flex h-11 w-11 items-center justify-center rounded-full bg-gray-100 text-gray-700">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-bold text-gray-800">{{ __('Filtros de Busca') }}</h2>
                        <button 
                            wire:click="clearFilters" 
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-50 cursor-wait"
                            class="text-sm text-primary hover:text-primary-dark transition-colors duration-300 flex items-center gap-1">
                            <i class="fas fa-redo-alt" wire:loading.class="animate-spin"></i>
                            <span wire:loading.remove>{{ __('Limpar') }}</span>
                            <span wire:loading>{{ __('Limpando...') }}</span>
                        </button>
                    </div>
                    
                    <!-- Filtro de destino -->
                    <div class="mb-6">
                        <h3 class="font-medium text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-map-marker-alt text-primary mr-2"></i> {{ __('Destino') }}
                        </h3>
                        <div class="relative">
                            <input 
                                type="text" 
                                wire:model.live.debounce.500ms="location"
                                placeholder="{{ __('Digite um destino') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary pl-10"
                            >
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                        </div>
                        
                        @if($popularDestinations && count($popularDestinations) > 0)
                        <div class="mt-2">
                            <p class="text-xs text-gray-500 mb-1">{{ __('Destinos populares:') }}</p>
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
                    
                    <!-- Filtro de Tipo de Propriedade -->
                    <div class="mb-6" x-data="{open: true}" wire:key="property-type-filter">
                        <div class="flex items-center justify-between cursor-pointer" @click="open = !open">
                            <h3 class="font-medium text-gray-700 flex items-center">
                                <i class="fas fa-building text-primary mr-2"></i>
                                {{ __('Tipo de Propriedade') }}
                                @if(count($propertyTypes) > 0)
                                    <span class="ml-2 text-xs bg-primary text-white rounded-full px-2 py-0.5">{{ count($propertyTypes) }}</span>
                                @endif
                                <span wire:loading wire:target="togglePropertyType" class="ml-2">
                                    <i class="fas fa-spinner fa-spin text-primary text-sm"></i>
                                </span>
                            </h3>
                            <i class="fas" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                        </div>
                        
                        <div x-show="open" class="mt-3 space-y-2" wire:loading.class="opacity-50" wire:target="togglePropertyType">
                            <!-- Hotel -->
                            <div 
                                wire:click="togglePropertyType('hotel')"
                                wire:key="filter-hotel"
                                class="flex items-center justify-between py-2 px-3 rounded-lg hover:bg-gray-50 transition-colors duration-200 border cursor-pointer {{ in_array('hotel', $propertyTypes) ? 'bg-blue-50 border-blue-300' : 'border-gray-100' }}"
                            >
                                <div class="flex items-center">
                                    <div class="w-5 h-5 flex items-center justify-center mr-3 border-2 rounded {{ in_array('hotel', $propertyTypes) ? 'bg-primary border-primary' : 'border-gray-300 bg-white' }}">
                                        @if(in_array('hotel', $propertyTypes))
                                            <i class="fas fa-check text-xs text-white"></i>
                                        @endif
                                    </div>
                                    <i class="fas fa-hotel text-blue-500 mr-2"></i>
                                    <span class="text-sm {{ in_array('hotel', $propertyTypes) ? 'text-primary font-medium' : 'text-gray-700' }}">{{ __('Hotel') }}</span>
                                </div>
                                <span class="text-xs text-gray-500 bg-gray-100 px-2 py-0.5 rounded-full">{{ $propertyTypeCounts['hotel'] ?? 0 }}</span>
                            </div>
                            
                            <!-- Resort -->
                            <div 
                                wire:click="togglePropertyType('resort')"
                                wire:key="filter-resort"
                                class="flex items-center justify-between py-2 px-3 rounded-lg hover:bg-gray-50 transition-colors duration-200 border cursor-pointer {{ in_array('resort', $propertyTypes) ? 'bg-orange-50 border-orange-300' : 'border-gray-100' }}"
                            >
                                <div class="flex items-center">
                                    <div class="w-5 h-5 flex items-center justify-center mr-3 border-2 rounded {{ in_array('resort', $propertyTypes) ? 'bg-primary border-primary' : 'border-gray-300 bg-white' }}">
                                        @if(in_array('resort', $propertyTypes))
                                            <i class="fas fa-check text-xs text-white"></i>
                                        @endif
                                    </div>
                                    <i class="fas fa-umbrella-beach text-orange-500 mr-2"></i>
                                    <span class="text-sm {{ in_array('resort', $propertyTypes) ? 'text-primary font-medium' : 'text-gray-700' }}">{{ __('Resort') }}</span>
                                </div>
                                <span class="text-xs text-gray-500 bg-gray-100 px-2 py-0.5 rounded-full">{{ $propertyTypeCounts['resort'] ?? 0 }}</span>
                            </div>
                            
                            <!-- Hospedaria -->
                            <div 
                                wire:click="togglePropertyType('hospedaria')"
                                wire:key="filter-hospedaria"
                                class="flex items-center justify-between py-2 px-3 rounded-lg hover:bg-gray-50 transition-colors duration-200 border cursor-pointer {{ in_array('hospedaria', $propertyTypes) ? 'bg-teal-50 border-teal-300' : 'border-gray-100' }}"
                            >
                                <div class="flex items-center">
                                    <div class="w-5 h-5 flex items-center justify-center mr-3 border-2 rounded {{ in_array('hospedaria', $propertyTypes) ? 'bg-primary border-primary' : 'border-gray-300 bg-white' }}">
                                        @if(in_array('hospedaria', $propertyTypes))
                                            <i class="fas fa-check text-xs text-white"></i>
                                        @endif
                                    </div>
                                    <i class="fas fa-home text-teal-500 mr-2"></i>
                                    <span class="text-sm {{ in_array('hospedaria', $propertyTypes) ? 'text-primary font-medium' : 'text-gray-700' }}">{{ __('Hospedaria') }}</span>
                                </div>
                                <span class="text-xs text-gray-500 bg-gray-100 px-2 py-0.5 rounded-full">{{ $propertyTypeCounts['hospedaria'] ?? 0 }}</span>
                            </div>
                        </div>
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
                                        <span>{{ \App\Models\Location::provinceName($selectedProvince) }}</span>
                                        <button wire:click="toggleProvinceFilter('{{ $selectedProvince }}')" class="ml-1 text-primary hover:text-primary-dark focus:outline-none">
                                            <i class="fas fa-times-circle"></i>
                                        </button>
                                    </div>
                                @endforeach
                                <button 
                                    wire:click="clearProvinceFilters" 
                                    class="text-xs text-gray-500 hover:text-primary underline focus:outline-none ml-1"
                                >
                                    {{ __('Limpar todos') }}
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
                                            <span class="text-sm truncate max-w-[150px]">{{ \App\Models\Location::provinceName($province->province) }}</span>
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
                                <div class="text-sm text-gray-500 py-2">{{ __('Nenhuma província disponível') }}</div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Filtro de preço -->
                    <div class="mb-6" x-data="{open: true}">
                        <div class="flex items-center justify-between cursor-pointer" @click="open = !open">
                            <h3 class="font-medium text-gray-700 flex items-center">
                                <i class="fas fa-tag text-primary mr-2"></i> {{ __('Preço por noite') }}
                            </h3>
                            <i class="fas" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                        </div>
                        
                        <div x-show="open" class="mt-3">
                            <!-- Inputs de preço -->
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div class="relative">
                                    <label for="min-price" class="block text-xs text-gray-500 mb-1">{{ __('Preço mínimo') }}</label>
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
                                    <label for="max-price" class="block text-xs text-gray-500 mb-1">{{ __('Preço máximo') }}</label>
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
                                <div>{{ __('Faixa atual:') }}</div>
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
                                    <span wire:loading.remove wire:target="applyPriceFilter">{{ __('Aplicar filtro de preço') }}</span>
                                    <span wire:loading wire:target="applyPriceFilter" class="flex items-center space-x-2">
                                        <span>{{ __('Aplicando...') }}</span>
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
                                <i class="fas fa-star text-primary mr-2"></i> {{ __('Classificação') }}
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
                                <i class="fas fa-thumbs-up text-primary mr-2"></i> {{ __('Avaliação de Hóspedes') }}
                            </h3>
                            <i class="fas" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                        </div>
                        
                        <div x-show="open" class="mt-2 space-y-2">
                            @foreach([['Excelente', 5], ['Muito Bom', 4], ['Bom', 3], ['Regular', 2], ['Razoável', 1]] as $rating)
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
                                                            @if($j <= $rating[1])
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
                                <i class="fas fa-concierge-bell text-primary mr-2"></i> {{ __('Comodidades') }}
                            </h3>
                            <i class="fas" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                        </div>
                        
                        <div x-show="open" class="mt-2 grid grid-cols-1 gap-2 max-h-64 overflow-y-auto">
                            @forelse($availableAmenities as $amenityName => $amenityCount)
                                @php
                                    $al = \Illuminate\Support\Str::lower($amenityName);
                                    $icon = 'fa-check';
                                    if (str_contains($al, 'wi-fi') || str_contains($al, 'wifi') || str_contains($al, 'internet')) $icon = 'fa-wifi';
                                    elseif (str_contains($al, 'piscina')) $icon = 'fa-swimming-pool';
                                    elseif (str_contains($al, 'estacion') || str_contains($al, 'parqu')) $icon = 'fa-parking';
                                    elseif (str_contains($al, 'restaurante')) $icon = 'fa-utensils';
                                    elseif (str_contains($al, 'bar')) $icon = 'fa-glass-martini-alt';
                                    elseif (str_contains($al, 'academia') || str_contains($al, 'ginás') || str_contains($al, 'gym')) $icon = 'fa-dumbbell';
                                    elseif (str_contains($al, 'spa')) $icon = 'fa-spa';
                                    elseif (str_contains($al, 'ar cond') || str_contains($al, 'climat')) $icon = 'fa-snowflake';
                                    elseif (str_contains($al, 'pequeno') || str_contains($al, 'almoço') || str_contains($al, 'café') || str_contains($al, 'refei')) $icon = 'fa-mug-hot';
                                    elseif (str_contains($al, 'pet')) $icon = 'fa-paw';
                                    elseif (str_contains($al, 'transfer') || str_contains($al, 'aeroporto')) $icon = 'fa-plane';
                                    elseif (str_contains($al, 'tv')) $icon = 'fa-tv';
                                    elseif (str_contains($al, 'jardim')) $icon = 'fa-tree';
                                    elseif (str_contains($al, 'praia') || str_contains($al, 'mar')) $icon = 'fa-umbrella-beach';
                                @endphp
                                <div class="flex items-center py-1 px-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                    <input type="checkbox" id="amenity-{{ $loop->index }}" wire:model.live="amenities" wire:loading.attr="disabled" value="{{ $amenityName }}" class="w-4 h-4 text-primary focus:ring-primary border-gray-300 rounded cursor-pointer">
                                    <label for="amenity-{{ $loop->index }}" class="ml-3 flex items-center cursor-pointer flex-1">
                                        <i class="fas {{ $icon }} text-gray-600 dark:text-gray-300 mr-2 w-4 text-center"></i>
                                        <span class="text-sm">{{ $amenityName }}</span>
                                    </label>
                                    <span class="ml-auto text-xs text-gray-500">({{ $amenityCount }})</span>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 px-2">{{ __('Sem comodidades disponíveis.') }}</p>
                            @endforelse
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
                            <span wire:loading.remove wire:target="clearFilters">{{ __('Limpar filtros') }}</span>
                            <span wire:loading wire:target="clearFilters" class="flex items-center space-x-2">
                                <span>{{ __('Limpando...') }}</span>
                                <div class="w-4 h-4 border-2 border-t-primary border-r-transparent border-b-transparent border-l-transparent rounded-full animate-spin"></div>
                            </span>
                        </div>
                        <!-- Indicador de progresso na borda inferior -->
                        <div wire:loading wire:target="clearFilters" class="absolute bottom-0 left-0 h-1 bg-primary animate-pulse w-full"></div>
                    </button>
                </div>
            </aside>
            
            <!-- Resultados da busca - Coluna direita -->
            <div class="order-1 w-full md:order-2 md:w-3/4 relative min-h-[500px]">
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
                            <p class="text-gray-700 font-medium">{{ __('Atualizando...') }}</p>
                        </div>
                    </div>
                    <!-- Grade de pulso para os resultados -->
                    <div class="absolute inset-0 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 p-6 opacity-10 pointer-events-none">
                        @for($i = 0; $i < 6; $i++)
                            <div class="bg-white h-48 rounded-lg shadow-sm animate-pulse"></div>
                        @endfor
                    </div>
                </div>

                <!-- Hotéis perto de si (via GPS) -->
                @if(!empty($nearbyHotels))
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 mb-6">
                        <h2 class="font-bold text-lg text-gray-800 dark:text-white mb-3 flex items-center">
                            <i class="fas fa-location-crosshairs text-primary mr-2"></i> {{ __('Hotéis perto de si') }}
                        </h2>
                        <div class="flex gap-4 overflow-x-auto pb-2 -mx-1 px-1">
                            @foreach($nearbyHotels as $nh)
                                <a href="{{ route('hotel.details', $nh['slug']) }}" class="flex-shrink-0 w-56 bg-gray-50 dark:bg-gray-700 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition group">
                                    <div class="relative h-28 overflow-hidden">
                                        <img src="{{ $nh['image'] }}" alt="{{ $nh['name'] }}" loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        <span class="absolute top-2 right-2 bg-primary text-white text-xs font-semibold px-2 py-0.5 rounded-full shadow">
                                            <i class="fas fa-map-marker-alt mr-1"></i>{{ $nh['distance'] }} km
                                        </span>
                                    </div>
                                    <div class="p-3">
                                        <h3 class="text-sm font-bold text-gray-800 dark:text-white truncate">{{ $nh['name'] }}</h3>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $nh['location'] }}{{ $nh['province'] ? ', ' . $nh['province'] : '' }}</p>
                                        <div class="flex items-center justify-between mt-1">
                                            <span class="text-xs text-amber-500">{!! str_repeat('<i class="fas fa-star"></i>', (int) $nh['stars']) !!}</span>
                                            @if($nh['rating'])
                                                <span class="text-xs font-bold text-primary"><i class="fas fa-star mr-0.5"></i>{{ number_format($nh['rating'], 1) }}/5</span>
                                            @endif
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Ordenação -->
                <div class="bg-white rounded-lg shadow-md p-4 mb-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
                        <div>
                            <span class="font-medium text-gray-700 block mb-2">{{ __('Ordenar por:') }}</span>
                            <div class="mobile-sort-scroll flex flex-wrap gap-2">
                                <button 
                                    wire:click="setSorting('recommended')" 
                                    wire:loading.attr="disabled"
                                    wire:target="setSorting"
                                    class="px-3 py-1.5 rounded-lg text-sm font-medium transition-colors duration-200 relative overflow-hidden {{ $sortBy == 'recommended' ? 'bg-primary text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-700' }}"
                                >
                                    <div class="flex items-center space-x-1 relative">
                                        <i class="fas fa-thumbs-up {{ $sortBy == 'recommended' ? 'text-white' : 'text-gray-600' }} text-xs"></i>
                                        <span>{{ __('Recomendados') }}</span>
                                        
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
                                        <span>{{ __('Menor preço') }}</span>
                                        
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
                                        <span>{{ __('Maior preço') }}</span>
                                        
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
                                        <span>{{ __('Melhor avaliação') }}</span>
                                        
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
                                        <span>{{ __('Mais estrelas') }}</span>
                                        
                                        <!-- Indicador de carregamento -->
                                        <div wire:loading wire:target="setSorting('stars_desc')" class="absolute right-0 ml-1">
                                            <div class="w-4 h-4 border-2 border-t-primary border-r-transparent border-b-transparent border-l-transparent rounded-full animate-spin"></div>
                                        </div>
                                    </div>
                                </button>
                            </div>
                        </div>
                        
                        <div class="md:flex items-center space-x-2">
                            <span class="text-sm text-gray-700 font-medium">{{ __('Mostrar:') }}</span>
                            <select 
                                wire:model.live="perPage"
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
                <div class="{{ $viewMode == 'grid' ? 'grid grid-cols-1 md:grid-cols-2 gap-6' : 'space-y-6' }}" wire:key="results-{{ implode('-', $propertyTypes) }}">
                    @forelse($searchResults as $hotel)
                        <div wire:key="hotel-{{ $hotel->id }}" class="h-full bg-white rounded-xl shadow-md overflow-hidden transition-all duration-300 hover:shadow-xl border border-gray-100 hover:border-primary/20 relative group">
                            <div class="{{ $viewMode == 'grid' ? 'flex flex-col h-full' : 'flex flex-col md:flex-row' }}">
                                <!-- Imagem do hotel -->
                                <div class="{{ $viewMode == 'grid' ? 'w-full h-48' : 'md:w-1/3 h-48 md:h-64' }} relative overflow-hidden">
                                    @php
                                        // Verificar se é uma URL completa ou caminho para storage
                                        $thumbnailUrl = null;
                                        
                                        if ($hotel->thumbnail) {
                                            // Verifica se já é uma URL (começa com http:// ou https://)
                                            if (Str::startsWith($hotel->thumbnail, ['http://', 'https://'])) {
                                                $thumbnailUrl = \App\Helpers\ImageHelper::getValidImage($hotel->thumbnail, 'hotel');
                                            } else {
                                                // Caminho para storage
                                                $thumbnailUrl = \App\Helpers\ImageHelper::getValidImage($hotel->thumbnail, 'hotel');
                                            }
                                        } else {
                                            // Fallback para o helper
                                            $thumbnailUrl = \App\Helpers\ImageHelper::getValidImage(null, 'hotel');
                                        }
                                    @endphp
                                    
                                    <img 
                                        src="{{ $thumbnailUrl }}" 
                                        alt="{{ $hotel->name }}" 
                                        class="w-full h-full object-cover object-center transition-transform duration-500 group-hover:scale-105"
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
                                        <i class="fas fa-certificate mr-1"></i> {{ __('Destaque') }}
                                    </div>
                                    @endif
                                </div>
                                
                                <!-- Informações do hotel -->
                                <div class="{{ $viewMode == 'grid' ? 'w-full flex-1' : 'md:w-2/3' }} p-5 flex flex-col h-full">
                                    <!-- Nome, localização e avaliação -->
                                    <div class="mb-3">
                                        <div class="flex justify-between items-start mb-1">
                                            <h3 class="text-xl font-bold text-gray-800 hover:text-primary transition-colors duration-300">{{ $hotel->name }}</h3>
                                            @if($hotel->rating > 0)
                                                <div class="flex items-center bg-{{ $hotel->rating >= 4 ? 'green' : ($hotel->rating >= 3.5 ? 'blue' : ($hotel->rating >= 3 ? 'yellow' : 'red')) }}-100 text-{{ $hotel->rating >= 4 ? 'green' : ($hotel->rating >= 3.5 ? 'blue' : ($hotel->rating >= 3 ? 'yellow' : 'red')) }}-700 font-bold px-2 py-1 rounded-lg">
                                                    <i class="fas fa-star text-xs mr-1"></i>
                                                    <span class="text-base">{{ number_format($hotel->rating, 1) }}</span>
                                                    <span class="text-xs ml-1">/5</span>
                                                </div>
                                            @else
                                                <span class="bg-gray-100 text-gray-600 font-semibold text-xs px-2 py-1 rounded-lg whitespace-nowrap">{{ __('Novo') }}</span>
                                            @endif
                                        </div>
                                        
                                        <div class="flex items-center text-sm text-gray-600 mb-2">
                                            <i class="fas fa-map-marker-alt mr-2 text-primary"></i>
                                            <span>{{ $hotel->location->name }}, {{ \App\Models\Location::provinceName($hotel->location->province) }}</span>
                                        </div>
                                        
                                        <!-- Avaliação simplificada -->
                                        @if($hotel->rating > 0)
                                        <div class="flex items-center gap-3">
                                            <span class="text-sm font-medium">
                                                @if($hotel->rating >= 4.5)
                                                    Excelente
                                                @elseif($hotel->rating >= 4)
                                                    Muito Bom
                                                @elseif($hotel->rating >= 3.5)
                                                    Bom
                                                @elseif($hotel->rating >= 3)
                                                    Regular
                                                @else
                                                    Razoável
                                                @endif
                                            </span>
                                        </div>
                                        @endif
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
                                        <div class="{{ $viewMode == 'grid' ? 'min-h-[72px]' : '' }}">
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
                                                // Fallback: sem registos de preço, usar o menor base_price
                                                // dos quartos disponíveis (senão mostra "Sem disponibilidade").
                                                if ($lowestPrice === null) {
                                                    foreach($hotel->roomTypes as $roomType) {
                                                        if ($roomType->is_available && (float) $roomType->base_price > 0
                                                            && ($lowestPrice === null || $roomType->base_price < $lowestPrice)) {
                                                            $lowestPrice = (float) $roomType->base_price;
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
                                            href="{{ route('hotel.details', ['slug' => $hotel->slug, 'check_in' => $checkIn, 'check_out' => $checkOut, 'guests' => $guests, 'rooms' => $rooms]) }}" 
                                            class="inline-flex items-center justify-center bg-primary hover:bg-primary-dark text-white font-medium py-2 px-4 rounded-lg transition-all duration-300 gap-1 hover:gap-2 {{ $viewMode == 'grid' ? 'w-full' : '' }}"
                                        >
                                            <span>{{ __('Ver detalhes') }}</span>
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
                                {{ __('Limpar filtros') }}
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
                            <span class="text-sm font-medium text-gray-700">{{ __('Carregando página...') }}</span>
                        </div>
                    </div>
                    
                    <div class="pagination-container" wire:loading.class="opacity-50">
                        {{ $searchResults->onEachSide(0)->links() }}
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

@push('scripts')
<script>
    document.addEventListener('livewire:initialized', () => {
        window.KiandaLocation.useIfAlreadyGranted('{{ $_instance->getId() }}');
    });
</script>
@endpush

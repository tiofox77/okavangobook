@section('title', 'Destinos em Angola — 18 províncias para explorar')
@section('meta_description', 'Descubra os melhores destinos turísticos de Angola: Luanda, Benguela, Lubango, Namibe e mais. Encontre hotéis e acomodações em cada província.')
<div>
    <div class="bg-gradient-to-b from-blue-50 to-white min-h-screen">
        <!-- Hero Section -->
        <div class="relative bg-primary overflow-hidden">
            <div class="absolute inset-0 opacity-20">
                @if($locations->isNotEmpty() && $locations->first()->image)
                    <img src="{{ $imageHelper::getValidImage($locations->first()->image, 'location') }}" 
                        alt="Angola Map Background" class="w-full h-full object-cover"
                        onerror="this.src='{{ $imageHelper::getValidImage('angola-map', 'banners') }}'">
                @else
                    <img src="{{ $imageHelper::getValidImage('angola-map', 'banners') }}" 
                        alt="Angola Map Background" class="w-full h-full object-cover">
                @endif
            </div>
            <div class="container mx-auto px-4 py-16 relative z-10">
                <div class="text-center text-white">
                    <h1 class="text-4xl md:text-5xl font-bold mb-4 animate-fade-in-down">{{ __('Províncias de Angola') }}</h1>
                    <p class="text-lg md:text-xl max-w-3xl mx-auto opacity-90 mb-8 animate-fade-in-up">
                        Explore as 18 províncias de Angola através das suas paisagens reais, histórias, culturas e lugares inesquecíveis.
                    </p>
                    <div class="flex justify-center space-x-2 animate-fade-in">
                        <span class="inline-block w-3 h-3 bg-white rounded-full"></span>
                        <span class="inline-block w-3 h-3 bg-white opacity-70 rounded-full"></span>
                        <span class="inline-block w-3 h-3 bg-white opacity-40 rounded-full"></span>
                    </div>
                </div>
            </div>
            <div class="absolute bottom-0 left-0 right-0 h-16 bg-gradient-to-t from-blue-50 to-transparent"></div>
        </div>

        <!-- Main Content -->
        <div class="container mx-auto px-4 py-12" data-island-zone>
            @once
                @push('islands')
                    @vite('resources/js/islands.jsx')
                @endpush
            @endonce

            {{-- Ilha React: explorador com pesquisa instantânea e grelha animada.
                 O bloco Blade abaixo (filtros + grelha) é o fallback sem JS. --}}
            @php
                $explorerData = $locations->map(fn ($l) => [
                    'slug' => $l->province,
                    'name' => \App\Models\Location::provinceName($l->province),
                    'image' => \App\Helpers\ImageHelper::getValidImage($l->image, 'location'),
                    'description' => \Illuminate\Support\Str::limit(strip_tags((string) $l->description), 130),
                    'hotels' => (int) $l->hotels_count,
                    'locations' => (int) ($l->locations_count ?? 1),
                    'minPrice' => $l->min_price ? (float) $l->min_price : null,
                    'url' => route('location.details', ['province' => $l->province]),
                ])->values();
            @endphp
            <div wire:ignore
                 data-island="destinations-explorer"
                 data-destinations='@json($explorerData)'
                 class="hidden"></div>

            <!-- Filter Section (fallback sem JS) -->
            <div class="native-explorer mb-12 flex flex-col md:flex-row justify-between items-center bg-white p-6 rounded-xl shadow-sm animate-fade-in">
                <div class="mb-4 md:mb-0">
                    <h2 class="text-2xl font-bold text-gray-800">{{ __('Descubra Angola') }}</h2>
                    <p class="text-gray-600">{{ __('Encontre o seu próximo destino entre as 18 províncias') }}</p>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-gray-600">{{ __('Ordenar por:') }}</span>
                    <button wire:click="setSorting('popular')" aria-pressed="{{ $sortBy === 'popular' ? 'true' : 'false' }}" class="px-4 py-2 rounded-lg transition {{ $sortBy === 'popular' ? 'bg-primary text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">{{ __('Popularidade') }}</button>
                    <button wire:click="setSorting('alphabetical')" aria-pressed="{{ $sortBy === 'alphabetical' ? 'true' : 'false' }}" class="px-4 py-2 rounded-lg transition {{ $sortBy === 'alphabetical' ? 'bg-primary text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">{{ __('Alfabética') }}</button>
                </div>
            </div>

            <!-- Provinces Grid (fallback sem JS) -->
            <div class="native-explorer grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($locations as $location)
                    <article wire:key="destination-{{ $location->province }}" class="group h-full bg-white rounded-2xl shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 animate-fade-in-up" style="animation-delay: {{ min($loop->iteration * 60, 600) }}ms">
                        <a href="{{ route('location.details', ['province' => $location->province]) }}" class="flex h-full flex-col">
                            <div class="h-56 overflow-hidden relative">
                                @php
                                    $imageSrc = \App\Helpers\ImageHelper::getValidImage($location->image, 'location');
                                @endphp
                                <img 
                                    src="{{ $imageSrc }}"
                                    alt="Paisagem da província de {{ \App\Models\Location::provinceName($location->province) }}"
                                    class="w-full h-full object-cover object-center transition-transform duration-500 group-hover:scale-105"
                                    onerror="this.onerror=null; this.src='{{ \App\Helpers\ImageHelper::generateDefaultSvg('location', $location->province, 400, 256) }}';"
                                >
                                <div class="absolute inset-0 bg-gradient-to-t from-black to-transparent opacity-60 group-hover:opacity-40 transition-opacity"></div>
                                <div class="absolute bottom-4 left-4 right-4">
                                    <h3 class="text-2xl font-bold text-white mb-1">{{ \App\Models\Location::provinceName($location->province) }}</h3>
                                    <p class="text-white text-sm opacity-90">{{ __('Destino principal: :name', ['name' => $location->name]) }}</p>
                                </div>
                            </div>
                            <div class="p-6 flex flex-1 flex-col">
                                <div class="flex justify-between items-center mb-4">
                                    <div class="flex items-center">
                                        <i class="fas fa-hotel text-primary mr-2"></i>
                                        <span class="text-gray-700">{{ trans_choice(':count hotel|:count hotéis', $location->hotels_count, ['count' => $location->hotels_count]) }}</span>
                                    </div>
                                    <div class="flex items-center text-sm font-medium text-primary">
                                        <span>{{ __('Explorar') }}</span>
                                        <i class="fas fa-arrow-right ml-2 group-hover:ml-3 transition-all"></i>
                                    </div>
                                </div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-primary mb-2">{{ __('História e identidade') }}</p>
                                <p class="text-gray-600 leading-relaxed line-clamp-4">{{ Str::limit($location->description, 230) }}</p>
                                <span class="mt-auto pt-5 text-sm font-semibold text-primary">{{ __('Conhecer a província') }} <i class="fas fa-arrow-right ml-1"></i></span>
                            </div>
                        </a>
                    </article>
                @endforeach
            </div>

            <!-- Call to Action -->
            <div class="mt-16 text-center animate-fade-in">
                <h3 class="text-2xl font-bold text-gray-800 mb-4">{{ __('Não sabe por onde começar?') }}</h3>
                <p class="text-gray-600 max-w-2xl mx-auto mb-6">Nossa equipe selecionou roteiros especiais para você conhecer o melhor de Angola.</p>
                <a href="{{ route('search.results') }}" class="inline-block px-8 py-4 bg-primary text-white rounded-lg hover:bg-primary-dark transition transform hover:scale-105">
                    {{ __('Ver Hotéis Recomendados') }}
                </a>
            </div>
        </div>
    </div>

    <style>
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .animate-fade-in-down {
            animation: fadeInDown 0.8s ease forwards;
        }
        
        .animate-fade-in-up {
            animation: fadeInUp 0.8s ease forwards;
        }
        
        .animate-fade-in {
            animation: fadeIn 1s ease forwards;
        }
    </style>
</div>

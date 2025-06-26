<div>
    <div class="bg-gray-50">
        <!-- Hero Section com paralaxe -->
        <div class="relative h-[70vh] overflow-hidden" x-data="{ scrolled: false }" x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 50 })" x-effect="scrolled">
            @if($locations->isNotEmpty())
                <div class="absolute inset-0 transform transition-transform duration-1000" :style="`transform: translateY(${window.scrollY * 0.3}px)`">
                    <img 
                        @if(filter_var($locations->first()->image, FILTER_VALIDATE_URL))
                            src="{{ $locations->first()->image }}"
                        @else
                            src="{{ asset('storage/' . $locations->first()->image) }}"
                        @endif
                        alt="{{ $provinceName }}" 
                        class="w-full h-full object-cover"
                        onerror="this.src='{{ asset('images/locations/default.jpg') }}'"
                    >
                    <div class="absolute inset-0 bg-gradient-to-t from-black via-black/40 to-transparent"></div>
                </div>
                <div class="absolute inset-0 flex items-center justify-center z-10">
                    <div class="text-center text-white px-4 animate-fade-in-down">
                        <span class="inline-block px-3 py-1 bg-primary/90 text-white text-xs font-semibold rounded-full mb-4 backdrop-blur-sm">
                            PROVÍNCIA DE ANGOLA
                        </span>
                        <h1 class="text-5xl sm:text-6xl font-bold mb-6 drop-shadow-lg">{{ $provinceName }}</h1>
                        <p class="text-xl max-w-3xl mx-auto mb-8 opacity-90">Explore as maravilhas de {{ $provinceName }} e descubra uma experiência única em Angola</p>
                        <div class="flex justify-center space-x-4">
                            <a href="#hoteis" class="px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary-dark transition transform hover:scale-105">
                                Ver Hotéis
                            </a>
                            <a href="#locais" class="px-6 py-3 bg-white/20 backdrop-blur-sm text-white rounded-lg hover:bg-white/30 transition transform hover:scale-105">
                                Principais Locais
                            </a>
                        </div>
                    </div>
                </div>
            @endif
            <!-- Overlay com gradiente na parte inferior -->
            <div class="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-gray-50 to-transparent"></div>
            
            <!-- Indicador de scroll -->
            <div class="absolute bottom-10 left-0 right-0 flex justify-center animate-bounce">
                <div class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center">
                    <i class="fas fa-chevron-down text-white"></i>
                </div>
            </div>
        </div>

    <div class="container mx-auto px-4 py-12 relative z-10">
        <!-- Breadcrumb -->
        <nav class="flex items-center text-sm mb-8 animate-fade-in">
            <a href="{{ route('home') }}" class="text-gray-500 hover:text-primary">Home</a>
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
            <a href="{{ route('destinations') }}" class="text-gray-500 hover:text-primary">Destinos</a>
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
            <span class="text-primary font-medium">{{ $provinceName }}</span>
        </nav>
        
        <!-- Informações da Província -->
        <div class="bg-white rounded-xl shadow-lg p-8 mb-16 transform transition hover:shadow-xl animate-fade-in-up">
            @if($locations->isNotEmpty())
                <div class="flex flex-col lg:flex-row gap-8">
                    <div class="lg:w-2/3">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-1 bg-primary rounded-full mr-4"></div>
                            <h2 class="text-3xl font-bold text-gray-800">Sobre {{ $provinceName }}</h2>
                        </div>
                        
                        <div class="prose prose-lg max-w-none mb-6">
                            <p class="text-gray-700">{{ $locations->first()->description }}</p>
                        </div>
                        
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-6 mt-8">
                            <div class="bg-blue-50 rounded-lg p-4 text-center transform transition hover:scale-105">
                                <i class="fas fa-map-marker-alt text-primary text-2xl mb-2"></i>
                                <h4 class="text-sm font-semibold text-gray-700 mb-1">Capital</h4>
                                <p class="text-primary font-medium">{{ $locations->first()->name }}</p>
                            </div>
                            
                            <div class="bg-blue-50 rounded-lg p-4 text-center transform transition hover:scale-105">
                                <i class="fas fa-hotel text-primary text-2xl mb-2"></i>
                                <h4 class="text-sm font-semibold text-gray-700 mb-1">Hotéis</h4>
                                <p class="text-primary font-medium">{{ $locations->sum('hotels_count') }}</p>
                            </div>
                            
                            <div class="bg-blue-50 rounded-lg p-4 text-center transform transition hover:scale-105">
                                <i class="fas fa-globe-africa text-primary text-2xl mb-2"></i>
                                <h4 class="text-sm font-semibold text-gray-700 mb-1">Região</h4>
                                <p class="text-primary font-medium">Angola</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="lg:w-1/3 rounded-xl overflow-hidden h-80 group">
                        <!-- Mapa interativo (representação) -->
                        <div class="relative h-full w-full overflow-hidden">
                            <img 
                                @if(isset($locations->first()->map_image) && filter_var($locations->first()->map_image, FILTER_VALIDATE_URL))
                                    src="{{ $locations->first()->map_image }}"
                                @elseif(isset($locations->first()->map_image))
                                    src="{{ asset('storage/' . $locations->first()->map_image) }}"
                                @elseif(filter_var($locations->first()->image, FILTER_VALIDATE_URL))
                                    src="{{ $locations->first()->image }}"
                                @elseif($locations->first()->image)
                                    src="{{ asset('storage/' . $locations->first()->image) }}"
                                @else
                                    src="{{ asset('images/maps/' . $provinceName . '-map.jpg') }}"
                                @endif
                                alt="Mapa de {{ $provinceName }}" 
                                class="w-full h-full object-cover group-hover:scale-110 transition duration-500"
                                onerror="this.src='{{ asset('images/maps/default-map.jpg') }}'"
                            >
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end">
                                <div class="p-4 text-white">
                                    <p class="text-sm opacity-80 mb-1">Coordenadas</p>
                                    <p class="font-medium">{{ $locations->first()->latitude }}, {{ $locations->first()->longitude }}</p>
                                </div>
                            </div>
                            <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm rounded-lg px-3 py-1 text-sm font-medium text-primary">
                                Mapa
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Principais Cidades/Locações -->
        <div id="locais" class="scroll-mt-24 mb-20">
            <div class="flex items-center mb-8">
                <div class="w-12 h-1 bg-primary rounded-full mr-4"></div>
                <h2 class="text-3xl font-bold text-gray-800">Principais Locais</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($locations as $location)
                    <div class="group bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 animate-fade-in-up" style="animation-delay: {{ $loop->iteration * 100 }}ms">
                        <div class="h-48 overflow-hidden relative">
                        <img 
                        @if(filter_var($locations->first()->image, FILTER_VALIDATE_URL))
                            src="{{ $locations->first()->image }}"
                        @else
                            src="{{ asset('storage/' . $locations->first()->image) }}"
                        @endif
                        alt="{{ $provinceName }}" 
                        class="w-full h-full object-cover"
                        onerror="this.src='{{ asset('images/locations/default.jpg') }}'"
                    >
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                            <div class="absolute bottom-0 left-0 right-0 p-4">
                                <h3 class="text-xl font-bold text-white mb-1">{{ $location->name }}</h3>
                                <div class="flex items-center text-white/80 text-sm">
                                    <i class="fas fa-map-marker-alt mr-1"></i>
                                    <span>{{ ucfirst($location->province) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-5">
                            <p class="text-gray-600 mb-4 line-clamp-2">{{ Str::limit($location->description, 100) }}</p>
                            <div class="flex justify-between items-center">
                                <div class="flex items-center">
                                    <i class="fas fa-hotel text-primary mr-2"></i>
                                    <span class="text-gray-700">{{ $location->hotels_count }} hotéis</span>
                                </div>
                                <a href="{{ route('search.results', ['selectedProvince' => $location->province, 'location' => $location->name]) }}" class="flex items-center text-sm font-medium text-primary group-hover:text-primary-dark">
                                    <span>Ver hotéis</span>
                                    <i class="fas fa-arrow-right ml-2 group-hover:ml-3 transition-all"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Hotéis em Destaque -->
        @if($hotels->isNotEmpty())
            <div id="hoteis" class="scroll-mt-24 mb-16">
                <div class="flex items-center mb-8">
                    <div class="w-12 h-1 bg-primary rounded-full mr-4"></div>
                    <h2 class="text-3xl font-bold text-gray-800">Hotéis em Destaque</h2>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($hotels as $hotel)
                        <div class="group bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 animate-fade-in-up" style="animation-delay: {{ $loop->iteration * 100 + 300 }}ms">
                            <a href="{{ route('hotel.details', ['id' => $hotel->id]) }}" class="block">
                                <div class="relative h-48 overflow-hidden">
                                    <img 
                                        src="{{ filter_var($hotel->featured_image, FILTER_VALIDATE_URL) ? $hotel->featured_image : $imageHelper::getValidImage($hotel->featured_image, 'hotel') }}" 
                                        alt="{{ $hotel->name }}" 
                                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                                        onerror="this.src='{{ $imageHelper::getValidImage('', 'hotel') }}'"
                                    >
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                                    <div class="absolute top-4 right-4 bg-white rounded-full px-2 py-1 flex items-center">
                                        <span class="text-primary font-bold mr-1">{{ number_format($hotel->rating, 1) }}</span>
                                        <i class="fas fa-star text-yellow-400"></i>
                                    </div>
                                </div>
                                <div class="p-5">
                                    <h3 class="text-xl font-bold text-primary mb-2 group-hover:text-primary-dark transition-colors">{{ $hotel->name }}</h3>
                                    <p class="text-sm text-gray-500 mb-3">
                                        <i class="fas fa-map-marker-alt mr-1"></i> {{ $hotel->location->name }}
                                    </p>
                                    <p class="text-gray-600 mb-4 line-clamp-2">{{ Str::limit($hotel->description, 80) }}</p>
                                    <div class="flex justify-between items-center">
                                        <span class="text-primary font-bold">{{ number_format($hotel->price_per_night, 0, ',', '.') }} AOA</span>
                                        <span class="bg-primary text-white px-3 py-1 rounded-full text-sm group-hover:bg-primary-dark transition-colors">Ver detalhes</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
                
                <div class="text-center mt-12 animate-fade-in" style="animation-delay: 800ms">
                    <a href="{{ route('search.results', ['selectedProvince' => $province]) }}" class="inline-block bg-primary text-white px-8 py-4 rounded-lg font-medium hover:bg-primary-dark transition transform hover:scale-105 shadow-lg hover:shadow-xl">
                        <i class="fas fa-hotel mr-2"></i> Ver todos os hotéis em {{ $provinceName }}
                    </a>
                </div>
            </div>
            
            <!-- CTA Final -->
            <div class="bg-blue-50 rounded-xl p-8 text-center my-16 animate-fade-in" style="animation-delay: 1000ms">
                <h3 class="text-2xl font-bold text-gray-800 mb-4">Planeje sua viagem para {{ $provinceName }}</h3>
                <p class="text-gray-600 max-w-2xl mx-auto mb-6">Descubra os melhores hotéis, restaurantes e atrações para tornar sua viagem inesquecível.</p>
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="{{ route('search.results', ['selectedProvince' => $province]) }}" class="px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary-dark transition">
                        Encontrar Hotéis
                    </a>
                    <a href="#" class="px-6 py-3 bg-white text-primary border border-primary rounded-lg hover:bg-primary/5 transition">
                        Guia de Viagem
                    </a>
                </div>
            </div>
        @endif
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
            
            .scroll-mt-24 {
                scroll-margin-top: 6rem;
            }
        </style>
    </div>
</div>

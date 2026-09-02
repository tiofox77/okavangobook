<div class="relative bg-gradient-to-b from-gray-50 to-white dark:from-gray-900 dark:to-gray-800 dark:text-white transition-all duration-500 ease-in-out">

    <!-- Hero com paisagens reais de Angola -->
    <section
        class="home-hero relative overflow-hidden"
        data-island-zone
        aria-roledescription="carrossel"
        aria-label="Paisagens de Angola"
        x-data="{
            active: 0,
            playing: true,
            timer: null,
            start() {
                this.timer = setInterval(() => {
                    if (this.playing) this.active = (this.active + 1) % 3;
                }, 6500);
            },
            goTo(index) { this.active = index; },
            previous() { this.active = (this.active + 2) % 3; },
            next() { this.active = (this.active + 1) % 3; },
            toggle() { this.playing = !this.playing; }
        }"
        x-init="start()"
    >
        @php
            $heroSlides = [
                ['src' => asset('assets/img/hero-angola/kalandula.jpg'), 'alt' => 'Quedas de Kalandula, na província de Malanje', 'caption' => 'Quedas de Kalandula · Malanje'],
                ['src' => asset('assets/img/hero-angola/miradouro-da-lua.jpg'), 'alt' => 'Formações naturais do Miradouro da Lua, em Luanda', 'caption' => 'Miradouro da Lua · Luanda'],
                ['src' => asset('assets/img/hero-angola/mussulo.jpg'), 'alt' => 'Praia e águas tranquilas da península do Mussulo', 'caption' => 'Mussulo · Luanda'],
            ];
        @endphp
        {{-- Ilha React: slideshow com Ken Burns, crossfade, progresso e swipe.
             As <figure> Alpine abaixo são o fallback sem JS. --}}
        <div wire:ignore
             data-island="hero-slideshow"
             data-slides='@json($heroSlides)'
             class="hidden absolute inset-0"></div>

        <div class="absolute inset-0 native-hero" aria-live="polite">
            <figure class="hero-slide" x-show="active === 0" x-transition.opacity.duration.900ms :aria-hidden="active !== 0">
                <img src="{{ asset('assets/img/hero-angola/kalandula.jpg') }}" alt="Quedas de Kalandula, na província de Malanje" class="w-full h-full object-cover" fetchpriority="high">
                <figcaption>Quedas de Kalandula · Malanje</figcaption>
            </figure>
            <figure class="hero-slide" x-cloak x-show="active === 1" x-transition.opacity.duration.900ms :aria-hidden="active !== 1">
                <img src="{{ asset('assets/img/hero-angola/miradouro-da-lua.jpg') }}" alt="Formações naturais do Miradouro da Lua, em Luanda" class="w-full h-full object-cover">
                <figcaption>Miradouro da Lua · Luanda</figcaption>
            </figure>
            <figure class="hero-slide" x-cloak x-show="active === 2" x-transition.opacity.duration.900ms :aria-hidden="active !== 2">
                <img src="{{ asset('assets/img/hero-angola/mussulo.jpg') }}" alt="Praia e águas tranquilas da península do Mussulo" class="w-full h-full object-cover">
                <figcaption>Mussulo · Luanda</figcaption>
            </figure>
        </div>

        <div class="home-hero__overlay absolute inset-0 z-10"></div>

        <!-- Conteúdo Hero -->
        <div class="container mx-auto px-4 min-h-full flex flex-col justify-center items-center relative z-30">
            <div class="animate-fade-in-down">
                <h1 class="text-4xl md:text-6xl font-extrabold text-white mb-3 text-center tracking-tight leading-tight">
                    <span class="bg-clip-text text-transparent bg-gradient-to-r from-white to-blue-100">{{ __('Explore Angola') }}</span>
                    <span class="block mt-2 text-3xl md:text-5xl">{{ __('Como Nunca Antes') }}</span>
                </h1>
                <p class="hero-subtitle text-lg md:text-xl mb-6 text-center max-w-3xl mx-auto font-light">
                    {{ __('Descubra experiências únicas em :year com nossa tecnologia de reservas inteligente', ['year' => date('Y')]) }}
                </p>
            </div>

            <!-- Card de busca com efeito de vidro (glassmorphism) -->
            <div class="hero-search-shell w-full max-w-3xl animate-fade-in-up rounded-2xl p-3 border shadow-2xl">
                @livewire('search-form')

                <!-- Estatísticas animadas abaixo do formulário (contador anima ao entrar no ecrã) -->
                <div class="flex flex-wrap justify-center mt-3 gap-5 md:gap-7 text-center text-white">
                    <div class="stat-item">
                        <span class="block text-xl font-bold" data-island="count-up" data-value="{{ $stats['hotels'] }}" data-suffix="+">{{ number_format($stats['hotels'], 0, ',', '.') }}+</span>
                        <span class="text-sm opacity-80">{{ __('Hotéis') }}</span>
                    </div>
                    <div class="stat-item">
                        <span class="block text-xl font-bold" data-island="count-up" data-value="{{ $stats['provinces'] }}">{{ $stats['provinces'] }}</span>
                        <span class="text-sm opacity-80">{{ __('Províncias') }}</span>
                    </div>
                    <div class="stat-item">
                        <span class="block text-xl font-bold" data-island="count-up" data-value="{{ $stats['users'] }}" data-suffix="+">{{ number_format($stats['users'], 0, ',', '.') }}+</span>
                        <span class="text-sm opacity-80">{{ __('Utilizadores') }}</span>
                    </div>
                </div>
            </div>

            <div class="hero-controls native-hero" aria-label="Controlos do slideshow">
                <button type="button" @click="previous()" aria-label="Imagem anterior"><i class="fas fa-chevron-left"></i></button>
                <template x-for="index in 3" :key="index">
                    <button type="button" class="hero-dot" :class="{ 'hero-dot--active': active === index - 1 }" @click="goTo(index - 1)" :aria-label="`Mostrar imagem ${index}`" :aria-current="active === index - 1 ? 'true' : 'false'"></button>
                </template>
                <button type="button" @click="next()" aria-label="Próxima imagem"><i class="fas fa-chevron-right"></i></button>
                <button type="button" @click="toggle()" :aria-label="playing ? 'Pausar slideshow' : 'Retomar slideshow'">
                    <i class="fas" :class="playing ? 'fa-pause' : 'fa-play'"></i>
                </button>
            </div>

            <p class="hero-attribution">
                Fotografias: <a href="https://commons.wikimedia.org/wiki/File:Kalandula_waterfalls_of_the_Lucala-River_in_Malange,_Angola.JPG" target="_blank" rel="noopener">Paulo César Santos (CC0)</a>,
                <a href="https://commons.wikimedia.org/wiki/File:Miradouro_da_Lua.jpg" target="_blank" rel="noopener">jlrsousa (CC BY-SA 2.0)</a> e
                <a href="https://commons.wikimedia.org/wiki/File:Mossulo_.jpg" target="_blank" rel="noopener">Ilenekrall (CC BY-SA 4.0)</a>.
            </p>
        </div>
    </section>

    <style>
        .home-hero {
            min-height: 820px;
            padding: 92px 0 34px;
            background: #0f2942;
        }
        .hero-slide {
            position: absolute;
            inset: 0;
            margin: 0;
        }
        .hero-slide img { transform: scale(1.015); }
        .hero-slide figcaption {
            position: absolute;
            right: 18px;
            bottom: 14px;
            color: rgba(255, 255, 255, .9);
            font-size: 12px;
            text-shadow: 0 1px 4px rgba(0, 0, 0, .8);
        }
        .home-hero__overlay {
            background: linear-gradient(180deg, rgba(4, 18, 32, .48), rgba(4, 18, 32, .34) 42%, rgba(4, 18, 32, .68));
        }
        .hero-subtitle { color: rgba(255, 255, 255, .92); text-shadow: 0 1px 4px rgba(0, 0, 0, .45); }
        .hero-search-shell {
            background: rgba(255, 255, 255, .12);
            border-color: rgba(255, 255, 255, .35);
            backdrop-filter: blur(14px);
        }
        .hero-controls {
            display: flex;
            align-items: center;
            gap: 9px;
            margin-top: 14px;
            color: #fff;
        }
        .hero-controls button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 999px;
            background: rgba(4, 18, 32, .52);
            border: 1px solid rgba(255, 255, 255, .4);
            transition: background-color 180ms ease, transform 180ms ease;
        }
        .hero-controls button:hover, .hero-controls button:focus-visible { background: rgba(31, 94, 160, .92); transform: translateY(-1px); }
        .hero-controls .hero-dot { width: 12px; height: 12px; background: rgba(255, 255, 255, .55); border: 0; }
        .hero-controls .hero-dot--active { width: 28px; background: #fff; }
        .hero-attribution { margin-top: 8px; color: rgba(255, 255, 255, .76); font-size: 10px; text-align: center; }
        .hero-attribution a { color: #fff; text-decoration: underline; }
        @media (max-width: 767px) {
            .home-hero { min-height: 930px; padding-top: 72px; }
            .hero-slide figcaption { right: 12px; bottom: 10px; }
            .hero-search-shell { padding: 10px; }
        }
        @media (prefers-reduced-motion: reduce) {
            .hero-slide, .hero-controls button { transition: none; }
        }
    </style>

    <!-- Hotéis Perto de Ti - Seção com Geolocalização -->
    <section id="nearby-hotels" class="py-16 bg-gradient-to-b from-white to-gray-50 dark:from-gray-800 dark:to-gray-900" x-data="{ locationLoading: true }">
        <div class="container mx-auto px-4">
            <!-- Cabeçalho da seção -->
            <div class="text-center mb-12 relative">
                <div class="inline-block px-4 py-1 bg-blue-50 dark:bg-blue-900/30 rounded-full text-primary dark:text-blue-300 font-medium text-sm mb-3">
                    <span class="mr-2">📍</span>{{ __('Personalizado para si') }}<span class="ml-2">📍</span>
                </div>
                <h2 class="text-4xl md:text-5xl font-bold mb-4 bg-clip-text text-transparent bg-gradient-to-r from-primary to-blue-600 dark:from-blue-400 dark:to-blue-300">
                    {{ __('Hotéis Perto de Ti') }}
                </h2>
                <p class="text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                    {{ __('Descubra acomodações próximas à sua localização atual') }}
                </p>
            </div>

            <!-- Loading state -->
            <div wire:loading.class.remove="hidden" class="hidden text-center py-12">
                <div class="inline-block">
                    <i class="fas fa-spinner fa-spin text-4xl text-primary mb-4"></i>
                    <p class="text-gray-600 dark:text-gray-300">{{ __('A procurar hotéis próximos...') }}</p>
                </div>
            </div>

            <!-- Alerta de permissão negada -->
            @if($locationPermissionDenied)
                <div class="max-w-2xl mx-auto mb-8 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-yellow-600 dark:text-yellow-400 text-xl mr-3 mt-1"></i>
                        <div>
                            <h3 class="font-semibold text-yellow-800 dark:text-yellow-300 mb-1">{{ __('Localização não disponível') }}</h3>
                            <p class="text-sm text-yellow-700 dark:text-yellow-400">
                                Ative a localização para ver hotéis próximos a si. Entretanto, mostramos os nossos hotéis em destaque.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Grid de hotéis próximos -->
            @if(count($nearbyHotels) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" wire:loading.remove>
                    @foreach($nearbyHotels as $hotel)
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                            <!-- Imagem do hotel -->
                            <div class="relative h-48 overflow-hidden">
                                <img src="{{ \App\Helpers\ImageHelper::getValidImage($hotel['image'] ?? null, 'hotel') }}"
                                     alt="{{ $hotel['name'] }}"
                                     class="w-full h-full object-cover transform hover:scale-110 transition-transform duration-500"
                                     loading="lazy">

                                <!-- Badge de distância (se disponível) -->
                                @if(isset($hotel['distance']))
                                    <div class="absolute top-3 right-3 bg-primary text-white px-3 py-1 rounded-full text-sm font-semibold shadow-lg">
                                        <i class="fas fa-map-marker-alt mr-1"></i>{{ $hotel['distance'] }} km
                                    </div>
                                @endif

                                <!-- Badge de destaque -->
                                @if(!isset($hotel['distance']))
                                    <div class="absolute top-3 right-3 bg-amber-500 text-white px-3 py-1 rounded-full text-sm font-semibold shadow-lg">
                                        <i class="fas fa-star mr-1"></i>{{ __('Destaque') }}
                                    </div>
                                @endif
                            </div>

                            <!-- Conteúdo do card -->
                            <div class="p-5">
                                <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2 line-clamp-1">
                                    {{ $hotel['name'] }}
                                </h3>

                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400 mb-3">
                                    <i class="fas fa-map-marker-alt mr-2 text-primary"></i>
                                    <span class="line-clamp-1">{{ $hotel['location'] }}, {{ $hotel['province'] }}</span>
                                </div>

                                <!-- Rating -->
                                <div class="flex items-center mb-3">
                                    <div class="flex text-yellow-400 mr-2">
                                        @for($i = 0; $i < floor($hotel['rating']); $i++)
                                            <i class="fas fa-star text-sm"></i>
                                        @endfor
                                        @if($hotel['rating'] - floor($hotel['rating']) >= 0.5)
                                            <i class="fas fa-star-half-alt text-sm"></i>
                                        @endif
                                    </div>
                                    <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                        {{ number_format($hotel['rating'], 1) }}
                                    </span>
                                    <span class="text-sm text-gray-500 dark:text-gray-400 ml-1">
                                        ({{ $hotel['reviews'] }} avaliações)
                                    </span>
                                </div>

                                <!-- Preço e botão -->
                                <div class="flex items-center justify-between pt-3 border-t border-gray-200 dark:border-gray-700">
                                    <div>
                                        <span class="text-sm text-gray-500 dark:text-gray-400">{{ __('A partir de') }}</span>
                                        <div class="text-2xl font-bold text-primary dark:text-blue-400">
                                            {{ number_format($hotel['price'], 0, ',', '.') }} Kz
                                        </div>
                                    </div>
                                    <a href="{{ route('hotel.details', $hotel['slug']) }}"
                                       class="bg-primary hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors duration-300 flex items-center">
                                        {{ __('Ver') }} <i class="fas fa-arrow-right ml-2"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Estado vazio - Aguardando geolocalização -->
                <div class="text-center py-12" wire:loading.remove x-show="!@js($locationPermissionDenied)">
                    <div class="max-w-md mx-auto">
                        <i class="fas fa-map-marked-alt text-6xl text-gray-300 dark:text-gray-600 mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('Permita o acesso à localização') }}
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">
                            Para mostrar hotéis próximos a si, precisamos da sua permissão para aceder à sua localização.
                        </p>
                        <button type="button"
                                data-location-button
                                onclick="window.KiandaLocation.request('{{ $_instance->getId() }}', 'locationDenied')"
                                class="bg-primary hover:bg-blue-700 disabled:cursor-wait text-white px-6 py-3 rounded-lg font-semibold transition-colors duration-300">
                            <i class="fas fa-location-crosshairs mr-2"></i><span data-location-label>{{ __('Usar a minha localização') }}</span>
                        </button>
                        <p data-location-status hidden role="status" aria-live="polite" class="mt-3 text-sm text-gray-600 dark:text-gray-300"></p>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- Destinos Populares com design imersivo 2025 -->
    <section id="destinations" class="py-24 relative overflow-hidden bg-white dark:bg-gray-900">
        <!-- Fundo com imagem real de Angola -->
        <div class="absolute inset-0 z-0">
            <!-- Imagem de paisagem angolana mais visível -->
            <img src="{{ asset('storage/locations/commons/malanje.jpg') }}"
                 alt="Angola Landscape Background"
                 class="w-full h-full object-cover opacity-20 dark:opacity-20 scale-110 animate-ken-burns"
                 loading="eager"
                 onerror="this.onerror=null; this.src='{{ \App\Helpers\ImageHelper::getDefaultImage('location') }}';">

            <!-- Overlay para melhor contraste e legibilidade -->
            <div class="absolute inset-0 bg-gradient-to-br from-white/95 via-white/90 to-blue-50/95 dark:from-gray-900/98 dark:via-gray-900/95 dark:to-gray-900/98"></div>

            <!-- Elementos decorativos com cores da bandeira de Angola - mais visíveis -->
            <div class="absolute top-20 left-20 w-80 h-80 bg-red-600 rounded-full mix-blend-multiply dark:mix-blend-overlay blur-3xl opacity-30 animate-float"></div>
            <div class="absolute bottom-20 right-40 w-96 h-96 bg-black rounded-full mix-blend-multiply dark:mix-blend-overlay blur-3xl opacity-30 animate-float-delayed"></div>
            <div class="absolute top-1/2 right-1/4 w-60 h-60 bg-yellow-400 rounded-full mix-blend-multiply dark:mix-blend-overlay blur-3xl opacity-25 animate-float-delayed"></div>
        </div>

        <!-- Estilo para as animações -->
        <style>
            .bg-dots-pattern {
                background-image: radial-gradient(circle, #4A6CF7 1px, transparent 1px);
                background-size: 20px 20px;
            }
            @keyframes float {
                0% { transform: translateY(0) scale(1); }
                50% { transform: translateY(-20px) scale(1.05); }
                100% { transform: translateY(0) scale(1); }
            }
            @keyframes float-delayed {
                0% { transform: translateY(0) scale(1); }
                50% { transform: translateY(20px) scale(1.05); }
                100% { transform: translateY(0) scale(1); }
            }
            @keyframes ken-burns {
                0% { transform: scale(1); }
                50% { transform: scale(1.1); }
                100% { transform: scale(1); }
            }
            .animate-float {
                animation: float 15s infinite ease-in-out;
            }
            .animate-float-delayed {
                animation: float-delayed 18s infinite ease-in-out;
            }
            .animate-ken-burns {
                animation: ken-burns 30s infinite ease-in-out;
            }
        </style>

        <div class="container mx-auto px-4 relative z-10">
            <div class="text-center mb-16">
                <span class="inline-block px-5 py-2 rounded-full bg-primary/10 text-primary dark:bg-primary/20 text-sm font-medium mb-6 animate-pulse shadow-sm border border-primary/20 dark:border-primary/30">
                    <i class="fas fa-map-marker-alt mr-2"></i>{{ __('Explore Angola') }}
                </span>
                <h2 class="text-4xl md:text-5xl font-extrabold mb-6 text-gray-800 dark:text-white">
                    {{ __('Províncias de Angola') }}
                    <div class="w-24 h-1.5 bg-gradient-to-r from-primary to-blue-500 mx-auto mt-5 rounded-full"></div>
                </h2>
                <p class="text-gray-600 dark:text-gray-300 max-w-2xl mx-auto text-lg font-normal leading-relaxed">
                    Descubra a beleza e cultura das 21 províncias de Angola em {{ date('Y') }}
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @php
                    $colorClasses = [
                        'bg-gradient-to-r from-blue-600 to-blue-800 text-white',
                        'bg-gradient-to-r from-yellow-500 to-yellow-600 text-gray-900',
                        'bg-gradient-to-r from-blue-500 to-blue-700 text-white',
                        'bg-gradient-to-r from-yellow-400 to-yellow-500 text-gray-900',
                        'bg-gradient-to-r from-blue-700 to-blue-900 text-white',
                        'bg-gradient-to-r from-yellow-600 to-yellow-700 text-white'
                    ];
                @endphp
                @forelse($popularDestinations as $index => $destination)
                @php
                    $colorIndex = $index % count($colorClasses);
                    $buttonClass = $colorClasses[$colorIndex];
                @endphp
                <article class="destination-card group relative bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 rounded-2xl overflow-hidden shadow-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-1 border border-blue-100/30 dark:border-blue-900/20">
                    <!-- Badge de província -->
                    <div class="absolute top-4 left-4 z-20 bg-white/95 dark:bg-black/80 py-1 px-3 rounded-full shadow-lg">
                        <div class="flex items-center">
                            <div class="w-2 h-2 rounded-full bg-primary animate-pulse mr-2"></div>
                            <span class="text-xs font-semibold text-primary dark:text-blue-400">{{ __('Província') }}</span>
                        </div>
                    </div>

                    <div class="relative overflow-hidden h-72">
                        <!-- Imagem da província -->
                        <img
                            src="{{ \App\Helpers\ImageHelper::getValidImage($destination['image'] ?? null, 'location') }}"
                            alt="{{ $destination['name'] }}"
                            class="destination-card__image block w-full h-full object-cover"
                            onerror="this.onerror=null; this.src='{{ asset('storage/locations/placeholder.svg') }}';"
                        >
                        <!-- Gradiente permanente leve para legibilidade do texto -->
                        <div class="destination-card__base-overlay absolute inset-0 pointer-events-none"></div>

                        <!-- Título sempre visível em branco sobre a imagem -->
                        <div class="absolute inset-x-0 bottom-0 p-5">
                            <h3 class="text-2xl font-bold text-white mb-2 drop-shadow-lg">{{ $destination['name'] }}</h3>
                            <!-- Descrição que aparece apenas no hover -->
                            <p class="text-white/90 line-clamp-2 text-sm max-w-md max-h-0 opacity-0 group-hover:max-h-20 group-hover:opacity-100 group-focus-within:max-h-20 group-focus-within:opacity-100 transition-all duration-300 overflow-hidden">{{ $destination['description'] }}</p>
                        </div>
                    </div>

                    <div class="p-6">
                        <!-- Nome da província sempre visível -->
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <i class="fas fa-map-marker-alt text-primary mr-2"></i>
                                <h3 class="text-xl font-bold text-gray-800 dark:text-white">{{ $destination['name'] }}</h3>
                            </div>
                            <div class="flex items-center px-2 py-1 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                <i class="fas fa-hotel text-primary mr-2"></i>
                                <span class="text-sm font-medium text-primary dark:text-blue-300">{{ $destination['hotels_count'] }}</span>
                            </div>
                        </div>

                        <!-- Botão de explorar estilizado -->
                        <a href="{{ route('search.results', ['location' => $destination['name']]) }}"
                           class="mt-2 w-full py-3 flex items-center justify-center {{ $buttonClass }} font-semibold hover:shadow-lg hover:shadow-primary/40 transition-all duration-300 transform group-hover:scale-[1.02] hover:brightness-110 rounded-xl">
                            <span>Explorar {{ $destination['name'] }}</span>
                            <i class="fas fa-arrow-right ml-2 transition-transform group-hover:translate-x-1"></i>
                        </a>
                    </div>
                </article>
                @empty
                <div class="col-span-3 text-center py-12 bg-white dark:bg-gray-800 rounded-2xl shadow">
                    <i class="fas fa-map-signs text-5xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2">{{ __('Nenhum destino encontrado') }}</h3>
                    <p class="text-gray-500 dark:text-gray-400">{{ __('No momento não há destinos populares disponíveis.') }}</p>
                </div>
                @endforelse
            </div>

            <div class="text-center mt-16">
                <a href="{{ route('destinations') }}" class="inline-flex items-center px-8 py-4 rounded-full bg-gradient-to-r from-blue-600 to-blue-800 text-white font-semibold hover:shadow-lg hover:shadow-blue-500/40 transition-all duration-300 transform hover:scale-105 hover:brightness-110">
                    <span>{{ __('Ver Todos os Destinos') }}</span>
                    <i class="fas fa-compass ml-2"></i>
                </a>
            </div>
        </div>

        <style>
            /*
             * Evita a falha de composição do Chromium causada por transformações
             * aninhadas (cartão + imagem + backdrop-filter), que fazia a imagem
             * desaparecer durante o hover em algumas GPUs.
             */
            .destination-card:hover,
            .destination-card:focus-within {
                box-shadow: 0 24px 45px -18px rgb(15 23 42 / 0.35);
            }

            .destination-card__image {
                opacity: 1;
                visibility: visible;
                transform: none;
            }

            .destination-card__base-overlay {
                background: linear-gradient(
                    to top,
                    rgba(0, 0, 0, 0.58) 0%,
                    rgba(0, 0, 0, 0.14) 42%,
                    rgba(0, 0, 0, 0) 72%
                );
            }

            @media (prefers-reduced-motion: reduce) {
                .destination-card,
                .destination-card * {
                    transition-duration: 0.01ms !important;
                }
            }
        </style>
    </section>

    <!-- Por que escolher o KiandaStay (versão moderna 2025) -->
    <section class="py-28 relative overflow-hidden">
        <!-- Background com efeito de gradiente e formas geométricas -->
        <div class="absolute inset-0 bg-gradient-to-tr from-gray-50 to-blue-50 dark:from-gray-900 dark:to-blue-900 opacity-50"></div>
        <div class="absolute inset-0 overflow-hidden">
            <div class="blur-3xl opacity-30 absolute -top-40 -left-40 w-80 h-80 bg-primary rounded-full"></div>
            <div class="blur-3xl opacity-20 absolute -bottom-60 -right-60 w-96 h-96 bg-blue-400 rounded-full"></div>
        </div>

        <div class="container mx-auto px-4 relative z-10">
            <div class="text-center mb-20">
                <span class="inline-block px-4 py-1 rounded-full bg-primary/10 text-primary dark:bg-primary/20 text-sm font-medium mb-4">Tecnologia de {{ date('Y') }}</span>
                <h2 class="text-4xl md:text-5xl font-extrabold mb-6">{{ __('Por que escolher o') }} <span class="text-primary">KiandaStay</span></h2>
                <p class="text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">Nosso sistema utiliza tecnologia de ponta com inteligência artificial para proporcionar a melhor experiência de reserva</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Card 1 - Comparação de Preços -->
                <div class="group bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 relative overflow-hidden">
                    <!-- Indicador de gradiente no topo -->
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-primary to-blue-400"></div>

                    <div class="w-16 h-16 mb-6 flex items-center justify-center bg-primary/10 dark:bg-primary/20 rounded-2xl text-primary group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-search-dollar text-2xl"></i>
                    </div>

                    <h3 class="text-xl font-bold mb-4 text-gray-800 dark:text-white">{{ __('Comparação Inteligente') }}</h3>
                    <p class="text-gray-600 dark:text-gray-300">Nossa IA analisa e compara preços em tempo real para garantir as melhores ofertas para sua viagem.</p>
                </div>

                <!-- Card 2 - Avaliações Verificadas -->
                <div class="group bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 relative overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-green-400 to-primary"></div>

                    <div class="w-16 h-16 mb-6 flex items-center justify-center bg-green-500/10 dark:bg-green-500/20 rounded-2xl text-green-500 group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-shield-check text-2xl"></i>
                    </div>

                    <h3 class="text-xl font-bold mb-4 text-gray-800 dark:text-white">{{ __('Avaliações Autenticadas') }}</h3>
                    <p class="text-gray-600 dark:text-gray-300">Sistema de verificação biométrica garante que todas as avaliações sejam de hóspedes reais.</p>
                </div>

                <!-- Card 3 - Especialistas em Angola -->
                <div class="group bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 relative overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-purple-400 to-pink-400"></div>

                    <div class="w-16 h-16 mb-6 flex items-center justify-center bg-purple-500/10 dark:bg-purple-500/20 rounded-2xl text-purple-500 group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-globe-africa text-2xl"></i>
                    </div>

                    <h3 class="text-xl font-bold mb-4 text-gray-800 dark:text-white">{{ __('Conhecimento Local') }}</h3>
                    <p class="text-gray-600 dark:text-gray-300">Nossa equipe de especialistas locais atualiza constantemente informações sobre cada região de Angola.</p>
                </div>

                <!-- Card 4 - Reservas Seguras -->
                <div class="group bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 relative overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-amber-400 to-orange-400"></div>

                    <div class="w-16 h-16 mb-6 flex items-center justify-center bg-amber-500/10 dark:bg-amber-500/20 rounded-2xl text-amber-500 group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-fingerprint text-2xl"></i>
                    </div>

                    <h3 class="text-xl font-bold mb-4 text-gray-800 dark:text-white">{{ __('Segurança Avançada') }}</h3>
                    <p class="text-gray-600 dark:text-gray-300">Tecnologia blockchain e autenticação em vários fatores para proteção total dos seus dados.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Seção: Explore por Tipo de Propriedade -->
    <section class="py-20" style="background: linear-gradient(180deg, #f8fafc 0%, #ffffff 100%);">
        <div class="container mx-auto px-4">
            <div class="text-center mb-14">
                <span class="inline-block px-5 py-2 rounded-full text-sm font-semibold mb-4" style="background: #eef2ff; color: #4f46e5;">
                    {{ __('Para Todos os Gostos') }}
                </span>
                <h2 class="text-3xl md:text-4xl font-bold mb-4" style="color: #1e293b;">
                    {{ __('Encontre a Estadia Perfeita') }}
                </h2>
                <p class="max-w-2xl mx-auto text-lg" style="color: #64748b;">
                    {{ __('De hotéis urbanos a resorts de luxo e hospedarias acolhedoras') }}
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Card: Hotéis -->
                <a href="{{ route('search.results', ['property_types' => ['hotel']]) }}"
                   class="group relative rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2" style="min-height: 320px;">
                    <div class="absolute inset-0" style="background: linear-gradient(135deg, #1e40af 0%, #3b82f6 50%, #60a5fa 100%);"></div>
                    <!-- Decorative circles -->
                    <div class="absolute top-0 right-0 w-40 h-40 rounded-full" style="background: rgba(255,255,255,0.1); transform: translate(30%, -30%);"></div>
                    <div class="absolute bottom-0 left-0 w-32 h-32 rounded-full" style="background: rgba(255,255,255,0.08); transform: translate(-20%, 20%);"></div>

                    <div class="relative h-full flex flex-col justify-between p-8 z-10">
                        <div class="flex items-start justify-between">
                            <div class="w-14 h-14 rounded-2xl flex items-center justify-center" style="background: rgba(255,255,255,0.2);">
                                <i class="fas fa-hotel text-2xl" style="color: #ffffff;"></i>
                            </div>
                            <span class="px-3 py-1 rounded-full text-xs font-bold" style="background: rgba(255,255,255,0.25); color: #ffffff;">
                                {{ \App\Models\Hotel::where('property_type', 'hotel')->where('is_active', true)->count() }}+ opções
                            </span>
                        </div>

                        <div class="mt-auto">
                            <h3 class="text-2xl font-bold mb-2" style="color: #ffffff;">{{ __('Hotéis Urbanos') }}</h3>
                            <p class="text-base mb-5" style="color: rgba(255,255,255,0.85);">
                                {{ __('Conforto e praticidade no coração das cidades') }}
                            </p>
                            <div class="inline-flex items-center px-5 py-2.5 rounded-full text-sm font-semibold transition-all duration-300 group-hover:shadow-lg" style="background: #ffffff; color: #1e40af;">
                                <span>{{ __('Explorar Hotéis') }}</span>
                                <i class="fas fa-arrow-right ml-2 transition-transform duration-300 group-hover:translate-x-1"></i>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Card: Resorts -->
                <a href="{{ route('search.results', ['property_types' => ['resort']]) }}"
                   class="group relative rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2" style="min-height: 320px;">
                    <div class="absolute inset-0" style="background: linear-gradient(135deg, #c2410c 0%, #ea580c 40%, #f59e0b 100%);"></div>
                    <!-- Decorative circles -->
                    <div class="absolute top-0 right-0 w-40 h-40 rounded-full" style="background: rgba(255,255,255,0.1); transform: translate(30%, -30%);"></div>
                    <div class="absolute bottom-0 left-0 w-32 h-32 rounded-full" style="background: rgba(255,255,255,0.08); transform: translate(-20%, 20%);"></div>

                    <div class="relative h-full flex flex-col justify-between p-8 z-10">
                        <div class="flex items-start justify-between">
                            <div class="w-14 h-14 rounded-2xl flex items-center justify-center" style="background: rgba(255,255,255,0.2);">
                                <i class="fas fa-umbrella-beach text-2xl" style="color: #ffffff;"></i>
                            </div>
                            <span class="px-4 py-1.5 rounded-full text-xs font-bold flex items-center" style="background: #ffffff; color: #c2410c;">
                                <i class="fas fa-crown mr-1.5"></i> PREMIUM
                            </span>
                        </div>

                        <div class="mt-auto">
                            <h3 class="text-2xl font-bold mb-2" style="color: #ffffff;">{{ __('Resorts de Luxo') }}</h3>
                            <p class="text-base mb-5" style="color: rgba(255,255,255,0.85);">
                                {{ __('Experiências premium à beira-mar e nas montanhas') }}
                            </p>
                            <div class="inline-flex items-center px-5 py-2.5 rounded-full text-sm font-semibold transition-all duration-300 group-hover:shadow-lg" style="background: #ffffff; color: #c2410c;">
                                <span>{{ __('Descobrir Resorts') }}</span>
                                <i class="fas fa-arrow-right ml-2 transition-transform duration-300 group-hover:translate-x-1"></i>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Card: Hospedarias -->
                <a href="{{ route('search.results', ['property_types' => ['hospedaria']]) }}"
                   class="group relative rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2" style="min-height: 320px;">
                    <div class="absolute inset-0" style="background: linear-gradient(135deg, #0f766e 0%, #14b8a6 50%, #2dd4bf 100%);"></div>
                    <!-- Decorative circles -->
                    <div class="absolute top-0 right-0 w-40 h-40 rounded-full" style="background: rgba(255,255,255,0.1); transform: translate(30%, -30%);"></div>
                    <div class="absolute bottom-0 left-0 w-32 h-32 rounded-full" style="background: rgba(255,255,255,0.08); transform: translate(-20%, 20%);"></div>

                    <div class="relative h-full flex flex-col justify-between p-8 z-10">
                        <div class="flex items-start justify-between">
                            <div class="w-14 h-14 rounded-2xl flex items-center justify-center" style="background: rgba(255,255,255,0.2);">
                                <i class="fas fa-home text-2xl" style="color: #ffffff;"></i>
                            </div>
                            <span class="px-4 py-1.5 rounded-full text-xs font-bold flex items-center" style="background: #ffffff; color: #0f766e;">
                                <i class="fas fa-heart mr-1.5"></i> {{ __('AUTÊNTICO') }}
                            </span>
                        </div>

                        <div class="mt-auto">
                            <h3 class="text-2xl font-bold mb-2" style="color: #ffffff;">{{ __('Hospedarias Locais') }}</h3>
                            <p class="text-base mb-5" style="color: rgba(255,255,255,0.85);">
                                {{ __('Acolhimento familiar e experiências autênticas') }}
                            </p>
                            <div class="inline-flex items-center px-5 py-2.5 rounded-full text-sm font-semibold transition-all duration-300 group-hover:shadow-lg" style="background: #ffffff; color: #0f766e;">
                                <span>{{ __('Ver Hospedarias') }}</span>
                                <i class="fas fa-arrow-right ml-2 transition-transform duration-300 group-hover:translate-x-1"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </section>

    <!-- Resorts em Destaque -->
    @if(count($featuredResorts) > 0)
    <section class="py-20" style="background: linear-gradient(135deg, #fff7ed 0%, #fef3c7 50%, #fffbeb 100%);">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between mb-10">
                <div>
                    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold mb-3" style="background: linear-gradient(135deg, #ea580c, #f59e0b); color: #ffffff;">
                        <i class="fas fa-crown mr-2"></i> {{ __('Experiências Premium') }}
                    </span>
                    <h2 class="text-3xl md:text-4xl font-bold" style="color: #1e293b;">
                        {{ __('Resorts em') }} <span style="color: #ea580c;">{{ __('Destaque') }}</span>
                    </h2>
                    <p class="mt-2" style="color: #64748b;">{{ __('Luxo e conforto em locais paradisíacos de Angola') }}</p>
                </div>
                <a href="{{ route('search.results', ['property_types' => ['resort']]) }}"
                   class="mt-4 md:mt-0 inline-flex items-center px-6 py-3 rounded-full font-semibold text-sm hover:shadow-lg transition-all duration-300 hover:scale-105" style="background: linear-gradient(135deg, #ea580c, #f59e0b); color: #ffffff;">
                    {{ __('Ver Todos os Resorts') }}
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($featuredResorts as $index => $resort)
                @php
                    $resortGradients = [
                        'linear-gradient(135deg, #c2410c 0%, #ea580c 100%)',
                        'linear-gradient(135deg, #b45309 0%, #d97706 100%)',
                        'linear-gradient(135deg, #9a3412 0%, #c2410c 100%)',
                        'linear-gradient(135deg, #92400e 0%, #b45309 100%)',
                    ];
                @endphp
                <a href="{{ route('hotel.details', $resort['slug']) }}"
                   class="group rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2" style="background: #ffffff;">
                    <!-- Image area with gradient fallback -->
                    <div class="relative h-48 overflow-hidden">
                        <div class="absolute inset-0" style="background: {{ $resortGradients[$index % 4] }};"></div>
                        <img src="{{ \App\Helpers\ImageHelper::getValidImage($resort['image'] ?? null, 'hotel') }}"
                             alt="{{ $resort['name'] }}"
                             class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                             loading="lazy"
                             onerror="this.style.display='none';">
                        <!-- Overlay -->
                        <div class="absolute inset-0" style="background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.1) 50%, transparent 100%);"></div>

                        <!-- Badge -->
                        <div class="absolute top-3 right-3 z-10">
                            <span class="px-3 py-1 rounded-full text-xs font-bold shadow-md" style="background: linear-gradient(135deg, #f59e0b, #ea580c); color: #ffffff;">
                                <i class="fas fa-crown mr-1"></i>{{ __('Resort') }}
                            </span>
                        </div>

                        <!-- Name over image -->
                        <div class="absolute bottom-3 left-4 right-4 z-10">
                            <h3 class="text-lg font-bold truncate" style="color: #ffffff;">{{ $resort['name'] }}</h3>
                            <p class="text-sm flex items-center" style="color: rgba(255,255,255,0.9);">
                                <i class="fas fa-map-marker-alt mr-1.5" style="color: #fbbf24;"></i>
                                {{ $resort['location'] }}
                            </p>
                        </div>
                    </div>
                    <!-- Info area -->
                    <div class="p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-1.5">
                                <i class="fas fa-star" style="color: #f59e0b;"></i>
                                <span class="font-bold" style="color: #1e293b;">{{ number_format($resort['rating'], 1) }}</span>
                                <span class="text-sm" style="color: #94a3b8;">({{ $resort['reviews'] }})</span>
                            </div>
                            <div class="text-right">
                                <span class="text-xs block" style="color: #94a3b8;">desde</span>
                                <span class="text-lg font-bold" style="color: #ea580c;">AKZ {{ number_format($resort['price'], 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Hospedarias em Destaque -->
    @if(count($featuredHospedarias) > 0)
    <section class="py-20" style="background: linear-gradient(135deg, #f0fdfa 0%, #ccfbf1 50%, #f0fdfa 100%);">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between mb-10">
                <div>
                    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold mb-3" style="background: linear-gradient(135deg, #0f766e, #14b8a6); color: #ffffff;">
                        <i class="fas fa-heart mr-2"></i> {{ __('Experiências Autênticas') }}
                    </span>
                    <h2 class="text-3xl md:text-4xl font-bold" style="color: #1e293b;">
                        {{ __('Hospedarias') }} <span style="color: #0f766e;">{{ __('Acolhedoras') }}</span>
                    </h2>
                    <p class="mt-2" style="color: #64748b;">{{ __('Conforto familiar e preços acessíveis em toda Angola') }}</p>
                </div>
                <a href="{{ route('search.results', ['property_types' => ['hospedaria']]) }}"
                   class="mt-4 md:mt-0 inline-flex items-center px-6 py-3 rounded-full font-semibold text-sm hover:shadow-lg transition-all duration-300 hover:scale-105" style="background: linear-gradient(135deg, #0f766e, #14b8a6); color: #ffffff;">
                    {{ __('Ver Todas as Hospedarias') }}
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($featuredHospedarias as $index => $hospedaria)
                @php
                    $hospGradients = [
                        'linear-gradient(135deg, #0f766e 0%, #14b8a6 100%)',
                        'linear-gradient(135deg, #065f46 0%, #059669 100%)',
                        'linear-gradient(135deg, #115e59 0%, #0d9488 100%)',
                        'linear-gradient(135deg, #064e3b 0%, #047857 100%)',
                    ];
                @endphp
                <a href="{{ route('hotel.details', $hospedaria['slug']) }}"
                   class="group rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2" style="background: #ffffff;">
                    <!-- Image area with gradient fallback -->
                    <div class="relative h-48 overflow-hidden">
                        <div class="absolute inset-0" style="background: {{ $hospGradients[$index % 4] }};"></div>
                        <img src="{{ \App\Helpers\ImageHelper::getValidImage($hospedaria['image'] ?? null, 'hotel') }}"
                             alt="{{ $hospedaria['name'] }}"
                             class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                             loading="lazy"
                             onerror="this.style.display='none';">
                        <!-- Overlay -->
                        <div class="absolute inset-0" style="background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.1) 50%, transparent 100%);"></div>

                        <!-- Badge -->
                        <div class="absolute top-3 right-3 z-10">
                            <span class="px-3 py-1 rounded-full text-xs font-bold shadow-md" style="background: linear-gradient(135deg, #0f766e, #14b8a6); color: #ffffff;">
                                <i class="fas fa-heart mr-1"></i>{{ __('Hospedaria') }}
                            </span>
                        </div>

                        <!-- Name over image -->
                        <div class="absolute bottom-3 left-4 right-4 z-10">
                            <h3 class="text-lg font-bold truncate" style="color: #ffffff;">{{ $hospedaria['name'] }}</h3>
                            <p class="text-sm flex items-center" style="color: rgba(255,255,255,0.9);">
                                <i class="fas fa-map-marker-alt mr-1.5" style="color: #5eead4;"></i>
                                {{ $hospedaria['location'] }}
                            </p>
                        </div>
                    </div>
                    <!-- Info area -->
                    <div class="p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-1.5">
                                <i class="fas fa-star" style="color: #14b8a6;"></i>
                                <span class="font-bold" style="color: #1e293b;">{{ number_format($hospedaria['rating'], 1) }}</span>
                                <span class="text-sm" style="color: #94a3b8;">({{ $hospedaria['reviews'] }})</span>
                            </div>
                            <div class="text-right">
                                <span class="text-xs block" style="color: #94a3b8;">desde</span>
                                <span class="text-lg font-bold" style="color: #0f766e;">AKZ {{ number_format($hospedaria['price'], 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Ofertas Especiais - Design Imersivo 2025 -->
    <section class="py-24 relative overflow-hidden">
        <!-- Background imersivo para ofertas especiais -->
        <div class="absolute inset-0 z-0">
            <!-- Imagem de fundo que representa Angola do Unsplash -->
            <img src="{{ $offersBackground ? Storage::url($offersBackground) : asset('storage/locations/commons/namibe.jpg') }}"
                 alt="Angola Landscape"
                 class="w-full h-full object-cover scale-105 animate-slow-zoom"
                 loading="lazy">

            <!-- Overlay de gradiente -->
            <div class="absolute inset-0 bg-gradient-to-br from-amber-500/90 via-secondary/80 to-amber-700/90 dark:from-amber-900/90 dark:via-secondary/80 dark:to-amber-950/90 mix-blend-multiply"></div>

            <!-- Padrão de linhas diagonais -->
            <div class="absolute inset-0 opacity-10">
                <div class="diagonal-pattern"></div>
            </div>

            <!-- Elementos decorativos flutuantes -->
            <div class="absolute top-40 left-10 w-60 h-60 rounded-full border-4 border-white/10 opacity-30 animate-spin-slow"></div>
            <div class="absolute bottom-20 right-20 w-80 h-80 rounded-full border-2 border-white/10 opacity-20 animate-spin-slow-reverse"></div>
        </div>

        <!-- Estilo para os elementos decorativos -->
        <style>
            .diagonal-pattern {
                background-image: repeating-linear-gradient(45deg, rgba(255,255,255,0.1), rgba(255,255,255,0.1) 2px, transparent 2px, transparent 8px);
                height: 100%;
                width: 100%;
            }
            @keyframes spin-slow {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }
            @keyframes spin-slow-reverse {
                from { transform: rotate(360deg); }
                to { transform: rotate(0deg); }
            }
            .animate-spin-slow {
                animation: spin-slow 60s linear infinite;
            }
            .animate-spin-slow-reverse {
                animation: spin-slow-reverse 45s linear infinite;
            }
        </style>
        <div class="container mx-auto px-4 relative z-10">
            <!-- Cabeçalho da seção com badge moderna -->
            <div class="text-center mb-16 relative">
                <div class="inline-block px-4 py-1 bg-primary/10 dark:bg-primary/20 rounded-full text-primary dark:text-blue-300 font-medium text-sm mb-3">
                    <span class="mr-2">💎</span>{{ __('Promoções Exclusivas') }}<span class="ml-2">💎</span>
                </div>
                <h2 class="text-4xl md:text-5xl font-bold mb-4 bg-clip-text text-transparent bg-gradient-to-r from-primary to-blue-600 dark:from-blue-400 dark:to-blue-300">{{ __('Ofertas Especiais') }}</h2>
                <p class="text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">Descubra nossas melhores ofertas e promoções para sua próxima aventura em Angola.</p>
                <div class="absolute w-20 h-1 bg-primary rounded-full left-1/2 transform -translate-x-1/2 bottom-[-1rem]"></div>
            </div>

            @php
                $offerColorClasses = [
                    'bg-gradient-to-r from-blue-600 to-blue-800 text-white',
                    'bg-gradient-to-r from-yellow-500 to-yellow-600 text-gray-900',
                    'bg-gradient-to-r from-green-600 to-green-800 text-white',
                    'bg-gradient-to-r from-red-500 to-red-700 text-white',
                    'bg-gradient-to-r from-purple-600 to-purple-800 text-white',
                    'bg-gradient-to-r from-amber-500 to-amber-600 text-gray-900'
                ];
            @endphp
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($specialOffers as $index => $offer)
                @php
                    $colorIndex = $index % count($offerColorClasses);
                    $offerButtonClass = $offerColorClasses[$colorIndex];
                @endphp
                <div class="group relative bg-white dark:bg-gray-800 rounded-2xl overflow-hidden shadow-xl hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 border border-gray-100 dark:border-gray-700 hover:border-primary/30 dark:hover:border-blue-400/30">
                    <!-- Efeito de brilho ao passar o mouse -->
                    <div class="absolute inset-0 bg-gradient-to-tr from-transparent via-white/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>

                    <div class="relative overflow-hidden h-64 bg-white">
                        <!-- Badge de desconto -->
                        <div class="absolute top-4 right-4 z-20">
                            <div class="relative">
                                <div class="relative bg-gradient-to-r from-red-500 to-red-600 text-white px-4 py-2 rounded-lg shadow-lg transform rotate-3 group-hover:rotate-0 transition-all duration-300">
                                    <div class="flex items-center">
                                        <span class="text-base font-bold">-{{ $offer['discount_percentage'] }}%</span>
                                        <span class="ml-1 text-xs font-semibold">OFF</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Imagem da oferta -->
                        <div class="relative h-full w-full overflow-hidden">
                            <img
                                src="{{ \App\Helpers\ImageHelper::getValidImage($offer['image'], 'hotel') }}"
                                alt="{{ $offer['name'] }}"
                                class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                                loading="lazy"
                                onerror="this.onerror=null; this.src='{{ \App\Helpers\ImageHelper::getDefaultImage('hotel') }}';"
                                style="min-height: 100%; min-width: 100%; object-position: center;"
                            >

                            <!-- Badge de tempo limitado -->
                            <div class="absolute bottom-4 left-4 z-10">
                                <div class="flex items-center bg-white text-gray-800 text-xs px-3 py-1.5 rounded-full shadow-md border border-gray-100">
                                    <i class="far fa-clock mr-1.5 text-yellow-500"></i>
                                    <span class="font-medium">{{ __('Oferta por tempo limitado') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 relative">
                        <!-- Localização do hotel -->
                        <div class="flex items-center mb-3 text-sm font-medium text-primary dark:text-blue-300">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            <span class="truncate">{{ $offer['location'] }}</span>
                        </div>

                        <!-- Título da oferta -->
                        <h3 class="text-xl font-extrabold text-gray-900 dark:text-white mb-4 leading-tight group-hover:text-primary dark:group-hover:text-blue-300 transition-colors duration-300">
                            {{ $offer['name'] }}
                        </h3>

                        <!-- Preço e economia -->
                        <div class="mb-5">
                            <div class="flex items-baseline mb-1">
                                <span class="text-3xl font-extrabold text-gray-900 dark:text-white">AKZ {{ number_format($offer['discount_price'], 0, ',', '.') }}</span>
                                <span class="ml-2 text-sm text-gray-500 dark:text-gray-400 line-through">AKZ {{ number_format($offer['original_price'], 0, ',', '.') }}</span>
                            </div>
                            <div class="flex items-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                    <i class="fas fa-wallet mr-1"></i>
                                    Economize {{ $offer['discount_percentage'] }}% (AKZ {{ number_format($offer['original_price'] - $offer['discount_price'], 0, ',', '.') }})
                                </span>
                            </div>
                        </div>

                        <!-- Botão de ação com efeito de brilho -->
                        <div class="relative group/button mt-4">
                            <div class="absolute -inset-0.5 rounded-xl opacity-75 group-hover/button:opacity-100 blur transition duration-500 group-hover/button:duration-200 animate-tilt {{ str_contains($offerButtonClass, 'text-white') ? 'from-blue-500 to-blue-700' : 'from-yellow-400 to-yellow-600' }}"></div>
                            <a href="{{ route('hotel.details', $offer['slug']) }}"
                               class="relative w-full py-3 px-6 flex items-center justify-between {{ $offerButtonClass }} rounded-xl font-bold hover:shadow-xl hover:shadow-primary/30 transition-all duration-300 transform group-hover/button:-translate-y-0.5">
                                <span>{{ __('Reservar Agora') }}</span>
                                <i class="fas fa-arrow-right ml-2 transition-transform group-hover/button:translate-x-1 group-hover/button:scale-110"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-3 text-center py-20 bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-blue-50 dark:bg-blue-900/30 text-primary dark:text-blue-300 mb-6">
                        <i class="fas fa-tag text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">{{ __('Nenhuma oferta disponível no momento') }}</h3>
                    <p class="text-gray-600 dark:text-gray-300 max-w-md mx-auto mb-6">Nossas ofertas especiais estão temporariamente esgotadas. Volte em breve para não perder as próximas promoções!</p>
                    <a href="{{ route('search.results') }}" class="inline-flex items-center px-6 py-3 rounded-full bg-gradient-to-r from-primary to-blue-600 text-white font-medium hover:shadow-lg hover:shadow-primary/20 transition-all duration-300">
                        <span>{{ __('Explorar Hotéis') }}</span>
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Newsletter & CTA -->
    <section
        class="newsletter-experience"
        aria-labelledby="newsletter-title"
        x-data="{ visible: false }"
        x-init="const observer = new IntersectionObserver(([entry]) => { if (entry.isIntersecting) { visible = true; observer.disconnect(); } }, { threshold: .16 }); observer.observe($el)"
        :class="{ 'is-visible': visible }"
    >
        <div class="newsletter-ambient newsletter-ambient--one" aria-hidden="true"></div>
        <div class="newsletter-ambient newsletter-ambient--two" aria-hidden="true"></div>
        <div class="newsletter-route" aria-hidden="true"><span></span></div>

        <div class="container mx-auto px-4 relative z-10">
            <div class="newsletter-panel">
                <div class="newsletter-copy newsletter-reveal">
                    <span class="newsletter-eyebrow">
                        <i class="fas fa-compass" aria-hidden="true"></i>
                        {{ __('INSPIRAÇÃO PARA A PRÓXIMA VIAGEM') }}
                    </span>
                    <h2 id="newsletter-title">{{ __('Angola ainda tem muito para lhe mostrar.') }}</h2>
                    <p class="newsletter-lead">
                        {{ __('Receba estadias selecionadas, preços especiais e histórias locais para planear a próxima escapadinha com tempo e confiança.') }}
                    </p>

                    <div class="newsletter-form-shell">
                        @livewire('newsletter-subscribe', ['variant' => 'hero'], key('home-newsletter'))
                        <p class="newsletter-privacy">
                            <i class="fas fa-shield-alt" aria-hidden="true"></i>
                            {{ __('Sem spam. Pode cancelar quando quiser.') }}
                        </p>
                    </div>

                    <div class="newsletter-benefits" aria-label="{{ __('Vantagens da newsletter') }}">
                        <article class="newsletter-benefit newsletter-reveal" style="--reveal-delay: 120ms">
                            <span class="newsletter-benefit__icon"><i class="fas fa-tags" aria-hidden="true"></i></span>
                            <div>
                                <h3>{{ __('Tarifas reservadas') }}</h3>
                                <p>{{ __('Ofertas selecionadas antes de chegarem ao público.') }}</p>
                            </div>
                        </article>
                        <article class="newsletter-benefit newsletter-reveal" style="--reveal-delay: 220ms">
                            <span class="newsletter-benefit__icon"><i class="fas fa-map-marked-alt" aria-hidden="true"></i></span>
                            <div>
                                <h3>{{ __('Angola por quem conhece') }}</h3>
                                <p>{{ __('Guias curtos, lugares especiais e dicas realmente úteis.') }}</p>
                            </div>
                        </article>
                        <article class="newsletter-benefit newsletter-reveal" style="--reveal-delay: 320ms">
                            <span class="newsletter-benefit__icon"><i class="fas fa-bell" aria-hidden="true"></i></span>
                            <div>
                                <h3>{{ __('Alertas que valem a pena') }}</h3>
                                <p>{{ __('Novidades relevantes, enviadas no momento certo.') }}</p>
                            </div>
                        </article>
                    </div>
                </div>

                <figure class="newsletter-visual newsletter-reveal" style="--reveal-delay: 140ms">
                    <img src="{{ asset('assets/img/hero-angola/mussulo.jpg') }}" alt="{{ __('Vista aérea da península do Mussulo, em Luanda') }}" loading="lazy">
                    <div class="newsletter-visual__shade"></div>
                    <figcaption>
                        <span>{{ __('Próxima paragem') }}</span>
                        <strong>{{ __('Mussulo, Luanda') }}</strong>
                    </figcaption>
                    <div class="newsletter-stamp" aria-hidden="true">
                        <i class="fas fa-plane"></i>
                        <span>KIANDA<br>STAY</span>
                    </div>
                </figure>
            </div>
        </div>
    </section>

    <style>
        .newsletter-experience {
            --newsletter-gold: #f5b82e;
            position: relative;
            overflow: hidden;
            padding: clamp(72px, 9vw, 132px) 0;
            color: #fff;
            background:
                radial-gradient(circle at 14% 14%, rgba(69, 133, 190, .34), transparent 31%),
                linear-gradient(135deg, #071d31 0%, #0b3554 52%, #0e466d 100%);
            isolation: isolate;
        }
        .newsletter-experience::before {
            content: '';
            position: absolute;
            inset: 0;
            opacity: .16;
            background-image: linear-gradient(rgba(255,255,255,.12) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.12) 1px, transparent 1px);
            background-size: 64px 64px;
            mask-image: linear-gradient(to right, #000, transparent 72%);
        }
        .newsletter-panel { display: grid; grid-template-columns: minmax(0, 1.12fr) minmax(320px, .88fr); gap: clamp(42px, 7vw, 104px); align-items: center; }
        .newsletter-copy { max-width: 720px; }
        .newsletter-eyebrow { display: inline-flex; align-items: center; gap: 10px; margin-bottom: 22px; color: #ffe29b; font-size: 12px; font-weight: 800; letter-spacing: .14em; }
        .newsletter-eyebrow i { display: grid; place-items: center; width: 34px; height: 34px; color: #102f49; background: var(--newsletter-gold); border-radius: 50%; box-shadow: 0 0 0 7px rgba(245,184,46,.12); }
        .newsletter-copy h2 { max-width: 700px; margin: 0; font-size: clamp(2.45rem, 5vw, 4.75rem); font-weight: 850; line-height: .98; letter-spacing: -.045em; text-wrap: balance; }
        .newsletter-lead { max-width: 640px; margin: 26px 0 30px; color: rgba(231,244,255,.82); font-size: clamp(1rem, 1.45vw, 1.18rem); line-height: 1.7; }
        .newsletter-form-shell { max-width: 650px; padding: 8px; border: 1px solid rgba(255,255,255,.17); border-radius: 20px; background: rgba(255,255,255,.08); box-shadow: 0 20px 60px rgba(0,0,0,.18); backdrop-filter: blur(14px); }
        .newsletter-signup-form { display: grid; grid-template-columns: minmax(0, 1fr) auto; gap: 8px; }
        .newsletter-email-input { width: 100%; min-width: 0; min-height: 58px; padding: 0 20px; color: #fff; background: rgba(4,22,37,.46); border: 1px solid rgba(255,255,255,.16); border-radius: 14px; outline: none; transition: border-color .2s ease, box-shadow .2s ease, background-color .2s ease; }
        .newsletter-email-input::placeholder { color: rgba(224,238,249,.58); }
        .newsletter-email-input:focus { background: rgba(4,22,37,.7); border-color: #ffd46b; box-shadow: 0 0 0 4px rgba(245,184,46,.18); }
        .newsletter-submit { min-height: 58px; padding: 0 24px; color: #10283b; font-weight: 800; white-space: nowrap; background: linear-gradient(135deg, #ffd66e, var(--newsletter-gold)); border: 0; border-radius: 14px; box-shadow: 0 12px 28px rgba(245,184,46,.23); transition: transform .22s ease, box-shadow .22s ease, filter .22s ease; }
        .newsletter-submit:hover { transform: translateY(-2px); filter: brightness(1.06); box-shadow: 0 17px 32px rgba(245,184,46,.32); }
        .newsletter-submit:focus-visible { outline: 3px solid #fff; outline-offset: 3px; }
        .newsletter-submit:hover i { transform: translateX(4px); }
        .newsletter-submit i { transition: transform .22s ease; }
        .newsletter-submit:disabled { cursor: wait; opacity: .7; }
        .newsletter-privacy { display: flex; align-items: center; gap: 8px; margin: 10px 10px 3px; color: rgba(224,238,249,.65); font-size: 12px; }
        .newsletter-privacy i { color: #8fd5a9; }
        .newsletter-success { margin: 0 0 8px; border: 0; border-radius: 14px; }
        .newsletter-error { margin: 8px 8px 0; color: #fecaca; font-size: 13px; }
        .newsletter-benefits { display: grid; grid-template-columns: repeat(3, minmax(0,1fr)); gap: 12px; margin-top: 30px; }
        .newsletter-benefit { display: flex; gap: 13px; min-height: 118px; padding: 18px; border: 1px solid rgba(255,255,255,.12); border-radius: 18px; background: rgba(255,255,255,.055); transition: transform .25s ease, background-color .25s ease, border-color .25s ease; }
        .newsletter-benefit:hover { transform: translateY(-5px); background: rgba(255,255,255,.1); border-color: rgba(245,184,46,.38); }
        .newsletter-benefit__icon { flex: 0 0 38px; display: grid; place-items: center; width: 38px; height: 38px; color: var(--newsletter-gold); background: rgba(245,184,46,.11); border-radius: 12px; }
        .newsletter-benefit h3 { margin: 1px 0 6px; color: #fff; font-size: 14px; font-weight: 750; line-height: 1.25; }
        .newsletter-benefit p { margin: 0; color: rgba(224,238,249,.64); font-size: 12px; line-height: 1.45; }
        .newsletter-visual { position: relative; min-height: 610px; margin: 0; overflow: hidden; border: 1px solid rgba(255,255,255,.18); border-radius: 42% 42% 24px 24px; box-shadow: 0 35px 90px rgba(0,0,0,.34); }
        .newsletter-visual::after { content: ''; position: absolute; inset: 14px; z-index: 3; border: 1px solid rgba(255,255,255,.36); border-radius: 42% 42% 18px 18px; pointer-events: none; }
        .newsletter-visual img { width: 100%; height: 100%; min-height: 610px; object-fit: cover; transition: transform 1.4s cubic-bezier(.2,.7,.2,1); }
        .newsletter-experience.is-visible .newsletter-visual img { animation: newsletter-drift 14s ease-in-out infinite alternate; }
        .newsletter-visual:hover img { transform: scale(1.055); }
        .newsletter-visual__shade { position: absolute; inset: 0; background: linear-gradient(to top, rgba(3,18,31,.88), transparent 56%), linear-gradient(135deg, rgba(12,64,95,.2), transparent); }
        .newsletter-visual figcaption { position: absolute; left: 38px; bottom: 34px; z-index: 4; display: flex; flex-direction: column; }
        .newsletter-visual figcaption span { margin-bottom: 5px; color: #ffe29b; font-size: 11px; font-weight: 800; letter-spacing: .16em; text-transform: uppercase; }
        .newsletter-visual figcaption strong { font-size: clamp(1.5rem, 2.3vw, 2.15rem); }
        .newsletter-stamp { position: absolute; top: 46px; right: 30px; z-index: 4; display: grid; place-items: center; width: 88px; height: 88px; color: #102f49; text-align: center; background: rgba(255,218,124,.93); border-radius: 50%; transform: rotate(8deg); box-shadow: 0 10px 30px rgba(0,0,0,.18); }
        .newsletter-stamp i { margin-bottom: -9px; transform: rotate(-18deg); }
        .newsletter-stamp span { font-size: 10px; font-weight: 900; line-height: 1.05; letter-spacing: .08em; }
        .newsletter-ambient { position: absolute; border-radius: 50%; filter: blur(2px); pointer-events: none; }
        .newsletter-ambient--one { top: -150px; right: 30%; width: 340px; height: 340px; background: rgba(44,130,181,.17); }
        .newsletter-ambient--two { bottom: -220px; left: -100px; width: 500px; height: 500px; border: 1px solid rgba(255,255,255,.09); }
        .newsletter-route { position: absolute; top: 12%; left: 43%; width: 220px; height: 100px; border-top: 1px dashed rgba(255,220,130,.42); border-radius: 50%; transform: rotate(12deg); }
        .newsletter-route span { position: absolute; top: -7px; left: 0; width: 12px; height: 12px; background: var(--newsletter-gold); border-radius: 50%; box-shadow: 0 0 0 7px rgba(245,184,46,.1); animation: newsletter-route 5s ease-in-out infinite; }
        .newsletter-reveal { opacity: 0; transform: translateY(28px); transition: opacity .75s ease var(--reveal-delay, 0ms), transform .75s cubic-bezier(.2,.7,.2,1) var(--reveal-delay, 0ms); }
        .newsletter-experience.is-visible .newsletter-reveal { opacity: 1; transform: translateY(0); }
        @keyframes newsletter-drift { from { transform: scale(1.02) translate3d(0,0,0); } to { transform: scale(1.09) translate3d(-1.5%, -1%, 0); } }
        @keyframes newsletter-route { 0%, 100% { left: 0; opacity: .35; } 50% { left: calc(100% - 12px); opacity: 1; } }
        @media (max-width: 1023px) {
            .newsletter-panel { grid-template-columns: 1fr; }
            .newsletter-copy { max-width: none; }
            .newsletter-visual { min-height: 460px; border-radius: 28px; }
            .newsletter-visual img { min-height: 460px; }
            .newsletter-visual::after { border-radius: 21px; }
            .newsletter-route { display: none; }
        }
        @media (max-width: 639px) {
            .newsletter-experience { padding: 68px 0; }
            .newsletter-copy h2 { font-size: 2.55rem; }
            .newsletter-signup-form { grid-template-columns: 1fr; }
            .newsletter-submit { width: 100%; }
            .newsletter-benefits { grid-template-columns: 1fr; }
            .newsletter-benefit { min-height: auto; }
            .newsletter-visual { min-height: 390px; }
            .newsletter-visual img { min-height: 390px; }
            .newsletter-stamp { top: 30px; right: 25px; width: 76px; height: 76px; }
            .newsletter-visual figcaption { left: 28px; bottom: 27px; }
        }
        @media (prefers-reduced-motion: reduce) {
            .newsletter-reveal { opacity: 1; transform: none; transition: none; }
            .newsletter-experience.is-visible .newsletter-visual img, .newsletter-route span { animation: none; }
            .newsletter-benefit, .newsletter-submit, .newsletter-visual img { transition: none; }
        }
    </style>


</div>

@push('scripts')
<script>
    document.addEventListener('livewire:initialized', () => {
        window.KiandaLocation.useIfAlreadyGranted('{{ $_instance->getId() }}', 'locationDenied');
    });
</script>
@endpush

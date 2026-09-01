@section('title', $hotel->name . ' — ' . ($hotel->location->name ?? 'Angola'))
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($hotel->description) ?: ('Reserve ' . $hotel->name . ' em ' . ($hotel->location->name ?? 'Angola') . '. Veja preços, fotos, comodidades e avaliações.'), 155))
@section('og_type', 'product')
@if($hotel->thumbnail)
    @section('meta_image', \App\Helpers\ImageHelper::getValidImage($hotel->thumbnail, 'hotel'))
@endif
@section('structured_data')
@php
    $hotelLd = array_filter([
        '@context' => 'https://schema.org',
        '@type' => 'Hotel',
        'name' => $hotel->name,
        'description' => \Illuminate\Support\Str::limit(strip_tags($hotel->description ?? ''), 300) ?: null,
        'image' => $hotel->thumbnail ? \App\Helpers\ImageHelper::getValidImage($hotel->thumbnail, 'hotel') : null,
        'url' => url()->current(),
        'telephone' => $hotel->phone ?: null,
        'email' => $hotel->email ?: null,
        'sameAs' => $hotel->website ?: null,
        'hasMap' => ($hotel->latitude && $hotel->longitude)
            ? ('https://www.google.com/maps/search/?api=1&query=' . $hotel->latitude . ',' . $hotel->longitude)
            : null,
        'checkinTime' => $hotel->check_in_time ?: null,
        'checkoutTime' => $hotel->check_out_time ?: null,
        'currenciesAccepted' => 'AOA',
        'priceRange' => $hotel->min_price ? ('AKZ ' . number_format($hotel->min_price, 0, ',', '.')) : null,
        'amenityFeature' => collect($hotel->amenities ?? [])->map(fn ($amenity) => [
            '@type' => 'LocationFeatureSpecification',
            'name' => $amenity,
            'value' => true,
        ])->values()->all() ?: null,
        'address' => array_filter([
            '@type' => 'PostalAddress',
            'streetAddress' => $hotel->address ?: null,
            'addressLocality' => $hotel->location->name ?? null,
            'addressRegion' => $hotel->location->province ?? null,
            'addressCountry' => 'AO',
        ]),
        'starRating' => $hotel->stars ? ['@type' => 'Rating', 'ratingValue' => (string) $hotel->stars] : null,
        'aggregateRating' => ($hotel->rating && $hotel->reviews_count)
            ? ['@type' => 'AggregateRating', 'ratingValue' => (string) $hotel->rating, 'reviewCount' => (string) $hotel->reviews_count]
            : null,
        'geo' => ($hotel->latitude && $hotel->longitude)
            ? ['@type' => 'GeoCoordinates', 'latitude' => (string) $hotel->latitude, 'longitude' => (string) $hotel->longitude]
            : null,
    ]);
@endphp
<script type="application/ld+json">
{!! json_encode($hotelLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => __('Início'), 'item' => route('home')],
        ['@type' => 'ListItem', 'position' => 2, 'name' => __('Hotéis'), 'item' => route('search.results')],
        ['@type' => 'ListItem', 'position' => 3, 'name' => $hotel->name, 'item' => url()->current()],
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endsection

@push('styles')
<style>
    .hospedaria-highlight-card {
        position: relative;
        isolation: isolate;
        overflow: hidden;
        background:
            radial-gradient(circle at 92% 14%, rgba(16, 185, 129, 0.22), transparent 28%),
            radial-gradient(circle at 8% 110%, rgba(245, 158, 11, 0.14), transparent 34%),
            linear-gradient(135deg, #ecfdf5 0%, #ffffff 52%, #f0fdfa 100%);
        border: 1px solid rgba(16, 185, 129, 0.4);
        box-shadow:
            0 22px 55px rgba(6, 95, 70, 0.13),
            0 4px 14px rgba(15, 23, 42, 0.06);
        transition: transform 280ms ease, box-shadow 280ms ease, border-color 280ms ease;
    }

    .hospedaria-highlight-card::before {
        content: '';
        position: absolute;
        inset: 0 0 auto;
        height: 4px;
        background: linear-gradient(90deg, #047857, #10b981 44%, #14b8a6 72%, #f59e0b);
        z-index: 2;
    }

    .hospedaria-highlight-card::after {
        content: '';
        position: absolute;
        width: 230px;
        height: 230px;
        top: -145px;
        right: -75px;
        border: 38px solid rgba(16, 185, 129, 0.1);
        border-radius: 9999px;
        pointer-events: none;
        z-index: 0;
    }

    .hospedaria-card-content {
        position: relative;
        z-index: 1;
    }

    .hospedaria-card-leaf {
        position: absolute;
        right: 2rem;
        bottom: -1.25rem;
        color: rgba(5, 150, 105, 0.09);
        font-size: 8rem;
        line-height: 1;
        transform: rotate(-14deg);
        pointer-events: none;
        z-index: 0;
    }

    .hospedaria-badge-enhanced {
        position: relative;
        display: inline-flex;
        align-items: center;
        gap: 0.55rem;
        padding: 0.42rem 0.8rem 0.42rem 0.45rem;
        color: #ffffff;
        background: linear-gradient(135deg, #047857 0%, #10b981 58%, #0d9488 100%);
        border: 1px solid rgba(255, 255, 255, 0.5);
        border-radius: 9999px;
        box-shadow: 0 8px 22px rgba(5, 150, 105, 0.28);
        overflow: hidden;
        transition: transform 220ms ease, box-shadow 220ms ease;
    }

    .hospedaria-badge-enhanced::after {
        content: '';
        position: absolute;
        top: -45%;
        left: -45%;
        width: 34%;
        height: 190%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.55), transparent);
        transform: rotate(18deg);
        pointer-events: none;
    }

    .hospedaria-badge-icon {
        display: inline-grid;
        width: 1.65rem;
        height: 1.65rem;
        place-items: center;
        flex: 0 0 auto;
        color: #047857;
        background: rgba(255, 255, 255, 0.95);
        border-radius: 9999px;
        box-shadow: 0 2px 7px rgba(6, 78, 59, 0.2);
    }

    .hospedaria-title-highlight {
        color: #052e2b;
        letter-spacing: -0.025em;
        text-wrap: balance;
    }

    .hospedaria-rating-pill,
    .hospedaria-location-pill {
        width: fit-content;
        background: rgba(255, 255, 255, 0.76);
        border: 1px solid rgba(16, 185, 129, 0.2);
        box-shadow: 0 5px 16px rgba(6, 95, 70, 0.07);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
    }

    .hospedaria-rating-pill {
        padding: 0.45rem 0.7rem;
        border-radius: 9999px;
    }

    .hospedaria-location-pill {
        max-width: 100%;
        padding: 0.65rem 0.8rem;
        border-radius: 0.85rem;
    }

    .hospedaria-highlight-card .hospedaria-location-pill i {
        color: #059669;
    }

    @media (hover: hover) {
        .hospedaria-highlight-card:hover {
            transform: translateY(-3px);
            border-color: rgba(5, 150, 105, 0.58);
            box-shadow:
                0 28px 65px rgba(6, 95, 70, 0.18),
                0 6px 18px rgba(15, 23, 42, 0.08);
        }

        .hospedaria-highlight-card:hover .hospedaria-badge-enhanced {
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 12px 28px rgba(5, 150, 105, 0.34);
        }
    }

    @media (prefers-reduced-motion: no-preference) {
        .hospedaria-highlight-card {
            animation: hospedaria-card-entrance 560ms cubic-bezier(0.2, 0.8, 0.2, 1) backwards;
        }

        .hospedaria-highlight-card::after {
            animation: hospedaria-orbit-float 6s ease-in-out infinite;
        }

        .hospedaria-card-leaf {
            animation: hospedaria-leaf-float 5.5s ease-in-out infinite;
        }

        .hospedaria-badge-enhanced::after {
            animation: hospedaria-badge-shine 4.8s ease-in-out infinite;
        }
    }

    @keyframes hospedaria-card-entrance {
        from { opacity: 0; transform: translateY(12px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes hospedaria-orbit-float {
        0%, 100% { transform: translate3d(0, 0, 0) rotate(0deg); }
        50% { transform: translate3d(-10px, 10px, 0) rotate(7deg); }
    }

    @keyframes hospedaria-leaf-float {
        0%, 100% { transform: translateY(0) rotate(-14deg); }
        50% { transform: translateY(-8px) rotate(-9deg); }
    }

    @keyframes hospedaria-badge-shine {
        0%, 62% { left: -45%; }
        82%, 100% { left: 125%; }
    }

    @media (max-width: 639px) {
        .hospedaria-card-leaf {
            right: -1rem;
            bottom: 1rem;
            font-size: 6.5rem;
        }

        .hospedaria-title-highlight {
            font-size: 2rem;
            line-height: 1.08;
        }

        .hospedaria-location-pill {
            width: 100%;
        }
    }

    .dark .hospedaria-highlight-card {
        background:
            radial-gradient(circle at 92% 14%, rgba(16, 185, 129, 0.19), transparent 30%),
            radial-gradient(circle at 8% 110%, rgba(245, 158, 11, 0.1), transparent 34%),
            linear-gradient(135deg, #10251f 0%, #111827 54%, #102927 100%);
        border-color: rgba(52, 211, 153, 0.34);
        box-shadow: 0 22px 55px rgba(0, 0, 0, 0.28);
    }

    .dark .hospedaria-title-highlight {
        color: #ecfdf5;
    }

    .dark .hospedaria-rating-pill,
    .dark .hospedaria-location-pill {
        color: #d1fae5;
        background: rgba(17, 24, 39, 0.72);
        border-color: rgba(52, 211, 153, 0.25);
    }

    .dark .hospedaria-rating-pill span,
    .dark .hospedaria-location-pill span {
        color: #d1fae5;
    }
</style>
@endpush

<div class="bg-gray-100 dark:bg-gray-900 min-h-screen" x-data="{
    showImageViewer: false,
    currentImage: '',
    currentIndex: 0,
    images: [],
    zoomLevel: 1,
    imageX: 0,
    imageY: 0,
    isDragging: false,
    startX: 0,
    startY: 0,

    openImageViewer(image, images, index = 0) {
        this.currentImage = image;
        this.images = images;
        this.currentIndex = index;
        this.showImageViewer = true;
        this.zoomLevel = 1;
        this.imageX = 0;
        this.imageY = 0;
    },

    closeImageViewer() {
        this.showImageViewer = false;
    },

    nextImage() {
        this.currentIndex = (this.currentIndex + 1) % this.images.length;
        this.currentImage = this.images[this.currentIndex];
        this.resetZoom();
    },

    prevImage() {
        this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length;
        this.currentImage = this.images[this.currentIndex];
        this.resetZoom();
    },

    zoomIn() {
        if (this.zoomLevel < 3) {
            this.zoomLevel += 0.5;
        }
    },

    zoomOut() {
        if (this.zoomLevel > 0.5) {
            this.zoomLevel -= 0.5;
            this.imageX = Math.max(Math.min(this.imageX, 0), -100);
            this.imageY = Math.max(Math.min(this.imageY, 0), -100);
        }
    },

    resetZoom() {
        this.zoomLevel = 1;
        this.imageX = 0;
        this.imageY = 0;
    },

    startDrag(e) {
        if (this.zoomLevel > 1) {
            this.isDragging = true;
            this.startX = e.clientX - this.imageX;
            this.startY = e.clientY - this.imageY;
        }
    },

    drag(e) {
        if (!this.isDragging) return;
        this.imageX = e.clientX - this.startX;
        this.imageY = e.clientY - this.startY;
    },

    stopDrag() {
        this.isDragging = false;
    }
}">
    @php
        // Função global para normalizar caminhos de imagens (definida uma única vez)
        if (!function_exists('normalizeImagePath')) {
            function normalizeImagePath($path) {
                if (!is_string($path)) return '';
                
                // Remove duplicação de 'storage/' no caminho
                $path = preg_replace('#^/+storage/+storage/#', 'storage/', $path);
                $path = preg_replace('#^/+storage/#', 'storage/', $path);
                
                // Garante que as imagens tenham caminho completo
                if (!empty($path) && !str_starts_with($path, 'http') && !str_starts_with($path, '/')) {
                    $path = '/' . $path;
                }
                
                return $path;
            }
        }
    @endphp
    <!-- Cabeçalho do hotel -->
    <div class="bg-white shadow-md">
        <div class="container mx-auto px-4 py-6">
            <!-- Navegação de volta -->
            <div class="mb-6">
                <a href="{{ url()->previous() }}" class="inline-flex items-center text-gray-600 hover:text-blue-600 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i> {{ __('Voltar aos resultados') }}
                </a>
            </div>
            
            <!-- Informações básicas do hotel -->
            <div class="{{ $propertyType === 'hospedaria' ? 'hospedaria-highlight-card rounded-2xl p-5 sm:p-6 lg:p-7 mb-6' : 'bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6' }}">
                @if($propertyType === 'hospedaria')
                    <span class="hospedaria-card-leaf" aria-hidden="true">
                        <i class="fas fa-leaf"></i>
                    </span>
                @endif

                <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6 {{ $propertyType === 'hospedaria' ? 'hospedaria-card-content' : '' }}">
                    <!-- Informações do hotel -->
                    <div class="flex-1">
                        <!-- Título e Favoritar -->
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1">
                                <!-- Badge de Tipo de Propriedade -->
                                @if($propertyType === 'resort')
                                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-gradient-to-r from-amber-500 to-orange-500 text-white rounded-full text-sm font-semibold mb-2">
                                        <i class="fas fa-umbrella-beach"></i>
                                        <span>{{ __('Resort de Luxo') }}</span>
                                    </div>
                                @elseif($propertyType === 'hospedaria')
                                    <div class="hospedaria-badge-enhanced text-sm font-semibold mb-3">
                                        <span class="hospedaria-badge-icon" aria-hidden="true">
                                            <i class="fas fa-home"></i>
                                        </span>
                                        <span>{{ __('Hospedaria') }}</span>
                                    </div>
                                @elseif($propertyType === 'residencial')
                                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-gradient-to-r from-purple-500 to-indigo-500 text-white rounded-full text-sm font-semibold mb-2">
                                        <i class="fas fa-building"></i>
                                        <span>{{ __('Residencial') }}</span>
                                    </div>
                                @else
                                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-full text-sm font-semibold mb-2">
                                        <i class="fas fa-hotel"></i>
                                        <span>{{ __('Hotel') }}</span>
                                    </div>
                                @endif
                                
                                <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-2 {{ $propertyType === 'hospedaria' ? 'hospedaria-title-highlight' : '' }}">
                                    {{ $hotel->name }}
                                    @if($propertyType === 'resort')
                                        <span class="text-amber-600">✨</span>
                                    @endif
                                </h1>
                                
                                <!-- Estrelas e Avaliações -->
                                <div class="flex items-center gap-4 mb-3">
                                    <div class="flex items-center {{ $propertyType === 'hospedaria' ? 'hospedaria-rating-pill' : '' }}">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="{{ $i <= $hotel->stars ? 'fas' : 'far' }} fa-star text-yellow-400 text-sm"></i>
                                        @endfor
                                        <span class="ml-2 text-sm font-medium text-gray-700">{{ $hotel->stars }} estrelas</span>
                                    </div>
                                </div>
                                
                                <!-- Endereço -->
                                <div class="flex items-start text-gray-600 {{ $propertyType === 'hospedaria' ? 'hospedaria-location-pill' : '' }}">
                                    <i class="fas fa-map-marker-alt mt-1 mr-2 text-blue-600"></i>
                                    <span class="text-sm">{{ collect([$hotel->address, $hotel->location?->name, $hotel->location?->province])->filter()->implode(', ') }}</span>
                                </div>
                            </div>
                            
                            <!-- Botões de Ação (Desktop) -->
                            <div class="hidden lg:flex gap-2 ml-4">
                                <button wire:click="addToCompare" wire:loading.attr="disabled" class="flex items-center gap-2 px-4 py-2 rounded-lg border-2 border-blue-300 text-blue-600 hover:bg-blue-50 transition-all disabled:opacity-50">
                                    <i class="fas fa-balance-scale" wire:loading.remove wire:target="addToCompare"></i>
                                    <i class="fas fa-spinner fa-spin" wire:loading wire:target="addToCompare"></i>
                                    <span class="font-medium" wire:loading.remove wire:target="addToCompare">{{ __('Comparar') }}</span>
                                </button>
                                <button wire:click="toggleFavorite" wire:loading.attr="disabled" class="flex items-center gap-2 px-4 py-2 rounded-lg border-2 transition-all disabled:opacity-50 {{ $isFavorited ? 'bg-red-50 border-red-500 text-red-600 hover:bg-red-100' : 'bg-white border-gray-300 text-gray-600 hover:border-red-500 hover:text-red-600' }}">
                                    <i class="{{ $isFavorited ? 'fas' : 'far' }} fa-heart" wire:loading.remove wire:target="toggleFavorite"></i>
                                    <i class="fas fa-spinner fa-spin" wire:loading wire:target="toggleFavorite"></i>
                                    <span class="font-medium" wire:loading.remove wire:target="toggleFavorite">{{ $isFavorited ? 'Remover' : 'Favoritar' }}</span>
                                    <span class="font-medium" wire:loading wire:target="toggleFavorite">{{ __('Processando...') }}</span>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Botões de Ação (Mobile) -->
                        <div class="lg:hidden mt-4 grid grid-cols-2 gap-2">
                            <button wire:click="addToCompare" wire:loading.attr="disabled" class="flex items-center justify-center gap-2 px-4 py-2 rounded-lg border-2 border-blue-300 text-blue-600 transition-all disabled:opacity-50">
                                <i class="fas fa-balance-scale" wire:loading.remove wire:target="addToCompare"></i>
                                <i class="fas fa-spinner fa-spin" wire:loading wire:target="addToCompare"></i>
                                <span class="font-medium" wire:loading.remove wire:target="addToCompare">{{ __('Comparar') }}</span>
                            </button>
                            <button wire:click="toggleFavorite" wire:loading.attr="disabled" class="flex items-center justify-center gap-2 px-4 py-2 rounded-lg border-2 transition-all disabled:opacity-50 {{ $isFavorited ? 'bg-red-50 border-red-500 text-red-600' : 'bg-white border-gray-300 text-gray-600' }}">
                                <i class="{{ $isFavorited ? 'fas' : 'far' }} fa-heart" wire:loading.remove wire:target="toggleFavorite"></i>
                                <i class="fas fa-spinner fa-spin" wire:loading wire:target="toggleFavorite"></i>
                                <span class="font-medium" wire:loading.remove wire:target="toggleFavorite">{{ $isFavorited ? 'Remover' : 'Favoritar' }}</span>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Card de Preço -->
                    <div class="lg:w-80 flex-shrink-0">
                        @if(count($roomTypes) > 0 && isset($roomTypes[0]['lowest_price']))
                            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-5 border-2 border-blue-200">
                                <div class="text-center">
                                    <p class="text-xs font-medium text-gray-600 uppercase tracking-wide mb-2">{{ __('A partir de') }}</p>
                                    <div class="text-4xl font-bold text-blue-600 mb-1">
                                        AKZ {{ number_format($roomTypes[0]['lowest_price'] / $nights, 0, ',', '.') }}
                                    </div>
                                    <p class="text-sm text-gray-600 mb-4">{{ __('por noite') }}</p>
                                    
                                    <div class="bg-white rounded-lg p-3 mb-4">
                                        <div class="flex items-center justify-between text-sm">
                                            <span class="text-gray-600">{{ $nights }} {{ $nights == 1 ? 'noite' : 'noites' }}</span>
                                            <span class="font-bold text-gray-900">AKZ {{ number_format($roomTypes[0]['lowest_price'], 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                    
                                    <a href="#rooms" class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg">
                                        {{ __('Ver Quartos Disponíveis') }}
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="bg-red-50 rounded-xl p-5 border-2 border-red-200">
                                <div class="text-center">
                                    <i class="fas fa-calendar-times text-red-400 text-3xl mb-3"></i>
                                    <h3 class="text-red-600 font-bold mb-2">{{ __('Sem Disponibilidade') }}</h3>
                                    <p class="text-gray-600 text-sm">Não há quartos disponíveis nas datas selecionadas. Tente outras datas.</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Galeria de fotos -->
            @php
                // Preparar todas as imagens para o visualizador
                $allHotelImages = [];
                $defaultPlaceholder = \App\Helpers\ImageHelper::getValidImage(null, 'hotel');
                
                // Adicionar thumbnail
                if (!empty($hotel->thumbnail)) {
                    $allHotelImages[] = \App\Helpers\ImageHelper::getValidImage($hotel->thumbnail, 'hotel');
                }
                
                // Adicionar imagem de destaque
                if (!empty($hotel->featured_image)) {
                    $featImg = \App\Helpers\ImageHelper::getValidImage($hotel->featured_image, 'hotel');
                    if (!in_array($featImg, $allHotelImages)) {
                        $allHotelImages[] = $featImg;
                    }
                }
                
                // Adicionar outras imagens da galeria
                $hotelImages = is_array($hotel->images) ? $hotel->images : json_decode($hotel->images ?? '[]');
                if (is_array($hotelImages)) {
                    foreach ($hotelImages as $img) {
                        if (!empty($img) && is_string($img)) {
                            $imageUrl = \App\Helpers\ImageHelper::getValidImage($img, 'hotel');
                            if (!in_array($imageUrl, $allHotelImages)) {
                                $allHotelImages[] = $imageUrl;
                            }
                        }
                    }
                }
                
                $hasImages = count($allHotelImages) > 0;
                $allHotelImagesJson = json_encode($allHotelImages);
                $featuredImageUrl = $allHotelImages[0] ?? $defaultPlaceholder;
            @endphp
            
            @if($hasImages)
            <div class="mt-6 grid grid-cols-4 grid-rows-2 gap-2 h-64 sm:h-80 lg:h-96">
                <!-- Imagem de destaque (dimensão fixa; a foto cabe inteira e o fundo é a própria foto desfocada) -->
                <div class="col-span-2 row-span-2 relative rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-800">
                    <div
                        class="w-full h-full cursor-pointer"
                        @click="openImageViewer('{{ $featuredImageUrl }}', {{ $allHotelImagesJson }}, 0)">
                        {{-- Fundo: mesma foto desfocada a cobrir a caixa (elimina o letterbox cinzento) --}}
                        <img src="{{ $featuredImageUrl }}" alt="" aria-hidden="true" loading="lazy"
                             class="absolute inset-0 w-full h-full object-cover"
                             style="filter: blur(18px) saturate(1.15) brightness(.92); transform: scale(1.15);"
                             onerror="this.style.display='none'">
                        <img src="{{ $featuredImageUrl }}" alt="{{ $hotel->name }}" loading="lazy" class="absolute inset-0 w-full h-full object-contain"
                             style="filter: drop-shadow(0 6px 18px rgba(0,0,0,.25));"
                             onerror="this.onerror=null; this.src='{{ $defaultPlaceholder }}'">
                        <div class="absolute inset-0 bg-black bg-opacity-10 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity">
                            <div class="bg-white bg-opacity-80 rounded-full p-4 shadow-lg">
                                <i class="fas fa-search-plus text-gray-800 text-2xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Galeria de imagens adicionais (até 4, dimensão fixa; foto inteira sobre fundo desfocado) -->
                @php $extraImages = array_slice($allHotelImages, 1, 4); @endphp
                @foreach($extraImages as $index => $imageUrl)
                    <div class="relative rounded-lg overflow-hidden cursor-pointer bg-gray-100 dark:bg-gray-800"
                         @click="openImageViewer('{{ $imageUrl }}', {{ $allHotelImagesJson }}, {{ $index + 1 }})">
                        {{-- Fundo: mesma foto desfocada a cobrir a caixa (elimina o letterbox cinzento) --}}
                        <img src="{{ $imageUrl }}" alt="" aria-hidden="true" loading="lazy"
                             class="absolute inset-0 w-full h-full object-cover"
                             style="filter: blur(14px) saturate(1.15) brightness(.92); transform: scale(1.15);"
                             onerror="this.style.display='none'">
                        <img src="{{ $imageUrl }}" alt="{{ $hotel->name }} - Imagem {{ $index + 1 }}" loading="lazy" class="absolute inset-0 w-full h-full object-contain"
                             style="filter: drop-shadow(0 4px 12px rgba(0,0,0,.25));"
                             onerror="this.onerror=null; this.src='{{ $defaultPlaceholder }}'">
                        <div class="absolute inset-0 bg-black bg-opacity-10 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity">
                            <i class="fas fa-search-plus text-white text-xl"></i>
                        </div>
                    </div>
                @endforeach

                {{-- Preenche as células que faltam para manter a grelha 4x2 estável --}}
                @for($i = count($extraImages); $i < 4; $i++)
                    <div class="relative rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                        <i class="fas fa-image text-gray-300 dark:text-gray-600 text-2xl"></i>
                    </div>
                @endfor
            </div>
            @else
            <!-- Placeholder quando não há imagens -->
            <div class="mt-6 rounded-xl overflow-hidden bg-gradient-to-br from-indigo-500 to-purple-600 h-64 flex flex-col items-center justify-center text-white">
                <div class="bg-white bg-opacity-20 rounded-full p-6 mb-4">
                    <i class="fas fa-camera text-4xl"></i>
                </div>
                <h3 class="text-lg font-semibold">{{ __('Sem imagens disponíveis') }}</h3>
                <p class="text-sm text-white text-opacity-80 mt-1">{{ __('Este hotel ainda não tem fotografias') }}</p>
            </div>
            @endif
            
            <!-- Navegação por tabs -->
            <div class="mt-8 border-b border-gray-200">
                <nav class="flex flex-nowrap -mb-px overflow-x-auto overscroll-x-contain" style="scrollbar-width:none">
                    <button 
                        wire:click="changeTab('info')" 
                        class="flex-none whitespace-nowrap py-3 px-4 sm:py-4 sm:px-6 font-medium {{ $activeTab == 'info' ? 'text-primary border-b-2 border-primary' : 'text-gray-500 hover:text-gray-700' }}"
                    >
                        <i class="fas fa-info-circle mr-2"></i> {{ __('Informações') }}
                    </button>
                    <button 
                        wire:click="changeTab('rooms')" 
                        class="flex-none whitespace-nowrap py-3 px-4 sm:py-4 sm:px-6 font-medium {{ $activeTab == 'rooms' ? 'text-primary border-b-2 border-primary' : 'text-gray-500 hover:text-gray-700' }}"
                    >
                        <i class="fas fa-bed mr-2"></i> {{ __('Quartos') }}
                    </button>
                    @if(isset($hotel->restaurantItems) && $hotel->restaurantItems->count() > 0)
                        <button 
                            wire:click="changeTab('restaurant')" 
                            class="flex-none whitespace-nowrap py-3 px-4 sm:py-4 sm:px-6 font-medium {{ $activeTab == 'restaurant' ? 'text-primary border-b-2 border-primary' : 'text-gray-500 hover:text-gray-700' }}"
                        >
                            <i class="fas fa-utensils mr-2"></i> {{ __('Restaurante') }}
                        </button>
                    @endif
                    @if(isset($hotel->leisureFacilities) && $hotel->leisureFacilities->count() > 0)
                        <button 
                            wire:click="changeTab('leisure')" 
                            class="flex-none whitespace-nowrap py-3 px-4 sm:py-4 sm:px-6 font-medium {{ $activeTab == 'leisure' ? 'text-primary border-b-2 border-primary' : 'text-gray-500 hover:text-gray-700' }}"
                        >
                            <i class="fas fa-swimming-pool mr-2"></i> {{ __('Lazer') }}
                        </button>
                    @endif
                    <button 
                        wire:click="changeTab('location')" 
                        class="flex-none whitespace-nowrap py-3 px-4 sm:py-4 sm:px-6 font-medium {{ $activeTab == 'location' ? 'text-primary border-b-2 border-primary' : 'text-gray-500 hover:text-gray-700' }}"
                    >
                        <i class="fas fa-map-marker-alt mr-2"></i> {{ __('Localização') }}
                    </button>
                    <button 
                        wire:click="changeTab('reviews')" 
                        class="flex-none whitespace-nowrap py-3 px-4 sm:py-4 sm:px-6 font-medium {{ $activeTab == 'reviews' ? 'text-primary border-b-2 border-primary' : 'text-gray-500 hover:text-gray-700' }}"
                    >
                        <i class="fas fa-star mr-2"></i> {{ __('Avaliações') }}
                    </button>
                </nav>
            </div>
        </div>
    </div>
    
    <!-- Conteúdo principal -->
    <div class="container mx-auto px-4 py-8">
        <div class="flex flex-wrap lg:flex-nowrap gap-8">
            <!-- Coluna principal (wire:key muda com a tab: o bloco é recriado e o fade replay-a) -->
            <div class="w-full lg:w-8/12 ks-fade-in" wire:key="tab-{{ $activeTab }}">
                <!-- Conteúdo baseado na tab ativa -->
                @if($activeTab == 'info')
                    <!-- Informações do hotel -->
                    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                        <h2 class="text-2xl font-bold mb-4">
                            @if($propertyType === 'resort')
                                Sobre o Resort
                            @elseif($propertyType === 'hospedaria')
                                Sobre a Hospedaria
                            @else
                                Sobre o Hotel
                            @endif
                        </h2>
                        <div class="prose max-w-none">
                            <p>{{ $hotel->description }}</p>
                        </div>
                        
                        <!-- Seção especial para Resorts -->
                        @if($propertyType === 'resort')
                            <div class="mt-8 bg-gradient-to-r from-amber-50 to-orange-50 rounded-xl p-6 border-2 border-amber-200">
                                <div class="flex items-center gap-3 mb-4">
                                    <i class="fas fa-crown text-3xl text-amber-600"></i>
                                    <h3 class="text-2xl font-bold text-gray-900">{{ __('Experiência Resort de Luxo') }}</h3>
                                </div>
                                <p class="text-gray-700 leading-relaxed mb-6">
                                    Desfrute de uma experiência completa com todas as comodidades de um resort de classe mundial. 
                                    Relaxe e deixe-se envolver pelo luxo, conforto e hospitalidade incomparável.
                                </p>
                                
                                <!-- Grid de Features Exclusivas de Resort -->
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                    <div class="bg-white rounded-lg p-4 shadow-sm">
                                        <div class="flex items-center gap-3 mb-2">
                                            <i class="fas fa-umbrella-beach text-2xl text-amber-600"></i>
                                            <h4 class="font-bold text-gray-900">{{ __('Área de Lazer') }}</h4>
                                        </div>
                                        <p class="text-sm text-gray-600">{{ __('Piscinas, jardins e áreas de relaxamento') }}</p>
                                    </div>
                                    
                                    <div class="bg-white rounded-lg p-4 shadow-sm">
                                        <div class="flex items-center gap-3 mb-2">
                                            <i class="fas fa-spa text-2xl text-teal-600"></i>
                                            <h4 class="font-bold text-gray-900">Spa & Wellness</h4>
                                        </div>
                                        <p class="text-sm text-gray-600">{{ __('Tratamentos e massagens exclusivas') }}</p>
                                    </div>
                                    
                                    <div class="bg-white rounded-lg p-4 shadow-sm">
                                        <div class="flex items-center gap-3 mb-2">
                                            <i class="fas fa-utensils text-2xl text-red-600"></i>
                                            <h4 class="font-bold text-gray-900">{{ __('Gastronomia') }}</h4>
                                        </div>
                                        <p class="text-sm text-gray-600">{{ __('Restaurantes e bares de alta cozinha') }}</p>
                                    </div>
                                    
                                    <div class="bg-white rounded-lg p-4 shadow-sm">
                                        <div class="flex items-center gap-3 mb-2">
                                            <i class="fas fa-dumbbell text-2xl text-blue-600"></i>
                                            <h4 class="font-bold text-gray-900">{{ __('Fitness & Desporto') }}</h4>
                                        </div>
                                        <p class="text-sm text-gray-600">{{ __('Ginásio, courts e atividades') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        <!-- Facilidades/Amenidades -->
                        <h3 class="text-xl font-bold mt-8 mb-4">
                            @if($propertyType === 'resort')
                                <i class="fas fa-star text-amber-500 mr-2"></i>Comodidades Premium
                            @else
                                Comodidades e serviços
                            @endif
                        </h3>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @php
                                // Tratamento seguro das amenidades para suportar diferentes formatos
                                $amenities = [];
                                if (is_string($hotel->amenities)) {
                                    // Se for string JSON, decodificar
                                    $amenities = json_decode($hotel->amenities) ?? [];
                                } elseif (is_array($hotel->amenities)) {
                                    // Se já for array, usar diretamente
                                    $amenities = $hotel->amenities;
                                }
                            @endphp
                            
                            @if(is_array($amenities) || is_object($amenities))
                                @foreach($amenities as $amenity)
                                    <div class="flex items-center">
                                        <i class="fas fa-check text-green-500 mr-2"></i>
                                        <span>{{ $amenity }}</span>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                @elseif($activeTab == 'rooms')
                    <!-- Quartos disponíveis -->
                    <div id="rooms" class="bg-white rounded-lg shadow-md p-4 sm:p-6 mb-8">
                        <div class="flex flex-col gap-4 mb-6 xl:flex-row xl:items-end xl:justify-between">
                            <h2 class="text-xl sm:text-2xl font-bold">{{ __('Quartos disponíveis') }}</h2>
                            
                            <!-- Formulário para alterar datas -->
                            <div class="grid w-full grid-cols-2 gap-3 xl:w-auto xl:grid-cols-[140px_140px_auto]">
                                <div class="min-w-0">
                                    <label for="check_in" class="block text-sm font-medium text-gray-700">{{ __('Check-in') }}</label>
                                    <input 
                                        type="date" 
                                        id="check_in" 
                                        wire:model="checkIn" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                                    >
                                </div>
                                <div class="min-w-0">
                                    <label for="check_out" class="block text-sm font-medium text-gray-700">{{ __('Check-out') }}</label>
                                    <input 
                                        type="date" 
                                        id="check_out" 
                                        wire:model="checkOut" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                                    >
                                </div>
                                <div class="col-span-2 xl:col-span-1 xl:pt-6">
                                    <button 
                                        wire:click="updateDates" 
                                        class="inline-flex min-h-[44px] w-full items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary"
                                    >
                                        <i class="fas fa-search mr-2"></i> {{ __('Atualizar') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Lista de quartos -->
                        @forelse($roomTypes as $room)
                            <div class="border border-gray-200 rounded-lg overflow-hidden mb-6 {{ $selectedRoomId == $room['id'] ? 'ring-2 ring-primary' : '' }}">
                                <div class="flex flex-col md:flex-row">
                                    <!-- Imagem do quarto -->
                                    <div class="w-full h-52 md:h-auto md:w-1/3">
                                        @php
                                            $roomImages = $room['images'] ?? [];
                                            $mainImage = '';
                                            $allImages = [];
                                            
                                            if (is_array($roomImages) && !empty($roomImages)) {
                                                // Converter todas as imagens para URLs completas
                                                foreach ($roomImages as $img) {
                                                    if (is_string($img)) {
                                                        $imageUrl = str_starts_with($img, 'http') 
                                                            ? $img 
                                                            : asset('storage/' . $img);
                                                        $allImages[] = $imageUrl;
                                                    }
                                                }
                                                
                                                // Define imagem principal como a primeira
                                                if (!empty($allImages)) {
                                                    $mainImage = $allImages[0];
                                                }
                                            }
                                            
                                            $allImagesJson = json_encode($allImages);
                                        @endphp
                                    
                                        @if($mainImage)
                                            <div 
                                                class="relative w-full h-full cursor-pointer" 
                                                @click="openImageViewer('{{ $mainImage }}', {{ $allImagesJson }}, 0)">
                                                <img src="{{ $mainImage }}" alt="{{ $room['name'] }}" class="w-full h-full object-cover">
                                                <div class="absolute inset-0 bg-black bg-opacity-10 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity">
                                                    <div class="bg-white bg-opacity-80 rounded-full p-3 shadow-md">
                                                        <i class="fas fa-search-plus text-gray-800 text-xl"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                                <i class="fas fa-bed text-gray-400 text-5xl"></i>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Informações do quarto -->
                                    <div class="w-full md:w-2/3 p-4 sm:p-6">
                                        <h3 class="text-xl font-bold text-gray-800">{{ $room['name'] }}</h3>
                                        
                                        <!-- Características do quarto -->
                                        <div class="flex flex-wrap gap-4 my-3">
                                            <div class="flex items-center text-sm text-gray-600">
                                                <i class="fas fa-user-friends mr-1"></i> {{ $room['capacity'] }} hóspedes
                                            </div>
                                            <div class="flex items-center text-sm text-gray-600">
                                                <i class="fas fa-bed mr-1"></i> {{ $room['beds'] }} {{ $room['beds'] == 1 ? 'cama' : 'camas' }} ({{ $room['bed_type'] }})
                                            </div>
                                            <div class="flex items-center text-sm text-gray-600">
                                                <i class="fas fa-expand-arrows-alt mr-1"></i> {{ $room['size'] }} m²
                                            </div>
                                        </div>
                                        
                                        <!-- Comodidades do quarto -->
                                        <div class="flex flex-wrap gap-2 my-3">
                                            @foreach(array_slice($room['amenities'], 0, 5) as $amenity)
                                                <span class="inline-block px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded-md">{{ $amenity }}</span>
                                            @endforeach
                                            @if(count($room['amenities']) > 5)
                                                <span class="inline-block px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded-md">+{{ count($room['amenities']) - 5 }} mais</span>
                                            @endif
                                        </div>
                                        
                                        <!-- Descrição curta -->
                                        <p class="text-gray-600 text-sm my-3 line-clamp-2">{{ $room['description'] }}</p>
                                        
                                        <!-- Badge especial para quartos de Resort -->
                                        @if($propertyType === 'resort')
                                            <div class="flex items-center gap-2 my-2">
                                                <span class="inline-flex items-center px-2 py-1 bg-amber-100 text-amber-800 text-xs font-semibold rounded">
                                                    <i class="fas fa-gem mr-1"></i>{{ __('Experiência Premium') }}
                                                </span>
                                            </div>
                                        @endif
                                        
                                        <!-- Ações -->
                                        <div class="flex flex-col gap-4 mt-4 sm:flex-row sm:items-end sm:justify-between">
                                            <!-- Preço -->
                                            <div>
                                                @if($room['is_available'])
                                                    @if($room['has_prices'])
                                                        <div class="text-2xl font-bold text-primary">AKZ {{ number_format($room['lowest_price'], 0, ',', '.') }}</div>
                                                        <div class="text-sm text-gray-600">por {{ $nights }} {{ $nights == 1 ? 'noite' : 'noites' }}</div>
                                                    @else
                                                        <div class="text-2xl font-bold text-primary">AKZ {{ number_format($room['base_price'] ?? 0, 0, ',', '.') }}</div>
                                                        <div class="text-sm text-gray-600">preço base por noite</div>
                                                    @endif
                                                @else
                                                    <div class="text-red-600">{{ __('Sem disponibilidade') }}</div>
                                                @endif
                                            </div>
                                            
                                            <!-- Botões -->
                                            <div class="grid grid-cols-2 gap-2 sm:flex">
                                                <button 
                                                    wire:click="selectRoom('{{ $room['id'] }}')" 
                                                    class="min-h-[44px] px-3 sm:px-4 py-2 border border-gray-300 rounded text-gray-700 hover:bg-gray-100 transition-colors"
                                                >
                                                    {{ __('Detalhes') }}
                                                </button>
                                                @if($room['is_available'])
                                                    <button
                                                        wire:click="bookRoom('{{ $room['id'] }}')" 
                                                        class="min-h-[44px] px-3 sm:px-4 py-2 bg-primary text-white rounded hover:bg-blue-700 transition-colors"
                                                    >
                                                        {{ __('Reservar agora') }}
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Detalhes expandidos do quarto -->
                                @if($selectedRoomId == $room['id'])
                                    <div class="border-t border-gray-200 p-6 bg-gray-50">
                                        <div class="flex flex-col gap-8">
                                            <!-- Galeria de fotos -->
                                            <div>
                                                <h4 class="font-bold text-lg mb-3">{{ __('Fotos do quarto') }}</h4>
                                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
                                                    @php
                                                        $galleryImages = $room['images'] ?? [];
                                                        $imageUrls = [];
                                                        
                                                        // Prepara URLs para o visualizador de imagens
                                                        if (is_array($galleryImages) && !empty($galleryImages)) {
                                                            foreach ($galleryImages as $img) {
                                                                if (is_string($img)) {
                                                                    $imageUrl = str_starts_with($img, 'http') 
                                                                        ? $img 
                                                                        : asset('storage/' . $img);
                                                                    $imageUrls[] = $imageUrl;
                                                                }
                                                            }
                                                        }
                                                        
                                                        $imageUrlsJson = json_encode($imageUrls);
                                                    @endphp
                                                    
                                                    @forelse($imageUrls as $index => $imageUrl)
                                                        <div 
                                                            class="relative rounded-md overflow-hidden cursor-pointer shadow-sm hover:shadow-md transition-shadow"
                                                            @click="openImageViewer('{{ $imageUrl }}', {{ $imageUrlsJson }}, {{ $index }})">
                                                            <img 
                                                                src="{{ $imageUrl }}" 
                                                                alt="{{ $room['name'] }}" 
                                                                class="w-full h-32 object-cover rounded-md"
                                                                onerror="this.src='{{ \App\Helpers\ImageHelper::getValidImage('', 'room') }}';"
                                                            >
                                                            <div class="absolute inset-0 bg-black bg-opacity-20 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity">
                                                                <i class="fas fa-search-plus text-white text-xl"></i>
                                                            </div>
                                                        </div>
                                                    @empty
                                                        <div class="col-span-full p-4 text-center text-gray-500">
                                                            <i class="fas fa-images mb-2 text-2xl"></i>
                                                            <p>{{ __('Sem imagens disponíveis') }}</p>
                                                        </div>
                                                    @endforelse
                                                </div>
                                            </div>
                                        
                                            <!-- Descrição completa e amenidades -->
                                            <div>
                                                <h4 class="font-bold text-lg mb-3">{{ __('Detalhes do quarto') }}</h4>
                                                <p class="text-gray-600 mb-4">{{ $room['description'] }}</p>
                                                
                                                <div class="flex flex-wrap gap-6 mb-6">
                                                    <div class="flex items-center">
                                                        <i class="fas fa-user-friends text-primary mr-2 text-lg"></i>
                                                        <div>
                                                            <div class="font-medium">{{ __('Capacidade') }}</div>
                                                            <div class="text-gray-600">{{ $room['capacity'] }} hóspedes</div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="flex items-center">
                                                        <i class="fas fa-bed text-primary mr-2 text-lg"></i>
                                                        <div>
                                                            <div class="font-medium">{{ __('Camas') }}</div>
                                                            <div class="text-gray-600">{{ $room['beds'] }} {{ $room['beds'] == 1 ? 'cama' : 'camas' }} ({{ $room['bed_type'] }})</div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="flex items-center">
                                                        <i class="fas fa-expand-arrows-alt text-primary mr-2 text-lg"></i>
                                                        <div>
                                                            <div class="font-medium">{{ __('Tamanho') }}</div>
                                                            <div class="text-gray-600">{{ $room['size'] }} m²</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <h4 class="font-bold text-lg mb-3">{{ __('Comodidades') }}</h4>
                                                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                                                    @foreach($room['amenities'] as $amenity)
                                                        <div class="flex items-center">
                                                            <i class="fas fa-check text-green-500 mr-2"></i>
                                                            <span class="text-gray-600">{{ $amenity }}</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-8 text-center">
                                <i class="fas fa-bed text-5xl text-gray-300 mb-4"></i>
                                <h3 class="text-xl font-bold text-gray-700 mb-2">{{ __('Nenhum quarto disponível') }}</h3>
                                <p class="text-gray-600">Não há quartos disponíveis para as datas selecionadas. Tente alterar as datas ou entrar em contato diretamente com o hotel.</p>
                            </div>
                        @endforelse
                    </div>
                @elseif($activeTab == 'restaurant')
                    <!-- Menu do Restaurante -->
                    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                        @if($propertyType === 'resort')
                            <!-- Header Premium para Resort -->
                            <div class="bg-gradient-to-r from-red-50 to-orange-50 rounded-xl p-6 mb-6 border-2 border-red-200">
                                <div class="flex items-center gap-3 mb-3">
                                    <i class="fas fa-concierge-bell text-3xl text-red-600"></i>
                                    <h2 class="text-2xl font-bold text-gray-900">{{ __('Gastronomia de Excelência') }}</h2>
                                </div>
                                <p class="text-gray-700">
                                    Delicie-se com nossa seleção gastronómica cuidadosamente elaborada pelos nossos chefs. 
                                    Uma experiência culinária inesquecível aguarda por si.
                                </p>
                            </div>
                        @else
                            <h2 class="text-2xl font-bold mb-6">{{ __('Menu do Restaurante') }}</h2>
                        @endif
                        
                        <h3 class="text-xl font-bold mb-4">
                            <i class="fas fa-utensils text-primary mr-2"></i> {{ __('Menu do Restaurante') }}
                        </h2>
                        
                        @if(isset($hotel->restaurantItems) && $hotel->restaurantItems->count() > 0)
                            @php
                                $groupedItems = $hotel->restaurantItems->groupBy('category');
                            @endphp
                        @else
                            @php
                                $groupedItems = collect();
                            @endphp
                        @endif
                        
                        @foreach($groupedItems as $category => $items)
                            <div class="mb-8">
                                <h3 class="text-xl font-semibold text-gray-800 mb-4 pb-2 border-b-2 border-primary">{{ $category }}</h3>
                                
                                <div class="grid gap-4">
                                    @foreach($items as $item)
                                        <div class="flex items-start justify-between p-4 border border-gray-200 rounded-lg hover:shadow-md transition-shadow">
                                            <div class="flex-1">
                                                <div class="flex items-start justify-between">
                                                    <div class="flex-1">
                                                        <h4 class="font-semibold text-gray-800 flex items-center gap-2">
                                                            {{ $item->name }}
                                                            @if($item->is_vegetarian)
                                                                <span class="text-xs bg-green-100 text-green-800 px-2 py-0.5 rounded-full" title="Vegetariano">
                                                                    <i class="fas fa-leaf"></i> Veg
                                                                </span>
                                                            @endif
                                                            @if($item->is_vegan)
                                                                <span class="text-xs bg-green-100 text-green-800 px-2 py-0.5 rounded-full" title="Vegano">
                                                                    <i class="fas fa-seedling"></i> Vegan
                                                                </span>
                                                            @endif
                                                            @if($item->is_gluten_free)
                                                                <span class="text-xs bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full" title="Sem Glúten">
                                                                    <i class="fas fa-wheat"></i> {{ __('S/Glúten') }}
                                                                </span>
                                                            @endif
                                                            @if($item->is_spicy)
                                                                <span class="text-xs bg-red-100 text-red-800 px-2 py-0.5 rounded-full" title="Picante">
                                                                    <i class="fas fa-pepper-hot"></i> {{ __('Picante') }}
                                                                </span>
                                                            @endif
                                                        </h4>
                                                        
                                                        @if($item->description)
                                                            <p class="text-sm text-gray-600 mt-1">{{ $item->description }}</p>
                                                        @endif
                                                        
                                                        <div class="flex items-center gap-4 mt-2 text-xs text-gray-500">
                                                            @if($item->preparation_time)
                                                                <span><i class="fas fa-clock mr-1"></i> {{ $item->preparation_time }} min</span>
                                                            @endif
                                                            @if($item->allergens && count($item->allergens) > 0)
                                                                <span class="text-orange-600">
                                                                    <i class="fas fa-exclamation-triangle mr-1"></i> 
                                                                    Alérgenos: {{ implode(', ', $item->allergens) }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="ml-4 text-right">
                                                        <p class="text-xl font-bold text-primary">{{ number_format($item->price, 2, ',', '.') }} Kz</p>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            @if($item->image)
                                                <div class="ml-4 w-24 h-24 rounded-lg overflow-hidden flex-shrink-0">
                                                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                        
                        <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                            <p class="text-sm text-gray-600">
                                <i class="fas fa-info-circle text-primary mr-2"></i>
                                Para reservas no restaurante ou informações adicionais, entre em contacto com a receção do hotel.
                            </p>
                        </div>
                    </div>
                @elseif($activeTab == 'leisure')
                    <!-- Instalações de Lazer -->
                    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                        @if($propertyType === 'resort')
                            <!-- Header Premium para Resort -->
                            <div class="bg-gradient-to-r from-blue-50 to-teal-50 rounded-xl p-6 mb-6 border-2 border-blue-200">
                                <div class="flex items-center gap-3 mb-3">
                                    <i class="fas fa-water text-3xl text-blue-600"></i>
                                    <h2 class="text-2xl font-bold text-gray-900">{{ __('Instalações de Lazer Premium') }}</h2>
                                </div>
                                <p class="text-gray-700">
                                    Explore nossas instalações de lazer de classe mundial. Desde piscinas deslumbrantes até áreas de fitness completas, 
                                    tudo foi pensado para proporcionar momentos inesquecíveis de relaxamento e diversão.
                                </p>
                            </div>
                        @else
                            <h2 class="text-2xl font-bold mb-6">{{ __('Instalações de Lazer') }}</h2>
                        @endif
                        
                        <h3 class="text-xl font-bold mb-4">
                            <i class="fas fa-swimming-pool text-primary mr-2"></i> {{ __('Instalações de Lazer') }}
                        </h2>
                        
                        <div class="grid md:grid-cols-2 gap-6">
                            @if(isset($hotel->leisureFacilities))
                                @foreach($hotel->leisureFacilities as $facility)
                                <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
                                    @if($facility->images && count($facility->images) > 0)
                                        <div class="h-48 overflow-hidden">
                                            <img src="{{ asset('storage/' . $facility->images[0]) }}" alt="{{ $facility->name }}" class="w-full h-full object-cover">
                                        </div>
                                    @else
                                        <div class="h-48 bg-gradient-to-br from-blue-100 to-blue-50 flex items-center justify-center">
                                            @php
                                                $icon = match($facility->type) {
                                                    'piscina' => 'fa-swimming-pool',
                                                    'spa' => 'fa-spa',
                                                    'ginasio' => 'fa-dumbbell',
                                                    'sauna' => 'fa-hot-tub',
                                                    'campo_tenis' => 'fa-tennis-ball',
                                                    'sala_jogos' => 'fa-gamepad',
                                                    'biblioteca' => 'fa-book',
                                                    'jardim' => 'fa-tree',
                                                    default => 'fa-star'
                                                };
                                            @endphp
                                            <i class="fas {{ $icon }} text-6xl text-blue-300"></i>
                                        </div>
                                    @endif
                                    
                                    <div class="p-4">
                                        <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $facility->name }}</h3>
                                        
                                        <div class="flex items-center gap-2 mb-3">
                                            @if($facility->is_free)
                                                <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full font-medium">
                                                    <i class="fas fa-check-circle mr-1"></i> {{ __('Grátis para hóspedes') }}
                                                </span>
                                            @else
                                                <div class="text-sm">
                                                    @if($facility->price_per_hour)
                                                        <span class="text-primary font-semibold">{{ number_format($facility->price_per_hour, 2, ',', '.') }} Kz/hora</span>
                                                    @endif
                                                    @if($facility->daily_price)
                                                        <span class="text-primary font-semibold">{{ number_format($facility->daily_price, 2, ',', '.') }} Kz/dia</span>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                        
                                        @if($facility->description)
                                            <p class="text-sm text-gray-600 mb-3">{{ $facility->description }}</p>
                                        @endif
                                        
                                        <div class="space-y-2 text-sm text-gray-600">
                                            @if($facility->opening_time && $facility->closing_time)
                                                <div class="flex items-center">
                                                    <i class="fas fa-clock w-5 text-gray-400"></i>
                                                    <span>{{ substr($facility->opening_time, 0, 5) }} - {{ substr($facility->closing_time, 0, 5) }}</span>
                                                </div>
                                            @endif
                                            
                                            @if($facility->capacity)
                                                <div class="flex items-center">
                                                    <i class="fas fa-users w-5 text-gray-400"></i>
                                                    <span>Capacidade: {{ $facility->capacity }} pessoas</span>
                                                </div>
                                            @endif
                                            
                                            @if($facility->location)
                                                <div class="flex items-center">
                                                    <i class="fas fa-map-marker-alt w-5 text-gray-400"></i>
                                                    <span>{{ $facility->location }}</span>
                                                </div>
                                            @endif
                                            
                                            @if($facility->requires_booking)
                                                <div class="flex items-center text-orange-600">
                                                    <i class="fas fa-calendar-check w-5"></i>
                                                    <span class="font-medium">{{ __('Reserva necessária') }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        @if($facility->rules)
                                            <div class="mt-3 pt-3 border-t border-gray-200">
                                                <p class="text-xs text-gray-500">
                                                    <i class="fas fa-info-circle mr-1"></i> {{ $facility->rules }}
                                                </p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                            @endif
                        </div>
                        
                        <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                            <p class="text-sm text-gray-600">
                                <i class="fas fa-info-circle text-primary mr-2"></i>
                                Para reservas ou mais informações sobre as instalações de lazer, contacte a receção do hotel.
                            </p>
                        </div>
                    </div>
                @elseif($activeTab == 'location')
                    <!-- Localização -->
                    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                        <h2 class="text-2xl font-bold mb-4">{{ __('Localização') }}</h2>
                        
                        <p class="text-gray-600 mb-4">
                            <i class="fas fa-map-marker-alt mr-2 text-primary"></i>
                            {{ $hotel->address }}, {{ $hotel->location->name }}, {{ $hotel->location->province }}
                        </p>
                        
                        <!-- Mapa -->
                        @if($hotel->latitude && $hotel->longitude)
                            <div class="rounded-lg overflow-hidden border border-gray-200 mb-3">
                                <iframe
                                    src="https://maps.google.com/maps?q={{ $hotel->latitude }},{{ $hotel->longitude }}&z=15&hl=pt&output=embed"
                                    class="w-full h-80 block"
                                    style="border:0"
                                    loading="lazy"
                                    referrerpolicy="no-referrer-when-downgrade"
                                    allowfullscreen
                                    title="{{ __('Mapa de') }} {{ $hotel->name }}"></iframe>
                            </div>
                            <div class="flex flex-wrap gap-2 mb-4">
                                <a href="https://www.google.com/maps/search/?api=1&query={{ $hotel->latitude }},{{ $hotel->longitude }}"
                                   target="_blank" rel="noopener"
                                   class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition">
                                    <i class="fas fa-map-marker-alt text-red-500"></i> {{ __('Abrir no Google Maps') }}
                                </a>
                                <a href="https://www.google.com/maps/dir/?api=1&destination={{ $hotel->latitude }},{{ $hotel->longitude }}"
                                   target="_blank" rel="noopener"
                                   class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition">
                                    <i class="fas fa-route text-primary"></i> {{ __('Como chegar') }}
                                </a>
                            </div>
                        @else
                            <div class="h-80 bg-gray-200 rounded-lg flex items-center justify-center mb-4">
                                <div class="text-center">
                                    <i class="fas fa-map-marked-alt text-5xl text-gray-400 mb-2"></i>
                                    <p>{{ __('Mapa estará disponível em breve') }}</p>
                                </div>
                            </div>
                        @endif
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                            <!-- Coordenadas -->
                            <div class="border border-gray-200 rounded-lg p-4">
                                <h3 class="font-bold mb-2">{{ __('Coordenadas') }}</h3>
                                <p class="text-gray-600">Latitude: {{ $hotel->latitude ?? 'N/A' }}</p>
                                <p class="text-gray-600">Longitude: {{ $hotel->longitude ?? 'N/A' }}</p>
                            </div>
                            
                            <!-- Sobre a região -->
                            <div class="border border-gray-200 rounded-lg p-4">
                                <h3 class="font-bold mb-2">Sobre {{ $hotel->location->name }}</h3>
                                <p class="text-gray-600">{{ $hotel->location->description ?? 'Descrição não disponível' }}</p>
                            </div>
                        </div>
                    </div>
                @elseif($activeTab == 'reviews')
                    <!-- Avaliações -->
                    @livewire('hotel-reviews', ['hotelId' => $hotel->id])
                @endif
            </div>
            
            <!-- Coluna lateral -->
            <div class="w-full lg:w-4/12">
                <!-- Reserva rápida -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6 sticky top-4">
                    <h2 class="text-xl font-bold mb-4">{{ __('Detalhes da reserva') }}</h2>
                    
                    <!-- Datas e hóspedes -->
                    <div class="mb-4">
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <span class="text-gray-600">{{ __('Check-in') }}</span>
                            <span class="font-medium">{{ \Carbon\Carbon::parse($checkIn)->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <span class="text-gray-600">{{ __('Check-out') }}</span>
                            <span class="font-medium">{{ \Carbon\Carbon::parse($checkOut)->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <span class="text-gray-600">{{ __('Duração') }}</span>
                            <span class="font-medium">{{ $nights }} {{ $nights == 1 ? 'noite' : 'noites' }}</span>
                        </div>
                        <div class="flex justify-between py-2">
                            <span class="text-gray-600">{{ __('Hóspedes') }}</span>
                            <span class="font-medium">{{ $guests }} {{ $guests == 1 ? 'hóspede' : 'hóspedes' }}</span>
                        </div>
                    </div>
                    
                    <!-- Melhor preço -->
                    @if(count($roomTypes) > 0 && isset($roomTypes[0]['lowest_price']))
                        <div class="mt-6 pt-4 border-t border-gray-200">
                            <div class="flex justify-between mb-2">
                                <span class="text-gray-600">{{ __('Melhor preço:') }}</span>
                                <div class="text-right">
                                    <div class="font-bold text-primary">AKZ {{ number_format($roomTypes[0]['lowest_price'], 0, ',', '.') }}</div>
                                    <div class="text-xs text-gray-500">por {{ $nights }} {{ $nights == 1 ? 'noite' : 'noites' }}</div>
                                </div>
                            </div>
                            <div class="text-sm text-gray-500 mb-4">
                                Através de {{ $roomTypes[0]['best_provider'] ?? 'Provedor não especificado' }}
                            </div>
                            <button 
                                wire:click="changeTab('rooms')" 
                                onclick="setTimeout(() => document.getElementById('rooms')?.scrollIntoView({behavior: 'smooth'}), 100)"
                                class="block w-full text-center bg-primary hover:bg-blue-700 text-white font-bold py-3 px-4 rounded transition duration-300 cursor-pointer"
                            >
                                {{ __('Ver quartos e preços') }}
                            </button>
                        </div>
                    @endif
                    
                    @php
                        // WhatsApp: normaliza o telefone do hotel para formato internacional (Angola +244)
                        $waDigits = preg_replace('/\D+/', '', (string) $hotel->phone);
                        if (str_starts_with($waDigits, '00')) { $waDigits = substr($waDigits, 2); }
                        if ($waDigits !== '' && !str_starts_with($waDigits, '244') && strlen($waDigits) === 9) { $waDigits = '244' . $waDigits; }
                        $waValid = strlen($waDigits) >= 11; // 244 + 9 dígitos
                        $propertyLabels = ['hotel' => 'o hotel', 'resort' => 'o resort', 'hospedaria' => 'a hospedaria', 'residencial' => 'o residencial', 'apartment' => 'o apartamento', 'house' => 'a casa'];
                        $waLabel = $propertyLabels[$hotel->property_type ?? 'hotel'] ?? 'o hotel';
                        $waText = rawurlencode(
                            'Olá! 👋 Encontrei ' . $waLabel . ' *' . $hotel->name . '* no KiandaStay e gostaria de mais informações sobre disponibilidade de '
                            . \Carbon\Carbon::parse($checkIn)->format('d/m/Y') . ' a ' . \Carbon\Carbon::parse($checkOut)->format('d/m/Y')
                            . ' para ' . $guests . ' ' . ($guests == 1 ? 'hóspede' : 'hóspedes') . '. '
                            . route('hotel.details', $hotel->slug)
                        );
                    @endphp
                    @if($waValid)
                        <a href="https://wa.me/{{ $waDigits }}?text={{ $waText }}" target="_blank" rel="noopener"
                           class="mt-4 flex items-center justify-center gap-2 w-full text-white font-bold py-3 px-4 rounded-lg transition duration-300 shadow-sm hover:shadow-md"
                           style="background-color:#25D366" onmouseover="this.style.backgroundColor='#1EBE5D'" onmouseout="this.style.backgroundColor='#25D366'">
                            <i class="fab fa-whatsapp text-2xl"></i>
                            <span>{{ __('Conversar no WhatsApp') }}</span>
                        </a>
                        <p class="text-xs text-gray-400 text-center mt-1.5">{{ __('Resposta rápida — mencione o KiandaStay') }}</p>
                    @endif

                    <!-- Contato do hotel -->
                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <h3 class="font-bold mb-2">{{ __('Contato') }}</h3>
                        @if($hotel->phone)
                            <div class="flex items-center mb-2">
                                <i class="fas fa-phone-alt text-gray-500 mr-2"></i>
                                <a href="tel:{{ $hotel->phone }}" class="text-primary hover:underline">{{ $hotel->phone }}</a>
                            </div>
                            @if($waValid)
                                <div class="flex items-center mb-2">
                                    <i class="fab fa-whatsapp text-green-500 mr-2"></i>
                                    <a href="https://wa.me/{{ $waDigits }}?text={{ $waText }}" target="_blank" rel="noopener" class="text-primary hover:underline">WhatsApp</a>
                                </div>
                            @endif
                        @endif
                        @if($hotel->email)
                            <div class="flex items-center mb-2">
                                <i class="fas fa-envelope text-gray-500 mr-2"></i>
                                <a href="mailto:{{ $hotel->email }}" class="text-primary hover:underline">{{ $hotel->email }}</a>
                            </div>
                        @endif
                        @if($hotel->website)
                            <div class="flex items-center">
                                <i class="fas fa-globe text-gray-500 mr-2"></i>
                                <a href="{{ $hotel->website }}" target="_blank" class="text-primary hover:underline">{{ __('Website oficial') }}</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Visualizador de imagens em tela cheia -->
    <div x-show="showImageViewer" x-cloak
         class="fixed inset-0 z-50 bg-black bg-opacity-90 flex items-center justify-center"
         x-on:keydown.escape.window="closeImageViewer()"
         x-on:mouseup="stopDrag()">
        
        <!-- Área de controles superiores -->
        <div class="absolute top-0 left-0 right-0 p-4 flex justify-between items-center text-white">
            <!-- Índice da imagem -->
            <div class="text-lg">
                <span x-text="currentIndex + 1"></span>/<span x-text="images.length"></span>
            </div>
            
            <!-- Botões de zoom -->
            <div class="space-x-4">
                <button @click="zoomOut()" class="p-2 hover:bg-gray-800 rounded-full">
                    <i class="fas fa-search-minus"></i>
                </button>
                <button @click="resetZoom()" class="p-2 hover:bg-gray-800 rounded-full">
                    <i class="fas fa-sync-alt"></i>
                </button>
                <button @click="zoomIn()" class="p-2 hover:bg-gray-800 rounded-full">
                    <i class="fas fa-search-plus"></i>
                </button>
                <button @click="closeImageViewer()" class="p-2 hover:bg-gray-800 rounded-full">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        
        <!-- Botão de navegação esquerda -->
        <button 
            class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-gray-800 bg-opacity-50 hover:bg-opacity-70 text-white p-3 rounded-full"
            @click="prevImage()">
            <i class="fas fa-chevron-left text-2xl"></i>
        </button>
        
        <!-- Imagem com zoom e arrastar -->
        <div class="w-full h-full flex items-center justify-center overflow-hidden">
            <template x-if="currentImage">
                <img
                    :src="currentImage"
                    alt="{{ __('Imagem ampliada de :hotel', ['hotel' => $hotel->name]) }}"
                    class="max-h-screen transition-transform cursor-move"
                    :style="`transform: translate(${imageX}px, ${imageY}px) scale(${zoomLevel})`"
                    @mousedown="startDrag($event)"
                    @mousemove="drag($event)"
                    @mouseup="stopDrag()"
                    @mouseleave="stopDrag()"
                    draggable="false"
                />
            </template>
        </div>
        
        <!-- Botão de navegação direita -->
        <button 
            class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-gray-800 bg-opacity-50 hover:bg-opacity-70 text-white p-3 rounded-full"
            @click="nextImage()">
            <i class="fas fa-chevron-right text-2xl"></i>
        </button>
    </div>
</div>

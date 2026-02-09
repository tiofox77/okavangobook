<div class="bg-gray-100 min-h-screen" x-data="{
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
    @endphp
    <!-- Cabeçalho do hotel -->
    <div class="bg-white shadow-md">
        <div class="container mx-auto px-4 py-6">
            <!-- Navegação de volta -->
            <div class="mb-6">
                <a href="{{ url()->previous() }}" class="inline-flex items-center text-gray-600 hover:text-blue-600 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i> Voltar aos resultados
                </a>
            </div>
            
            <!-- Informações básicas do hotel -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
                <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
                    <!-- Informações do hotel -->
                    <div class="flex-1">
                        <!-- Título e Favoritar -->
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1">
                                <!-- Badge de Tipo de Propriedade -->
                                @if($propertyType === 'resort')
                                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-gradient-to-r from-amber-500 to-orange-500 text-white rounded-full text-sm font-semibold mb-2">
                                        <i class="fas fa-umbrella-beach"></i>
                                        <span>Resort de Luxo</span>
                                    </div>
                                @elseif($propertyType === 'hospedaria')
                                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-gradient-to-r from-green-500 to-teal-500 text-white rounded-full text-sm font-semibold mb-2">
                                        <i class="fas fa-home"></i>
                                        <span>Hospedaria</span>
                                    </div>
                                @else
                                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-full text-sm font-semibold mb-2">
                                        <i class="fas fa-hotel"></i>
                                        <span>Hotel</span>
                                    </div>
                                @endif
                                
                                <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-2">
                                    {{ $hotel->name }}
                                    @if($propertyType === 'resort')
                                        <span class="text-amber-600">✨</span>
                                    @endif
                                </h1>
                                
                                <!-- Estrelas e Avaliações -->
                                <div class="flex items-center gap-4 mb-3">
                                    <div class="flex items-center">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="{{ $i <= $hotel->stars ? 'fas' : 'far' }} fa-star text-yellow-400 text-sm"></i>
                                        @endfor
                                        <span class="ml-2 text-sm font-medium text-gray-700">{{ $hotel->stars }} estrelas</span>
                                    </div>
                                </div>
                                
                                <!-- Endereço -->
                                <div class="flex items-start text-gray-600">
                                    <i class="fas fa-map-marker-alt mt-1 mr-2 text-blue-600"></i>
                                    <span class="text-sm">{{ $hotel->address }}, {{ $hotel->location->name }}, {{ $hotel->location->province }}</span>
                                </div>
                            </div>
                            
                            <!-- Botões de Ação (Desktop) -->
                            <div class="hidden lg:flex gap-2 ml-4">
                                <button wire:click="addToCompare" wire:loading.attr="disabled" class="flex items-center gap-2 px-4 py-2 rounded-lg border-2 border-blue-300 text-blue-600 hover:bg-blue-50 transition-all disabled:opacity-50">
                                    <i class="fas fa-balance-scale" wire:loading.remove wire:target="addToCompare"></i>
                                    <i class="fas fa-spinner fa-spin" wire:loading wire:target="addToCompare"></i>
                                    <span class="font-medium" wire:loading.remove wire:target="addToCompare">Comparar</span>
                                </button>
                                <button wire:click="toggleFavorite" wire:loading.attr="disabled" class="flex items-center gap-2 px-4 py-2 rounded-lg border-2 transition-all disabled:opacity-50 {{ $isFavorited ? 'bg-red-50 border-red-500 text-red-600 hover:bg-red-100' : 'bg-white border-gray-300 text-gray-600 hover:border-red-500 hover:text-red-600' }}">
                                    <i class="{{ $isFavorited ? 'fas' : 'far' }} fa-heart" wire:loading.remove wire:target="toggleFavorite"></i>
                                    <i class="fas fa-spinner fa-spin" wire:loading wire:target="toggleFavorite"></i>
                                    <span class="font-medium" wire:loading.remove wire:target="toggleFavorite">{{ $isFavorited ? 'Remover' : 'Favoritar' }}</span>
                                    <span class="font-medium" wire:loading wire:target="toggleFavorite">Processando...</span>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Botões de Ação (Mobile) -->
                        <div class="lg:hidden mt-4 grid grid-cols-2 gap-2">
                            <button wire:click="addToCompare" wire:loading.attr="disabled" class="flex items-center justify-center gap-2 px-4 py-2 rounded-lg border-2 border-blue-300 text-blue-600 transition-all disabled:opacity-50">
                                <i class="fas fa-balance-scale" wire:loading.remove wire:target="addToCompare"></i>
                                <i class="fas fa-spinner fa-spin" wire:loading wire:target="addToCompare"></i>
                                <span class="font-medium" wire:loading.remove wire:target="addToCompare">Comparar</span>
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
                                    <p class="text-xs font-medium text-gray-600 uppercase tracking-wide mb-2">A partir de</p>
                                    <div class="text-4xl font-bold text-blue-600 mb-1">
                                        AKZ {{ number_format($roomTypes[0]['lowest_price'] / $nights, 0, ',', '.') }}
                                    </div>
                                    <p class="text-sm text-gray-600 mb-4">por noite</p>
                                    
                                    <div class="bg-white rounded-lg p-3 mb-4">
                                        <div class="flex items-center justify-between text-sm">
                                            <span class="text-gray-600">{{ $nights }} {{ $nights == 1 ? 'noite' : 'noites' }}</span>
                                            <span class="font-bold text-gray-900">AKZ {{ number_format($roomTypes[0]['lowest_price'], 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                    
                                    <a href="#rooms" class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg">
                                        Ver Quartos Disponíveis
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="bg-red-50 rounded-xl p-5 border-2 border-red-200">
                                <div class="text-center">
                                    <i class="fas fa-calendar-times text-red-400 text-3xl mb-3"></i>
                                    <h3 class="text-red-600 font-bold mb-2">Sem Disponibilidade</h3>
                                    <p class="text-gray-600 text-sm">Não há quartos disponíveis nas datas selecionadas. Tente outras datas.</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Galeria de fotos -->
            <div class="mt-6 grid grid-cols-4 gap-2">
                @php
                    // Preparar todas as imagens para o visualizador
                    $allHotelImages = [];
                    
                    // Adicionar imagem de destaque
                    if (!empty($hotel->featured_image)) {
                        $allHotelImages[] = str_starts_with($hotel->featured_image, 'http') 
                            ? $hotel->featured_image 
                            : asset('storage/' . $hotel->featured_image);
                    }
                    
                    // Adicionar outras imagens da galeria
                    $hotelImages = is_array($hotel->images) ? $hotel->images : json_decode($hotel->images ?? '[]');
                    if (is_array($hotelImages)) {
                        foreach ($hotelImages as $img) {
                            if (!empty($img) && is_string($img)) {
                                $imageUrl = str_starts_with($img, 'http') ? $img : asset('storage/' . $img);
                                $allHotelImages[] = $imageUrl;
                            }
                        }
                    }
                    
                    $allHotelImagesJson = json_encode($allHotelImages);
                @endphp
                
                <!-- Imagem de destaque -->
                <div class="col-span-2 row-span-2 relative rounded-lg overflow-hidden">
                    @php
                        $featuredImageUrl = !empty($hotel->featured_image) 
                            ? (str_starts_with($hotel->featured_image, 'http') 
                                ? $hotel->featured_image 
                                : asset('storage/' . $hotel->featured_image))
                            : (isset($allHotelImages[0]) 
                                ? (str_starts_with($allHotelImages[0], 'http') 
                                    ? $allHotelImages[0] 
                                    : asset('storage/' . $allHotelImages[0]))
                                : '');
                    @endphp
                    <div 
                        class="w-full h-full cursor-pointer" 
                        @click="openImageViewer('{{ $featuredImageUrl }}', {{ $allHotelImagesJson }}, 0)">
                        <img src="{{ $featuredImageUrl }}" alt="{{ $hotel->name }}" class="w-full h-full object-cover rounded-lg">
                        <div class="absolute inset-0 bg-black bg-opacity-10 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity">
                            <div class="bg-white bg-opacity-80 rounded-full p-4 shadow-lg">
                                <i class="fas fa-search-plus text-gray-800 text-2xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Galeria de imagens adicionais -->
                @if(is_array($hotelImages))
                    @foreach(array_slice($hotelImages, 0, 4) as $index => $image)
                        @php
                            $imageUrl = str_starts_with($image, 'http') ? $image : asset('storage/' . $image);
                        @endphp
                        <div class="relative rounded-lg overflow-hidden">
                            <div 
                                class="w-full h-full cursor-pointer" 
                                @click="openImageViewer('{{ $imageUrl }}', {{ $allHotelImagesJson }}, {{ $index + 1 }})">
                                <img src="{{ $imageUrl }}" alt="{{ $hotel->name }} - Imagem {{ $index + 1 }}" class="w-full h-full object-cover rounded-lg">
                                <div class="absolute inset-0 bg-black bg-opacity-10 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity">
                                    <i class="fas fa-search-plus text-white text-xl"></i>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            
            <!-- Navegação por tabs -->
            <div class="mt-8 border-b border-gray-200">
                <nav class="flex flex-wrap -mb-px">
                    <button 
                        wire:click="changeTab('info')" 
                        class="py-4 px-6 font-medium {{ $activeTab == 'info' ? 'text-primary border-b-2 border-primary' : 'text-gray-500 hover:text-gray-700' }}"
                    >
                        <i class="fas fa-info-circle mr-2"></i> Informações
                    </button>
                    <button 
                        wire:click="changeTab('rooms')" 
                        class="py-4 px-6 font-medium {{ $activeTab == 'rooms' ? 'text-primary border-b-2 border-primary' : 'text-gray-500 hover:text-gray-700' }}"
                    >
                        <i class="fas fa-bed mr-2"></i> Quartos
                    </button>
                    @if(isset($hotel->restaurantItems) && $hotel->restaurantItems->count() > 0)
                        <button 
                            wire:click="changeTab('restaurant')" 
                            class="py-4 px-6 font-medium {{ $activeTab == 'restaurant' ? 'text-primary border-b-2 border-primary' : 'text-gray-500 hover:text-gray-700' }}"
                        >
                            <i class="fas fa-utensils mr-2"></i> Restaurante
                        </button>
                    @endif
                    @if(isset($hotel->leisureFacilities) && $hotel->leisureFacilities->count() > 0)
                        <button 
                            wire:click="changeTab('leisure')" 
                            class="py-4 px-6 font-medium {{ $activeTab == 'leisure' ? 'text-primary border-b-2 border-primary' : 'text-gray-500 hover:text-gray-700' }}"
                        >
                            <i class="fas fa-swimming-pool mr-2"></i> Lazer
                        </button>
                    @endif
                    <button 
                        wire:click="changeTab('location')" 
                        class="py-4 px-6 font-medium {{ $activeTab == 'location' ? 'text-primary border-b-2 border-primary' : 'text-gray-500 hover:text-gray-700' }}"
                    >
                        <i class="fas fa-map-marker-alt mr-2"></i> Localização
                    </button>
                    <button 
                        wire:click="changeTab('reviews')" 
                        class="py-4 px-6 font-medium {{ $activeTab == 'reviews' ? 'text-primary border-b-2 border-primary' : 'text-gray-500 hover:text-gray-700' }}"
                    >
                        <i class="fas fa-star mr-2"></i> Avaliações
                    </button>
                </nav>
            </div>
        </div>
    </div>
    
    <!-- Conteúdo principal -->
    <div class="container mx-auto px-4 py-8">
        <div class="flex flex-wrap lg:flex-nowrap gap-8">
            <!-- Coluna principal -->
            <div class="w-full lg:w-8/12">
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
                                    <h3 class="text-2xl font-bold text-gray-900">Experiência Resort de Luxo</h3>
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
                                            <h4 class="font-bold text-gray-900">Área de Lazer</h4>
                                        </div>
                                        <p class="text-sm text-gray-600">Piscinas, jardins e áreas de relaxamento</p>
                                    </div>
                                    
                                    <div class="bg-white rounded-lg p-4 shadow-sm">
                                        <div class="flex items-center gap-3 mb-2">
                                            <i class="fas fa-spa text-2xl text-teal-600"></i>
                                            <h4 class="font-bold text-gray-900">Spa & Wellness</h4>
                                        </div>
                                        <p class="text-sm text-gray-600">Tratamentos e massagens exclusivas</p>
                                    </div>
                                    
                                    <div class="bg-white rounded-lg p-4 shadow-sm">
                                        <div class="flex items-center gap-3 mb-2">
                                            <i class="fas fa-utensils text-2xl text-red-600"></i>
                                            <h4 class="font-bold text-gray-900">Gastronomia</h4>
                                        </div>
                                        <p class="text-sm text-gray-600">Restaurantes e bares de alta cozinha</p>
                                    </div>
                                    
                                    <div class="bg-white rounded-lg p-4 shadow-sm">
                                        <div class="flex items-center gap-3 mb-2">
                                            <i class="fas fa-dumbbell text-2xl text-blue-600"></i>
                                            <h4 class="font-bold text-gray-900">Fitness & Desporto</h4>
                                        </div>
                                        <p class="text-sm text-gray-600">Ginásio, courts e atividades</p>
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
                    <div id="rooms" class="bg-white rounded-lg shadow-md p-6 mb-8">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold">Quartos disponíveis</h2>
                            
                            <!-- Formulário para alterar datas -->
                            <div class="flex items-center space-x-2">
                                <div>
                                    <label for="check_in" class="block text-sm font-medium text-gray-700">Check-in</label>
                                    <input 
                                        type="date" 
                                        id="check_in" 
                                        wire:model="checkIn" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                                    >
                                </div>
                                <div>
                                    <label for="check_out" class="block text-sm font-medium text-gray-700">Check-out</label>
                                    <input 
                                        type="date" 
                                        id="check_out" 
                                        wire:model="checkOut" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                                    >
                                </div>
                                <div class="pt-6">
                                    <button 
                                        wire:click="updateDates" 
                                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary"
                                    >
                                        <i class="fas fa-search mr-2"></i> Atualizar
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Lista de quartos -->
                        @forelse($roomTypes as $room)
                            <div class="border border-gray-200 rounded-lg overflow-hidden mb-6 {{ $selectedRoomId == $room['id'] ? 'ring-2 ring-primary' : '' }}">
                                <div class="flex flex-col md:flex-row">
                                    <!-- Imagem do quarto -->
                                    <div class="w-full md:w-1/3">
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
                                    <div class="w-full md:w-2/3 p-6">
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
                                                    <i class="fas fa-gem mr-1"></i>Experiência Premium
                                                </span>
                                            </div>
                                        @endif
                                        
                                        <!-- Ações -->
                                        <div class="flex justify-between items-end mt-4">
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
                                                    <div class="text-red-600">Sem disponibilidade</div>
                                                @endif
                                            </div>
                                            
                                            <!-- Botões -->
                                            <div class="space-x-2">
                                                <button 
                                                    wire:click="selectRoom('{{ $room['id'] }}')" 
                                                    class="px-4 py-2 border border-gray-300 rounded text-gray-700 hover:bg-gray-100 transition-colors"
                                                >
                                                    Detalhes
                                                </button>
                                                @if($room['is_available'])
                                                    <button
                                                        wire:click="bookRoom('{{ $room['id'] }}')" 
                                                        class="px-4 py-2 bg-primary text-white rounded hover:bg-blue-700 transition-colors"
                                                    >
                                                        Reservar agora
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
                                                <h4 class="font-bold text-lg mb-3">Fotos do quarto</h4>
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
                                                            <p>Sem imagens disponíveis</p>
                                                        </div>
                                                    @endforelse
                                                </div>
                                            </div>
                                        
                                            <!-- Descrição completa e amenidades -->
                                            <div>
                                                <h4 class="font-bold text-lg mb-3">Detalhes do quarto</h4>
                                                <p class="text-gray-600 mb-4">{{ $room['description'] }}</p>
                                                
                                                <div class="flex flex-wrap gap-6 mb-6">
                                                    <div class="flex items-center">
                                                        <i class="fas fa-user-friends text-primary mr-2 text-lg"></i>
                                                        <div>
                                                            <div class="font-medium">Capacidade</div>
                                                            <div class="text-gray-600">{{ $room['capacity'] }} hóspedes</div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="flex items-center">
                                                        <i class="fas fa-bed text-primary mr-2 text-lg"></i>
                                                        <div>
                                                            <div class="font-medium">Camas</div>
                                                            <div class="text-gray-600">{{ $room['beds'] }} {{ $room['beds'] == 1 ? 'cama' : 'camas' }} ({{ $room['bed_type'] }})</div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="flex items-center">
                                                        <i class="fas fa-expand-arrows-alt text-primary mr-2 text-lg"></i>
                                                        <div>
                                                            <div class="font-medium">Tamanho</div>
                                                            <div class="text-gray-600">{{ $room['size'] }} m²</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <h4 class="font-bold text-lg mb-3">Comodidades</h4>
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
                                <h3 class="text-xl font-bold text-gray-700 mb-2">Nenhum quarto disponível</h3>
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
                                    <h2 class="text-2xl font-bold text-gray-900">Gastronomia de Excelência</h2>
                                </div>
                                <p class="text-gray-700">
                                    Delicie-se com nossa seleção gastronómica cuidadosamente elaborada pelos nossos chefs. 
                                    Uma experiência culinária inesquecível aguarda por si.
                                </p>
                            </div>
                        @else
                            <h2 class="text-2xl font-bold mb-6">Menu do Restaurante</h2>
                        @endif
                        
                        <h3 class="text-xl font-bold mb-4">
                            <i class="fas fa-utensils text-primary mr-2"></i> Menu do Restaurante
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
                                                                    <i class="fas fa-wheat"></i> S/Glúten
                                                                </span>
                                                            @endif
                                                            @if($item->is_spicy)
                                                                <span class="text-xs bg-red-100 text-red-800 px-2 py-0.5 rounded-full" title="Picante">
                                                                    <i class="fas fa-pepper-hot"></i> Picante
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
                                    <h2 class="text-2xl font-bold text-gray-900">Instalações de Lazer Premium</h2>
                                </div>
                                <p class="text-gray-700">
                                    Explore nossas instalações de lazer de classe mundial. Desde piscinas deslumbrantes até áreas de fitness completas, 
                                    tudo foi pensado para proporcionar momentos inesquecíveis de relaxamento e diversão.
                                </p>
                            </div>
                        @else
                            <h2 class="text-2xl font-bold mb-6">Instalações de Lazer</h2>
                        @endif
                        
                        <h3 class="text-xl font-bold mb-4">
                            <i class="fas fa-swimming-pool text-primary mr-2"></i> Instalações de Lazer
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
                                                    <i class="fas fa-check-circle mr-1"></i> Grátis para hóspedes
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
                                                    <span class="font-medium">Reserva necessária</span>
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
                        <h2 class="text-2xl font-bold mb-4">Localização</h2>
                        
                        <p class="text-gray-600 mb-4">
                            <i class="fas fa-map-marker-alt mr-2 text-primary"></i>
                            {{ $hotel->address }}, {{ $hotel->location->name }}, {{ $hotel->location->province }}
                        </p>
                        
                        <!-- Mapa (placeholder) -->
                        <div class="h-80 bg-gray-200 rounded-lg flex items-center justify-center mb-4">
                            <div class="text-center">
                                <i class="fas fa-map-marked-alt text-5xl text-gray-400 mb-2"></i>
                                <p>Mapa estará disponível em breve</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                            <!-- Coordenadas -->
                            <div class="border border-gray-200 rounded-lg p-4">
                                <h3 class="font-bold mb-2">Coordenadas</h3>
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
                    <h2 class="text-xl font-bold mb-4">Detalhes da reserva</h2>
                    
                    <!-- Datas e hóspedes -->
                    <div class="mb-4">
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <span class="text-gray-600">Check-in</span>
                            <span class="font-medium">{{ \Carbon\Carbon::parse($checkIn)->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <span class="text-gray-600">Check-out</span>
                            <span class="font-medium">{{ \Carbon\Carbon::parse($checkOut)->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <span class="text-gray-600">Duração</span>
                            <span class="font-medium">{{ $nights }} {{ $nights == 1 ? 'noite' : 'noites' }}</span>
                        </div>
                        <div class="flex justify-between py-2">
                            <span class="text-gray-600">Hóspedes</span>
                            <span class="font-medium">{{ $guests }} {{ $guests == 1 ? 'hóspede' : 'hóspedes' }}</span>
                        </div>
                    </div>
                    
                    <!-- Melhor preço -->
                    @if(count($roomTypes) > 0 && isset($roomTypes[0]['lowest_price']))
                        <div class="mt-6 pt-4 border-t border-gray-200">
                            <div class="flex justify-between mb-2">
                                <span class="text-gray-600">Melhor preço:</span>
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
                                Ver quartos e preços
                            </button>
                        </div>
                    @endif
                    
                    <!-- Contato do hotel -->
                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <h3 class="font-bold mb-2">Contato</h3>
                        @if($hotel->phone)
                            <div class="flex items-center mb-2">
                                <i class="fas fa-phone-alt text-gray-500 mr-2"></i>
                                <a href="tel:{{ $hotel->phone }}" class="text-primary hover:underline">{{ $hotel->phone }}</a>
                            </div>
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
                                <a href="{{ $hotel->website }}" target="_blank" class="text-primary hover:underline">Website oficial</a>
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
            <img 
                :src="currentImage" 
                class="max-h-screen transition-transform cursor-move" 
                :style="`transform: translate(${imageX}px, ${imageY}px) scale(${zoomLevel})`"
                @mousedown="startDrag($event)"
                @mousemove="drag($event)"
                @mouseup="stopDrag()"
                @mouseleave="stopDrag()"
                draggable="false"
            />
        </div>
        
        <!-- Botão de navegação direita -->
        <button 
            class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-gray-800 bg-opacity-50 hover:bg-opacity-70 text-white p-3 rounded-full"
            @click="nextImage()">
            <i class="fas fa-chevron-right text-2xl"></i>
        </button>
    </div>
</div>

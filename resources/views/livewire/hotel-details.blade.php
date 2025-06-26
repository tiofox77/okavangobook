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
            <div class="mb-4">
                <a href="{{ url()->previous() }}" class="inline-flex items-center text-gray-600 hover:text-primary">
                    <i class="fas fa-arrow-left mr-2"></i> Voltar aos resultados
                </a>
            </div>
            
            <!-- Informações básicas do hotel -->
            <div class="flex flex-wrap items-start justify-between">
                <div class="w-full lg:w-8/12 mb-4 lg:mb-0">
                    <h1 class="text-3xl font-bold text-gray-800">{{ $hotel->name }}</h1>
                    
                    <!-- Classificação por estrelas -->
                    <div class="flex items-center my-2">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $hotel->stars)
                                <i class="fas fa-star text-yellow-400"></i>
                            @else
                                <i class="far fa-star text-yellow-400"></i>
                            @endif
                        @endfor
                        <span class="ml-2 text-gray-700">{{ $hotel->stars }} estrelas</span>
                    </div>
                    
                    <!-- Endereço -->
                    <p class="text-gray-600 mb-2">
                        <i class="fas fa-map-marker-alt mr-2"></i> 
                        {{ $hotel->address }}, {{ $hotel->location->name }}, {{ $hotel->location->province }}
                    </p>
                </div>
                
                <!-- Caixa de preço -->
                <div class="w-full lg:w-4/12 lg:text-right">
                    @if(count($roomTypes) > 0 && isset($roomTypes[0]['lowest_price']))
                        <div class="bg-blue-50 border border-blue-100 rounded-lg p-4">
                            <div class="text-gray-600 mb-1">Melhor preço por noite a partir de</div>
                            <div class="text-3xl font-bold text-primary">AKZ {{ number_format($roomTypes[0]['lowest_price'] / $nights, 0, ',', '.') }}</div>
                            <div class="text-sm text-gray-600 mt-1">AKZ {{ number_format($roomTypes[0]['lowest_price'], 0, ',', '.') }} por {{ $nights }} {{ $nights == 1 ? 'noite' : 'noites' }}</div>
                            <a href="#rooms" class="inline-block mt-3 bg-primary hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-300">
                                Ver quartos disponíveis
                            </a>
                        </div>
                    @else
                        <div class="bg-red-50 border border-red-100 rounded-lg p-4">
                            <div class="text-red-600 font-medium">Sem disponibilidade nas datas selecionadas</div>
                            <p class="text-gray-600 text-sm mt-1">Tente outras datas ou entre em contato diretamente com o hotel</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Galeria de fotos -->
            <div class="mt-6 grid grid-cols-4 gap-2">
                @php
                    // Preparar todas as imagens para o visualizador
                    $allHotelImages = [];
                    
                    // Adicionar imagem de destaque
                    if (!empty($hotel->featured_image)) {
                        $allHotelImages[] = $hotel->featured_image;
                    }
                    
                    // Adicionar outras imagens da galeria
                    $hotelImages = json_decode($hotel->images);
                    if (is_array($hotelImages)) {
                        foreach ($hotelImages as $img) {
                            if (!empty($img) && is_string($img)) {
                                $allHotelImages[] = $img;
                            }
                        }
                    }
                    
                    $allHotelImagesJson = json_encode($allHotelImages);
                @endphp
                
                <!-- Imagem de destaque -->
                <div class="col-span-2 row-span-2 relative rounded-lg overflow-hidden">
                    <div 
                        class="w-full h-full cursor-pointer" 
                        @click="openImageViewer('{{ $hotel->featured_image }}', {{ $allHotelImagesJson }}, 0)">
                        <img src="{{ $hotel->featured_image }}" alt="{{ $hotel->name }}" class="w-full h-full object-cover rounded-lg">
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
                        <div class="relative rounded-lg overflow-hidden">
                            <div 
                                class="w-full h-full cursor-pointer" 
                                @click="openImageViewer('{{ $image }}', {{ $allHotelImagesJson }}, {{ $index + 1 }})">
                                <img src="{{ $image }}" alt="{{ $hotel->name }} - Imagem {{ $index + 1 }}" class="w-full h-full object-cover rounded-lg">
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
                <nav class="flex -mb-px">
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
                        <h2 class="text-2xl font-bold mb-4">Sobre o hotel</h2>
                        <div class="prose max-w-none">
                            <p>{{ $hotel->description }}</p>
                        </div>
                        
                        <!-- Facilidades/Amenidades -->
                        <h3 class="text-xl font-bold mt-8 mb-4">Comodidades e serviços</h3>
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
                                            
                                            // Preparar todas as imagens para o visualizador
                                            if (is_array($roomImages)) {
                                                // Coletar imagens da galeria (formato novo)
                                                if (isset($roomImages['gallery']) && is_array($roomImages['gallery'])) {
                                                    foreach ($roomImages['gallery'] as $img) {
                                                        if (is_string($img)) {
                                                            $allImages[] = url(normalizeImagePath($img));
                                                        }
                                                    }
                                                }
                                                
                                                // Adicionar thumbnail se estiver definido
                                                if (isset($roomImages['thumbnail']) && is_string($roomImages['thumbnail'])) {
                                                    // Adicionar thumbnail à frente da lista
                                                    array_unshift($allImages, url(normalizeImagePath($roomImages['thumbnail'])));
                                                    $mainImage = $allImages[0];
                                                }
                                                
                                                // Formato antigo (array simples)
                                                if (count($allImages) == 0 && count($roomImages) > 0 && !isset($roomImages['thumbnail']) && !isset($roomImages['gallery'])) {
                                                    foreach ($roomImages as $img) {
                                                        if (is_string($img)) {
                                                            $allImages[] = url(normalizeImagePath($img));
                                                        }
                                                    }
                                                }
                                                
                                                // Define imagem principal se não estiver definida
                                                if (empty($mainImage) && count($allImages) > 0) {
                                                    $randomIndex = array_rand($allImages);
                                                    $mainImage = $allImages[$randomIndex];
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
                                                    @if($room['has_prices'] && isset($room['prices'][$room['best_provider']]['link']))
                                                        <a 
                                                            href="{{ $room['prices'][$room['best_provider']]['link'] }}" 
                                                            target="_blank" 
                                                            class="px-4 py-2 bg-primary text-white rounded hover:bg-blue-700 transition-colors"
                                                        >
                                                            Reservar agora
                                                        </a>
                                                    @else
                                                        <button
                                                            wire:click="bookRoomBasic('{{ $room['id'] }}')" 
                                                            class="px-4 py-2 bg-primary text-white rounded hover:bg-blue-700 transition-colors"
                                                        >
                                                            Reservar pelo preço base
                                                        </button>
                                                    @endif
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
                                                        $galleryImages = [];
                                                        $imageUrls = []; // Para ser usado no visualizador
                                                        
                                                        if (isset($room['images']['gallery']) && is_array($room['images']['gallery'])) {
                                                            // Nova estrutura com galeria
                                                            $galleryImages = $room['images']['gallery'];
                                                        } elseif (isset($room['images']['thumbnail'])) {
                                                            // Adiciona thumbnail se disponível
                                                            $galleryImages[] = $room['images']['thumbnail'];
                                                        } elseif (is_array($room['images']) && !isset($room['images']['gallery']) && !isset($room['images']['thumbnail'])) {
                                                            // Array simples (formato antigo)
                                                            $galleryImages = $room['images'];
                                                        }
                                                        
                                                        // Prepara URLs para o visualizador de imagens
                                                        foreach ($galleryImages as $img) {
                                                            if (is_string($img)) {
                                                                $imageUrls[] = url(normalizeImagePath($img));
                                                            }
                                                        }
                                                        $imageUrlsJson = json_encode($imageUrls);
                                                    @endphp
                                                    
                                                    @forelse($galleryImages as $index => $image)
                                                        @if(is_string($image))
                                                            <div 
                                                                class="relative rounded-md overflow-hidden cursor-pointer shadow-sm hover:shadow-md transition-shadow"
                                                                @click="openImageViewer('{{ url(normalizeImagePath($image)) }}', {{ $imageUrlsJson }}, {{ $index }})">
                                                                <img 
                                                                    src="{{ url(normalizeImagePath($image)) }}" 
                                                                    alt="{{ $room['name'] }}" 
                                                                    class="w-full h-32 object-cover rounded-md"
                                                                >
                                                                <div class="absolute inset-0 bg-black bg-opacity-20 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity">
                                                                    <i class="fas fa-search-plus text-white text-xl"></i>
                                                                </div>
                                                            </div>
                                                        @endif
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
                    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                        <h2 class="text-2xl font-bold mb-4">Avaliações</h2>
                        
                        <!-- Placeholder para avaliações (a ser implementado em versões futuras) -->
                        <div class="text-center py-8">
                            <i class="far fa-comment-dots text-5xl text-gray-300 mb-4"></i>
                            <h3 class="text-xl font-medium text-gray-700 mb-2">Avaliações em breve</h3>
                            <p class="text-gray-600">
                                O sistema de avaliações estará disponível na próxima atualização.
                            </p>
                        </div>
                    </div>
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
                            <a 
                                href="#rooms" 
                                class="block w-full text-center bg-primary hover:bg-blue-700 text-white font-bold py-3 px-4 rounded transition duration-300"
                            >
                                Ver quartos e preços
                            </a>
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

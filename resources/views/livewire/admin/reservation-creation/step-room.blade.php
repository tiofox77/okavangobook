<div>
   

    <div class="space-y-6">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                        <i class="fas fa-bed text-blue-600 mr-2"></i>
                        Selecionar Quarto
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        Escolha o tipo de quarto para a sua reserva
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
                       wire:model.live.debounce.300ms="roomFilter"
                       placeholder="Pesquisar tipo de quarto..."
                       class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-all duration-200">
            </div>
        </div>

        <!-- Room Types Grid -->
        @if($roomTypes->count() > 0)
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                @foreach($roomTypes as $roomType)
                    <div class="group relative bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden transition-all duration-300 cursor-pointer"
                         wire:click="selectRoomType({{ $roomType->id }})"
                         role="button"
                         tabindex="0">
                        
                        <!-- Room Type Image -->
                        <div class="relative h-48 bg-gradient-to-br from-purple-400 to-pink-600 overflow-hidden">
                            @php
                                $images = $roomType->images;
                                if (is_string($images)) {
                                    $images = json_decode($images, true) ?: [];
                                }
                                
                                // Combinar todas as imagens disponíveis
                                $allImages = [];
                                if (is_array($images)) {
                                    // Adicionar thumbnail se existir
                                    if (isset($images['thumbnail']) && !empty($images['thumbnail'])) {
                                        $allImages[] = $images['thumbnail'];
                                    }
                                    // Adicionar imagens da galeria se existirem
                                    if (isset($images['gallery']) && is_array($images['gallery'])) {
                                        $allImages = array_merge($allImages, $images['gallery']);
                                    }
                                }
                                
                                // Selecionar imagem aleatória do array combinado
                                $randomImage = !empty($allImages) ? $allImages[array_rand($allImages)] : null;
                            @endphp
                            
                            @if(!empty($allImages) && $randomImage)
                                @php
                                    $imageSrc = \App\Helpers\ImageHelper::getValidImage($randomImage, 'room');
                                @endphp
                                <img src="{{ $imageSrc }}" 
                                     alt="{{ $roomType->name }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                     onerror="this.onerror=null; this.src='{{ \App\Helpers\ImageHelper::generateDefaultSvg('room', $roomType->name, 400, 300) }}';">
                            @else
                                <img src="{{ \App\Helpers\ImageHelper::generateDefaultSvg('room', $roomType->name, 400, 300) }}" 
                                     alt="{{ $roomType->name }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @endif

                            <!-- Overlay -->
                            <div class="absolute inset-0 bg-black/20 group-hover:bg-black/30 transition-all duration-300"></div>
                            
                            <!-- Room Capacity -->
                            <div class="absolute top-3 left-3">
                                <div class="flex items-center bg-white/90 dark:bg-gray-800/90 rounded-full px-3 py-1">
                                    <i class="fas fa-users text-sm text-gray-600 dark:text-gray-400 mr-1"></i>
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                        {{ $roomType->capacity }} {{ $roomType->capacity > 1 ? 'pessoas' : 'pessoa' }}
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Available Rooms -->
                            @if($roomType->available_rooms_count)
                                <div class="absolute top-3 right-3">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        {{ $roomType->available_rooms_count }} disponível(is)
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- Room Type Info -->
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-3">
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                    {{ $roomType->name }}
                                </h4>
                                <div class="text-right">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">por noite</p>
                                    <p class="text-lg font-bold text-green-600 dark:text-green-400">
                                        {{ \App\Helpers\CurrencyHelper::formatKwanza($roomType->base_price) }}
                                    </p>
                                </div>
                            </div>
                            
                            @if($roomType->description)
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 line-clamp-2">
                                    {{ Str::limit($roomType->description, 150) }}
                                </p>
                            @endif

                            <!-- Room Features -->
                            <div class="space-y-2 mb-4">
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                    <i class="fas fa-expand-arrows-alt mr-2 text-gray-400 w-4"></i>
                                    {{ $roomType->size }} m²
                                </div>
                                
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                    <i class="fas fa-bed mr-2 text-gray-400 w-4"></i>
                                    {{ $roomType->bed_type }} ({{ $roomType->bed_count }} {{ $roomType->bed_count > 1 ? 'camas' : 'cama' }})
                                </div>

                                @if($roomType->bathroom_type)
                                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                        <i class="fas fa-bath mr-2 text-gray-400 w-4"></i>
                                        {{ $roomType->bathroom_type }}
                                    </div>
                                @endif

                                @if($roomType->view_type)
                                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                        <i class="fas fa-eye mr-2 text-gray-400 w-4"></i>
                                        {{ $roomType->view_type }}
                                    </div>
                                @endif
                            </div>

                            <!-- Room Amenities -->
                            @php
                                $amenities = $roomType->amenities;
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
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200">
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

                            <!-- Image Gallery Preview -->
                            @if(!empty($allImages) && count($allImages) > 1)
                                <div class="mb-4">
                                    <h5 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Galeria:</h5>
                                    <div class="flex space-x-2 overflow-x-auto">
                                        @php
                                            // Filtrar as imagens para excluir a imagem principal já selecionada
                                            $galleryImages = array_filter($allImages, function($image) use ($randomImage) {
                                                return $image !== $randomImage;
                                            });
                                            $galleryImages = array_values($galleryImages); // Reindexar array
                                        @endphp
                                        
                                        @foreach(array_slice($galleryImages, 0, 3) as $image)
                                            <div class="flex-shrink-0 w-16 h-16 rounded-lg overflow-hidden">
                                                @php
                                                    $imageSrc = \App\Helpers\ImageHelper::getValidImage($image, 'room');
                                                @endphp
                                                <img src="{{ $imageSrc }}" 
                                                     alt="Room Image"
                                                     class="w-full h-full object-cover"
                                                     onerror="this.onerror=null; this.src='{{ \App\Helpers\ImageHelper::generateDefaultSvg('room', 'Room', 64, 64) }}';">
                                            </div>
                                        @endforeach
                                        
                                        @if(count($galleryImages) > 3)
                                            <div class="flex-shrink-0 w-16 h-16 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                                <span class="text-xs text-gray-600 dark:text-gray-400 font-medium">
                                                    +{{ count($galleryImages) - 3 }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <!-- Action Button -->
                            <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                                <button type="button" 
                                        class="w-full inline-flex items-center justify-center px-4 py-3 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 group-hover:bg-purple-700">
                                    <i class="fas fa-check mr-2"></i>
                                    Selecionar Quarto
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
                    <i class="fas fa-bed text-3xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
                    Nenhum quarto encontrado
                </h3>
                <p class="text-gray-600 dark:text-gray-400 mb-4">
                    @if(!empty($roomFilter))
                        Nenhum tipo de quarto corresponde ao filtro "{{ $roomFilter }}". Tente ajustar a pesquisa.
                    @else
                        Não há tipos de quarto disponíveis neste hotel.
                    @endif
                </p>
                <div class="flex justify-center space-x-3">
                    @if(!empty($roomFilter))
                        <button wire:click="$set('roomFilter', '')" 
                                class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                            <i class="fas fa-times mr-2"></i>
                            Limpar Filtro
                        </button>
                    @endif
                    <button wire:click="goToPreviousStep" 
                            class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Escolher Outro Hotel
                    </button>
                </div>
            </div>
        @endif
    </div>

    @if($roomTypeId && $availableRooms->count() > 0)
        <div class="mt-8 bg-blue-50 dark:bg-blue-900 rounded-lg p-6">
            <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                Quartos Disponíveis - {{ $selectedRoomType['name'] ?? '' }}
            </h4>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($availableRooms as $room)
                    <div wire:click="selectRoom({{ $room->id }})" 
                         class="bg-white dark:bg-gray-800 rounded-lg border-2 border-gray-200 dark:border-gray-600 hover:border-blue-500 dark:hover:border-blue-400 p-4 cursor-pointer transition-all duration-200 transform hover:scale-105">
                        <div class="flex items-center justify-between mb-2">
                            <h5 class="font-medium text-gray-900 dark:text-gray-100">
                                Quarto {{ $room->room_number }}
                            </h5>
                            @if($room->floor)
                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $room->floor }}º andar
                                </span>
                            @endif
                        </div>
                        
                        @if($room->description)
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                                {{ $room->description }}
                            </p>
                        @endif
                        
                        <div class="flex items-center justify-between">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
                                <i class="fas fa-check-circle mr-1"></i>
                                Disponível
                            </span>
                            
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @elseif($roomTypeId && $availableRooms->count() === 0)
        <div class="mt-8 bg-red-50 dark:bg-red-900 rounded-lg p-6 text-center">
            <div class="w-12 h-12 bg-red-100 dark:bg-red-800 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400"></i>
            </div>
            <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                Nenhum quarto disponível
            </h4>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Não há quartos deste tipo disponíveis para as datas selecionadas.
            </p>
            <button wire:click="clearRoomSelection" 
                    class="mt-4 inline-flex items-center px-4 py-2 text-sm font-medium text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900 rounded-md hover:bg-red-100 dark:hover:bg-red-800 transition-colors duration-200">
                Selecionar outro tipo de quarto
            </button>
        </div>
    @endif
        
    @if($roomTypes->count() === 0)
        <div class="text-center py-12">
            <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-bed text-gray-400 text-xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Nenhum tipo de quarto disponível</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Este hotel não possui tipos de quarto configurados ou disponíveis.
            </p>
            <button wire:click="goToStep('hotel')" 
                    class="mt-4 inline-flex items-center px-4 py-2 text-sm font-medium text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900 rounded-md hover:bg-blue-100 dark:hover:bg-blue-800 transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                Escolher outro hotel
            </button>
        </div>
    @endif
</div>

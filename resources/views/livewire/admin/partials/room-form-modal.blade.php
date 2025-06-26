<!-- Modal para adicionar/editar tipos de quarto -->
<div class="fixed inset-0 overflow-y-auto z-50" x-show="$wire.showModal"
     x-transition:enter="ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Overlay -->
        <div class="fixed inset-0 transition-opacity" aria-hidden="true" x-show="$wire.showModal">
            <div class="absolute inset-0 bg-gray-500 dark:bg-gray-800 opacity-75"></div>
        </div>

        <!-- Modal centralizado -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <!-- Conteúdo do modal -->
        <div x-data="{ activeTab: 'basic' }" 
             x-init="$watch('activeTab', value => console.log('Tab alterada para:', value))"
             class="relative inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full" 
             x-show="$wire.showModal" 
             x-transition:enter="ease-out duration-300" 
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
             x-transition:leave="ease-in duration-200" 
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
            
            <!-- Botão de fechar -->
            <div class="absolute top-0 right-0 pt-4 pr-4">
                <button wire:click="closeModal" type="button" class="bg-white dark:bg-transparent rounded-md text-gray-400 hover:text-gray-500 focus:outline-none transition-colors duration-200">
                    <span class="sr-only">Fechar</span>
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <!-- Cabeçalho e conteúdo -->
            <form wire:submit.prevent="save">
                <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <!-- Cabeçalho do modal -->
                    <div class="mb-6 flex items-center">
                        <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 dark:bg-blue-900 mr-4">
                            <i class="fas fa-bed text-blue-600 dark:text-blue-400 text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-xl leading-6 font-bold text-gray-900 dark:text-white">
                                {{ $room_id ? 'Editar Tipo de Quarto' : 'Adicionar Novo Tipo de Quarto' }}
                            </h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Defina as informações do tipo de quarto e suas características
                            </p>
                        </div>
                    </div>
                        
                    <!-- Navegação de abas -->
                    <div class="border-b border-gray-200 dark:border-gray-700 mb-6">
                        <nav class="-mb-px flex space-x-6 overflow-x-auto scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-600" aria-label="Tabs">
                            <button
                                type="button"
                                @click.stop="activeTab = 'basic'"
                                :class="{'border-blue-500 text-blue-600 dark:text-blue-400': activeTab === 'basic', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400': activeTab !== 'basic'}"
                                class="py-3 px-1 border-b-2 font-medium text-sm transition-colors duration-200 flex items-center whitespace-nowrap"
                                aria-current="page">
                                <i class="fas fa-info-circle mr-2"></i>
                                Informações Básicas
                            </button>
                            <button
                                type="button"
                                @click.stop="activeTab = 'description'"
                                :class="{'border-blue-500 text-blue-600 dark:text-blue-400': activeTab === 'description', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400': activeTab !== 'description'}"
                                class="py-3 px-1 border-b-2 font-medium text-sm transition-colors duration-200 flex items-center whitespace-nowrap">
                                <i class="fas fa-align-left mr-2"></i>
                                Descrição
                            </button>
                            <button
                                type="button"
                                @click.stop="activeTab = 'images'"
                                :class="{'border-blue-500 text-blue-600 dark:text-blue-400': activeTab === 'images', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400': activeTab !== 'images'}"
                                class="py-3 px-1 border-b-2 font-medium text-sm transition-colors duration-200 flex items-center whitespace-nowrap">
                                <i class="fas fa-images mr-2"></i>
                                Imagens
                            </button>
                            <button
                                type="button"
                                @click.stop="activeTab = 'features'"
                                :class="{'border-blue-500 text-blue-600 dark:text-blue-400': activeTab === 'features', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400': activeTab !== 'features'}"
                                class="py-3 px-1 border-b-2 font-medium text-sm transition-colors duration-200 flex items-center whitespace-nowrap">
                                <i class="fas fa-concierge-bell mr-2"></i>
                                Facilidades
                            </button>
                            <button
                                type="button"
                                @click.stop="activeTab = 'pricing'"
                                :class="{'border-blue-500 text-blue-600 dark:text-blue-400': activeTab === 'pricing', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400': activeTab !== 'pricing'}"
                                class="py-3 px-1 border-b-2 font-medium text-sm transition-colors duration-200 flex items-center whitespace-nowrap">
                                <i class="fas fa-tags mr-2"></i>
                                Preços e Status
                            </button>
                        </nav>
                    </div>

                    <!-- Conteúdo das abas -->
                    <div class="space-y-6">
                        <!-- Aba: Informações Básicas -->
                        <div x-show="activeTab === 'basic'" x-cloak class="animate__animated animate__fadeIn">
                            <!-- Hotel -->
                            <div class="mb-5">
                                <label for="form_hotel_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Hotel</label>
                                <div class="relative">
                                    <select id="form_hotel_id" wire:model.defer="form_hotel_id" class="block w-full pl-3 pr-10 py-2.5 text-base border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md shadow-sm">
                                        <option value="">-- Selecione o Hotel --</option>
                                        @foreach($hotels as $hotel)
                                            <option value="{{ $hotel->id }}">{{ $hotel->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <i class="fas fa-building text-gray-400"></i>
                                    </div>
                                </div>
                                @error('form_hotel_id') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Nome e Tamanho -->
                            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nome do Tipo de Quarto</label>
                                    <div class="relative">
                                        <input type="text" id="name" wire:model.defer="name" placeholder="Ex: Suite Executiva" class="pl-3 pr-10 py-2.5 shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                            <i class="fas fa-bed text-gray-400"></i>
                                        </div>
                                    </div>
                                    @error('name') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="size" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tamanho (m²)</label>
                                    <div class="relative">
                                        <input type="number" id="size" wire:model.defer="size" placeholder="Ex: 24" class="pl-3 pr-10 py-2.5 shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                            <i class="fas fa-ruler-combined text-gray-400"></i>
                                        </div>
                                    </div>
                                    @error('size') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Camas, Tipo de Cama e Capacidade -->
                            <div class="grid grid-cols-1 gap-5 sm:grid-cols-3 mt-5">
                                <div>
                                    <label for="beds" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Número de Camas</label>
                                    <div class="relative">
                                        <input type="number" id="beds" wire:model.defer="beds" min="1" max="10" placeholder="Ex: 1" class="pl-3 pr-10 py-2.5 shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                            <i class="fas fa-bed text-gray-400"></i>
                                        </div>
                                    </div>
                                    @error('beds') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="bed_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipo de Cama</label>
                                    <div class="relative">
                                        <input type="text" id="bed_type" wire:model.defer="bed_type" placeholder="Ex: Queen-size" class="pl-3 pr-10 py-2.5 shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                            <i class="fas fa-bed text-gray-400"></i>
                                        </div>
                                    </div>
                                    @error('bed_type') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="capacity" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Capacidade (pessoas)</label>
                                    <div class="relative">
                                        <input type="number" id="capacity" wire:model.defer="capacity" min="1" max="20" placeholder="Ex: 2" class="pl-3 pr-10 py-2.5 shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                            <i class="fas fa-user-friends text-gray-400"></i>
                                        </div>
                                    </div>
                                    @error('capacity') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            
                        </div>
                        <!-- Aba: Descrição -->
                        <div x-show="activeTab === 'description'" x-cloak class="animate__animated animate__fadeIn">
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Descrição do Tipo de Quarto</label>
                                <textarea id="description" wire:model.defer="description" rows="6" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md" placeholder="Descreva este tipo de quarto, incluindo detalhes sobre o ambiente, decoração, etc."></textarea>
                                @error('description') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        
                        <!-- Aba: Imagens -->
                        <div x-show="activeTab === 'images'" x-cloak class="animate__animated animate__fadeIn">
                            <!-- Imagem Principal -->
                            <div class="mb-6">
                                <label for="thumbnail" class="block text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center">
                                    <i class="fas fa-image text-purple-500 mr-2"></i> Imagem Principal
                                </label>
                                
                                <div class="mt-2 flex items-center justify-center space-x-2">
                                    <div class="flex-shrink-0 relative">
                                        @if ($thumbnail)
                                            <div class="relative h-28 w-28 rounded-md overflow-hidden shadow-md">
                                                <img src="{{ $thumbnail->temporaryUrl() }}" alt="Imagem de pré-visualização" class="h-full w-full object-cover">
                                                <button type="button" wire:click="removeThumbnail" class="absolute top-0 right-0 mt-1 mr-1 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-400">
                                                    <i class="fas fa-times text-xs"></i>
                                                </button>
                                            </div>
                                        @elseif ($room_id && $existingThumbnail)
                                            <div class="relative h-28 w-28 rounded-md overflow-hidden shadow-md">
                                                <img src="{{ $existingThumbnail }}" alt="Imagem atual" class="h-full w-full object-cover">
                                                <button type="button" wire:click="removeExistingThumbnail" class="absolute top-0 right-0 mt-1 mr-1 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-400">
                                                    <i class="fas fa-times text-xs"></i>
                                                </button>
                                            </div>
                                        @else
                                            <div class="h-28 w-28 flex items-center justify-center rounded-md border-2 border-dashed border-gray-300 dark:border-gray-600">
                                                <i class="fas fa-camera text-gray-400 text-3xl"></i>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="flex flex-col space-y-2">
                                        <label for="thumbnail-upload" class="cursor-pointer bg-white dark:bg-gray-700 py-2 px-3 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <i class="fas fa-upload mr-2"></i> Carregar Imagem
                                        </label>
                                        <input id="thumbnail-upload" wire:model="thumbnail" type="file" accept="image/*" class="sr-only">
                                        @error('thumbnail') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                        
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            JPG, PNG ou GIF. Máximo 2MB.
                                            <br>Resolução ideal: 800x600px
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Galeria de Imagens -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center">
                                    <i class="fas fa-images text-indigo-500 mr-2"></i> Galeria de Imagens
                                </label>
                                
                                <div class="mt-2 space-y-4">
                                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                        <!-- Imagens carregadas temporariamente -->
                                        @if ($galleryImages)
                                            @foreach($galleryImages as $index => $image)
                                                <div class="relative h-24 w-full rounded-md overflow-hidden shadow-md">
                                                    <img src="{{ $image->temporaryUrl() }}" alt="Imagem da galeria" class="h-full w-full object-cover">
                                                    <button type="button" wire:click="removeGalleryImage({{ $index }})" class="absolute top-0 right-0 mt-1 mr-1 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-400">
                                                        <i class="fas fa-times text-xs"></i>
                                                    </button>
                                                </div>
                                            @endforeach
                                        @endif
                                            
                                        <!-- Imagens existentes -->
                                        @if ($room_id && $existingGalleryImages)
                                            @foreach($existingGalleryImages as $index => $image)
                                                <div class="relative h-24 w-full rounded-md overflow-hidden shadow-md">
                                                    <img src="{{ $image }}" alt="Imagem da galeria" class="h-full w-full object-cover">
                                                    <button type="button" wire:click="removeExistingGalleryImage({{ $index }})" class="absolute top-0 right-0 mt-1 mr-1 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-400">
                                                        <i class="fas fa-times text-xs"></i>
                                                    </button>
                                                </div>
                                            @endforeach
                                        @endif
                                            
                                        <!-- Botão de upload -->
                                        <div class="h-24 w-full flex items-center justify-center rounded-md border-2 border-dashed border-gray-300 dark:border-gray-600 hover:border-blue-400 dark:hover:border-blue-500 transition-colors cursor-pointer" onclick="document.getElementById('gallery-upload').click()">
                                            <div class="space-y-1 text-center">
                                                <i class="fas fa-plus text-gray-400"></i>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">Adicionar</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <input id="gallery-upload" wire:model="galleryImages" multiple type="file" accept="image/*" class="hidden">
                                    @error('galleryImages.*') <span class="text-red-500 text-xs block mt-1">{{ $message }}</span> @enderror
                                    
                                    <div class="text-sm text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-700 p-3 rounded-md border border-gray-200 dark:border-gray-600">
                                        <p class="flex items-center"><i class="fas fa-info-circle mr-2 text-blue-500"></i> Carregue várias imagens que mostrem este tipo de quarto em detalhes.</p>
                                        <ul class="list-disc list-inside text-xs mt-2 space-y-1">
                                            <li>Máximo de 10 imagens por tipo de quarto</li>
                                            <li>Tamanho máximo de 2MB por imagem</li>
                                            <li>Formatos aceites: JPG, PNG ou GIF</li>
                                            <li>Resolução recomendada: 1200x800px ou maior</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Ordem da Galeria -->
                            <div class="mt-6 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg border border-gray-200 dark:border-gray-600">
                                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 flex items-center">
                                    <i class="fas fa-sort text-amber-500 mr-2"></i> Organização da Galeria
                                </h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Para reordenar as imagens, remova-as e carregue-as novamente na ordem desejada. A primeira imagem da galeria será usada como capa caso não exista uma imagem principal definida.
                                </p>
                            </div>
                        </div>
                        
                        <!-- Aba: Comodidades -->
                        <div x-show="activeTab === 'features'" x-cloak class="animate__animated animate__fadeIn">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Comodidades do Quarto</label>
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                    @foreach($amenitiesList as $amenityId => $amenity)
                                        <div class="flex items-center space-x-2 bg-gray-50 dark:bg-gray-700 p-3 rounded-md hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                            <input id="amenity_{{ $amenityId }}" wire:model.defer="amenities" value="{{ $amenityId }}" type="checkbox" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                            <label for="amenity_{{ $amenityId }}" class="flex items-center text-sm text-gray-700 dark:text-gray-300 cursor-pointer">
                                                @if($amenity['icon'])
                                                    <i class="{{ $amenity['icon'] }} mr-2 text-blue-500"></i>
                                                @else
                                                    <i class="fas fa-check-circle mr-2 text-blue-500"></i>
                                                @endif
                                                {{ $amenity['name'] }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                @if(count($amenitiesList) == 0)
                                    <div class="text-center py-4 text-gray-500 dark:text-gray-400">
                                        <i class="fas fa-info-circle mb-2 text-xl"></i>
                                        <p>Nenhuma comodidade de quarto disponível.</p>
                                        <p class="text-sm">Adicione comodidades através do gestor de comodidades.</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Aba: Preços e Status -->
                        <div x-show="activeTab === 'pricing'" x-cloak class="animate__animated animate__fadeIn">
                            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                                <!-- Preço Base -->
                                <div class="mb-5">
                                    <label for="base_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Preço Base (AOA)</label>
                                    <div class="relative mt-1 rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 dark:text-gray-400 sm:text-sm">AOA</span>
                                        </div>
                                        <input type="number" id="base_price" wire:model.defer="base_price" min="0" step="0.01" class="pl-14 pr-10 py-2.5 focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md" placeholder="0.00">
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                            <i class="fas fa-tag text-gray-400"></i>
                                        </div>
                                    </div>
                                    @error('base_price') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                
                                <!-- Número de Quartos -->
                                <div class="mb-5">
                                    <label for="rooms_count" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Quantidade de Quartos</label>
                                    <div class="relative">
                                        <input type="number" id="rooms_count" wire:model.defer="rooms_count" min="0" class="pl-3 pr-10 py-2.5 shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md" placeholder="Ex: 5">
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                            <i class="fas fa-door-closed text-gray-400"></i>
                                        </div>
                                    </div>
                                    @error('rooms_count') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            
                            <!-- Status -->
                            <div class="space-y-4 mt-4">
                                <!-- Status de Disponibilidade -->
                                <div class="flex items-center space-x-2">
                                    <div class="flex items-center h-5">
                                        <input id="is_available" wire:model.defer="is_available" type="checkbox" class="h-5 w-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    </div>
                                    <div class="ml-3">
                                        <label for="is_available" class="text-sm font-medium text-gray-700 dark:text-gray-300">Tipo de quarto disponível para reserva</label>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Desmarque esta opção se este tipo de quarto estiver em manutenção ou indisponível.</p>
                                    </div>
                                </div>
                                @error('is_available') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                
                                <!-- Status de Destaque -->
                                <div class="flex items-center space-x-2 mt-4">
                                    <div class="flex items-center h-5">
                                        <input id="is_featured" wire:model.defer="is_featured" type="checkbox" class="h-5 w-5 text-amber-600 border-gray-300 rounded focus:ring-amber-500">
                                    </div>
                                    <div class="ml-3">
                                        <label for="is_featured" class="text-sm font-medium text-gray-700 dark:text-gray-300">Destacar este tipo de quarto</label>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Tipos de quarto em destaque aparecem em posições privilegiadas na página inicial e resultados de pesquisa.</p>
                                    </div>
                                </div>
                                @error('is_featured') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botões de ação -->
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-4 sm:px-6 flex justify-end space-x-3 border-t border-gray-200 dark:border-gray-600">
                    <button type="button" wire:click="closeModal" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                        <i class="fas fa-times mr-2"></i> Cancelar
                    </button>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                        <i class="fas fa-save mr-2"></i> {{ $room_id ? 'Atualizar' : 'Guardar' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

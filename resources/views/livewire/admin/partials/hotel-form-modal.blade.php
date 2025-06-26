<!-- Modal para adicionar/editar hotéis -->
<div x-data="{ 
     open: $wire.entangle('showModal'),
     coordsMessage: '',
     extractCoordinates(mapLink) {
        if (!mapLink) return;
        
        // Verificar se é um link encurtado
        if (mapLink.includes('goo.gl') || mapLink.includes('maps.app.goo.gl')) {
            this.coordsMessage = 'Link encurtado detectado. Por favor, abra o link no navegador e copie o URL completo da barra de endereços.';
            return;
        }
        
        // Padrões para capturar coordenadas de diferentes formatos de URLs do Google Maps
        const patterns = [
            // Formato padrão: https://www.google.com/maps/place/nome+do+local/@-8.839268,13.289005,15z/
            /@(-?\d+\.\d+),(-?\d+\.\d+)/,
            
            // Formato de place: https://www.google.com/maps/place/-8.839268,13.289005
            /place/(-?\d+\.\d+),(-?\d+\.\d+)/,
            
            // Formato q=lat,lng: https://www.google.com/maps?q=-8.839268,13.289005
            /[?&]q=(-?\d+\.\d+),(-?\d+\.\d+)/,
            
            // Formato ll=lat,lng: https://www.google.com/maps?ll=-8.839268,13.289005
            /[?&]ll=(-?\d+\.\d+),(-?\d+\.\d+)/,
            
            // Formato data=latitud,longitude: https://www.google.com/maps/dir//@-8.8,13.2/data=!...
            /data=.*!3d(-?\d+\.\d+)!4d(-?\d+\.\d+)/,
            
            // Formato com coordenadas no final da URL
            /(-?\d+\.\d+),(-?\d+\.\d+)$/
        ];
        
        let found = false;
        
        // Testar cada padrão
        for (const pattern of patterns) {
            const match = mapLink.match(pattern);
            if (match && match.length >= 3) {
                // Se encontrar coordenadas, atualizar os campos
                const lat = parseFloat(match[1]);
                const lng = parseFloat(match[2]);
                
                if (!isNaN(lat) && !isNaN(lng)) {
                    // Usamos Alpine para atualizar os valores Livewire
                    $wire.set('latitude', lat);
                    $wire.set('longitude', lng);
                    this.coordsMessage = 'Coordenadas extraídas com sucesso!';
                    found = true;
                    break;
                }
            }
        }
        
        if (!found) {
            this.coordsMessage = 'Não foi possível extrair coordenadas automaticamente. Por favor, insira manualmente.';
        }
    },
    
    // Função para verificar se as coordenadas inseridas manualmente são válidas
    validateCoords() {
        const lat = parseFloat($wire.get('latitude'));
        const lng = parseFloat($wire.get('longitude'));
        
        if (!isNaN(lat) && !isNaN(lng)) {
            if (lat >= -90 && lat <= 90 && lng >= -180 && lng <= 180) {
                this.coordsMessage = 'Coordenadas válidas!';
            } else {
                this.coordsMessage = 'Coordenadas fora dos limites válidos!';
            }
        } else {
            this.coordsMessage = '';
        }
    }
}" 
     x-show="open" 
     x-cloak
     x-transition:enter="ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed z-50 inset-0 overflow-y-auto" 
     aria-labelledby="modal-title" 
     role="dialog" 
     aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Overlay de fundo com animação -->
        <div 
            x-show="open"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-gray-900 bg-opacity-75 backdrop-blur-sm transition-opacity dark:bg-opacity-90" 
            aria-hidden="true"
            @click="open = false"></div>

        <!-- Centralizador de conteúdo -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <!-- Modal com animação -->
        <div 
            x-show="open"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="relative inline-block align-bottom bg-white dark:bg-gray-800 rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full dark:text-white border border-gray-200 dark:border-gray-700">
            
            <!-- Cabeçalho do modal com degradê -->
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-6 py-4 flex items-center justify-between">
                <h3 class="text-xl font-bold text-white flex items-center">
                    <i class="fas {{ $hotelId ? 'fa-edit' : 'fa-plus-circle' }} mr-2"></i>
                    {{ $hotelId ? 'Editar Hotel' : 'Adicionar Novo Hotel' }}
                </h3>
                <button @click="open = false" type="button" class="text-white hover:text-gray-200 focus:outline-none transition-colors duration-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form wire:submit.prevent="save">
                <div class="bg-white dark:bg-gray-800 px-6 py-6 overflow-y-auto max-h-[70vh]">
                    <div x-data="{activeTab: 'basic'}" class="space-y-6">
                        <!-- Abas de navegação -->
                        <div class="flex flex-wrap gap-2 border-b border-gray-200 dark:border-gray-700 pb-2">
                            <button 
                                type="button"
                                @click.stop="activeTab = 'basic'" 
                                :class="{'text-blue-600 border-b-2 border-blue-500 font-medium dark:text-blue-400 dark:border-blue-400': activeTab === 'basic', 'text-gray-500 dark:text-gray-400': activeTab !== 'basic'}"
                                class="py-2 px-4 text-sm font-medium transition-colors duration-200 focus:outline-none flex items-center">
                                <i class="fas fa-info-circle mr-2"></i> Informações Básicas
                            </button>
                            <button 
                                type="button"
                                @click.stop="activeTab = 'contact'" 
                                :class="{'text-blue-600 border-b-2 border-blue-500 font-medium dark:text-blue-400 dark:border-blue-400': activeTab === 'contact', 'text-gray-500 dark:text-gray-400': activeTab !== 'contact'}"
                                class="py-2 px-4 text-sm font-medium transition-colors duration-200 focus:outline-none flex items-center">
                                <i class="fas fa-phone-alt mr-2"></i> Contacto
                            </button>
                            <button 
                                type="button"
                                @click.stop="activeTab = 'location'" 
                                :class="{'text-blue-600 border-b-2 border-blue-500 font-medium dark:text-blue-400 dark:border-blue-400': activeTab === 'location', 'text-gray-500 dark:text-gray-400': activeTab !== 'location'}"
                                class="py-2 px-4 text-sm font-medium transition-colors duration-200 focus:outline-none flex items-center">
                                <i class="fas fa-map-marker-alt mr-2"></i> Localização
                            </button>
                            <button 
                                type="button"
                                @click.stop="activeTab = 'features'" 
                                :class="{'text-blue-600 border-b-2 border-blue-500 font-medium dark:text-blue-400 dark:border-blue-400': activeTab === 'features', 'text-gray-500 dark:text-gray-400': activeTab !== 'features'}"
                                class="py-2 px-4 text-sm font-medium transition-colors duration-200 focus:outline-none flex items-center">
                                <i class="fas fa-concierge-bell mr-2"></i> Funcionalidades
                            </button>
                        </div>
                        
                        <!-- Aba de Informações Básicas -->
                        <div x-show="activeTab === 'basic'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                            <!-- Nome do Hotel -->
                            <div class="mb-4">
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center">
                                    <i class="fas fa-hotel text-blue-500 mr-2"></i> Nome do Hotel
                                </label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-signature text-gray-400"></i>
                                    </div>
                                    <input type="text" wire:model="name" id="name" 
                                        class="pl-10 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                                </div>
                                @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            
                            <!-- Gestor do Hotel -->
                            <div class="mb-4">
                                <label for="userId" class="block text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center">
                                    <i class="fas fa-user-tie text-purple-500 mr-2"></i> Gestor do Hotel
                                </label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-user-cog text-gray-400"></i>
                                    </div>
                                    <select wire:model="userId" id="userId" 
                                        class="pl-10 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                                        <option value="">Selecione um gestor</option>
                                        @foreach($managers as $manager)
                                            <option value="{{ $manager->id }}">{{ $manager->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('userId') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">O gestor terá permissões especiais para gerir este hotel</p>
                            </div>
                            
                            <!-- Preço -->
                            <div class="mb-4">
                                <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center">
                                    <i class="fas fa-tag text-green-500 mr-2"></i> Preço Base (AOA)
                                </label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-money-bill-wave text-gray-400"></i>
                                    </div>
                                    <input type="number" wire:model="price" id="price" step="0.01" min="0" 
                                        class="pl-10 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                                </div>
                                @error('price') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            
                            <!-- Classificação -->
                            <div class="mb-4">
                                <label for="rating" class="block text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center">
                                    <i class="fas fa-star text-yellow-400 mr-2"></i> Classificação
                                </label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-star-half-alt text-gray-400"></i>
                                    </div>
                                    <select wire:model="rating" id="rating" 
                                        class="pl-10 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                                        <option value="">Selecione uma classificação</option>
                                        <option value="5">5 estrelas ★★★★★</option>
                                        <option value="4">4 estrelas ★★★★☆</option>
                                        <option value="3">3 estrelas ★★★☆☆</option>
                                        <option value="2">2 estrelas ★★☆☆☆</option>
                                        <option value="1">1 estrela ★☆☆☆☆</option>
                                    </select>
                                </div>
                                @error('rating') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            
                            <!-- Imagem Thumbnail -->
                            <div class="mb-6">
                                <label for="thumbnail" class="block text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center">
                                    <i class="fas fa-image text-purple-500 mr-2"></i> Imagem Principal
                                </label>
                                
                                <!-- Mostrar imagem atual se existir -->
                                @if($thumbnail)
                                <div class="mt-2 mb-3">
                                    <img src="{{ $thumbnail }}" alt="Thumbnail atual" class="h-32 w-auto object-cover rounded-md border border-gray-200 dark:border-gray-700">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Imagem atual</p>
                                </div>
                                @endif
                                
                                <!-- Opção 1: URL externa -->
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <div class="relative flex items-stretch flex-grow">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-link text-gray-400"></i>
                                        </div>
                                        <input type="text" wire:model="thumbnail" id="thumbnail" placeholder="https://exemplo.com/imagem.jpg"
                                            class="pl-10 focus:ring-blue-500 focus:border-blue-500 flex-grow block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md">
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">URL da imagem principal de destaque</p>
                                
                                <!-- OU -->
                                <div class="relative my-3">
                                    <div class="absolute inset-0 flex items-center">
                                        <div class="w-full border-t border-gray-300 dark:border-gray-600"></div>
                                    </div>
                                    <div class="relative flex justify-center text-sm">
                                        <span class="px-2 bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400">OU</span>
                                    </div>
                                </div>
                                
                                <!-- Opção 2: Fazer upload -->
                                <div class="mt-1">
                                    <label for="thumbnailUpload" class="flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-md cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                        <div class="space-y-1 text-center">
                                            <i class="fas fa-cloud-upload-alt text-2xl text-gray-400"></i>
                                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                                <span>Arraste ou clique para fazer upload</span>
                                            </div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, WEBP até 2MB</p>
                                        </div>
                                        <input wire:model="thumbnailUpload" id="thumbnailUpload" type="file" accept="image/*" class="sr-only">
                                    </label>
                                    @error('thumbnailUpload') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                    
                                    <!-- Preview da imagem a ser carregada -->
                                    @if($thumbnailUpload)
                                    <div class="mt-3">
                                        <img src="{{ $thumbnailUpload->temporaryUrl() }}" alt="Preview" class="h-32 w-auto object-cover rounded-md">
                                        <p class="text-xs text-green-500 mt-1">Nova imagem selecionada</p>
                                    </div>
                                    @endif
                                </div>
                                @error('thumbnail') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            
                            <!-- Galeria de Imagens -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center">
                                    <i class="fas fa-images text-indigo-500 mr-2"></i> Galeria de Imagens
                                </label>
                                
                                <!-- Imagens existentes -->
                                @if(count($images) > 0)
                                <div class="mt-2 mb-4">
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                        @foreach($images as $index => $imageUrl)
                                        <div class="relative group">
                                            <img src="{{ $imageUrl }}" alt="Galeria {{ $index+1 }}" class="h-24 w-full object-cover rounded-md border border-gray-200 dark:border-gray-700">
                                            <button type="button" wire:click="removeImage({{ $index }})" class="absolute top-1 right-1 bg-white dark:bg-gray-800 rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                                <i class="fas fa-times text-red-500"></i>
                                            </button>
                                        </div>
                                        @endforeach
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Imagens atuais da galeria</p>
                                </div>
                                @endif
                                
                                <!-- Upload de novas imagens -->
                                <div class="mt-1">
                                    <label for="imagesUpload" class="flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-md cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                        <div class="space-y-1 text-center">
                                            <i class="fas fa-cloud-upload-alt text-2xl text-gray-400"></i>
                                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                                <span>Adicione várias imagens à galeria</span>
                                            </div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, WEBP até 2MB cada</p>
                                        </div>
                                        <input wire:model="imagesUpload" id="imagesUpload" type="file" multiple accept="image/*" class="sr-only">
                                    </label>
                                    @error('imagesUpload.*') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                    
                                    <!-- Preview das imagens a serem carregadas -->
                                    @if($imagesUpload && count($imagesUpload) > 0)
                                    <div class="mt-3">
                                        <p class="text-xs text-green-500 mb-2">{{ count($imagesUpload) }} novas imagens selecionadas</p>
                                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                            @foreach($imagesUpload as $image)
                                            <img src="{{ $image->temporaryUrl() }}" alt="Preview" class="h-24 w-full object-cover rounded-md">
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Descrição -->
                            <div class="mb-4">
                                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center">
                                    <i class="fas fa-align-left text-purple-500 mr-2"></i> Descrição
                                </label>
                                <div class="mt-1 rounded-md shadow-sm">
                                    <textarea wire:model="description" id="description" rows="3" 
                                        class="focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md"
                                        placeholder="Descreva o hotel, suas características e diferenciais..."></textarea>
                                </div>
                                @error('description') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            
                            <!-- Destaques e Estado -->
                            <div class="mb-6 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg border border-gray-200 dark:border-gray-600">
                                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3 flex items-center">
                                    <i class="fas fa-toggle-on text-blue-500 mr-2"></i> Estado e Visibilidade
                                </h4>
                                <div class="space-y-2">
                                    <div class="flex items-center">
                                        <div class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
                                            <input type="checkbox" wire:model="is_featured" id="is_featured" 
                                                class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer transition-transform duration-300"/>
                                            <label for="is_featured" 
                                                class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 dark:bg-gray-600 cursor-pointer"></label>
                                        </div>
                                        <label for="is_featured" class="text-sm text-gray-700 dark:text-gray-300">
                                            Hotel em Destaque
                                        </label>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
                                            <input type="checkbox" wire:model="is_active" id="is_active" 
                                                class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer transition-transform duration-300"/>
                                            <label for="is_active" 
                                                class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 dark:bg-gray-600 cursor-pointer"></label>
                                        </div>
                                        <label for="is_active" class="text-sm text-gray-700 dark:text-gray-300">
                                            Hotel Ativo
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Aba de Contacto -->
                        <div x-show="activeTab === 'contact'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                            <div class="bg-blue-50 dark:bg-blue-900/30 p-4 rounded-lg border border-blue-100 dark:border-blue-800 mb-6">
                                <div class="flex items-start">
                                    <i class="fas fa-info-circle text-blue-600 dark:text-blue-400 mt-0.5 mr-3"></i>
                                    <p class="text-sm text-blue-700 dark:text-blue-300">
                                        Estas informações serão exibidas publicamente e usadas para contacto pelos clientes.
                                    </p>
                                </div>
                            </div>

                            <!-- Informações de contacto -->
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 mb-6">
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center">
                                        <i class="fas fa-phone text-green-500 mr-2"></i> Telefone
                                    </label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-phone-alt text-gray-400"></i>
                                        </div>
                                        <input type="text" wire:model="phone" id="phone" placeholder="+244 9XX XXX XXX"
                                            class="pl-10 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                                    </div>
                                    @error('phone') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center">
                                        <i class="fas fa-envelope text-blue-500 mr-2"></i> Email
                                    </label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-at text-gray-400"></i>
                                        </div>
                                        <input type="email" wire:model="email" id="email" placeholder="hotel@exemplo.com"
                                            class="pl-10 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                                    </div>
                                    @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            
                            <!-- Website e Check-in/out -->
                            <div class="mb-6">
                                <label for="website" class="block text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center">
                                    <i class="fas fa-globe text-purple-500 mr-2"></i> Website
                                </label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-link text-gray-400"></i>
                                    </div>
                                    <input type="url" wire:model="website" id="website" placeholder="https://www.exemplo.com"
                                        class="pl-10 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                                </div>
                                @error('website') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg border border-gray-200 dark:border-gray-600 mb-6">
                                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3 flex items-center">
                                    <i class="fas fa-clock text-amber-500 mr-2"></i> Horários de Check-in/Check-out
                                </h4>
                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                    <div>
                                        <label for="check_in_time" class="block text-xs font-medium text-gray-700 dark:text-gray-400">Hora de Check-in</label>
                                        <div class="mt-1 relative rounded-md shadow-sm">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-sign-in-alt text-gray-400"></i>
                                            </div>
                                            <input type="time" wire:model="check_in_time" id="check_in_time" 
                                                class="pl-10 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                                        </div>
                                        @error('check_in_time') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label for="check_out_time" class="block text-xs font-medium text-gray-700 dark:text-gray-400">Hora de Check-out</label>
                                        <div class="mt-1 relative rounded-md shadow-sm">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-sign-out-alt text-gray-400"></i>
                                            </div>
                                            <input type="time" wire:model="check_out_time" id="check_out_time" 
                                                class="pl-10 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                                        </div>
                                        @error('check_out_time') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Campo Slug -->
                            <div class="mb-4">
                                <label for="slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center">
                                    <i class="fas fa-link text-gray-500 mr-2"></i> Slug (URL amigável)
                                </label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-pen-alt text-gray-400"></i>
                                    </div>
                                    <input type="text" wire:model="slug" id="slug" placeholder="nome-do-hotel"
                                        class="pl-10 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                                </div>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Utilizado para gerar uma URL bonita e SEO-friendly.</p>
                                @error('slug') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        
                        <!-- Aba de Localização -->
                        <div x-show="activeTab === 'location'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                            <div class="mb-6">
                                <label for="locationId" class="block text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center">
                                    <i class="fas fa-map-marker-alt text-red-500 mr-2"></i> Província/Localização
                                </label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-map text-gray-400"></i>
                                    </div>
                                    <select wire:model="locationId" id="locationId" 
                                        class="pl-10 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                                        <option value="">Selecione a província/município</option>
                                        @foreach($locations as $location)
                                            <option value="{{ $location->id }}">{{ $location->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('locationId') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            
                            <!-- Endereço -->
                            <div class="mb-6">
                                <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center">
                                    <i class="fas fa-map-pin text-orange-500 mr-2"></i> Endereço Completo
                                </label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-road text-gray-400"></i>
                                    </div>
                                    <input type="text" wire:model="address" id="address" placeholder="Rua, Número, Bairro..."
                                        class="pl-10 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                                </div>
                                @error('address') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            
                            <!-- Coordenadas geográficas -->
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg border border-gray-200 dark:border-gray-600 mb-6">
                                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3 flex items-center">
                                    <i class="fas fa-map-marked-alt text-blue-500 mr-2"></i> Coordenadas GPS
                                </h4>
                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                    <div>
                                        <label for="latitude" class="block text-xs font-medium text-gray-700 dark:text-gray-400">Latitude</label>
                                        <div class="mt-1 relative rounded-md shadow-sm">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-arrows-alt-v text-gray-400"></i>
                                            </div>
                                            <input type="text" wire:model.defer="latitude" id="latitude" placeholder="-8.838333"
                                                class="pl-10 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md"
                                                x-on:change="validateCoords()">
                                        </div>
                                        @error('latitude') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label for="longitude" class="block text-xs font-medium text-gray-700 dark:text-gray-400">Longitude</label>
                                        <div class="mt-1 relative rounded-md shadow-sm">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-arrows-alt-h text-gray-400"></i>
                                            </div>
                                            <input type="text" wire:model.defer="longitude" id="longitude" placeholder="13.235889"
                                                class="pl-10 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md"
                                                x-on:change="validateCoords()">
                                        </div>
                                        @error('longitude') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <label for="mapLink" class="block text-xs font-medium text-gray-700 dark:text-gray-400">Link do Mapa (Google Maps, etc)</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fab fa-google text-gray-400"></i>
                                        </div>
                                        <input type="url" wire:model.defer="mapLink" id="mapLink" placeholder="https://goo.gl/maps/exemplo"
                                            class="pl-10 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md"
                                            x-on:change="extractCoordinates($event.target.value)">
                                    </div>
                                    @error('mapLink') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                    <p class="mt-1 text-xs text-green-500 dark:text-green-400" x-text="coordsMessage"></p>
                                </div>
                                <div class="mt-2 text-xs text-blue-600 dark:text-blue-400">
                                    <p class="flex items-center mb-1"><i class="fas fa-info-circle mr-1"></i> As coordenadas GPS ajudam os hóspedes a localizar o hotel com precisão.</p>
                                    <p class="flex items-center"><i class="fas fa-exclamation-triangle text-amber-500 mr-1"></i> Para links encurtados (goo.gl), abra o link no navegador e copie o URL completo da barra de endereços.</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Aba de Funcionalidades/Features -->
                        <div x-show="activeTab === 'features'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                            <!-- Imagem Thumbnail -->
                            <div class="mb-6">
                                <label for="thumbnail" class="block text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center">
                                    <i class="fas fa-image text-purple-500 mr-2"></i> Imagem Principal
                                </label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-camera text-gray-400"></i>
                                    </div>
                                    <input type="url" wire:model="thumbnail" id="thumbnail" placeholder="https://exemplo.com/imagem.jpg"
                                        class="pl-10 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                                </div>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">URL da imagem principal que aparecerá nos resultados de busca e listagens.</p>
                                @error('thumbnail') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <!-- Status do Hotel -->
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg border border-gray-200 dark:border-gray-600 mb-6">
                                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3 flex items-center">
                                    <i class="fas fa-toggle-on text-green-500 mr-2"></i> Status do Hotel
                                </h4>
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between">
                                        <label for="is_featured" class="flex items-center cursor-pointer">
                                            <i class="fas fa-star text-yellow-500 mr-2"></i>
                                            <span class="text-sm text-gray-700 dark:text-gray-300">Hotel em Destaque</span>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 ml-2">(Aparecerá na página inicial)</p>
                                        </label>
                                        <div class="relative inline-block w-12 align-middle select-none transition duration-200 ease-in">
                                            <input type="checkbox" wire:model="is_featured" id="is_featured" 
                                                class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white dark:bg-gray-300 border-4 appearance-none cursor-pointer focus:outline-none focus:ring-0"/>
                                            <label for="is_featured" 
                                                class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 dark:bg-gray-600 cursor-pointer"></label>
                                        </div>
                                    </div>
                                    @error('is_featured') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                    
                                    <div class="flex items-center justify-between">
                                        <label for="is_active" class="flex items-center cursor-pointer">
                                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                            <span class="text-sm text-gray-700 dark:text-gray-300">Hotel Ativo</span>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 ml-2">(Visível para reservas)</p>
                                        </label>
                                        <div class="relative inline-block w-12 align-middle select-none transition duration-200 ease-in">
                                            <input type="checkbox" wire:model="is_active" id="is_active" 
                                                class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white dark:bg-gray-300 border-4 appearance-none cursor-pointer focus:outline-none focus:ring-0"/>
                                            <label for="is_active" 
                                                class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 dark:bg-gray-600 cursor-pointer"></label>
                                        </div>
                                    </div>
                                    @error('is_active') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Amenidades -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center mb-3">
                                    <i class="fas fa-concierge-bell text-amber-500 mr-2"></i> Comodidades do Hotel
                                </label>
                                
                                @if(isset($hotelAmenities) && count($hotelAmenities) > 0)
                                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                                        @foreach($hotelAmenities as $amenity)
                                            <div class="flex items-start space-x-2">
                                                <input type="checkbox" 
                                                    wire:model="amenities" 
                                                    value="{{ $amenity['id'] }}" 
                                                    id="amenity-{{ $amenity['id'] }}"
                                                    class="h-4 w-4 mt-1 text-blue-600 focus:ring-blue-500 rounded border-gray-300 dark:border-gray-500 dark:bg-gray-700">
                                                <label for="amenity-{{ $amenity['id'] }}" class="flex items-center text-sm text-gray-700 dark:text-gray-300">
                                                    @if($amenity['icon'])
                                                        <i class="{{ $amenity['icon'] }} text-blue-500 mr-2"></i>
                                                    @endif
                                                    <span>{{ $amenity['name'] }}</span>
                                                    @if($amenity['description'])
                                                        <span x-data="{ tooltip: false }" class="relative ml-1">
                                                            <i @mouseenter="tooltip = true" @mouseleave="tooltip = false" class="fas fa-info-circle text-gray-400 hover:text-blue-500 cursor-help"></i>
                                                            <div x-show="tooltip" class="absolute z-50 -mt-1 ml-2 w-48 p-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded shadow-lg text-xs" x-transition>
                                                                {{ $amenity['description'] }}
                                                            </div>
                                                        </span>
                                                    @endif
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="p-4 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-center">
                                        <p class="text-gray-500 dark:text-gray-400">
                                            <i class="fas fa-info-circle mr-2"></i> Não há comodidades de hotel disponíveis.
                                            Adicione-as através da gestão de comodidades.
                                        </p>
                                    </div>
                                @endif
                                @error('amenities') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            
                            <!-- Preço e Classificação -->
                            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 mb-6">
                                <div>
                                    <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center">
                                        <i class="fas fa-dollar-sign text-green-500 mr-2"></i> Preço Base (AOA)
                                    </label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">AOA</span>
                                        </div>
                                        <input type="number" wire:model="price" id="price" min="0" step="500"
                                            class="pl-14 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                                    </div>
                                    @error('price') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="rating" class="block text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center">
                                        <i class="fas fa-star text-yellow-400 mr-2"></i> Classificação (Estrelas)
                                    </label>
                                    <div class="mt-1">
                                        <select wire:model="rating" id="rating"
                                            class="focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                                            <option value="">Selecione</option>
                                            <option value="1">1 Estrela</option>
                                            <option value="2">2 Estrelas</option>
                                            <option value="3">3 Estrelas</option>
                                            <option value="4">4 Estrelas</option>
                                            <option value="5">5 Estrelas</option>
                                        </select>
                                        <div class="mt-1 flex text-yellow-400">
                                            <template x-for="i in parseInt(document.getElementById('rating')?.value || 0)">
                                                <i class="fas fa-star"></i>
                                            </template>
                                        </div>
                                    </div>
                                    @error('rating') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        
                        <style>
                            /* CSS para os toggles de switch */
                            .toggle-checkbox:checked {
                                right: 0;
                                border-color: #68D391;
                            }
                            .toggle-checkbox:checked + .toggle-label {
                                background-color: #68D391;
                            }
                            .toggle-checkbox:focus {
                                outline: none;
                                box-shadow: none;
                            }
                            .toggle-label {
                                transition: background-color 0.2s ease;
                            }
                        </style>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 flex flex-col sm:flex-row justify-end gap-2">
                    <button type="button" @click="open = false" class="w-full sm:w-auto inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm">
                        Cancelar
                    </button>
                    <button type="submit" class="w-full sm:w-auto inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm">
                        {{ $hotelId ? 'Atualizar' : 'Adicionar' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

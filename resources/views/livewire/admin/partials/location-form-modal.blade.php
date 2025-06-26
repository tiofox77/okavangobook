<!-- Modal de criação/edição de localizações (Design 2025) -->
<div class="fixed inset-0 overflow-y-auto z-50" x-show="$wire.showModal"
     x-transition:enter="ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     aria-labelledby="modalFormLocation" 
     role="dialog" 
     aria-modal="true">
     
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Overlay com blur de fundo (glassmorphism) -->
        <div class="fixed inset-0 transition-opacity" aria-hidden="true" x-show="$wire.showModal">
            <div class="absolute inset-0 bg-gradient-to-br from-gray-900 to-blue-900 backdrop-blur-sm opacity-80"></div>
        </div>
        
        <!-- Elemento para centralização vertical -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <!-- Modal com efeito de entrada -->
        <div class="relative inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl text-left overflow-hidden shadow-2xl border border-white/50 dark:border-gray-700/50 transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full"
             x-show="$wire.showModal"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
            
            <!-- Cabeçalho do modal com gradiente -->
            <div class="bg-gradient-to-r from-blue-500/10 to-indigo-500/10 dark:from-blue-800/30 dark:to-indigo-900/30 px-6 py-4 flex justify-between items-center">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white tracking-tight" id="modalFormLocation">
                    <span class="inline-block">{{ $locationId ? 'Editar Localização' : 'Nova Localização' }}</span>
                    <div class="h-1 w-10 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full mt-1"></div>
                </h3>
                <button type="button" 
                        class="text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white p-2 rounded-full hover:bg-gray-100/50 dark:hover:bg-gray-700/50 transition-colors duration-200" 
                        wire:click="closeModal">
                    <span class="sr-only">Fechar</span>
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <!-- Formulário do modal com design moderno -->
            <div class="bg-white dark:bg-gray-800 p-8 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-white via-white to-gray-100 dark:from-gray-800 dark:via-gray-800 dark:to-gray-900">
                <form wire:submit.prevent="save" class="space-y-6">
                    <!-- Nome da Localização -->
                    <div class="group">
                        <label for="name" class="inline-flex items-center text-sm font-medium mb-2 text-gray-700 dark:text-gray-300 group-focus-within:text-blue-600 dark:group-focus-within:text-blue-400 transition-colors duration-200">
                            <span class="flex items-center justify-center w-5 h-5 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-md mr-2 text-xs text-white">
                                <i class="fas fa-map-marker-alt"></i>
                            </span>
                            Nome da Localização
                        </label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <input type="text" wire:model="name" id="name" 
                                class="block w-full pl-10 pr-12 py-2 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700/50 dark:text-white shadow-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:focus:border-blue-500 sm:text-sm" 
                                placeholder="Ex: Luanda, Benguela, Huambo...">
                        </div>
                        @error('name') <span class="text-red-500 text-xs mt-1 flex items-center"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Província -->
                        <div class="group">
                            <label for="province" class="inline-flex items-center text-sm font-medium mb-2 text-gray-700 dark:text-gray-300 group-focus-within:text-blue-600 dark:group-focus-within:text-blue-400 transition-colors duration-200">
                                <span class="flex items-center justify-center w-5 h-5 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-md mr-2 text-xs text-white">
                                    <i class="fas fa-flag"></i>
                                </span>
                                Província
                            </label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                    <i class="fas fa-flag"></i>
                                </div>
                                <input type="text" wire:model="province" id="province" 
                                    class="block w-full pl-10 pr-12 py-2 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700/50 dark:text-white shadow-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:focus:border-blue-500 sm:text-sm" 
                                    placeholder="Ex: Luanda, Benguela, Huíla...">
                            </div>
                            @error('province') <span class="text-red-500 text-xs mt-1 flex items-center"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</span> @enderror
                        </div>

                        <!-- Capital -->
                        <div class="group">
                            <label for="capital" class="inline-flex items-center text-sm font-medium mb-2 text-gray-700 dark:text-gray-300 group-focus-within:text-blue-600 dark:group-focus-within:text-blue-400 transition-colors duration-200">
                                <span class="flex items-center justify-center w-5 h-5 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-md mr-2 text-xs text-white">
                                    <i class="fas fa-city"></i>
                                </span>
                                Capital
                            </label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                    <i class="fas fa-city"></i>
                                </div>
                                <input type="text" wire:model="capital" id="capital" 
                                    class="block w-full pl-10 pr-12 py-2 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700/50 dark:text-white shadow-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:focus:border-blue-500 sm:text-sm" 
                                    placeholder="Ex: Luanda, Benguela, Lubango...">
                            </div>
                            @error('capital') <span class="text-red-500 text-xs mt-1 flex items-center"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</span> @enderror
                        </div>
                        
                        <!-- População -->
                        <div class="group">
                            <label for="population" class="inline-flex items-center text-sm font-medium mb-2 text-gray-700 dark:text-gray-300 group-focus-within:text-blue-600 dark:group-focus-within:text-blue-400 transition-colors duration-200">
                                <span class="flex items-center justify-center w-5 h-5 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-md mr-2 text-xs text-white">
                                    <i class="fas fa-users"></i>
                                </span>
                                População
                            </label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                    <i class="fas fa-users"></i>
                                </div>
                                <input type="number" wire:model="population" id="population" 
                                    class="block w-full pl-10 pr-12 py-2 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700/50 dark:text-white shadow-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:focus:border-blue-500 sm:text-sm" 
                                    placeholder="Ex: 2500000">
                            </div>
                            @error('population') <span class="text-red-500 text-xs mt-1 flex items-center"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</span> @enderror
                        </div>
                        
                        <!-- Destaque -->
                        <div class="flex items-center h-full md:pt-6">
                            <div
                                x-data="{
                                    localFeatured: $wire.isFeatured
                                }"
                                class="flex items-center">
                                <div class="relative inline-block h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
                                    :class="{'bg-blue-600': localFeatured, 'bg-gray-200 dark:bg-gray-700': !localFeatured}"
                                    @click="localFeatured = !localFeatured; $wire.isFeatured = localFeatured">
                                    <span class="sr-only">Usar preferência</span>
                                    <span aria-hidden="true"
                                        class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                                        :class="{'translate-x-5': localFeatured, 'translate-x-0': !localFeatured}">
                                    </span>
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="isFeatured" class="font-medium text-gray-700 dark:text-gray-300">
                                        <i class="fas fa-star text-yellow-500 mr-1"></i>
                                        Localização em Destaque
                                    </label>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Aparecerá com prioridade nas sugestões de destinos</p>
                                </div>
                            </div>
                            @error('isFeatured') <span class="text-red-500 text-xs mt-1 flex items-center"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</span> @enderror
                        </div>
                    </div>
                    
                    <!-- Descrição -->
                    <div class="group">
                        <label for="description" class="inline-flex items-center text-sm font-medium mb-2 text-gray-700 dark:text-gray-300 group-focus-within:text-blue-600 dark:group-focus-within:text-blue-400 transition-colors duration-200">
                            <span class="flex items-center justify-center w-5 h-5 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-md mr-2 text-xs text-white">
                                <i class="fas fa-file-alt"></i>
                            </span>
                            Descrição
                        </label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 pt-2 flex items-start pointer-events-none text-gray-400">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <textarea wire:model="description" id="description" rows="4" 
                                class="block w-full pl-10 pr-3 py-2 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700/50 dark:text-white shadow-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:focus:border-blue-500 sm:text-sm" 
                                placeholder="Descrição da localização, pontos turísticos, características especiais..."></textarea>
                        </div>
                        @error('description') <span class="text-red-500 text-xs mt-1 flex items-center"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</span> @enderror
                    </div>
                    
                    <!-- Imagem com upload moderno -->
                    <div class="group">
                        <label class="inline-flex items-center text-sm font-medium mb-2 text-gray-700 dark:text-gray-300 group-focus-within:text-blue-600 dark:group-focus-within:text-blue-400 transition-colors duration-200">
                            <span class="flex items-center justify-center w-5 h-5 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-md mr-2 text-xs text-white">
                                <i class="fas fa-image"></i>
                            </span>
                            Imagem
                        </label>

                        <!-- Upload moderno com drag & drop -->
                        <div 
                            x-data="{ 
                                isHovering: false,
                                isUploading: false,
                                progress: 0,
                                handleFiles(e) {
                                    if (e.dataTransfer?.files?.length) {
                                        const fileInput = document.getElementById('newImage');
                                        fileInput.files = e.dataTransfer.files;
                                        const event = new Event('change', { bubbles: true });
                                        fileInput.dispatchEvent(event);
                                    }
                                }
                            }"
                            x-on:dragover.prevent="isHovering = true"
                            x-on:dragleave.prevent="isHovering = false"
                            x-on:drop.prevent="isHovering = false; handleFiles($event)"
                            class="relative">
                            
                            <!-- Área de upload estilizada -->
                            <label 
                                for="newImage"
                                :class="{'bg-blue-50 dark:bg-blue-900/30 border-blue-200 dark:border-blue-700': isHovering}"
                                class="group flex flex-col items-center justify-center w-full h-32 px-4 transition-all duration-300 border-2 border-dashed rounded-xl border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 cursor-pointer hover:bg-gradient-to-br hover:from-blue-50 hover:to-indigo-50 dark:hover:from-blue-900/20 dark:hover:to-indigo-900/20">
                                
                                <!-- Ícone dinâmico -->
                                <div class="relative z-10">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6 text-center">
                                        <div class="p-3 mb-2 rounded-full bg-gradient-to-r from-blue-500/10 to-indigo-500/10 dark:from-blue-500/20 dark:to-indigo-500/20 transition-all duration-300 group-hover:scale-110 group-hover:from-blue-500/20 group-hover:to-indigo-500/20 dark:group-hover:from-blue-500/30 dark:group-hover:to-indigo-500/30">
                                            <i class="fas fa-cloud-upload-alt text-xl text-blue-500 dark:text-blue-400"></i>
                                        </div>
                                        <p class="mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Clique aqui para <span class="text-blue-600 dark:text-blue-400">escolher uma imagem</span> ou arraste e solte</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            Formatos aceites: PNG, JPG, JPEG, WEBP (máx. 5MB)
                                        </p>
                                    </div>
                                </div>
                                
                                <!-- Animação de partículas no fundo (apenas visual) -->
                                <div class="absolute inset-0 overflow-hidden rounded-xl pointer-events-none">
                                    <div class="absolute -right-1 top-1 w-10 h-10 bg-gradient-to-br from-blue-400 to-indigo-400 rounded-full opacity-20 blur-xl dark:opacity-10 group-hover:opacity-30 dark:group-hover:opacity-20 transition-all duration-500"></div>
                                    <div class="absolute left-2 -bottom-4 w-16 h-16 bg-gradient-to-br from-blue-300 to-indigo-500 rounded-full opacity-10 blur-xl dark:opacity-5 group-hover:opacity-20 dark:group-hover:opacity-15 transition-all duration-500 delay-100"></div>
                                </div>
                            </label>

                            <!-- Input file oculto -->
                            <input 
                                type="file" 
                                wire:model="newImage" 
                                id="newImage" 
                                accept="image/*"
                                class="hidden">
                        </div>
                        @error('newImage') <span class="text-red-500 text-xs mt-1 flex items-center"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</span> @enderror
                        
                        <!-- Preview de imagens melhorado -->
                        <div class="mt-4">
                            <!-- Progress bar de upload -->
                            <div wire:loading wire:target="newImage" class="mb-3">
                                <div class="w-full bg-gray-200 rounded-full h-1.5 dark:bg-gray-700">
                                    <div class="bg-gradient-to-r from-blue-500 to-indigo-500 h-1.5 rounded-full animate-pulse" style="width: 100%"></div>
                                </div>
                                <p class="text-xs text-blue-500 dark:text-blue-400 mt-1 flex items-center">
                                    <i class="fas fa-spinner fa-spin mr-1"></i> 
                                    A carregar imagem...
                                </p>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Preview da imagem atual -->
                                @if ($image && !$newImage)
                                    <div class="relative group overflow-hidden rounded-lg shadow-md border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 transition-all duration-300">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-all duration-300 flex items-end">
                                            <div class="p-3 w-full">
                                                <span class="text-xs font-medium text-white">Imagem atual</span>
                                            </div>
                                        </div>
                                        <img src="{{ asset('storage/'.$image) }}" alt="Preview da imagem" 
                                            class="h-40 w-full object-cover transition-all duration-300 group-hover:scale-105">
                                    </div>
                                @endif
                                
                                <!-- Preview da nova imagem -->
                                @if ($newImage)
                                    <div class="relative group overflow-hidden rounded-lg shadow-md border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 transition-all duration-300">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-all duration-300 flex items-end">
                                            <div class="p-3 w-full flex justify-between items-center">
                                                <span class="text-xs font-medium text-white">Nova imagem</span>
                                                <button type="button" wire:click="$set('newImage', null)" class="p-1 bg-red-500/80 hover:bg-red-600 rounded-full text-white transition-colors duration-200">
                                                    <i class="fas fa-times text-xs"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <img src="{{ $newImage->temporaryUrl() }}" alt="Preview da nova imagem" 
                                            class="h-40 w-full object-cover transition-all duration-300 group-hover:scale-105">
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            
            <!-- Botões de ação (design 2025) -->
            <div class="bg-gradient-to-b from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 px-6 py-5 flex justify-end space-x-4 border-t border-gray-100 dark:border-gray-700 rounded-b-2xl">
                <button 
                    wire:click="closeModal"
                    type="button" 
                    class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                    <i class="fas fa-times mr-2"></i>
                    Cancelar
                </button>
                <button 
                    wire:click="save"
                    type="button" 
                    class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                    <i class="fas fa-save mr-2"></i>
                    {{ $locationId ? 'Guardar Alterações' : 'Adicionar Localização' }}
                </button>
            </div>
        </div>
    </div>
</div>

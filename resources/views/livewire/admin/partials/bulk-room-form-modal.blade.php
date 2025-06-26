<!-- Modal de criação em lote (Design 2025) -->
<div class="fixed inset-0 overflow-y-auto z-50" x-show="$wire.showBulkModal"
     x-transition:enter="ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     aria-labelledby="modalBulkRoom" 
     role="dialog" 
     aria-modal="true">
     
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Overlay com blur de fundo (glassmorphism) -->
        <div class="fixed inset-0 transition-opacity" aria-hidden="true" x-show="$wire.showBulkModal">
            <div class="absolute inset-0 bg-gradient-to-br from-gray-900 to-blue-900 backdrop-blur-sm opacity-80"></div>
        </div>
        
        <!-- Elemento para centralização vertical -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <!-- Modal com efeito de entrada -->
        <div class="relative inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl text-left overflow-hidden shadow-2xl border border-white/50 dark:border-gray-700/50 transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full"
             x-show="$wire.showBulkModal"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
            
            <!-- Cabeçalho do modal com gradiente -->
            <div class="bg-gradient-to-r from-green-500/10 to-blue-500/10 dark:from-green-800/30 dark:to-blue-900/30 px-6 py-4 flex justify-between items-center">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white tracking-tight" id="modalBulkRoom">
                    <span class="inline-block flex items-center">
                        <i class="fas fa-layer-group mr-3 text-green-600"></i>
                        Criar Quartos em Lote
                    </span>
                    <div class="h-1 w-10 bg-gradient-to-r from-green-500 to-blue-500 rounded-full mt-1"></div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 font-normal">
                        Crie múltiplos quartos de uma só vez com numeração automática
                    </p>
                </h3>
                <button type="button" 
                        class="text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white p-2 rounded-full hover:bg-gray-100/50 dark:hover:bg-gray-700/50 transition-colors duration-200" 
                        wire:click="closeBulkModal">
                    <span class="sr-only">Fechar</span>
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <!-- Formulário do modal com design moderno -->
            <div class="bg-white dark:bg-gray-800 p-8 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-white via-white to-gray-100 dark:from-gray-800 dark:via-gray-800 dark:to-gray-900">
                <form wire:submit.prevent="bulkCreate" class="space-y-8">
                    
                    <!-- Hotel e Tipo de Quarto -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Hotel -->
                        <div class="group">
                            <label for="bulk_hotel_id" class="inline-flex items-center text-sm font-medium mb-2 text-gray-700 dark:text-gray-300 group-focus-within:text-green-600 dark:group-focus-within:text-green-400 transition-colors duration-200">
                                <span class="flex items-center justify-center w-5 h-5 bg-gradient-to-r from-green-500 to-blue-500 rounded-md mr-2 text-xs text-white">
                                    <i class="fas fa-hotel text-xs"></i>
                                </span>
                                Hotel <span class="text-red-500 ml-1">*</span>
                            </label>
                            <select id="bulk_hotel_id" wire:model.live="bulkData.hotel_id" 
                                    class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:border-green-500 dark:focus:border-green-400 focus:ring-4 focus:ring-green-100 dark:focus:ring-green-900/50 transition-all duration-200 shadow-sm hover:shadow-md group-hover:border-green-300 dark:group-hover:border-green-500">
                                <option value="">Seleccionar Hotel</option>
                                @foreach($hotels as $hotel)
                                    <option value="{{ $hotel->id }}">{{ $hotel->name }}</option>
                                @endforeach
                            </select>
                            @error('bulkData.hotel_id') 
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                        
                        <!-- Tipo de Quarto -->
                        <div class="group">
                            <label for="bulk_room_type_id" class="inline-flex items-center text-sm font-medium mb-2 text-gray-700 dark:text-gray-300 group-focus-within:text-green-600 dark:group-focus-within:text-green-400 transition-colors duration-200">
                                <span class="flex items-center justify-center w-5 h-5 bg-gradient-to-r from-green-500 to-blue-500 rounded-md mr-2 text-xs text-white">
                                    <i class="fas fa-bed text-xs"></i>
                                </span>
                                Tipo de Quarto <span class="text-red-500 ml-1">*</span>
                            </label>
                            <select id="bulk_room_type_id" wire:model="bulkData.room_type_id" 
                                    class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:border-green-500 dark:focus:border-green-400 focus:ring-4 focus:ring-green-100 dark:focus:ring-green-900/50 transition-all duration-200 shadow-sm hover:shadow-md group-hover:border-green-300 dark:group-hover:border-green-500">
                                <option value="">Seleccionar Tipo</option>
                                @foreach($roomTypes as $roomType)
                                    <option value="{{ $roomType->id }}">{{ $roomType->name }}</option>
                                @endforeach
                            </select>
                            @error('bulkData.room_type_id') 
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Configuração de Numeração -->
                    <div class="bg-gradient-to-br from-green-50 to-blue-50 dark:from-green-900/20 dark:to-blue-900/20 p-6 rounded-2xl border border-green-200/50 dark:border-green-700/50 shadow-sm">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <span class="flex items-center justify-center w-6 h-6 bg-gradient-to-r from-green-500 to-blue-500 rounded-lg mr-3 text-xs text-white">
                                <i class="fas fa-hashtag"></i>
                            </span>
                            Configuração de Numeração
                        </h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Prefixo -->
                            <div class="group">
                                <label for="bulk_prefix" class="inline-flex items-center text-sm font-medium mb-2 text-gray-700 dark:text-gray-300 group-focus-within:text-green-600 dark:group-focus-within:text-green-400 transition-colors duration-200">
                                    <span class="flex items-center justify-center w-5 h-5 bg-gradient-to-r from-green-500 to-blue-500 rounded-md mr-2 text-xs text-white">
                                        <i class="fas fa-font text-xs"></i>
                                    </span>
                                    Prefixo
                                </label>
                                <input type="text" id="bulk_prefix" wire:model.live="bulkData.prefix" 
                                       placeholder="ex: A, B, SUITE..."
                                       class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:border-green-500 dark:focus:border-green-400 focus:ring-4 focus:ring-green-100 dark:focus:ring-green-900/50 transition-all duration-200 shadow-sm hover:shadow-md group-hover:border-green-300 dark:group-hover:border-green-500">
                                @error('bulkData.prefix') 
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Andar -->
                            <div class="group">
                                <label for="bulk_floor" class="inline-flex items-center text-sm font-medium mb-2 text-gray-700 dark:text-gray-300 group-focus-within:text-green-600 dark:group-focus-within:text-green-400 transition-colors duration-200">
                                    <span class="flex items-center justify-center w-5 h-5 bg-gradient-to-r from-green-500 to-blue-500 rounded-md mr-2 text-xs text-white">
                                        <i class="fas fa-building text-xs"></i>
                                    </span>
                                    Andar <span class="text-red-500 ml-1">*</span>
                                </label>
                                <input type="number" id="bulk_floor" wire:model.live="bulkData.floor" 
                                       placeholder="ex: 1, 2, 3..."
                                       class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:border-green-500 dark:focus:border-green-400 focus:ring-4 focus:ring-green-100 dark:focus:ring-green-900/50 transition-all duration-200 shadow-sm hover:shadow-md group-hover:border-green-300 dark:group-hover:border-green-500">
                                @error('bulkData.floor') 
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Número Inicial -->
                            <div class="group">
                                <label for="bulk_start_number" class="inline-flex items-center text-sm font-medium mb-2 text-gray-700 dark:text-gray-300 group-focus-within:text-green-600 dark:group-focus-within:text-green-400 transition-colors duration-200">
                                    <span class="flex items-center justify-center w-5 h-5 bg-gradient-to-r from-green-500 to-blue-500 rounded-md mr-2 text-xs text-white">
                                        <i class="fas fa-play text-xs"></i>
                                    </span>
                                    Número Inicial <span class="text-red-500 ml-1">*</span>
                                </label>
                                <input type="number" id="bulk_start_number" wire:model.live="bulkData.start_number" 
                                       placeholder="ex: 1, 101, 201..."
                                       class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:border-green-500 dark:focus:border-green-400 focus:ring-4 focus:ring-green-100 dark:focus:ring-green-900/50 transition-all duration-200 shadow-sm hover:shadow-md group-hover:border-green-300 dark:group-hover:border-green-500">
                                @error('bulkData.start_number') 
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Número Final -->
                            <div class="group">
                                <label for="bulk_end_number" class="inline-flex items-center text-sm font-medium mb-2 text-gray-700 dark:text-gray-300 group-focus-within:text-green-600 dark:group-focus-within:text-green-400 transition-colors duration-200">
                                    <span class="flex items-center justify-center w-5 h-5 bg-gradient-to-r from-green-500 to-blue-500 rounded-md mr-2 text-xs text-white">
                                        <i class="fas fa-stop text-xs"></i>
                                    </span>
                                    Número Final <span class="text-red-500 ml-1">*</span>
                                </label>
                                <input type="number" id="bulk_end_number" wire:model.live="bulkData.end_number" 
                                       placeholder="ex: 10, 110, 210..."
                                       class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:border-green-500 dark:focus:border-green-400 focus:ring-4 focus:ring-green-100 dark:focus:ring-green-900/50 transition-all duration-200 shadow-sm hover:shadow-md group-hover:border-green-300 dark:group-hover:border-green-500">
                                @error('bulkData.end_number') 
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Pré-visualização -->
                    @if($this->bulkPreview)
                        <div class="bg-gradient-to-br from-blue-50 to-purple-50 dark:from-blue-900/20 dark:to-purple-900/20 p-6 rounded-2xl border border-blue-200/50 dark:border-blue-700/50 shadow-sm">
                            <h4 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-4 flex items-center">
                                <span class="flex items-center justify-center w-6 h-6 bg-gradient-to-r from-blue-500 to-purple-500 rounded-lg mr-3 text-xs text-white">
                                    <i class="fas fa-eye"></i>
                                </span>
                                Pré-visualização ({{ count($this->bulkPreview) }} quartos)
                            </h4>
                            <div class="grid grid-cols-6 sm:grid-cols-8 md:grid-cols-10 gap-2">
                                @foreach($this->bulkPreview as $roomNumber)
                                    <span class="bg-gradient-to-r from-blue-100 to-purple-100 dark:from-blue-800 dark:to-purple-800 text-blue-800 dark:text-blue-100 text-xs px-3 py-2 rounded-lg text-center font-medium shadow-sm border border-blue-200/50 dark:border-blue-600/50 hover:shadow-md transition-shadow duration-200">
                                        {{ $roomNumber }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Configurações Padrão -->
                    <div class="bg-gradient-to-br from-gray-50 to-slate-50 dark:from-gray-900/20 dark:to-slate-900/20 p-6 rounded-2xl border border-gray-200/50 dark:border-gray-700/50 shadow-sm">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <span class="flex items-center justify-center w-6 h-6 bg-gradient-to-r from-gray-500 to-slate-500 rounded-lg mr-3 text-xs text-white">
                                <i class="fas fa-cog"></i>
                            </span>
                            Configurações Padrão
                        </h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Disponível -->
                            <div class="flex items-center">
                                <div x-data="{ 
                                    localActive: $wire.bulkData.is_available,
                                    toggle() {
                                        this.localActive = !this.localActive;
                                        $wire.set('bulkData.is_available', this.localActive);
                                    }
                                }" class="flex items-center space-x-3">
                                    <button type="button" @click="toggle()" 
                                            class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                                            :class="localActive ? 'bg-green-500' : 'bg-gray-300 dark:bg-gray-600'">
                                        <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform duration-200"
                                              :class="localActive ? 'translate-x-6' : 'translate-x-1'"></span>
                                    </button>
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 cursor-pointer" @click="toggle()">
                                        Disponível
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Limpo -->
                            <div class="flex items-center">
                                <div x-data="{ 
                                    localActive: $wire.bulkData.is_clean,
                                    toggle() {
                                        this.localActive = !this.localActive;
                                        $wire.set('bulkData.is_clean', this.localActive);
                                    }
                                }" class="flex items-center space-x-3">
                                    <button type="button" @click="toggle()" 
                                            class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                                            :class="localActive ? 'bg-blue-500' : 'bg-gray-300 dark:bg-gray-600'">
                                        <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform duration-200"
                                              :class="localActive ? 'translate-x-6' : 'translate-x-1'"></span>
                                    </button>
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 cursor-pointer" @click="toggle()">
                                        Limpo
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Em Manutenção -->
                            <div class="flex items-center">
                                <div x-data="{ 
                                    localActive: $wire.bulkData.is_maintenance,
                                    toggle() {
                                        this.localActive = !this.localActive;
                                        $wire.set('bulkData.is_maintenance', this.localActive);
                                    }
                                }" class="flex items-center space-x-3">
                                    <button type="button" @click="toggle()" 
                                            class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                                            :class="localActive ? 'bg-red-500' : 'bg-gray-300 dark:bg-gray-600'">
                                        <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform duration-200"
                                              :class="localActive ? 'translate-x-6' : 'translate-x-1'"></span>
                                    </button>
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 cursor-pointer" @click="toggle()">
                                        Em Manutenção
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            
            <!-- Botões de ação (design 2025) -->
            <div class="bg-gradient-to-b from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 px-6 py-5 flex justify-end space-x-4 border-t border-gray-100 dark:border-gray-700 rounded-b-2xl">
                <button type="button" 
                        wire:click="closeBulkModal"
                        class="px-6 py-3 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-600 hover:border-gray-400 dark:hover:border-gray-500 focus:outline-none focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-800 transition-all duration-200 shadow-sm hover:shadow-md">
                    <i class="fas fa-times mr-2"></i>
                    Cancelar
                </button>
                <button type="button" 
                        wire:click="bulkCreate"
                        wire:loading.attr="disabled"
                        class="px-6 py-3 text-sm font-medium text-white bg-gradient-to-r from-green-500 to-blue-500 hover:from-green-600 hover:to-blue-600 border-2 border-transparent rounded-xl focus:outline-none focus:ring-4 focus:ring-green-100 dark:focus:ring-green-900/50 transition-all duration-200 shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed transform hover:scale-105 active:scale-95">
                    <span wire:loading.remove wire:target="bulkCreate">
                        <i class="fas fa-layer-group mr-2"></i>
                        Criar Quartos
                    </span>
                    <span wire:loading wire:target="bulkCreate" class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Criando...
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>

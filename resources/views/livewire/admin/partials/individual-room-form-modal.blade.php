<!-- Modal de criação/edição de quartos individuais (Design 2025) -->
<div class="fixed inset-0 overflow-y-auto z-50" x-show="$wire.showModal"
     x-transition:enter="ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     aria-labelledby="modalFormRoom" 
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
        <div class="relative inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl text-left overflow-hidden shadow-2xl border border-white/50 dark:border-gray-700/50 transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full"
             x-show="$wire.showModal"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
            
            <!-- Cabeçalho do modal com gradiente -->
            <div class="bg-gradient-to-r from-blue-500/10 to-purple-500/10 dark:from-blue-800/30 dark:to-purple-900/30 px-6 py-4 flex justify-between items-center">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white tracking-tight" id="modalFormRoom">
                    <span class="inline-block">{{ $room_id ? 'Editar Quarto' : 'Adicionar Quarto' }}</span>
                    <div class="h-1 w-10 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full mt-1"></div>
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
                    <!-- Grid de campos principais -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Hotel -->
                        <div class="group">
                            <label for="form_hotel_id" class="inline-flex items-center text-sm font-medium mb-2 text-gray-700 dark:text-gray-300 group-focus-within:text-blue-600 dark:group-focus-within:text-blue-400 transition-colors duration-200">
                                <span class="flex items-center justify-center w-5 h-5 bg-gradient-to-r from-blue-500 to-purple-500 rounded-md mr-2 text-xs text-white">
                                    <i class="fas fa-hotel"></i>
                                </span>
                                Hotel <span class="text-red-500 ml-1">*</span>
                            </label>
                            <div class="relative">
                                <select wire:model.live="form_hotel_id" 
                                        id="form_hotel_id" 
                                        class="w-full px-4 py-2.5 rounded-xl border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition-all duration-200" 
                                        required>
                                    <option value="">Selecionar Hotel</option>
                                    @foreach($hotels as $hotel)
                                        <option value="{{ $hotel->id }}">{{ $hotel->name }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute bottom-0 left-0 h-0.5 bg-gradient-to-r from-blue-500 to-purple-500 transform scale-x-0 group-focus-within:scale-x-100 transition-transform duration-300 origin-left rounded-full"></div>
                            </div>
                            @error('form_hotel_id') 
                                <p class="mt-1.5 text-sm text-red-500 dark:text-red-400 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Tipo de Quarto -->
                        <div class="group">
                            <label for="form_room_type_id" class="inline-flex items-center text-sm font-medium mb-2 text-gray-700 dark:text-gray-300 group-focus-within:text-blue-600 dark:group-focus-within:text-blue-400 transition-colors duration-200">
                                <span class="flex items-center justify-center w-5 h-5 bg-gradient-to-r from-blue-500 to-purple-500 rounded-md mr-2 text-xs text-white">
                                    <i class="fas fa-bed"></i>
                                </span>
                                Tipo de Quarto <span class="text-red-500 ml-1">*</span>
                            </label>
                            <div class="relative">
                                <select wire:model="form_room_type_id" 
                                        id="form_room_type_id" 
                                        class="w-full px-4 py-2.5 rounded-xl border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition-all duration-200" 
                                        required>
                                    <option value="">Selecionar Tipo</option>
                                    @foreach($roomTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute bottom-0 left-0 h-0.5 bg-gradient-to-r from-blue-500 to-purple-500 transform scale-x-0 group-focus-within:scale-x-100 transition-transform duration-300 origin-left rounded-full"></div>
                            </div>
                            @error('form_room_type_id') 
                                <p class="mt-1.5 text-sm text-red-500 dark:text-red-400 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Grid de campos de identificação -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Número do Quarto -->
                        <div class="group">
                            <label for="room_number" class="inline-flex items-center text-sm font-medium mb-2 text-gray-700 dark:text-gray-300 group-focus-within:text-blue-600 dark:group-focus-within:text-blue-400 transition-colors duration-200">
                                <span class="flex items-center justify-center w-5 h-5 bg-gradient-to-r from-blue-500 to-purple-500 rounded-md mr-2 text-xs text-white">
                                    <i class="fas fa-door-open"></i>
                                </span>
                                Número do Quarto <span class="text-red-500 ml-1">*</span>
                            </label>
                            <div class="relative">
                                <input wire:model="room_number" 
                                       type="text" 
                                       id="room_number" 
                                       class="w-full px-4 py-2.5 rounded-xl border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition-all duration-200" 
                                       required
                                       placeholder="Ex: 101, A201, Suite Presidential">
                                <div class="absolute bottom-0 left-0 h-0.5 bg-gradient-to-r from-blue-500 to-purple-500 transform scale-x-0 group-focus-within:scale-x-100 transition-transform duration-300 origin-left rounded-full"></div>
                            </div>
                            @error('room_number') 
                                <p class="mt-1.5 text-sm text-red-500 dark:text-red-400 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Andar -->
                        <div class="group">
                            <label for="form_floor" class="inline-flex items-center text-sm font-medium mb-2 text-gray-700 dark:text-gray-300 group-focus-within:text-blue-600 dark:group-focus-within:text-blue-400 transition-colors duration-200">
                                <span class="flex items-center justify-center w-5 h-5 bg-gradient-to-r from-blue-500 to-purple-500 rounded-md mr-2 text-xs text-white">
                                    <i class="fas fa-layer-group"></i>
                                </span>
                                Andar
                            </label>
                            <div class="relative">
                                <input wire:model="form_floor" 
                                       type="text" 
                                       id="form_floor" 
                                       class="w-full px-4 py-2.5 rounded-xl border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition-all duration-200" 
                                       placeholder="Ex: 1, 2, Térreo, Mezanino">
                                <div class="absolute bottom-0 left-0 h-0.5 bg-gradient-to-r from-blue-500 to-purple-500 transform scale-x-0 group-focus-within:scale-x-100 transition-transform duration-300 origin-left rounded-full"></div>
                            </div>
                            @error('form_floor') 
                                <p class="mt-1.5 text-sm text-red-500 dark:text-red-400 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Status e Disponibilidade -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Status do Quarto -->
                        <div class="group">
                            <label for="form_status" class="inline-flex items-center text-sm font-medium mb-2 text-gray-700 dark:text-gray-300 group-focus-within:text-blue-600 dark:group-focus-within:text-blue-400 transition-colors duration-200">
                                <span class="flex items-center justify-center w-5 h-5 bg-gradient-to-r from-blue-500 to-purple-500 rounded-md mr-2 text-xs text-white">
                                    <i class="fas fa-info-circle"></i>
                                </span>
                                Status <span class="text-red-500 ml-1">*</span>
                            </label>
                            <div class="relative">
                                <select wire:model="form_status" 
                                        id="form_status" 
                                        class="w-full px-4 py-2.5 rounded-xl border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition-all duration-200" 
                                        required>
                                    @foreach($statusOptions as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute bottom-0 left-0 h-0.5 bg-gradient-to-r from-blue-500 to-purple-500 transform scale-x-0 group-focus-within:scale-x-100 transition-transform duration-300 origin-left rounded-full"></div>
                            </div>
                            @error('form_status') 
                                <p class="mt-1.5 text-sm text-red-500 dark:text-red-400 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Data de Disponibilidade -->
                        <div class="group">
                            <label for="available_from" class="inline-flex items-center text-sm font-medium mb-2 text-gray-700 dark:text-gray-300 group-focus-within:text-blue-600 dark:group-focus-within:text-blue-400 transition-colors duration-200">
                                <span class="flex items-center justify-center w-5 h-5 bg-gradient-to-r from-blue-500 to-purple-500 rounded-md mr-2 text-xs text-white">
                                    <i class="fas fa-calendar"></i>
                                </span>
                                Disponível a partir de
                            </label>
                            <div class="relative">
                                <input wire:model="available_from" 
                                       type="date" 
                                       id="available_from" 
                                       class="w-full px-4 py-2.5 rounded-xl border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition-all duration-200">
                                <div class="absolute bottom-0 left-0 h-0.5 bg-gradient-to-r from-blue-500 to-purple-500 transform scale-x-0 group-focus-within:scale-x-100 transition-transform duration-300 origin-left rounded-full"></div>
                            </div>
                            @error('available_from') 
                                <p class="mt-1.5 text-sm text-red-500 dark:text-red-400 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Estados Booleanos -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Disponível -->
                        <div class="flex items-center">
                            <div x-data="{ 
                                localActive: $wire.is_available,
                                toggle() {
                                    this.localActive = !this.localActive;
                                    $wire.is_available = this.localActive;
                                }
                            }" class="flex items-center space-x-3">
                                <button type="button" 
                                        @click="toggle()"
                                        :class="localActive ? 'bg-gradient-to-r from-green-500 to-green-600' : 'bg-gray-300 dark:bg-gray-600'" 
                                        class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                                    <span :class="localActive ? 'translate-x-5' : 'translate-x-0'" 
                                          class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                                </button>
                                <label for="is_available" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                    <i class="fas fa-check-circle text-green-500 mr-1"></i>
                                    Disponível
                                </label>
                            </div>
                        </div>

                        <!-- Limpo -->
                        <div class="flex items-center">
                            <div x-data="{ 
                                localActive: $wire.is_clean,
                                toggle() {
                                    this.localActive = !this.localActive;
                                    $wire.is_clean = this.localActive;
                                }
                            }" class="flex items-center space-x-3">
                                <button type="button" 
                                        @click="toggle()"
                                        :class="localActive ? 'bg-gradient-to-r from-blue-500 to-blue-600' : 'bg-gray-300 dark:bg-gray-600'" 
                                        class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                                    <span :class="localActive ? 'translate-x-5' : 'translate-x-0'" 
                                          class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                                </button>
                                <label for="is_clean" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                    <i class="fas fa-broom text-blue-500 mr-1"></i>
                                    Limpo
                                </label>
                            </div>
                        </div>

                        <!-- Em Manutenção -->
                        <div class="flex items-center">
                            <div x-data="{ 
                                localActive: $wire.is_maintenance,
                                toggle() {
                                    this.localActive = !this.localActive;
                                    $wire.is_maintenance = this.localActive;
                                }
                            }" class="flex items-center space-x-3">
                                <button type="button" 
                                        @click="toggle()"
                                        :class="localActive ? 'bg-gradient-to-r from-orange-500 to-orange-600' : 'bg-gray-300 dark:bg-gray-600'" 
                                        class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                                    <span :class="localActive ? 'translate-x-5' : 'translate-x-0'" 
                                          class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                                </button>
                                <label for="is_maintenance" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                    <i class="fas fa-tools text-orange-500 mr-1"></i>
                                    Em Manutenção
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Notas -->
                    <div class="group">
                        <label for="notes" class="inline-flex items-center text-sm font-medium mb-2 text-gray-700 dark:text-gray-300 group-focus-within:text-blue-600 dark:group-focus-within:text-blue-400 transition-colors duration-200">
                            <span class="flex items-center justify-center w-5 h-5 bg-gradient-to-r from-blue-500 to-purple-500 rounded-md mr-2 text-xs text-white">
                                <i class="fas fa-sticky-note"></i>
                            </span>
                            Notas
                        </label>
                        <div class="relative">
                            <textarea wire:model="notes" 
                                      id="notes" 
                                      rows="3"
                                      class="w-full px-4 py-2.5 rounded-xl border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition-all duration-200 resize-none" 
                                      placeholder="Observações sobre o quarto (opcional)"></textarea>
                            <div class="absolute bottom-0 left-0 h-0.5 bg-gradient-to-r from-blue-500 to-purple-500 transform scale-x-0 group-focus-within:scale-x-100 transition-transform duration-300 origin-left rounded-full"></div>
                        </div>
                        @error('notes') 
                            <p class="mt-1.5 text-sm text-red-500 dark:text-red-400 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>
                </form>
            </div>
            
            <!-- Botões de ação (design 2025) -->
            <div class="bg-gradient-to-b from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 px-6 py-5 flex justify-end space-x-4 border-t border-gray-100 dark:border-gray-700 rounded-b-2xl">
                <button type="button" 
                        class="px-6 py-3 border border-gray-300 dark:border-gray-600 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200" 
                        wire:click="closeModal">
                    <i class="fas fa-times mr-2"></i>
                    Cancelar
                </button>
                <button type="submit" 
                        form="room-form"
                        class="px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center"
                        wire:loading.attr="disabled"
                        wire:click="save">
                    <span wire:loading.remove>
                        <i class="fas fa-save mr-2"></i>
                        {{ $room_id ? 'Actualizar' : 'Salvar' }}
                    </span>
                    <span wire:loading class="flex items-center">
                        <i class="fas fa-spinner fa-spin mr-2"></i>
                        Salvando...
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>

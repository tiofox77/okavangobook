<!-- Modal de criação/edição de comodidades (Design 2025) -->
<div class="fixed inset-0 overflow-y-auto z-50" x-show="$wire.showModal"
     x-transition:enter="ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     aria-labelledby="modalFormAmenity" 
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
            <div class="bg-gradient-to-r from-blue-500/10 to-purple-500/10 dark:from-blue-800/30 dark:to-purple-900/30 px-6 py-4 flex justify-between items-center">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white tracking-tight" id="modalFormAmenity">
                    <span class="inline-block">{{ $editing_id ? 'Editar Comodidade' : 'Nova Comodidade' }}</span>
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
                    <!-- Nome com design moderno -->
                    <div class="group">
                        <label for="name" class="inline-flex items-center text-sm font-medium mb-2 text-gray-700 dark:text-gray-300 group-focus-within:text-blue-600 dark:group-focus-within:text-blue-400 transition-colors duration-200">
                            <span class="flex items-center justify-center w-5 h-5 bg-gradient-to-r from-blue-500 to-purple-500 rounded-md mr-2 text-xs text-white">
                                <i class="fas fa-signature"></i>
                            </span>
                            Nome <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative">
                            <input 
                                wire:model="name" 
                                type="text" 
                                id="name" 
                                class="w-full px-4 py-2.5 rounded-xl border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition-all duration-200" 
                                required
                                placeholder="Nome da comodidade"
                            >
                            <div class="absolute bottom-0 left-0 h-0.5 bg-gradient-to-r from-blue-500 to-purple-500 transform scale-x-0 group-focus-within:scale-x-100 transition-transform duration-300 origin-left rounded-full"></div>
                        </div>
                        @error('name') 
                            <p class="mt-1.5 text-sm text-red-500 dark:text-red-400 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Seletor de Ícone FontAwesome -->
                    <div x-data="{
                        showIconSelector: false,
                        selectedIcon: @entangle('icon').defer,
                        iconCategories: [
                            {
                                name: 'Design',
                                icons: [
                                    'fas fa-wifi', 'fas fa-tv', 'fas fa-shower', 'fas fa-utensils',
                                    'fas fa-coffee', 'fas fa-glass-martini', 'fas fa-concierge-bell',
                                    'fas fa-swimming-pool', 'fas fa-hot-tub', 'fas fa-spa', 'fas fa-dumbbell', 
                                    'fas fa-snowflake'
                                ]
                            },
                            {
                                name: 'Geral',
                                icons: [
                                    'fas fa-check-circle', 'fas fa-info-circle', 'fas fa-heart',
                                    'fas fa-star', 'fas fa-thumbs-up', 'fas fa-map-marker-alt',
                                    'fas fa-plug', 'fas fa-umbrella-beach', 'fas fa-baby',
                                    'fas fa-paw', 'fas fa-car', 'fas fa-bicycle'
                                ]
                            }
                        ],
                        selectIcon(icon) {
                            this.selectedIcon = icon;
                            this.showIconSelector = false;
                            // Atualiza o valor do input Livewire
                            const iconInput = document.querySelector('#icon');
                            if (iconInput) {
                                iconInput.value = icon;
                                iconInput.dispatchEvent(new Event('input', { bubbles: true }));
                            }
                            // Também atualiza diretamente a propriedade Livewire
                            $wire.set('icon', icon);
                        }
                    }">
                        <label for="icon" class="inline-flex items-center text-sm font-medium mb-2 text-gray-700 dark:text-gray-300 group-focus-within:text-blue-600 dark:group-focus-within:text-blue-400 transition-colors duration-200">
                            <span class="flex items-center justify-center w-5 h-5 bg-gradient-to-r from-blue-500 to-purple-500 rounded-md mr-2 text-xs text-white">
                                <i class="fas fa-palette"></i>
                            </span>
                            Ícone FontAwesome
                        </label>
                        
                        <!-- Campo de entrada com visualização do ícone (moderno) -->
                        <div class="flex space-x-3 mb-3">
                            <div class="relative flex-grow">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i :class="selectedIcon" class="text-blue-500"></i>
                                </div>
                                <input 
                                    wire:model="icon" 
                                    type="text" 
                                    id="icon" 
                                    placeholder="fas fa-wifi" 
                                    class="pl-10 w-full px-4 py-2.5 rounded-xl border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition-all duration-200"
                                >
                                <div class="absolute bottom-0 left-0 h-0.5 bg-gradient-to-r from-blue-500 to-purple-500 transform scale-x-0 focus-within:scale-x-100 transition-transform duration-300 origin-left rounded-full"></div>
                            </div>
                            <button 
                                type="button" 
                                class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-700 dark:to-gray-800 hover:from-blue-100 hover:to-indigo-100 dark:hover:from-gray-600 dark:hover:to-gray-700 p-2.5 rounded-xl text-blue-600 dark:text-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-all duration-200 shadow-sm border border-blue-100 dark:border-gray-600"
                                x-on:click="showIconSelector = !showIconSelector" 
                                title="Selecionar Ícone">
                                <i class="fas fa-icons"></i>
                            </button>
                        </div>
                        
                        <!-- Visualização grande do ícone selecionado (moderno) -->
                        <div class="mb-4 text-center transition-all duration-300" x-show="selectedIcon" style="display: none;">
                            <div class="inline-flex flex-col items-center justify-center gap-y-2">
                                <div class="p-6 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-indigo-900/30 rounded-2xl border border-blue-100/80 dark:border-blue-900/30 shadow-md backdrop-blur-sm group-hover:shadow-blue-200 dark:group-hover:shadow-blue-900/20 transition-all duration-300">
                                    <i :class="selectedIcon" class="text-transparent bg-clip-text bg-gradient-to-r from-blue-500 to-indigo-600 dark:from-blue-400 dark:to-indigo-300 text-4xl"></i>
                                </div>
                                <div class="inline-block px-3 py-1 bg-gray-50 dark:bg-gray-700 rounded-full text-xs text-blue-600 dark:text-blue-300 font-medium border border-blue-100 dark:border-blue-900/30">
                                    <span x-text="selectedIcon"></span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Seletor Visual de Ícones (moderno) -->
                        <div 
                            x-show="showIconSelector" 
                            style="display: none;"
                            class="mt-3 bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-white/50 dark:border-gray-700 max-h-64 overflow-hidden transition-all duration-300"
                        >
                            <!-- Tabs para categorias (moderno) -->
                            <div class="flex border-b border-gray-100 dark:border-gray-700 sticky top-0 bg-white dark:bg-gray-800 z-10 p-1">
                                <template x-for="(category, index) in iconCategories" :key="index">
                                    <button 
                                        type="button"
                                        x-on:click="$refs.categoryContent.scrollTo({ left: index * $refs.categoryContent.offsetWidth, behavior: 'smooth' })"
                                        class="flex-1 py-2 px-4 text-sm font-medium text-center rounded-t-lg mx-1 transition-all duration-200 relative overflow-hidden backdrop-blur-sm focus:outline-none"
                                        :class="{ 
                                            'bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 text-blue-600 dark:text-blue-400 shadow-sm': 
                                                $refs && $refs.categoryContent && Math.round($refs.categoryContent.scrollLeft / $refs.categoryContent.offsetWidth) === index,
                                            'hover:bg-gray-50/80 dark:hover:bg-gray-700/50 text-gray-600 dark:text-gray-300': 
                                                !($refs && $refs.categoryContent && Math.round($refs.categoryContent.scrollLeft / $refs.categoryContent.offsetWidth) === index)
                                        }"
                                    >
                                        <span x-text="category.name"></span>
                                        <div 
                                            class="absolute bottom-0 left-0 right-0 h-0.5 bg-gradient-to-r from-blue-500 to-indigo-500 transform scale-x-0 transition-transform duration-300"
                                            :class="{ 'scale-x-100': $refs && $refs.categoryContent && Math.round($refs.categoryContent.scrollLeft / $refs.categoryContent.offsetWidth) === index }"
                                        ></div>
                                    </button>
                                </template>
                            </div>
                            
                            <!-- Conteúdo das categorias -->
                            <div 
                                x-ref="categoryContent"
                                class="flex overflow-x-auto snap-x snap-mandatory scrollbar-hide" 
                                style="scroll-snap-type: x mandatory;"
                                x-on:scroll.debounce.50ms="$el.querySelectorAll('button').forEach((btn, i) => { 
                                    const active = Math.round($el.scrollLeft / $el.offsetWidth) === i;
                                    btn.classList.toggle('text-blue-600', active);
                                    btn.classList.toggle('border-b-2', active);
                                    btn.classList.toggle('border-blue-500', active);
                                })">
                                
                                <template x-for="(category, catIndex) in iconCategories" :key="catIndex">
                                    <div class="min-w-full p-4 snap-center">
                                        <div class="grid grid-cols-6 gap-3">
                                            <template x-for="(icon, iconIndex) in category.icons" :key="iconIndex">
                                                <button 
                                                    type="button"
                                                    x-on:click="selectIcon(icon)"
                                                    class="group flex flex-col items-center justify-center p-3 rounded-xl transition-all duration-200 text-center relative overflow-hidden focus:outline-none"
                                                    :class="{ 
                                                        'bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 shadow-md': selectedIcon === icon,
                                                        'hover:bg-gray-50/80 dark:hover:bg-gray-700/50': selectedIcon !== icon 
                                                    }"
                                                >
                                                    <div class="relative z-10 w-8 h-8 flex items-center justify-center">
                                                        <i 
                                                            :class="icon" 
                                                            class="text-lg transition-all duration-200 transform group-hover:scale-110" 
                                                            :class="{ 
                                                                'text-transparent bg-clip-text bg-gradient-to-br from-blue-600 to-indigo-600 dark:from-blue-400 dark:to-indigo-300': selectedIcon === icon,
                                                                'text-gray-600 dark:text-gray-300 group-hover:text-blue-500 dark:group-hover:text-blue-400': selectedIcon !== icon 
                                                            }"
                                                        ></i>
                                                    </div>
                                                    
                                                    <!-- Ripple effect on click -->
                                                    <div class="absolute inset-0 bg-blue-500/10 dark:bg-blue-500/30 transform scale-0 opacity-0 origin-center rounded-full transition-all duration-500"
                                                        x-on:click="$el.classList.add('animate-ripple'); setTimeout(() => { $el.classList.remove('animate-ripple'); }, 500)">
                                                    </div>
                                                </button>
                                            </template>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                        
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            Selecione um ícone visual ou digite manualmente (ex: fas fa-wifi, fas fa-shower)
                        </p>
                        @error('icon') 
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Tipo com design moderno -->
                    <div class="group">
                        <label for="type" class="inline-flex items-center text-sm font-medium mb-2 text-gray-700 dark:text-gray-300 group-focus-within:text-blue-600 dark:group-focus-within:text-blue-400 transition-colors duration-200">
                            <span class="flex items-center justify-center w-5 h-5 bg-gradient-to-r from-blue-500 to-purple-500 rounded-md mr-2 text-xs text-white">
                                <i class="fas fa-tag"></i>
                            </span>
                            Tipo de Comodidade
                        </label>
                        <div class="relative">
                            <select 
                                wire:model="type" 
                                id="type" 
                                class="w-full appearance-none px-4 py-2.5 rounded-xl border-gray-200 dark:border-gray-600 bg-white/50 dark:bg-gray-700/50 dark:text-white backdrop-blur-sm shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition-all duration-200"
                            >
                                <option value="hotel">Hotel</option>
                                <option value="quarto">Quarto</option>
                                <option value="geral">Geral</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-gray-500 dark:text-gray-400">
                                <i class="fas fa-chevron-down text-xs transition-transform group-focus-within:rotate-180 duration-200"></i>
                            </div>
                            <div class="absolute bottom-0 left-0 h-0.5 bg-gradient-to-r from-blue-500 to-purple-500 transform scale-x-0 group-focus-within:scale-x-100 transition-transform duration-300 origin-left rounded-full"></div>
                        </div>
                    </div>

                    <!-- Descrição com design moderno -->
                    <div class="group">
                        <label for="description" class="inline-flex items-center text-sm font-medium mb-2 text-gray-700 dark:text-gray-300 group-focus-within:text-blue-600 dark:group-focus-within:text-blue-400 transition-colors duration-200">
                            <span class="flex items-center justify-center w-5 h-5 bg-gradient-to-r from-blue-500 to-purple-500 rounded-md mr-2 text-xs text-white">
                                <i class="fas fa-file-alt"></i>
                            </span>
                            Descrição
                        </label>
                        <div class="relative">
                            <textarea 
                                wire:model="description" 
                                id="description" 
                                rows="3" 
                                class="w-full px-4 py-2.5 rounded-xl border-gray-200 dark:border-gray-600 bg-white/50 dark:bg-gray-700/50 dark:text-white backdrop-blur-sm shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition-all duration-200"
                                placeholder="Descreva a comodidade..."
                            ></textarea>
                            <div class="absolute bottom-0 left-0 h-0.5 bg-gradient-to-r from-blue-500 to-purple-500 transform scale-x-0 focus-within:scale-x-100 transition-transform duration-300 origin-left rounded-full"></div>
                        </div>
                        @error('description') 
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Ordem e Status (design moderno) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="group">
                            <label for="display_order" class="inline-flex items-center text-sm font-medium mb-2 text-gray-700 dark:text-gray-300 group-focus-within:text-blue-600 dark:group-focus-within:text-blue-400 transition-colors duration-200">
                                <span class="flex items-center justify-center w-5 h-5 bg-gradient-to-r from-blue-500 to-purple-500 rounded-md mr-2 text-xs text-white">
                                    <i class="fas fa-sort-numeric-down"></i>
                                </span>
                                Ordem de Exibição
                            </label>
                            <div class="relative">
                                <input 
                                    wire:model="display_order" 
                                    type="number" 
                                    id="display_order" 
                                    min="0" 
                                    step="1" 
                                    class="w-full px-4 py-2.5 rounded-xl border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition-all duration-200"
                                >
                                <div class="absolute bottom-0 left-0 h-0.5 bg-gradient-to-r from-blue-500 to-purple-500 transform scale-x-0 group-focus-within:scale-x-100 transition-transform duration-300 origin-left rounded-full"></div>
                            </div>
                            @error('display_order') 
                                <p class="mt-1.5 text-sm text-red-500 dark:text-red-400 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>
                        <div class="flex items-center h-full md:pt-6">
                            <!-- Botão toggle de comodidade ativa/inativa -->
                            <div
                                x-data="{
                                    localActive: $wire.is_active,
                                    toggleState() {
                                        this.localActive = !this.localActive;
                                        $wire.set('is_active', this.localActive);
                                        $wire.toggleActive(); // Chama o método Livewire
                                        this.$refs.clickEffect.classList.add('scale-100', 'opacity-50');
                                        setTimeout(() => {
                                            this.$refs.clickEffect.classList.remove('scale-100', 'opacity-50');
                                        }, 400);
                                    }
                                }"
                                @click="toggleState"
                                class="cursor-pointer select-none"
                            >
                                <!-- Container principal do toggle -->
                                <div class="flex items-center">
                                    <!-- Botão toggle com efeitos -->
                                    <div 
                                        class="relative w-14 h-7 flex items-center rounded-full p-1 duration-300 ease-in-out"
                                        :class="{
                                            'bg-blue-600 dark:bg-blue-500': localActive,
                                            'bg-gray-300 dark:bg-gray-600': !localActive,
                                            'shadow-inner': localActive
                                        }"
                                    >
                                        <!-- Textos ON/OFF -->
                                     
                                        
                                        <!-- Botão circular que desliza -->
                                        <div
                                            class="bg-white dark:bg-gray-50 shadow-md rounded-full h-5 w-5 transform duration-300 flex items-center justify-center"
                                            :class="{
                                                'translate-x-7': localActive,
                                                'translate-x-0': !localActive
                                            }"
                                        >
                                            <!-- Ícone mini dentro do circulo -->
                                            <span class="text-[9px] opacity-75" :class="{'text-blue-600': localActive, 'text-gray-400': !localActive}">
                                                <i :class="localActive ? 'fas fa-check' : 'fas fa-times'"></i>
                                            </span>
                                        </div>
                                        
                                        <!-- Efeito de clique radial -->
                                        <div 
                                            x-ref="clickEffect"
                                            class="absolute inset-0 rounded-full bg-white/40 transform scale-0 opacity-0 transition-all duration-300"
                                        ></div>
                                    </div>
                                    
                                    <!-- Texto descritivo ao lado do toggle -->
                                    <div class="ml-3">
                                        <p class="text-sm flex items-center" :class="localActive ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400'">
                                            <i 
                                                class="mr-1.5 fas" 
                                                :class="localActive ? 'fa-check-circle text-green-500' : 'fa-times-circle text-red-500'"
                                            ></i>
                                            Comodidade - <span class="font-medium" x-text="localActive ? 'Ativa' : 'Inativa'"></span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @error('is_active') 
                                <p class="mt-1.5 text-sm text-red-500 dark:text-red-400 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </form>
            </div>
            
            <!-- Botões de ação (design 2025) -->
            <div class="bg-gradient-to-b from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 px-6 py-5 flex justify-end space-x-4 border-t border-gray-100 dark:border-gray-700 rounded-b-2xl">
                <button 
                    type="button" 
                    wire:click="closeModal"
                    class="relative overflow-hidden inline-flex items-center justify-center px-5 py-2.5 border border-gray-200 dark:border-gray-600 shadow-sm text-sm font-medium rounded-xl text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 group"
                >
                    <span class="absolute inset-0 rounded-xl bg-gradient-to-r from-gray-100/0 to-gray-100/0 dark:from-gray-800/0 dark:to-gray-700/0 group-hover:from-gray-100 group-hover:to-gray-50 dark:group-hover:from-gray-700/40 dark:group-hover:to-gray-800/40 opacity-0 group-hover:opacity-100 transition-all duration-300"></span>
                    <i class="fas fa-times mr-2 relative z-10 group-hover:text-red-500 dark:group-hover:text-red-400 transition-colors duration-200"></i> 
                    <span class="relative z-10">Cancelar</span>
                </button>
                <button 
                    type="button" 
                    wire:click="save" 
                    class="relative overflow-hidden inline-flex items-center justify-center px-6 py-2.5 border border-transparent shadow-md text-sm font-medium rounded-xl text-white bg-gradient-to-br from-blue-500 to-indigo-600 dark:from-blue-600 dark:to-indigo-700 hover:shadow-lg hover:from-blue-600 hover:to-indigo-700 dark:hover:from-blue-500 dark:hover:to-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 group"
                >
                    <span class="absolute inset-0 rounded-xl bg-gradient-to-r from-white/0 to-white/0 group-hover:from-white/10 group-hover:to-white/0 opacity-0 group-hover:opacity-100 transition-all duration-200"></span>
                    <i class="fas fa-save mr-2 relative z-10 group-hover:scale-110 transition-transform duration-200"></i> 
                    <span class="relative z-10">{{ $editing_id ? 'Atualizar' : 'Guardar' }}</span>
                </button>
            </div>
        </div>
    </div>
</div>

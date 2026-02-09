<div class="py-6 px-4" x-data="{ confirmingDeletion: false, facilityIdToDelete: null }">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
            <span class="inline-flex items-center">
                <i class="fas fa-swimming-pool mr-2 text-green-600"></i>
                Gestão de Instalações de Lazer
            </span>
        </h1>
        
        <button wire:click="openModal" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition-colors duration-200 flex items-center shadow-md">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Adicionar Instalação
        </button>
    </div>
    
    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded" role="alert">
            <div class="flex">
                <i class="fas fa-check-circle mr-3 text-green-500"></i>
                <p>{{ session('message') }}</p>
            </div>
        </div>
    @endif
    
    @if (session()->has('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded" role="alert">
            <div class="flex">
                <i class="fas fa-exclamation-circle mr-3 text-red-500"></i>
                <p>{{ session('error') }}</p>
            </div>
        </div>
    @endif
    
    <!-- Filtros -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 mb-6 animate__animated animate__fadeIn">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Filtro de Hotel -->
            <div>
                <label for="filterHotel" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Hotel</label>
                <select id="filterHotel" wire:model.live="filterHotel" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <option value="">Todos os Hotéis</option>
                    @foreach($hotels as $hotel)
                        <option value="{{ $hotel->id }}">{{ $hotel->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <!-- Filtro de Tipo -->
            <div>
                <label for="filterType" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipo</label>
                <select id="filterType" wire:model.live="filterType" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <option value="">Todos os Tipos</option>
                    @foreach($facilityTypes as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            
            <!-- Filtro de Disponibilidade -->
            <div class="flex items-center h-full pt-6">
                <label class="inline-flex items-center">
                    <input type="checkbox" wire:model.live="filterAvailability" value="1" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <span class="ml-2 text-gray-700 dark:text-gray-300">Apenas Disponíveis</span>
                </label>
            </div>
            
            <!-- Pesquisa -->
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pesquisar</label>
                <div class="relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" 
                           id="search" 
                           wire:model.blur="search" 
                           wire:keydown.enter="$refresh"
                           class="pl-10 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" 
                           placeholder="Nome da instalação...">
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tabela de Instalações -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Hotel</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Instalação</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tipo</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Horário</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Preço</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Disponibilidade</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($facilities as $facility)
                        <tr wire:key="facility-{{ $facility->id }}" class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                                        <i class="fas fa-hotel text-blue-600 dark:text-blue-400"></i>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $facility->hotel->name }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">ID: #{{ $facility->hotel_id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    @if($facility->images && count($facility->images) > 0)
                                        <img src="{{ asset('storage/' . $facility->images[0]) }}" class="h-12 w-12 rounded-lg object-cover mr-3 border-2 border-gray-100 dark:border-gray-700" alt="{{ $facility->name }}">
                                    @else
                                        <div class="h-12 w-12 rounded-lg bg-gradient-to-br from-blue-100 to-blue-50 dark:from-blue-900 dark:to-blue-800 flex items-center justify-center mr-3">
                                            @php
                                                $icon = match($facility->type) {
                                                    'piscina' => 'swimming-pool',
                                                    'spa' => 'spa',
                                                    'ginasio' => 'dumbbell',
                                                    'sauna' => 'hot-tub',
                                                    default => 'star'
                                                };
                                            @endphp
                                            <i class="fas fa-{{ $icon }} text-blue-500 text-xl"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $facility->name }}</div>
                                        @if($facility->capacity)
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                                <i class="fas fa-users mr-1"></i>{{ $facility->capacity }} pessoas
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                    {{ $facilityTypes[$facility->type] ?? $facility->type }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                @if($facility->opening_time && $facility->closing_time)
                                    <div class="flex items-center">
                                        <i class="fas fa-clock text-blue-500 mr-2"></i>
                                        <span>{{ substr($facility->opening_time, 0, 5) }} - {{ substr($facility->closing_time, 0, 5) }}</span>
                                    </div>
                                @else
                                    <span class="text-gray-400 italic">N/A</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                @if($facility->is_free)
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                        <i class="fas fa-check mr-1"></i>Grátis
                                    </span>
                                @else
                                    <div class="flex items-center font-mono bg-yellow-50 dark:bg-yellow-900/20 rounded-lg px-3 py-1.5 justify-center">
                                        <i class="fas fa-tag text-yellow-600 dark:text-yellow-400 mr-2 transform -rotate-90"></i>
                                        @if($facility->price_per_hour)
                                            <span class="font-bold text-yellow-700 dark:text-yellow-300">{{ number_format($facility->price_per_hour, 2, ',', '.') }}/h</span>
                                        @elseif($facility->daily_price)
                                            <span class="font-bold text-yellow-700 dark:text-yellow-300">{{ number_format($facility->daily_price, 2, ',', '.') }}/dia</span>
                                        @endif
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex justify-center">
                                    @if($facility->is_available)
                                        <span class="px-3 py-1.5 inline-flex items-center rounded-lg bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 transition-all duration-200">
                                            <i class="fas fa-check-circle mr-1.5"></i>
                                            <span class="font-medium">Disponível</span>
                                        </span>
                                    @else
                                        <span class="px-3 py-1.5 inline-flex items-center rounded-lg bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300 transition-all duration-200">
                                            <i class="fas fa-times-circle mr-1.5"></i>
                                            <span class="font-medium">Indisponível</span>
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <button wire:click="edit({{ $facility->id }})" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button 
                                        @click="confirmingDeletion = true; facilityIdToDelete = {{ $facility->id }}" 
                                        class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300"
                                        title="Excluir">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center py-6">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    <p class="text-lg font-medium">Nenhuma instalação encontrada</p>
                                    @if($search || $filterHotel || $filterType)
                                        <p class="text-sm text-gray-500 mt-2">Tente ajustar os filtros ou</p>
                                        <button wire:click="openModal" class="mt-4 px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            Adicionar Nova Instalação
                                        </button>
                                    @else
                                        <button wire:click="openModal" class="mt-4 px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            Adicionar Primeira Instalação
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Paginação -->
        <div class="px-6 py-3 border-t border-gray-200 dark:border-gray-700">
            {{ $facilities->links() }}
        </div>
    </div>
    
    <!-- Modal de Criação/Edição -->
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
            <div class="relative inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full" 
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
                                <i class="fas fa-swimming-pool text-blue-600 dark:text-blue-400 text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-xl leading-6 font-bold text-gray-900 dark:text-white">
                                    {{ $facilityId ? 'Editar Instalação de Lazer' : 'Adicionar Instalação de Lazer' }}
                                </h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    Defina as informações da instalação
                                </p>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <!-- Hotel -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Hotel *</label>
                                <select wire:model.defer="hotel_id" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                                    <option value="">Selecionar hotel...</option>
                                    @foreach($hotels as $hotel)
                                        <option value="{{ $hotel->id }}">{{ $hotel->name }}</option>
                                    @endforeach
                                </select>
                                @error('hotel_id') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <!-- Nome -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nome *</label>
                                    <input type="text" wire:model.defer="name" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                                    @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                
                                <!-- Tipo -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipo *</label>
                                    <select wire:model.defer="type" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                                    <option value="">Selecionar...</option>
                                    @foreach($facilityTypes as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('type') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <!-- Descrição -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Descrição</label>
                                <textarea wire:model.defer="description" rows="2" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <!-- Localização -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Localização</label>
                                    <input type="text" wire:model.defer="location" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Ex: Terraço">
                                </div>
                                
                                <!-- Capacidade -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Capacidade (pessoas)</label>
                                    <input type="number" wire:model.defer="capacity" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <!-- Horário Abertura -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Abertura</label>
                                    <input type="time" wire:model.defer="opening_time" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                
                                <!-- Horário Fechamento -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fechamento</label>
                                    <input type="time" wire:model.defer="closing_time" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <!-- Preço por Hora -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Preço/Hora (AOA)</label>
                                    <input type="number" step="0.01" wire:model.defer="price_per_hour" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                
                                <!-- Preço Diário -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Preço/Dia (AOA)</label>
                                    <input type="number" step="0.01" wire:model.defer="daily_price" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>
                            
                            <!-- Regras -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Regras de Uso</label>
                                <textarea wire:model.defer="rules" rows="2" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                            </div>
                            
                            <!-- Imagens -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Imagens</label>
                                <input type="file" wire:model="imagesUpload" multiple accept="image/*" class="w-full text-sm">
                                @if(!empty($images))
                                    <div class="mt-2 flex gap-2 flex-wrap">
                                        @foreach($images as $index => $img)
                                            <div class="relative">
                                                <img src="{{ asset('storage/' . $img) }}" class="h-20 w-20 object-cover rounded">
                                                <button type="button" wire:click="removeImage({{ $index }})" class="absolute -top-2 -right-2 bg-red-600 text-white rounded-full w-5 h-5 text-xs">×</button>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                                @error('imagesUpload.*') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <!-- Atributos -->
                            <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Configurações</label>
                                <div class="grid grid-cols-3 gap-2">
                                    <label class="flex items-center">
                                        <input type="checkbox" wire:model.defer="is_available" class="rounded text-blue-600 focus:ring-blue-500">
                                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Disponível</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" wire:model.defer="is_free" class="rounded text-green-600 focus:ring-green-500">
                                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Grátis</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" wire:model.defer="requires_booking" class="rounded text-amber-600 focus:ring-amber-500">
                                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Requer Reserva</span>
                                    </label>
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
                            <i class="fas fa-save mr-2"></i> {{ $facilityId ? 'Atualizar' : 'Guardar' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Modal de Confirmação de Exclusão -->
    <div x-show="confirmingDeletion" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="confirmingDeletion" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0" 
                 x-transition:enter-end="opacity-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100" 
                 x-transition:leave-end="opacity-0" 
                 class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
                 aria-hidden="true"></div>
    
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
    
            <div x-show="confirmingDeletion" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 class="relative inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">Confirmar Exclusão</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Tem certeza que deseja excluir esta instalação? Esta ação não pode ser desfeita.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="delete(facilityIdToDelete)" @click="confirmingDeletion = false" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">Excluir</button>
                    <button @click="confirmingDeletion = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Loading Indicator during AJAX calls -->
    <div wire:loading.flex class="fixed inset-0 z-40 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg px-4 py-3 flex items-center shadow-xl">
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="text-gray-700 dark:text-gray-300">Processando...</span>
        </div>
    </div>
</div>

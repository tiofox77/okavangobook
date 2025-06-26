<div class="py-6 px-4" x-data="{ confirmingDeletion: false, roomIdToDelete: null }">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
            <span class="inline-flex items-center">
                <i class="fas fa-door-open mr-2 text-green-600"></i>
                Gestão de Quartos
            </span>
        </h1>
        
        <button wire:click="openModal" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition-colors duration-200 flex items-center shadow-md">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Adicionar Quarto
        </button>
    </div>
    
    <!-- Filtros -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 mb-6 animate__animated animate__fadeIn">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Filtro de Hotel -->
            <div>
                <label for="hotel_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Hotel</label>
                <select id="hotel_id" wire:model.live="hotel_id" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <option value="">Todos os Hotéis</option>
                    @foreach($hotels as $hotel)
                        <option value="{{ $hotel->id }}">{{ $hotel->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <!-- Filtro de Tipo -->
            <div>
                <label for="roomType" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipo de Quarto</label>
                <select id="roomType" wire:model.live="roomType" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <option value="">Todos os Tipos</option>
                    @foreach($roomTypes as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            
            <!-- Filtro de Disponibilidade -->
            <div class="flex items-center h-full pt-6">
                <label class="inline-flex items-center">
                    <input type="checkbox" wire:model.live="availableOnly" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
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
                    <input type="text" id="search" wire:model.live="search" class="pl-10 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="Nome, número, descrição...">
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tabela de Quartos -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Hotel</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nome</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Camas</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Capacidade</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Preço Base</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Disponibilidade</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($rooms as $room)
                        <tr wire:key="room-{{ $room->id }}" class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                                        <i class="fas fa-hotel text-blue-600 dark:text-blue-400"></i>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $room->hotel->name ?? 'N/A' }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">ID: #{{ $room->hotel_id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col">
                                    <div class="flex items-center">
                                        <span class="font-medium text-gray-900 dark:text-white">{{ $room->name }}</span>
                                        @if($room->is_featured)
                                            <span class="ml-2 px-2 py-0.5 text-xs inline-flex rounded-md bg-amber-100 dark:bg-amber-700 text-amber-800 dark:text-amber-300">
                                                <i class="fas fa-star mr-1"></i> Destaque
                                            </span>
                                        @endif
                                    </div>
                                    <div class="mt-1 flex flex-wrap gap-1">
                                        @if(!empty($room->amenities))
                                            @foreach(array_slice((array)$room->amenities, 0, 3) as $amenity)
                                                <span class="inline-flex items-center text-xs text-gray-600 dark:text-gray-400" title="{{ $featureOptions[$amenity] ?? $amenity }}">
                                                    <i class="{{ $this->getFeatureIcon($amenity) }} mr-1 text-blue-500"></i>
                                                    <span class="hidden sm:inline">{{ Str::limit($featureOptions[$amenity] ?? $amenity, 10) }}</span>
                                                </span>
                                            @endforeach
                                            @if(count((array)$room->amenities) > 3)
                                                <span class="text-xs text-gray-500 dark:text-gray-400">+{{ count((array)$room->amenities) - 3 }}</span>
                                            @endif
                                        @else
                                            <span class="text-xs text-gray-400 dark:text-gray-500 italic">Sem facilidades</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center justify-center bg-gray-50 dark:bg-gray-700 rounded-lg px-3 py-2">
                                    <div class="flex flex-col items-center">
                                        <div class="flex items-center justify-center mb-1">
                                            <span class="text-lg font-bold text-indigo-600 dark:text-indigo-300 mr-1">{{ $room->beds }}</span>
                                            <i class="fas fa-bed text-indigo-500"></i>
                                        </div>
                                        <span class="text-xs text-gray-600 dark:text-gray-400">{{ $room->bed_type ?: 'Standard' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                <div class="flex items-center justify-center bg-gray-50 dark:bg-gray-700 rounded-lg px-3 py-1.5">
                                    <div class="flex items-center">
                                        <i class="fas fa-user text-blue-500 mr-2"></i>
                                        <span class="font-medium">{{ $room->capacity }}</span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400 ml-1">{{ $room->capacity == 1 ? 'pessoa' : 'pessoas' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                <div class="flex items-center font-mono bg-yellow-50 dark:bg-yellow-900/20 rounded-lg px-3 py-1.5 justify-center">
                                    <i class="fas fa-tag text-yellow-600 dark:text-yellow-400 mr-2 transform -rotate-90"></i>
                                    <span class="font-bold text-yellow-700 dark:text-yellow-300">{{ number_format($room->base_price, 2, ',', '.') }}</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400 ml-1">KZ</span>
                                </div>
                                @if($room->rooms_count > 0)
                                    <div class="text-xs text-center mt-1 text-gray-500 dark:text-gray-400">
                                        {{ $room->rooms_count }} {{ $room->rooms_count === 1 ? 'quarto' : 'quartos' }} disponíveis
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex justify-center">
                                    @if($room->is_available)
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
                                    <button wire:click="edit({{ $room->id }})" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button 
                                        @click="confirmingDeletion = true; roomIdToDelete = {{ $room->id }}" 
                                        class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
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
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    <p class="text-lg font-medium">Nenhum quarto encontrado</p>
                                    @if($search || $hotel_id || $roomType || $availableOnly)
                                        <p class="text-sm text-gray-500 mt-2">Tente ajustar os filtros ou</p>
                                        <button wire:click="openModal" class="mt-4 px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            Adicionar Novo Quarto
                                        </button>
                                    @else
                                        <button wire:click="openModal" class="mt-4 px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            Adicionar Primeiro Quarto
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
            {{ $rooms->links() }}
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
                                <p class="text-sm text-gray-500 dark:text-gray-400">Tem certeza que deseja excluir este quarto? Esta ação não pode ser desfeita.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="delete(roomIdToDelete)" @click="confirmingDeletion = false" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">Excluir</button>
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
    
    <!-- Form Modal -->
    @include('livewire.admin.partials.room-form-modal')
</div>

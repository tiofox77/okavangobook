<div class="py-6 px-4" x-data="{ confirmingDeletion: false, hotelIdToDelete: null }">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
            <span class="inline-flex items-center">
                <i class="fas fa-hotel mr-2 text-blue-600"></i>
                Gestão de Propriedades
            </span>
        </h1>
        
        <button wire:click="openModal" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition-colors duration-200 flex items-center shadow-md">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Adicionar Propriedade
        </button>
    </div>

    <!-- Mensagem de feedback com animação -->
    @if (session()->has('message'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 animate__animated animate__fadeIn" role="alert">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <p>{{ session('message') }}</p>
            </div>
        </div>
    @endif
            
            <!-- Filtros -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 mb-6 animate__animated animate__fadeIn">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Campo de pesquisa -->
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pesquisar</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text" id="search" wire:model.live="search" 
                                class="pl-10 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                placeholder="Nome, localização, detalhes...">
                        </div>
                    </div>
                    
                    <!-- Filtro de Localização -->
                    <div>
                        <label for="filterLocation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Localização</label>
                        <select id="filterLocation" wire:model.live="filterLocation" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <option value="">Todas Localizações</option>
                            @foreach($locations as $location)
                                <option value="{{ $location->id }}">{{ $location->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Filtro de Tipo de Propriedade -->
                    <div>
                        <label for="filterPropertyType" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            <i class="fas fa-building text-blue-500 mr-1"></i>
                            Tipo de Propriedade
                        </label>
                        <select id="filterPropertyType" wire:model.live="filterPropertyType" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <option value="">Todos os Tipos</option>
                            <option value="hotel">🏨 Hotel</option>
                            <option value="resort">🏖️ Resort</option>
                            <option value="hospedaria">🏠 Hospedaria</option>
                        </select>
                    </div>
                    
                    <!-- Filtro de Classificação -->
                    <div>
                        <label for="filterRating" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            <i class="fas fa-star text-yellow-500 mr-1"></i>
                            Classificação
                        </label>
                        <select id="filterRating" wire:model.live="filterRating" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <option value="">Todas Classificações</option>
                            <option value="5">⭐⭐⭐⭐⭐ 5 estrelas</option>
                            <option value="4">⭐⭐⭐⭐ 4 estrelas</option>
                            <option value="3">⭐⭐⭐ 3 estrelas</option>
                            <option value="2">⭐⭐ 2 estrelas</option>
                            <option value="1">⭐ 1 estrela</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <!-- Tabela de hotéis -->
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nome</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tipo</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Localização</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Preço</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Classificação</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($hotels as $hotel)
                                <tr wire:key="hotel-{{ $hotel->id }}" class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 bg-gray-200 dark:bg-gray-600 rounded-full flex items-center justify-center">
                                                <i class="fas fa-hotel text-gray-500 dark:text-gray-400"></i>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $hotel->name }}</div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                    <i class="fas fa-map-marker-alt mr-1"></i> {{ $hotel->address ?? 'Endereço não disponível' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($hotel->property_type === 'resort')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gradient-to-r from-orange-100 to-red-100 text-orange-800 dark:from-orange-900 dark:to-red-900 dark:text-orange-200">
                                                <i class="fas fa-umbrella-beach mr-1"></i> Resort
                                            </span>
                                        @elseif($hotel->property_type === 'hospedaria')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-teal-100 text-teal-800 dark:bg-teal-900 dark:text-teal-200">
                                                <i class="fas fa-home mr-1"></i> Hospedaria
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                <i class="fas fa-hotel mr-1"></i> Hotel
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300">
                                            {{ $hotel->location->name ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $hotel->price ? number_format($hotel->price, 2, ',', '.') . ' KZ' : 'N/A' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star text-sm {{ $i <= $hotel->rating ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600' }} mr-1"></i>
                                            @endfor
                                            <span class="ml-1 text-xs text-gray-500 dark:text-gray-400">({{ $hotel->rating }}/5)</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end space-x-2">
                                            <a href="{{ route('hotel.details', $hotel->id) }}" target="_blank" class="text-purple-600 hover:text-purple-800 dark:text-purple-400 dark:hover:text-purple-300 transition-colors duration-150" title="Ver na Página Pública">
                                                <i class="fas fa-external-link-alt"></i>
                                            </a>
                                            <button wire:click="view({{ $hotel->id }})" class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300 transition-colors duration-150" title="Visualizar">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button wire:click="openModal({{ $hotel->id }})" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition-colors duration-150" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button 
                                                @click="confirmingDeletion = true; hotelIdToDelete = {{ $hotel->id }}" 
                                                class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition-colors duration-150"
                                                title="Excluir">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                        <div class="flex flex-col items-center py-6">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                            </svg>
                                            <p class="text-lg font-medium">Nenhuma propriedade encontrada</p>
                                            @if($search || $filterLocation || $filterRating)
                                                <p class="text-sm text-gray-500 mt-2">Tente ajustar os filtros ou</p>
                                                <button wire:click="openModal" class="mt-4 px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                    Adicionar Nova Propriedade
                                                </button>
                                            @else
                                                <button wire:click="openModal" class="mt-4 px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                    Adicionar Primeira Propriedade
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Paginação -->
            <div class="px-6 py-3 border-t border-gray-200 dark:border-gray-700">
                {{ $hotels->links() }}
            </div>
            
            <!-- Modal de criação/edição -->
            @if ($showModal)
                @include('livewire.admin.partials.hotel-form-modal')
            @endif
            
            <!-- Modal de visualização -->
            @include('livewire.admin.partials.hotel-view-modal')
            
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
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Tem certeza que deseja excluir este hotel? Esta ação não pode ser desfeita.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="delete(hotelIdToDelete)" @click="confirmingDeletion = false" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">Excluir</button>
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

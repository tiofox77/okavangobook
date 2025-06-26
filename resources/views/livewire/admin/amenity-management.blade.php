<div class="py-6 px-4" x-data="{ confirmingDeletion: false, amenityIdToDelete: null }">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
            <span class="inline-flex items-center">
                <i class="fas fa-concierge-bell mr-2 text-blue-600"></i>
                Gestão de Comodidades
            </span>
        </h1>
        
        <button wire:click="create" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition-colors duration-200 flex items-center shadow-md">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Nova Comodidade
        </button>
    </div>
    
    <!-- Cartões de estatísticas -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Total de Comodidades -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-md rounded-lg border-l-4 border-blue-500">
            <div class="p-4 flex items-center">
                <div class="flex-shrink-0 rounded-full p-3 bg-blue-100 dark:bg-blue-900">
                    <i class="fas fa-list text-blue-600 dark:text-blue-400"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase">Total de Comodidades</p>
                    <p class="text-2xl font-semibold text-gray-800 dark:text-white">{{ $counts['total'] }}</p>
                </div>
            </div>
        </div>
        
        <!-- Comodidades de Hotel -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-md rounded-lg border-l-4 border-green-500">
            <div class="p-4 flex items-center">
                <div class="flex-shrink-0 rounded-full p-3 bg-green-100 dark:bg-green-900">
                    <i class="fas fa-hotel text-green-600 dark:text-green-400"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase">Comodidades de Hotel</p>
                    <p class="text-2xl font-semibold text-gray-800 dark:text-white">{{ $counts['hotel'] }}</p>
                </div>
            </div>
        </div>
        
        <!-- Comodidades de Quarto -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-md rounded-lg border-l-4 border-indigo-500">
            <div class="p-4 flex items-center">
                <div class="flex-shrink-0 rounded-full p-3 bg-indigo-100 dark:bg-indigo-900">
                    <i class="fas fa-bed text-indigo-600 dark:text-indigo-400"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase">Comodidades de Quarto</p>
                    <p class="text-2xl font-semibold text-gray-800 dark:text-white">{{ $counts['room'] }}</p>
                </div>
            </div>
        </div>
        
        <!-- Comodidades Ativas -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-md rounded-lg border-l-4 border-amber-500">
            <div class="p-4 flex items-center">
                <div class="flex-shrink-0 rounded-full p-3 bg-amber-100 dark:bg-amber-900">
                    <i class="fas fa-check-circle text-amber-600 dark:text-amber-400"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase">Comodidades Ativas</p>
                    <p class="text-2xl font-semibold text-gray-800 dark:text-white">{{ $counts['active'] }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Filtros e pesquisa -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 mb-6 animate__animated animate__fadeIn">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-end">
            <!-- Pesquisa -->
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pesquisar</label>
                <div class="relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input wire:model.live.debounce.300ms="search" type="text" class="pl-10 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="Nome ou descrição...">
                </div>
            </div>
            
            <!-- Filtros de tipo -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipo de Comodidade</label>
                <div class="inline-flex rounded-md shadow-sm">
                    <button wire:click="filterByType('all')" type="button" class="px-4 py-2.5 text-sm font-medium {{ $filter_type == 'all' ? 'bg-blue-600 hover:bg-blue-700 text-white' : 'bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600' }} border rounded-l-lg focus:z-10 focus:ring-2 focus:ring-blue-500 transition-colors">
                        <i class="fas fa-list-ul mr-1"></i> Todos
                    </button>
                    <button wire:click="filterByType('hotel')" type="button" class="px-4 py-2.5 text-sm font-medium {{ $filter_type == 'hotel' ? 'bg-blue-600 hover:bg-blue-700 text-white' : 'bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600' }} border-t border-b border-r focus:z-10 focus:ring-2 focus:ring-blue-500 transition-colors">
                        <i class="fas fa-hotel mr-1"></i> Hotel
                    </button>
                    <button wire:click="filterByType('room')" type="button" class="px-4 py-2.5 text-sm font-medium {{ $filter_type == 'room' ? 'bg-blue-600 hover:bg-blue-700 text-white' : 'bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600' }} border-t border-b border-r rounded-r-lg focus:z-10 focus:ring-2 focus:ring-blue-500 transition-colors">
                        <i class="fas fa-bed mr-1"></i> Quarto
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tabela de comodidades -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" wire:click="sortBy('id')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer">
                            <div class="flex items-center">
                                ID
                                @if ($sortField === 'id')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                @endif
                            </div>
                        </th>
                        <th scope="col" wire:click="sortBy('name')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer">
                            <div class="flex items-center">
                                Nome
                                @if ($sortField === 'name')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                @endif
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ícone</th>
                        <th scope="col" wire:click="sortBy('type')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer">
                            <div class="flex items-center">
                                Tipo
                                @if ($sortField === 'type')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                @endif
                            </div>
                        </th>
                        <th scope="col" wire:click="sortBy('display_order')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer">
                            <div class="flex items-center">
                                Ordem
                                @if ($sortField === 'display_order')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                @endif
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Estado</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($amenities as $amenity)
                        <tr wire:key="amenity-{{ $amenity->id }}" class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $amenity->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">                                
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $amenity->name }}</div>
                                @if($amenity->description)
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ Str::limit($amenity->description, 30) }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if ($amenity->icon)
                                    <i class="{{ $amenity->icon }} text-lg text-blue-500 dark:text-blue-400"></i>
                                @else
                                    <span class="text-gray-400 dark:text-gray-500">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $amenity->type === 'hotel' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300' : 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-300' }}">
                                    @if($amenity->type === 'hotel')
                                        <i class="fas fa-hotel mr-1"></i> Hotel
                                    @else
                                        <i class="fas fa-bed mr-1"></i> Quarto
                                    @endif
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900 dark:text-gray-100">{{ $amenity->display_order }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $amenity->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                                    @if($amenity->is_active)
                                        <i class="fas fa-check-circle mr-1"></i> Ativo
                                    @else
                                        <i class="fas fa-times-circle mr-1"></i> Inativo
                                    @endif
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                <button wire:click="edit({{ $amenity->id }})" class="inline-flex items-center px-2 py-1 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-200 active:bg-blue-700 transition">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button 
                                    wire:click="delete({{ $amenity->id }})" 
                                    class="inline-flex items-center px-2 py-1 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:border-red-700 focus:ring focus:ring-red-200 active:bg-red-700 transition"
                                    onclick="return confirm('Tem certeza que deseja excluir esta comodidade?')"
                                >
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-info-circle text-3xl mb-3"></i>
                                    <p class="text-lg font-medium">Nenhuma comodidade encontrada</p>
                                    <p class="text-sm">Tente ajustar os filtros ou adicionar novas comodidades</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
            {{ $amenities->links() }}
        </div>
    </div>
    
    <!-- Incluir o modal de criação/edição de comodidades -->
    @include('livewire.admin.partials.amenity-form-modal')
    
    <!-- Notificações Tailwind CSS -->
    <div x-data="{ show: false, message: '', timer: null }" 
         x-init="
            $watch('show', value => {
                if (value) {
                    clearTimeout(timer);
                    timer = setTimeout(() => { show = false }, 5000);
                }
            });
            @if(session()->has('message'))
                show = true;
                message = '{{ session('message') }}';
            @endif
         "
         @notify.window="show = true; message = $event.detail.message"
         class="fixed bottom-5 right-5 z-50">
        <div x-show="show" 
             x-transition:enter="transition ease-out duration-300" 
             x-transition:enter-start="transform translate-y-2 opacity-0" 
             x-transition:enter-end="transform translate-y-0 opacity-100" 
             x-transition:leave="transition ease-in duration-200" 
             x-transition:leave-start="transform translate-y-0 opacity-100" 
             x-transition:leave-end="transform translate-y-2 opacity-0" 
             class="flex items-center bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden max-w-md border-l-4 border-blue-500">
            <div class="p-4 w-full">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 text-blue-500">
                            <i class="fas fa-info-circle text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100" x-text="message"></p>
                        </div>
                    </div>
                    <div class="ml-4 flex-shrink-0 flex">
                        <button @click="show = false" class="inline-flex text-gray-400 hover:text-gray-500 focus:outline-none">
                            <span class="sr-only">Fechar</span>
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Livewire.on('notify', function (data) {
                // Despachando um evento personalizado que o Alpine.js irá capturar
                window.dispatchEvent(new CustomEvent('notify', {
                    detail: {
                        message: data.message,
                        type: data.type || 'info'
                    }
                }));
            });
        });
    </script>
    @endpush
</div>

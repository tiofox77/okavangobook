<div class="py-6 px-4" x-data="{ confirmingDeletion: false, roomIdToDelete: null }">
    {{-- Knowing others is intelligence; knowing yourself is true wisdom. --}}
    
    <!-- Cabeçalho -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
            <span class="inline-flex items-center">
                <i class="fas fa-door-closed mr-2 text-blue-600"></i>
                Gestão de Quartos Individuais
            </span>
        </h1>
        
        <div class="flex space-x-3">
            <button wire:click="openBulkModal" class="bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg transition-colors duration-200 flex items-center shadow-md">
                <i class="fas fa-layer-group mr-2"></i>
                Criar em Lote
            </button>
            <button wire:click="openModal" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition-colors duration-200 flex items-center shadow-md">
                <i class="fas fa-plus mr-2"></i>
                Adicionar Quarto
            </button>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
            <!-- Pesquisa -->
            <div class="lg:col-span-2">
                <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pesquisar</label>
                <input type="text" id="search" wire:model.live.debounce.300ms="search" 
                       placeholder="Número do quarto, andar ou notas..."
                       class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
            </div>
            
            <!-- Hotel -->
            <div>
                <label for="hotel_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Hotel</label>
                <select id="hotel_id" wire:model.live="hotel_id" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <option value="">Todos</option>
                    @foreach($hotels as $hotel)
                        <option value="{{ $hotel->id }}">{{ $hotel->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <!-- Tipo de Quarto -->
            <div>
                <label for="room_type_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipo</label>
                <select id="room_type_id" wire:model.live="room_type_id" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <option value="">Todos</option>
                    @foreach($roomTypes as $roomType)
                        <option value="{{ $roomType->id }}">{{ $roomType->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <!-- Status -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                <select id="status" wire:model.live="status" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <option value="">Todos</option>
                    @foreach($statusOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            
            <!-- Andar -->
            <div>
                <label for="floor" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Andar</label>
                <select id="floor" wire:model.live="floor" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <option value="">Todos</option>
                    @foreach($floors as $floorOption)
                        <option value="{{ $floorOption }}">{{ $floorOption }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        
        <div class="mt-4 flex items-center">
            <label class="inline-flex items-center">
                <input type="checkbox" wire:model.live="availableOnly" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                <span class="ml-2 text-gray-700 dark:text-gray-300">Apenas Disponíveis</span>
            </label>
        </div>
    </div>

    <!-- Mensagens -->
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <!-- Tabela de Quartos -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Hotel</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Quarto</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tipo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Andar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Preço</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acções</th>
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
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-200">
                                            {{ $room->hotel->name }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-900 dark:text-gray-200 bg-gray-100 dark:bg-gray-700 rounded-lg px-3 py-1 text-center">
                                    {{ $room->room_number }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-gray-200">
                                    {{ $room->roomType->name }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-gray-200">
                                    {{ $room->floor ?? 'N/A' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($room->status === 'available') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                    @elseif($room->status === 'occupied') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                    @elseif($room->status === 'maintenance') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                    @elseif($room->status === 'cleaning') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200
                                    @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                    @endif">
                                    
                                    @if($room->status === 'available')
                                        <i class="fas fa-check-circle mr-1"></i>
                                    @elseif($room->status === 'occupied')
                                        <i class="fas fa-user mr-1"></i>
                                    @elseif($room->status === 'maintenance')
                                        <i class="fas fa-tools mr-1"></i>
                                    @elseif($room->status === 'cleaning')
                                        <i class="fas fa-broom mr-1"></i>
                                    @else
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                    @endif
                                    
                                    {{ $statusOptions[$room->status] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-green-600 dark:text-green-400">
                                    {{ \App\Helpers\CurrencyHelper::formatKwanza($room->roomType->base_price) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <button wire:click="openModal({{ $room->id }})" 
                                            class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-200 transition-colors">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button @click="confirmingDeletion = true; roomIdToDelete = {{ $room->id }}" 
                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-200 transition-colors">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center py-6">
                                    <i class="fas fa-door-closed text-4xl text-gray-400 mb-4"></i>
                                    <p class="text-lg font-medium">Nenhum quarto encontrado</p>
                                    <p class="text-sm">Comece criando o primeiro quarto do sistema</p>
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
    <div x-show="confirmingDeletion" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="confirmingDeletion" class="fixed inset-0 transition-opacity">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-exclamation-triangle text-red-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Confirmar Exclusão</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">Tem certeza que deseja eliminar este quarto? Esta ação não pode ser desfeita.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button @click="$wire.delete(roomIdToDelete); confirmingDeletion = false" 
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Eliminar
                    </button>
                    <button @click="confirmingDeletion = false" 
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Formulário Individual -->
    @if($showModal)
        @include('livewire.admin.partials.individual-room-form-modal')
    @endif

    <!-- Modal de Criação em Lote -->
    @if($showBulkModal)
        @include('livewire.admin.partials.bulk-room-form-modal')
    @endif
</div>

<div>
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">
                    <i class="fas fa-users text-blue-600 mr-2"></i>
                    Selecionar Hóspede
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Escolha o hóspede para a reserva no Quarto {{ $selectedRoom['room_number'] ?? '' }}
                </p>
            </div>
            <button wire:click="goToStep('room')" 
                    class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                Alterar Quarto
            </button>
        </div>
    </div>

    <!-- Guest Selection Options -->
    @if(!$selectedUser)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Search Existing Guest -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-lg border border-gray-200 dark:border-gray-700 p-6 transition-all duration-300">
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-search text-blue-600 dark:text-blue-400 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                        Procurar Hóspede Existente
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                        Pesquise por hóspedes já cadastrados no sistema
                    </p>
                    <button wire:click="openUserModal" 
                            class="w-full inline-flex items-center justify-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                        <i class="fas fa-search mr-2"></i>
                        Procurar Hóspede
                    </button>
                </div>
            </div>

            <!-- Create New Guest -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-lg border border-gray-200 dark:border-gray-700 p-6 transition-all duration-300">
                <div class="text-center">
                    <div class="w-16 h-16 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-user-plus text-green-600 dark:text-green-400 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                        Criar Novo Hóspede
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                        Cadastre um novo hóspede preenchendo os dados manualmente
                    </p>
                    <button wire:click="openCreateUserModal" 
                            class="w-full inline-flex items-center justify-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                        <i class="fas fa-user-plus mr-2"></i>
                        Criar Hóspede
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Selected Guest Display -->
    @if($selectedUser)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
            <div class="flex items-center justify-between p-4 bg-green-50 dark:bg-green-900/30 rounded-lg border border-green-200 dark:border-green-700">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-green-100 dark:bg-green-800 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-green-600 dark:text-green-400"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ $selectedUser['name'] }}
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ $selectedUser['email'] }}
                        </p>
                        @if(isset($selectedUser['phone']) && !empty($selectedUser['phone']))
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                <i class="fas fa-phone mr-1"></i>
                                {{ $selectedUser['phone'] }}
                            </p>
                        @endif
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-200">
                        <i class="fas fa-check-circle mr-1"></i>
                        Selecionado
                    </span>
                    <button wire:click="$set('selectedUser', null)" 
                            class="inline-flex items-center px-3 py-1 text-sm font-medium text-green-600 dark:text-green-400 bg-white dark:bg-gray-800 border border-green-300 dark:border-green-600 rounded-md hover:bg-green-50 dark:hover:bg-green-900/20 transition-colors duration-200">
                        Alterar
                    </button>
                </div>
            </div>
            
            <!-- Additional Guest Information -->
            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-users mr-1"></i>
                        Número de Hóspedes
                    </label>
                    <input type="number" 
                           wire:model.live="guests" 
                           min="1" 
                           max="{{ $selectedRoomType['capacity'] ?? 10 }}"
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        Máximo: {{ $selectedRoomType['capacity'] ?? 10 }} pessoas
                    </p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-star mr-1"></i>
                        Pedidos Especiais
                    </label>
                    <textarea wire:model.live="specialRequests" 
                              rows="3" 
                              placeholder="Ex: Cama extra, vegetariano, aniversário..."
                              class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"></textarea>
                </div>
            </div>
            
            <!-- Continue Button -->
            <div class="mt-6 flex justify-end">
                <button wire:click="goToStep('confirm')" 
                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white text-sm font-medium rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105">
                    <i class="fas fa-check mr-2"></i>
                    Continuar para Confirmação
                    <i class="fas fa-arrow-right ml-2"></i>
                </button>
            </div>
        </div>
    @endif

    <!-- Quick Stats -->
    @if($selectedUser)
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-calendar-check text-blue-600 dark:text-blue-400"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Check-in</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ \Carbon\Carbon::parse($checkIn)->format('d/m/Y') }}
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-calendar-times text-indigo-600 dark:text-indigo-400"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Check-out</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ \Carbon\Carbon::parse($checkOut)->format('d/m/Y') }}
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-moon text-green-600 dark:text-green-400"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Duração</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ $nights }} {{ $nights === 1 ? 'noite' : 'noites' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

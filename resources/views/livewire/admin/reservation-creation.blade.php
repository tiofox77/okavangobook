<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg mb-6">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                            <i class="fas fa-plus-circle text-blue-600 dark:text-blue-400"></i>
                        </div>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Criar Nova Reserva</h1>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Sistema interativo de criação de reservas para administradores</p>
                    </div>
                </div>
                <a href="{{ route('admin.reservations') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Voltar às Reservas
                </a>
            </div>
        </div>
    </div>

    <!-- Progress Steps -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg mb-6">
        <div class="px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <!-- Step 1: Location -->
                    <div class="flex items-center {{ $currentStep === 'location' ? 'text-blue-600 dark:text-blue-400' : ($selectedLocationId ? 'text-green-600 dark:text-green-400' : 'text-gray-400') }}">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $currentStep === 'location' ? 'bg-blue-100 dark:bg-blue-900' : ($selectedLocationId ? 'bg-green-100 dark:bg-green-900' : 'bg-gray-100 dark:bg-gray-700') }}">
                            @if($selectedLocationId)
                                <i class="fas fa-check text-sm"></i>
                            @else
                                <span class="text-sm font-medium">1</span>
                            @endif
                        </div>
                        <span class="ml-2 text-sm font-medium">Localização</span>
                    </div>

                    <div class="w-8 h-0.5 {{ $selectedLocationId ? 'bg-green-400' : 'bg-gray-300 dark:bg-gray-600' }}"></div>

                    <!-- Step 2: Hotel -->
                    <div class="flex items-center {{ $currentStep === 'hotel' ? 'text-blue-600 dark:text-blue-400' : ($hotelId ? 'text-green-600 dark:text-green-400' : 'text-gray-400') }}">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $currentStep === 'hotel' ? 'bg-blue-100 dark:bg-blue-900' : ($hotelId ? 'bg-green-100 dark:bg-green-900' : 'bg-gray-100 dark:bg-gray-700') }}">
                            @if($hotelId)
                                <i class="fas fa-check text-sm"></i>
                            @else
                                <span class="text-sm font-medium">2</span>
                            @endif
                        </div>
                        <span class="ml-2 text-sm font-medium">Hotel</span>
                    </div>

                    <div class="w-8 h-0.5 {{ $hotelId ? 'bg-green-400' : 'bg-gray-300 dark:bg-gray-600' }}"></div>

                    <!-- Step 3: Room -->
                    <div class="flex items-center {{ $currentStep === 'room' ? 'text-blue-600 dark:text-blue-400' : ($roomId ? 'text-green-600 dark:text-green-400' : 'text-gray-400') }}">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $currentStep === 'room' ? 'bg-blue-100 dark:bg-blue-900' : ($roomId ? 'bg-green-100 dark:bg-green-900' : 'bg-gray-100 dark:bg-gray-700') }}">
                            @if($roomId)
                                <i class="fas fa-check text-sm"></i>
                            @else
                                <span class="text-sm font-medium">3</span>
                            @endif
                        </div>
                        <span class="ml-2 text-sm font-medium">Quarto</span>
                    </div>

                    <div class="w-8 h-0.5 {{ $roomId ? 'bg-green-400' : 'bg-gray-300 dark:bg-gray-600' }}"></div>

                    <!-- Step 4: Guest -->
                    <div class="flex items-center {{ $currentStep === 'guest' ? 'text-blue-600 dark:text-blue-400' : ($userId ? 'text-green-600 dark:text-green-400' : 'text-gray-400') }}">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $currentStep === 'guest' ? 'bg-blue-100 dark:bg-blue-900' : ($userId ? 'bg-green-100 dark:bg-green-900' : 'bg-gray-100 dark:bg-gray-700') }}">
                            @if($userId)
                                <i class="fas fa-check text-sm"></i>
                            @else
                                <span class="text-sm font-medium">4</span>
                            @endif
                        </div>
                        <span class="ml-2 text-sm font-medium">Hóspede</span>
                    </div>

                    <div class="w-8 h-0.5 {{ $userId ? 'bg-green-400' : 'bg-gray-300 dark:bg-gray-600' }}"></div>

                    <!-- Step 5: Confirm -->
                    <div class="flex items-center {{ $currentStep === 'confirm' ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400' }}">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $currentStep === 'confirm' ? 'bg-blue-100 dark:bg-blue-900' : 'bg-gray-100 dark:bg-gray-700' }}">
                            <span class="text-sm font-medium">5</span>
                        </div>
                        <span class="ml-2 text-sm font-medium">Confirmar</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Main Form -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                <div class="p-6">
                    @switch($currentStep)
                        @case('location')
                            @include('livewire.admin.reservation-creation.step-location')
                            @break
                            
                        @case('hotel')
                            @include('livewire.admin.reservation-creation.step-hotel')
                            @break
                            
                        @case('room')
                            @include('livewire.admin.reservation-creation.step-room')
                            @break
                            
                        @case('guest')
                            @include('livewire.admin.reservation-creation.step-guest')
                            @break
                            
                        @case('confirm')
                            @include('livewire.admin.reservation-creation.step-confirm')
                            @break
                            
                        @default
                            <div class="text-center py-12">
                                <div class="w-16 h-16 bg-red-100 dark:bg-red-900 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400 text-xl"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Erro</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    Passo não reconhecido. Por favor, reinicie o processo.
                                </p>
                                <button wire:click="resetForm" 
                                        class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors duration-200">
                                    Reiniciar
                                </button>
                            </div>
                    @endswitch
                </div>
            </div>
        </div>

        <!-- Right Column - Summary -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg sticky top-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Resumo da Reserva</h3>
                    
                    <!-- Dates -->
                    <div class="mb-4">
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Check-in</label>
                            <input type="date" wire:model.live="checkIn" class="text-sm border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        </div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Check-out</label>
                            <input type="date" wire:model.live="checkOut" class="text-sm border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        </div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Hóspedes</label>
                            <input type="number" wire:model.live="guests" min="1" class="text-sm border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 w-20">
                        </div>
                        @if($nights)
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $nights }} {{ $nights === 1 ? 'noite' : 'noites' }}</p>
                        @endif
                    </div>

                    <div class="border-t dark:border-gray-700 pt-4">
                        <!-- Location -->
                        @if($selectedLocationId)
                            <div class="mb-3">
                                <div class="flex items-center text-sm">
                                    <i class="fas fa-map-marker-alt text-gray-400 w-4"></i>
                                    <span class="ml-2 text-gray-600 dark:text-gray-400">
                                        {{ $locations->find($selectedLocationId)->name ?? 'Localização' }}
                                    </span>
                                </div>
                            </div>
                        @endif

                        <!-- Hotel -->
                        @if($selectedHotel)
                            <div class="mb-3">
                                <div class="flex items-center text-sm">
                                    <i class="fas fa-hotel text-gray-400 w-4"></i>
                                    <span class="ml-2 text-gray-600 dark:text-gray-400">{{ $selectedHotel['name'] }}</span>
                                </div>
                            </div>
                        @endif

                        <!-- Room Type -->
                        @if($selectedRoomType)
                            <div class="mb-3">
                                <div class="flex items-center text-sm">
                                    <i class="fas fa-bed text-gray-400 w-4"></i>
                                    <span class="ml-2 text-gray-600 dark:text-gray-400">{{ $selectedRoomType['name'] }}</span>
                                </div>
                            </div>
                        @endif

                        <!-- Room Number -->
                        @if($selectedRoom)
                            <div class="mb-3">
                                <div class="flex items-center text-sm">
                                    <i class="fas fa-door-open text-gray-400 w-4"></i>
                                    <span class="ml-2 text-gray-600 dark:text-gray-400">Quarto {{ $selectedRoom['room_number'] }}</span>
                                </div>
                            </div>
                        @endif

                        <!-- Guest -->
                        @if($selectedUser)
                            <div class="mb-3">
                                <div class="flex items-center text-sm">
                                    <i class="fas fa-user text-gray-400 w-4"></i>
                                    <span class="ml-2 text-gray-600 dark:text-gray-400">{{ $selectedUser['name'] }}</span>
                                </div>
                            </div>
                        @endif

                        <!-- Price -->
                        @if($totalPrice)
                            <div class="border-t dark:border-gray-700 pt-3 mt-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-lg font-semibold text-gray-900 dark:text-gray-100">Total</span>
                                    <span class="text-lg font-bold text-blue-600 dark:text-blue-400">{{ number_format($totalPrice, 0, ',', '.') }} Kz</span>
                                </div>
                                @if($selectedRoomType && $nights)
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        {{ number_format($selectedRoomType['base_price'], 0, ',', '.') }} Kz × {{ $nights }} {{ $nights === 1 ? 'noite' : 'noites' }}
                                    </p>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- User Selection Modal -->
    @if($showUserModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: @entangle('showUserModal') }">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeUserModal"></div>
                
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100 mb-4">
                            Selecionar Hóspede
                        </h3>
                        
                        <div class="mb-4">
                            <input type="text" 
                                   wire:model.live.debounce.300ms="searchUser" 
                                   wire:keyup="searchUsers"
                                   placeholder="Pesquisar por nome ou email..."
                                   class="w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        </div>
                        
                        @if($users->count() > 0)
                            <div class="max-h-60 overflow-y-auto">
                                @foreach($users as $user)
                                    <div wire:click="selectUser({{ $user->id }})" 
                                         class="p-3 border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-user text-blue-600 dark:text-blue-400 text-sm"></i>
                                                </div>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $user->name }}</p>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @elseif(strlen($searchUser) >= 2)
                            <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">
                                Nenhum utilizador encontrado.
                            </p>
                        @endif
                    </div>
                    
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="closeUserModal" 
                                type="button" 
                                class="w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Create New User Modal -->
    @if($showCreateUserModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: @entangle('showCreateUserModal') }">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeCreateUserModal"></div>
                
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="flex items-center mb-6">
                            <div class="w-10 h-10 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-user-plus text-green-600 dark:text-green-400"></i>
                            </div>
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">
                                Criar Novo Hóspede
                            </h3>
                        </div>
                        
                        <form wire:submit.prevent="createUser">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Nome Completo -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        <i class="fas fa-user mr-1"></i>
                                        Nome Completo *
                                    </label>
                                    <input type="text" 
                                           wire:model="newUserName"
                                           required
                                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200">
                                    @error('newUserName') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <!-- Email -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        <i class="fas fa-envelope mr-1"></i>
                                        Email *
                                    </label>
                                    <input type="email" 
                                           wire:model="newUserEmail"
                                           required
                                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200">
                                    @error('newUserEmail') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <!-- Telefone -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        <i class="fas fa-phone mr-1"></i>
                                        Telefone *
                                    </label>
                                    <input type="tel" 
                                           wire:model="newUserPhone"
                                           required
                                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200">
                                    @error('newUserPhone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <!-- Tipo de Documento -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        <i class="fas fa-id-card mr-1"></i>
                                        Tipo de Documento *
                                    </label>
                                    <select wire:model="newUserDocumentType"
                                            required
                                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200">
                                        @foreach($documentTypes as $key => $label)
                                            <option value="{{ $key }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('newUserDocumentType') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <!-- Número do Documento -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        <i class="fas fa-hashtag mr-1"></i>
                                        Número do Documento *
                                    </label>
                                    <input type="text" 
                                           wire:model="newUserDocument"
                                           required
                                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200">
                                    @error('newUserDocument') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <!-- Data de Nascimento -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        <i class="fas fa-calendar mr-1"></i>
                                        Data de Nascimento
                                    </label>
                                    <input type="date" 
                                           wire:model="newUserBirthdate"
                                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200">
                                    @error('newUserBirthdate') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <!-- Nacionalidade -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        <i class="fas fa-flag mr-1"></i>
                                        Nacionalidade *
                                    </label>
                                    <input type="text" 
                                           wire:model="newUserNationality"
                                           required
                                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200">
                                    @error('newUserNationality') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="createUser" 
                                type="button" 
                                class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-base font-medium text-white hover:shadow-lg transform hover:scale-105 transition-all duration-200 sm:ml-3 sm:w-auto sm:text-sm">
                            <i class="fas fa-plus mr-2"></i>
                            Criar Hóspede
                        </button>
                        <button wire:click="closeCreateUserModal" 
                                type="button" 
                                class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200 sm:mt-0 sm:w-auto sm:text-sm">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

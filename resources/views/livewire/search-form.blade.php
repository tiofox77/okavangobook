<div class="bg-white rounded-lg shadow-lg p-6 mb-8">
    <form wire:submit.prevent="search" class="space-y-4">
        <!-- Título do formulário -->
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Encontre as melhores acomodações em Angola</h2>
        
        <!-- Mensagens de erro -->
        @error('location') <div class="text-red-600 text-sm mb-2">{{ $message }}</div> @enderror
        @error('checkIn') <div class="text-red-600 text-sm mb-2">{{ $message }}</div> @enderror
        @error('checkOut') <div class="text-red-600 text-sm mb-2">{{ $message }}</div> @enderror
        
        <!-- Filtro de Províncias e Campo de localização -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <!-- Seletor de Províncias -->
            <div>
                <label for="province" class="block text-gray-700 font-medium mb-2">
                    Província
                    @if(!empty($selectedProvince))
                        <span class="ml-2 text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full">
                            <i class="fas fa-check-circle mr-1"></i>Filtro ativo
                        </span>
                    @endif
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-map text-gray-400"></i>
                    </div>
                    <select 
                        id="province" 
                        wire:model.live="selectedProvince"
                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent appearance-none"
                    >
                        <option value="" selected class="font-bold">Todas as províncias</option>
                        @foreach($provinces as $provinceName => $hotelCount)
                            <option value="{{ $provinceName }}">
                                {{ ucfirst($provinceName) }} ({{ $hotelCount }} {{ $hotelCount == 1 ? 'hotel' : 'hotéis' }})
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <i class="fas fa-chevron-down text-gray-400"></i>
                    </div>
                </div>
            </div>
            
            <!-- Campo de localização com sugestões -->
            <div class="relative">
                <label for="location" class="block text-gray-700 font-medium mb-2">
                    Destino específico (opcional)
                    @if(!empty($location) && !empty($locationId))
                        <span class="ml-2 text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full">
                            <i class="fas fa-map-pin mr-1"></i>Selecionado
                        </span>
                    @endif
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-map-marker-alt text-gray-400"></i>
                    </div>
                    <input 
                        type="text" 
                        id="location" 
                        wire:model.live.debounce.300ms="location" 
                        placeholder="Hotel ou localidade específica" 
                        class="w-full pl-10 pr-4 py-3 border @error('location') border-red-500 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                        autocomplete="off"
                    >
                </div>
                
                <!-- Sugestões de localização -->
                @if(!empty($location) && count($locationSuggestions) > 0)
                    <div class="absolute z-10 w-full bg-white mt-1 border border-gray-300 rounded-lg shadow-lg max-h-64 overflow-y-auto">
                        @foreach($locationSuggestions as $suggestion)
                            <div 
                                class="px-4 py-3 hover:bg-blue-50 cursor-pointer border-b border-gray-100 last:border-b-0 transition-colors"
                                wire:click="selectLocation('{{ $suggestion['name'] }}', {{ $suggestion['id'] }})"
                            >
                                <div class="flex items-center">
                                    @if($suggestion['type'] === 'hotel')
                                        <i class="fas fa-hotel text-primary mr-3 text-lg"></i>
                                        <div class="flex-1">
                                            <span class="font-semibold text-gray-800">{{ $suggestion['name'] }}</span>
                                            @if(isset($suggestion['location_name']))
                                                <span class="text-gray-500 text-sm block">{{ $suggestion['location_name'] }}, {{ $suggestion['province'] }}</span>
                                            @endif
                                        </div>
                                        <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full">Hotel</span>
                                    @else
                                        <i class="fas fa-map-marker-alt text-orange-500 mr-3 text-lg"></i>
                                        <div class="flex-1">
                                            <span class="font-semibold text-gray-800">{{ $suggestion['name'] }}</span>
                                            @if(isset($suggestion['province']))
                                                <span class="text-gray-500 text-sm block">{{ $suggestion['province'] }}</span>
                                            @endif
                                        </div>
                                        <span class="text-xs bg-orange-100 text-orange-700 px-2 py-1 rounded-full">Cidade</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Datas de Check-in e Check-out -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="check-in" class="block text-gray-700 font-medium mb-2">Check-in</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="far fa-calendar-alt text-gray-400"></i>
                    </div>
                    <input 
                        type="date" 
                        id="check-in" 
                        wire:model="checkIn" 
                        class="w-full pl-10 pr-4 py-3 border @error('checkIn') border-red-500 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                        min="{{ date('Y-m-d') }}"
                        required
                    >
                </div>
            </div>
            <div>
                <label for="check-out" class="block text-gray-700 font-medium mb-2">Check-out</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="far fa-calendar-alt text-gray-400"></i>
                    </div>
                    <input 
                        type="date" 
                        id="check-out" 
                        wire:model="checkOut" 
                        class="w-full pl-10 pr-4 py-3 border @error('checkOut') border-red-500 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                        min="{{ $checkIn }}"
                        required
                    >
                </div>
            </div>
        </div>
        
        <!-- Número de hóspedes e quartos -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="guests" class="block text-gray-700 font-medium mb-2">Hóspedes</label>
                <div class="flex items-center border border-gray-300 rounded-lg">
                    <button 
                        type="button" 
                        class="px-4 py-3 bg-gray-100 text-gray-600 hover:bg-gray-200 focus:outline-none transition-colors {{ $guests <= 1 ? 'opacity-50 cursor-not-allowed' : '' }}"
                        wire:click="decrementGuests"
                        {{ $guests <= 1 ? 'disabled' : '' }}
                    >
                        <i class="fas fa-minus"></i>
                    </button>
                    <div class="flex-1 text-center py-3 font-medium">
                        {{ $guestLabel }}
                    </div>
                    <button 
                        type="button" 
                        class="px-4 py-3 bg-gray-100 text-gray-600 hover:bg-gray-200 focus:outline-none transition-colors {{ $guests >= 10 ? 'opacity-50 cursor-not-allowed' : '' }}"
                        wire:click="incrementGuests"
                        {{ $guests >= 10 ? 'disabled' : '' }}
                    >
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
                <input type="hidden" wire:model.defer="guests" min="1" max="10" required>
            </div>
            <div>
                <label for="rooms" class="block text-gray-700 font-medium mb-2">Quartos</label>
                <div class="flex items-center border border-gray-300 rounded-lg">
                    <button 
                        type="button" 
                        class="px-4 py-3 bg-gray-100 text-gray-600 hover:bg-gray-200 focus:outline-none transition-colors {{ $rooms <= 1 ? 'opacity-50 cursor-not-allowed' : '' }}"
                        wire:click="decrementRooms"
                        {{ $rooms <= 1 ? 'disabled' : '' }}
                    >
                        <i class="fas fa-minus"></i>
                    </button>
                    <div class="flex-1 text-center py-3 font-medium">
                        {{ $roomLabel }}
                    </div>
                    <button 
                        type="button" 
                        class="px-4 py-3 bg-gray-100 text-gray-600 hover:bg-gray-200 focus:outline-none transition-colors {{ $rooms >= 5 ? 'opacity-50 cursor-not-allowed' : '' }}"
                        wire:click="incrementRooms"
                        {{ $rooms >= 5 ? 'disabled' : '' }}
                    >
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
                <input type="hidden" wire:model.defer="rooms" min="1" max="5" required>
            </div>
        </div>
        
        <!-- Botão de pesquisa -->
        <div class="pt-2">
            <button 
                type="submit" 
                class="w-full bg-primary hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300 flex items-center justify-center"
                wire:loading.class="opacity-75 cursor-wait"
                wire:loading.attr="disabled"
                wire:target="search"
                x-data="{}"
            >
                <span wire:loading.remove wire:target="search"><i class="fas fa-search mr-2"></i> Pesquisar</span>
                <span wire:loading wire:target="search"><i class="fas fa-spinner fa-spin mr-2"></i> Pesquisando...</span>
            </button>
        </div>
        
        <!-- Mensagens de erro -->
        @if (session()->has('error'))
            <div class="mt-4 p-3 bg-red-100 text-red-700 rounded-lg">
                {{ session('error') }}
            </div>
        @endif
    </form>
</div>

<!-- Livewire v3 script para redirecionamento -->
@push('scripts')
<script>
    document.addEventListener('livewire:init', () => {
        // Livewire v3 usa o padrão dispatch/listen para eventos
        Livewire.on('redirect', ({ url }) => {
            window.location.href = url;
        });
    });
</script>
@endpush

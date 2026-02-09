<div class="min-h-screen bg-gray-100 dark:bg-gray-900 py-8">
    <div class="max-w-6xl mx-auto px-4">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                <i class="fas fa-bell text-yellow-500 mr-2"></i> Meus Alertas de Preço
            </h1>
            <button wire:click="create" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                <i class="fas fa-plus mr-2"></i> Criar Alerta
            </button>
        </div>

        @if (session()->has('message'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{session('message') }}
            </div>
        @endif

        <div class="grid gap-4">
            @forelse($alerts as $alert)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                                    {{ $alert->hotel->name }}
                                </h3>
                                <span class="px-2 py-1 rounded text-xs {{ $alert->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $alert->is_active ? 'Ativo' : 'Inativo' }}
                                </span>
                                @if($alert->notification_sent)
                                    <span class="px-2 py-1 rounded text-xs bg-blue-100 text-blue-800">
                                        <i class="fas fa-check"></i> Atingido
                                    </span>
                                @endif
                            </div>
                            
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4 text-sm">
                                <div>
                                    <span class="text-gray-600 dark:text-gray-400">Preço Alvo:</span>
                                    <p class="font-bold text-blue-600">{{ number_format($alert->target_price, 2) }} Kz</p>
                                </div>
                                @if($alert->current_price)
                                    <div>
                                        <span class="text-gray-600 dark:text-gray-400">Preço Atual:</span>
                                        <p class="font-bold {{ $alert->current_price <= $alert->target_price ? 'text-green-600' : 'text-gray-900 dark:text-white' }}">
                                            {{ number_format($alert->current_price, 2) }} Kz
                                        </p>
                                    </div>
                                @endif
                                @if($alert->roomType)
                                    <div>
                                        <span class="text-gray-600 dark:text-gray-400">Quarto:</span>
                                        <p class="font-medium">{{ $alert->roomType->name }}</p>
                                    </div>
                                @endif
                                @if($alert->last_checked_at)
                                    <div>
                                        <span class="text-gray-600 dark:text-gray-400">Última Verificação:</span>
                                        <p class="text-xs">{{ $alert->last_checked_at->diffForHumans() }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="flex gap-2 ml-4">
                            <button wire:click="toggleStatus({{ $alert->id }})" class="text-gray-600 hover:text-blue-600">
                                <i class="fas fa-power-off"></i>
                            </button>
                            <button wire:click="delete({{ $alert->id }})" onclick="return confirm('Remover este alerta?')" class="text-gray-600 hover:text-red-600">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-12 text-center">
                    <i class="fas fa-bell-slash text-gray-400 text-6xl mb-4"></i>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Nenhum alerta criado</h2>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">Crie alertas para ser notificado quando o preço baixar</p>
                    <button wire:click="create" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                        Criar Primeiro Alerta
                    </button>
                </div>
            @endforelse
        </div>

        @if($alerts->hasPages())
            <div class="mt-6">
                {{ $alerts->links() }}
            </div>
        @endif

        @if($showModal)
            <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" wire:click="closeModal">
                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-2xl" wire:click.stop>
                    <h2 class="text-xl font-bold mb-4">Criar Alerta de Preço</h2>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="block mb-2">Hotel *</label>
                            <select wire:model.live="hotel_id" class="w-full px-4 py-2 border rounded-lg">
                                <option value="">Selecione um hotel</option>
                                @foreach($hotels as $hotel)
                                    <option value="{{ $hotel->id }}">{{ $hotel->name }}</option>
                                @endforeach
                            </select>
                            @error('hotel_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        @if(count($roomTypes) > 0)
                            <div class="col-span-2">
                                <label class="block mb-2">Tipo de Quarto (Opcional)</label>
                                <select wire:model="room_type_id" class="w-full px-4 py-2 border rounded-lg">
                                    <option value="">Qualquer quarto</option>
                                    @foreach($roomTypes as $roomType)
                                        <option value="{{ $roomType->id }}">{{ $roomType->name }} - {{ number_format($roomType->base_price, 2) }} Kz</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div>
                            <label class="block mb-2">Preço Alvo (Kz) *</label>
                            <input wire:model="target_price" type="number" step="0.01" class="w-full px-4 py-2 border rounded-lg">
                            @error('target_price') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block mb-2">Hóspedes</label>
                            <input wire:model="guests" type="number" min="1" class="w-full px-4 py-2 border rounded-lg">
                        </div>

                        <div>
                            <label class="block mb-2">Check-in</label>
                            <input wire:model="check_in" type="date" class="w-full px-4 py-2 border rounded-lg">
                        </div>

                        <div>
                            <label class="block mb-2">Check-out</label>
                            <input wire:model="check_out" type="date" class="w-full px-4 py-2 border rounded-lg">
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 mt-6">
                        <button wire:click="closeModal" class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">Cancelar</button>
                        <button wire:click="save" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Criar Alerta</button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

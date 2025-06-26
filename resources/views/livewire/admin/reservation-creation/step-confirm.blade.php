<div class="space-y-8">
    <!-- Header Section -->
    <div class="text-center">
        <div class="w-20 h-20 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-check text-3xl text-green-600 dark:text-green-400"></i>
        </div>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
            Confirmar Reserva
        </h2>
        <p class="text-gray-600 dark:text-gray-400">
            Verifique os detalhes da sua reserva antes de finalizar
        </p>
    </div>

    <!-- Reservation Summary Card -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-500 to-purple-600 p-6">
            <div class="flex items-center justify-between text-white">
                <div>
                    <h3 class="text-xl font-semibold mb-1">Resumo da Reserva</h3>
                    <p class="text-blue-100">{{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-blue-100">ID Temporário</p>
                    <p class="text-lg font-mono font-bold">#{{ Str::random(8) }}</p>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6 space-y-6">
            <!-- Guest Info -->
            @if($selectedUser)
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                <div class="flex items-center mb-3">
                    <div class="w-10 h-10 bg-orange-100 dark:bg-orange-900 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-user text-orange-600 dark:text-orange-400"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900 dark:text-white">Hóspede Principal</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Informações do utilizador</p>
                    </div>
                </div>
                <div class="space-y-2">
                    <p class="text-gray-900 dark:text-white font-medium">
                        {{ $selectedUser['name'] ?? 'N/A' }}
                    </p>
                    @if($selectedUser['email'] ?? false)
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            <i class="fas fa-envelope mr-1"></i>
                            {{ $selectedUser['email'] }}
                        </p>
                    @endif
                </div>
            </div>
            @endif

            <!-- Location & Hotel -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                    <div class="flex items-center mb-3">
                        <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-map-marker-alt text-blue-600 dark:text-blue-400"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 dark:text-white">Localização</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Destino</p>
                        </div>
                    </div>
                    <p class="text-gray-900 dark:text-white font-medium">
                        {{ $locations->find($selectedLocationId)->name ?? 'N/A' }}
                    </p>
                </div>

                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                    <div class="flex items-center mb-3">
                        <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-hotel text-purple-600 dark:text-purple-400"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 dark:text-white">Hotel</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Acomodação</p>
                        </div>
                    </div>
                    <p class="text-gray-900 dark:text-white font-medium">
                        {{ $selectedHotel['name'] ?? 'N/A' }}
                    </p>
                </div>
            </div>

            <!-- Room Info -->
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                <div class="flex items-center mb-3">
                    <div class="w-10 h-10 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-bed text-green-600 dark:text-green-400"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900 dark:text-white">Quarto</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $guests }} hóspede(s)</p>
                    </div>
                </div>
                <p class="text-gray-900 dark:text-white font-medium">
                    {{ $selectedRoomType['name'] ?? 'N/A' }}
                </p>
            </div>

            <!-- Dates -->
            <div class="bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-indigo-900 dark:to-purple-900 rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                        <i class="fas fa-calendar-alt text-indigo-600 dark:text-indigo-400 mr-2"></i>
                        Período da Estadia
                    </h4>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 dark:bg-indigo-800 text-indigo-800 dark:text-indigo-200">
                        {{ $nights }} {{ $nights > 1 ? 'noites' : 'noite' }}
                    </span>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="text-center">
                        <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm">
                            <i class="fas fa-sign-in-alt text-green-600 dark:text-green-400 text-xl mb-2"></i>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Check-in</p>
                            <p class="font-semibold text-gray-900 dark:text-white">
                                {{ \Carbon\Carbon::parse($checkIn)->format('d/m/Y') }}
                            </p>
                        </div>
                    </div>
                    
                    <div class="text-center flex items-center justify-center">
                        <div class="flex items-center text-gray-400">
                            <i class="fas fa-arrow-right"></i>
                        </div>
                    </div>
                    
                    <div class="text-center">
                        <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm">
                            <i class="fas fa-sign-out-alt text-red-600 dark:text-red-400 text-xl mb-2"></i>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Check-out</p>
                            <p class="font-semibold text-gray-900 dark:text-white">
                                {{ \Carbon\Carbon::parse($checkOut)->format('d/m/Y') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Method Selection -->
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <i class="fas fa-credit-card text-blue-600 dark:text-blue-400 mr-2"></i>
                    Método de Pagamento
                </h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($paymentMethods as $method => $label)
                        <div class="relative">
                            <input type="radio" 
                                   id="payment_{{ $method }}" 
                                   wire:model.live="paymentMethod" 
                                   value="{{ $method }}"
                                   class="peer sr-only">
                            <label for="payment_{{ $method }}" 
                                   class="flex items-center p-4 border-2 border-gray-200 dark:border-gray-600 rounded-lg cursor-pointer transition-all duration-200 peer-checked:border-blue-500 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-900/20 hover:border-gray-300">
                                <div class="flex items-center w-full">
                                    @if($method === 'credit_card')
                                        <i class="fas fa-credit-card text-blue-600 text-xl mr-3"></i>
                                    @elseif($method === 'debit_card')
                                        <i class="fas fa-money-check-alt text-green-600 text-xl mr-3"></i>
                                    @elseif($method === 'bank_transfer')
                                        <i class="fas fa-university text-purple-600 text-xl mr-3"></i>
                                    @elseif($method === 'cash')
                                        <i class="fas fa-money-bill-wave text-yellow-600 text-xl mr-3"></i>
                                    @elseif($method === 'mobile_payment')
                                        <i class="fas fa-mobile-alt text-pink-600 text-xl mr-3"></i>
                                    @endif
                                    <div class="flex-1">
                                        <div class="font-medium text-gray-900 dark:text-white">
                                            {{ $label }}
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    @endforeach
                </div>
                
                <!-- Current Payment Method -->
                @if($paymentMethod)
                    <div class="mt-4 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-blue-600 dark:text-blue-400 mr-2"></i>
                            <span class="text-sm text-blue-800 dark:text-blue-200">
                                Método selecionado: <strong>{{ $paymentMethods[$paymentMethod] ?? $paymentMethod }}</strong>
                            </span>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Special Requests -->
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <i class="fas fa-sticky-note text-orange-600 dark:text-orange-400 mr-2"></i>
                    Pedidos Especiais
                </h4>
                
                <textarea wire:model.live="specialRequests" 
                          rows="3" 
                          placeholder="Descreva qualquer pedido especial..."
                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"></textarea>
            </div>

            <!-- Price Details -->
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <i class="fas fa-calculator text-green-600 dark:text-green-400 mr-2"></i>
                    Detalhes do Preço
                </h4>
                
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Preço por noite</span>
                        <span class="font-medium text-gray-900 dark:text-white">
                            {{ \App\Helpers\CurrencyHelper::formatKwanza($selectedRoomType['price_per_night'] ?? 0) }}
                        </span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Noites ({{ $nights }})</span>
                        <span class="font-medium text-gray-900 dark:text-white">
                            {{ \App\Helpers\CurrencyHelper::formatKwanza(($selectedRoomType['price_per_night'] ?? 0) * ($nights ?? 1)) }}
                        </span>
                    </div>
                    
                    <div class="border-t pt-3">
                        <div class="flex justify-between">
                            <span class="text-lg font-semibold text-gray-900 dark:text-white">Total</span>
                            <span class="text-2xl font-bold text-green-600 dark:text-green-400">
                                {{ \App\Helpers\CurrencyHelper::formatKwanza($totalPrice ?? 0) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-between">
                <button wire:click="goToPreviousStep" 
                        class="inline-flex items-center justify-center px-6 py-3 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-lg transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Voltar
                </button>
                
                <div class="flex gap-3">
                    <button wire:click="saveAsDraft" 
                            wire:loading.attr="disabled"
                            class="inline-flex items-center justify-center px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg transition-colors disabled:opacity-50">
                        <span wire:loading.remove wire:target="saveAsDraft">
                            <i class="fas fa-save mr-2"></i>
                            Salvar como Rascunho
                        </span>
                        <span wire:loading wire:target="saveAsDraft">
                            Salvando...
                        </span>
                    </button>
                    
                    <button wire:click="confirmReservation" 
                            wire:loading.attr="disabled"
                            class="inline-flex items-center justify-center px-8 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow-lg transition-all disabled:opacity-50">
                        <span wire:loading.remove wire:target="confirmReservation">
                            <i class="fas fa-check-circle mr-2"></i>
                            Confirmar Reserva
                        </span>
                        <span wire:loading wire:target="confirmReservation">
                            Confirmando...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmação -->
@if($showConfirmModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 max-w-md w-full mx-4 shadow-2xl">
            <div class="text-center">
                <div class="w-16 h-16 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-check text-2xl text-green-600 dark:text-green-400"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                    Reserva Criada com Sucesso!
                </h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6">
                    A reserva foi registrada e está pronta para processamento.
                </p>
                <div class="flex flex-col sm:flex-row gap-3">
                    <button wire:click="resetForm" 
                            class="inline-flex items-center justify-center px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-plus mr-2"></i>
                        Nova Reserva
                    </button>
                    <button onclick="window.location.href='{{ route('admin.reservations') }}'" 
                            class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-list mr-2"></i>
                        Ver Reservas
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif

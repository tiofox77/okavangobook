<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                        <i class="fas fa-calendar-check text-blue-600 mr-2"></i>
                        Criar Reserva
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">
                        Complete os dados para finalizar sua reserva
                    </p>
                </div>
                
                <!-- Progress Steps -->
                <div class="hidden md:flex items-center space-x-4">
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full {{ $currentStep === 'details' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-600' }} flex items-center justify-center text-sm font-medium">
                            1
                        </div>
                        <span class="ml-2 text-sm {{ $currentStep === 'details' ? 'text-blue-600 font-medium' : 'text-gray-500' }}">Detalhes</span>
                    </div>
                    <div class="w-8 h-0.5 bg-gray-200"></div>
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full {{ $currentStep === 'confirmation' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-600' }} flex items-center justify-center text-sm font-medium">
                            2
                        </div>
                        <span class="ml-2 text-sm {{ $currentStep === 'confirmation' ? 'text-blue-600 font-medium' : 'text-gray-500' }}">Confirmação</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Formulário Principal -->
            <div class="lg:col-span-2">
                @if($currentStep === 'details')
                    <!-- Step 1: Detalhes da Reserva -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                            <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                            Detalhes da Reserva
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <!-- Check-in -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    <i class="fas fa-calendar-alt text-green-500 mr-1"></i>
                                    Check-in
                                </label>
                                <input type="date" 
                                       wire:model.blur="check_in" 
                                       class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                       min="{{ date('Y-m-d') }}">
                                @if($errors->has('check_in'))
                                    <span class="text-red-600 dark:text-red-400 text-sm">{{ $errors->first('check_in') }}</span>
                                @endif
                            </div>
                            
                            <!-- Check-out -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    <i class="fas fa-calendar-alt text-red-500 mr-1"></i>
                                    Check-out
                                </label>
                                <input type="date" 
                                       wire:model.blur="check_out" 
                                       class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                       min="{{ $check_in ?: date('Y-m-d') }}">
                                @if($errors->has('check_out'))
                                    <span class="text-red-600 dark:text-red-400 text-sm">{{ $errors->first('check_out') }}</span>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Hóspedes -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                <i class="fas fa-users text-purple-500 mr-1"></i>
                                Número de Hóspedes
                            </label>
                            <select wire:model.live="guests" 
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                @for($i = 1; $i <= 10; $i++)
                                    <option value="{{ $i }}">{{ $i }} {{ $i === 1 ? 'Hóspede' : 'Hóspedes' }}</option>
                                @endfor
                            </select>
                            @if($errors->has('guests'))
                                <span class="text-red-600 dark:text-red-400 text-sm">{{ $errors->first('guests') }}</span>
                            @endif
                        </div>
                        
                        <!-- Seleção de Quarto -->
                        @if($availableRooms->count() > 0)
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    <i class="fas fa-bed text-indigo-500 mr-1"></i>
                                    Selecionar Quarto (Opcional)
                                </label>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    @foreach($availableRooms as $room)
                                        <div class="relative">
                                            <input type="radio" 
                                                   wire:model.live="room_id" 
                                                   value="{{ $room->id }}" 
                                                   id="room_{{ $room->id }}" 
                                                   class="sr-only">
                                            <label for="room_{{ $room->id }}" 
                                                   class="block p-3 border rounded-lg cursor-pointer transition-all duration-200 hover:border-blue-300 hover:shadow-sm {{ $room_id == $room->id ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-gray-200 dark:border-gray-600' }}">
                                                <div class="flex items-center justify-between">
                                                    <div>
                                                        <span class="font-medium text-gray-900 dark:text-white">
                                                            Quarto {{ $room->room_number }}
                                                        </span>
                                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                                            {{ $room->floor }}º andar
                                                        </p>
                                                    </div>
                                                    @if($room_id == $room->id)
                                                        <i class="fas fa-check-circle text-blue-500"></i>
                                                    @endif
                                                </div>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Se não selecionar um quarto específico, será atribuído automaticamente
                                </p>
                            </div>
                        @endif
                        
                        <!-- Método de Pagamento -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-credit-card text-green-500 mr-1"></i>
                                Método de Pagamento
                            </label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @php
                                    $paymentMethods = [
                                        'cash' => ['name' => 'Dinheiro', 'icon' => 'fas fa-money-bill-wave', 'color' => 'green'],
                                        'card' => ['name' => 'Cartão', 'icon' => 'fas fa-credit-card', 'color' => 'blue'],
                                        'transfer' => ['name' => 'Transferência', 'icon' => 'fas fa-exchange-alt', 'color' => 'purple'],
                                        'mobile_money' => ['name' => 'Mobile Money', 'icon' => 'fas fa-mobile-alt', 'color' => 'orange'],
                                    ];
                                @endphp
                                
                                @foreach($paymentMethods as $method => $details)
                                    <div class="relative">
                                        <input type="radio" 
                                               wire:model.live="payment_method" 
                                               value="{{ $method }}" 
                                               id="payment_{{ $method }}" 
                                               class="sr-only">
                                        <label for="payment_{{ $method }}" 
                                               class="block p-3 border rounded-lg cursor-pointer transition-all duration-200 hover:border-{{ $details['color'] }}-300 hover:shadow-sm {{ $payment_method === $method ? 'border-' . $details['color'] . '-500 bg-' . $details['color'] . '-50 dark:bg-' . $details['color'] . '-900/20' : 'border-gray-200 dark:border-gray-600' }}">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center">
                                                    <i class="{{ $details['icon'] }} text-{{ $details['color'] }}-500 mr-2"></i>
                                                    <span class="font-medium text-gray-900 dark:text-white">
                                                        {{ $details['name'] }}
                                                    </span>
                                                </div>
                                                @if($payment_method === $method)
                                                    <i class="fas fa-check-circle text-{{ $details['color'] }}-500"></i>
                                                @endif
                                            </div>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- Pedidos Especiais -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                <i class="fas fa-comment-alt text-yellow-500 mr-1"></i>
                                Pedidos Especiais (Opcional)
                            </label>
                            <textarea wire:model.blur="special_requests" 
                                      rows="3" 
                                      placeholder="Ex: Quarto no andar alto, cama extra, necessidades especiais..."
                                      class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"></textarea>
                            @if($errors->has('special_requests'))
                                <span class="text-red-600 dark:text-red-400 text-sm">{{ $errors->first('special_requests') }}</span>
                            @endif
                        </div>
                        
                        @if(!$isLoggedIn)
                            <!-- Dados do Hóspede (para não logados) -->
                            <div class="border-t pt-6">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                                    <i class="fas fa-user text-blue-500 mr-2"></i>
                                    Dados do Hóspede
                                </h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Nome Completo *
                                        </label>
                                        <input type="text" 
                                               wire:model.blur="guest_name" 
                                               placeholder="Nome completo do hóspede"
                                               class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        @if($errors->has('guest_name'))
                                            <span class="text-red-600 dark:text-red-400 text-sm">{{ $errors->first('guest_name') }}</span>
                                        @endif
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Email *
                                        </label>
                                        <input type="email" 
                                               wire:model.blur="guest_email" 
                                               placeholder="email@exemplo.co.ao"
                                               class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        @if($errors->has('guest_email'))
                                            <span class="text-red-600 dark:text-red-400 text-sm">{{ $errors->first('guest_email') }}</span>
                                        @endif
                                    </div>
                                    
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Telefone
                                        </label>
                                        <input type="tel" 
                                               wire:model.blur="guest_phone" 
                                               placeholder="+244 900 000 000"
                                               class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        @if($errors->has('guest_phone'))
                                            <span class="text-red-600 dark:text-red-400 text-sm">{{ $errors->first('guest_phone') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        <!-- Botão Avançar -->
                        <div class="flex justify-end pt-6 border-t">
                            <button type="button" 
                                    wire:click="nextStep" 
                                    class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200 flex items-center">
                                Continuar
                                <i class="fas fa-arrow-right ml-2"></i>
                            </button>
                        </div>
                    </div>
                    
                @elseif($currentStep === 'confirmation')
                    <!-- Step 2: Confirmação -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                            <i class="fas fa-check-circle text-green-600 mr-2"></i>
                            Confirmação da Reserva
                        </h2>
                        
                        <!-- Termos e Condições -->
                        <div class="mb-6 p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded-lg">
                            <div class="flex items-start">
                                <input type="checkbox" 
                                       wire:model.live="agreedToTerms" 
                                       id="terms" 
                                       class="mt-1 rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <label for="terms" class="ml-3 text-sm">
                                    <span class="font-medium text-gray-900 dark:text-white">
                                        Aceito os termos e condições *
                                    </span>
                                    <p class="text-gray-600 dark:text-gray-400 mt-1">
                                        Ao confirmar esta reserva, aceito as políticas de cancelamento, 
                                        check-in/check-out e outras condições do hotel.
                                    </p>
                                </label>
                            </div>
                            @if($errors->has('agreedToTerms'))
                                <span class="text-red-600 dark:text-red-400 text-sm">{{ $errors->first('agreedToTerms') }}</span>
                            @endif
                        </div>
                        
                        <!-- Botões -->
                        <div class="flex justify-between">
                            <button type="button" 
                                    wire:click="previousStep" 
                                    class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors duration-200 flex items-center">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Voltar
                            </button>
                            
                            <button type="button" 
                                    wire:click="confirmBooking" 
                                    wire:loading.attr="disabled"
                                    class="px-6 py-2 bg-green-600 hover:bg-green-700 disabled:bg-green-400 text-white font-medium rounded-lg transition-colors duration-200 flex items-center">
                                <span wire:loading.remove wire:target="confirmBooking">
                                    <i class="fas fa-check mr-2"></i>
                                    Confirmar Reserva
                                </span>
                                <span wire:loading wire:target="confirmBooking">
                                    <i class="fas fa-spinner fa-spin mr-2"></i>
                                    Processando...
                                </span>
                            </button>
                        </div>
                    </div>
                @endif
            </div>
            
            <!-- Sidebar com Resumo da Reserva -->
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 sticky top-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        <i class="fas fa-file-invoice-dollar text-blue-600 mr-2"></i>
                        Resumo da Reserva
                    </h3>
                    
                    @if($selectedHotel)
                        <!-- Hotel -->
                        <div class="mb-4 pb-4 border-b border-gray-200 dark:border-gray-600">
                            <div class="flex items-start space-x-3">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-hotel text-white"></i>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-900 dark:text-white">{{ $selectedHotel->name }}</h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        <i class="fas fa-map-marker-alt mr-1"></i>
                                        {{ $selectedHotel->location->name ?? 'N/A' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    @if($selectedRoomType)
                        <!-- Tipo de Quarto -->
                        <div class="mb-4 pb-4 border-b border-gray-200 dark:border-gray-600">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-medium text-gray-900 dark:text-white">
                                    <i class="fas fa-bed text-indigo-500 mr-1"></i>
                                    {{ $selectedRoomType->name }}
                                </span>
                            </div>
                            <div class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                                <div class="flex justify-between">
                                    <span>Capacidade:</span>
                                    <span>{{ $selectedRoomType->capacity }} pessoas</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Preço/noite:</span>
                                    <span class="font-medium">{{ number_format($selectedRoomType->base_price, 0, ',', '.') }} Kz</span>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Período -->
                    @if($check_in && $check_out)
                        <div class="mb-4 pb-4 border-b border-gray-200 dark:border-gray-600">
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">
                                        <i class="fas fa-calendar-alt text-green-500 mr-1"></i>
                                        Check-in:
                                    </span>
                                    <span class="font-medium text-gray-900 dark:text-white">
                                        {{ \Carbon\Carbon::parse($check_in)->format('d/m/Y') }}
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">
                                        <i class="fas fa-calendar-alt text-red-500 mr-1"></i>
                                        Check-out:
                                    </span>
                                    <span class="font-medium text-gray-900 dark:text-white">
                                        {{ \Carbon\Carbon::parse($check_out)->format('d/m/Y') }}
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Noites:</span>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $nights }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Hóspedes:</span>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $guests }}</span>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Total -->
                    @if($total_price > 0)
                        <div class="mb-4">
                            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 p-4 rounded-lg">
                                <div class="flex justify-between items-center">
                                    <span class="text-lg font-semibold text-gray-900 dark:text-white">Total:</span>
                                    <span class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                                        {{ number_format($total_price, 0, ',', '.') }} Kz
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Método de Pagamento -->
                    @if($payment_method)
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            <div class="flex justify-between">
                                <span>Pagamento:</span>
                                <span class="font-medium text-gray-900 dark:text-white">
                                    @switch($payment_method)
                                        @case('cash')
                                            <i class="fas fa-money-bill-wave text-green-500 mr-1"></i>
                                            Dinheiro
                                            @break
                                        @case('card')
                                            <i class="fas fa-credit-card text-blue-500 mr-1"></i>
                                            Cartão
                                            @break
                                        @case('transfer')
                                            <i class="fas fa-exchange-alt text-purple-500 mr-1"></i>
                                            Transferência
                                            @break
                                        @case('mobile_money')
                                            <i class="fas fa-mobile-alt text-orange-500 mr-1"></i>
                                            Mobile Money
                                            @break
                                    @endswitch
                                </span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

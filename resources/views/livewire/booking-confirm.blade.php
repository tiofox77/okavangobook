<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-yellow-100 dark:bg-yellow-900/20 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-exclamation-triangle text-yellow-600 dark:text-yellow-400 text-2xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                Confirmar Reserva
            </h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2">
                Por favor, confirme os detalhes da sua reserva
            </p>
        </div>

        <!-- Detalhes da Reserva -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                <i class="fas fa-calendar-check text-blue-600 mr-2"></i>
                Detalhes da Reserva
            </h2>
            
            <div class="space-y-4">
                <!-- Código de Confirmação -->
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 dark:text-gray-400">Código:</span>
                    <span class="font-mono font-medium text-blue-600">{{ $booking->confirmation_code }}</span>
                </div>
                
                <!-- Hotel -->
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 dark:text-gray-400">Hotel:</span>
                    <span class="font-medium text-gray-900 dark:text-white">{{ $booking->hotel->name }}</span>
                </div>
                
                <!-- Tipo de Quarto -->
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 dark:text-gray-400">Tipo de Quarto:</span>
                    <span class="font-medium text-gray-900 dark:text-white">{{ $booking->roomType->name }}</span>
                </div>
                
                @if($booking->room)
                    <!-- Número do Quarto -->
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 dark:text-gray-400">Quarto:</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ $booking->room->room_number }}</span>
                    </div>
                @endif
                
                <!-- Datas -->
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 dark:text-gray-400">Check-in:</span>
                    <span class="font-medium text-gray-900 dark:text-white">{{ $booking->check_in?->format('d/m/Y') }}</span>
                </div>
                
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 dark:text-gray-400">Check-out:</span>
                    <span class="font-medium text-gray-900 dark:text-white">{{ $booking->check_out?->format('d/m/Y') }}</span>
                </div>
                
                <!-- Hóspedes e Noites -->
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 dark:text-gray-400">Hóspedes:</span>
                    <span class="font-medium text-gray-900 dark:text-white">{{ $booking->guests }}</span>
                </div>
                
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 dark:text-gray-400">Noites:</span>
                    <span class="font-medium text-gray-900 dark:text-white">{{ $booking->nights }}</span>
                </div>
                
                <!-- Preço Total -->
                <hr class="border-gray-200 dark:border-gray-600">
                <div class="flex justify-between items-center">
                    <span class="text-lg font-semibold text-gray-900 dark:text-white">Total:</span>
                    <span class="text-lg font-bold text-green-600 dark:text-green-400">
                        {{ number_format($booking->total_price, 0, ',', '.') }} Kz
                    </span>
                </div>
            </div>
        </div>

        <!-- Hóspede Principal -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                <i class="fas fa-user text-indigo-600 mr-2"></i>
                Hóspede Principal
            </h2>
            
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 dark:text-gray-400">Nome:</span>
                    <span class="font-medium text-gray-900 dark:text-white">{{ $booking->user->name }}</span>
                </div>
                
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 dark:text-gray-400">Email:</span>
                    <span class="font-medium text-gray-900 dark:text-white">{{ $booking->user->email }}</span>
                </div>
            </div>
        </div>

        <!-- Pedidos Especiais -->
        @if($booking->special_requests)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                    <i class="fas fa-comment-alt text-yellow-600 mr-2"></i>
                    Pedidos Especiais
                </h2>
                
                <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded-lg p-4">
                    <p class="text-gray-800 dark:text-gray-200">{{ $booking->special_requests }}</p>
                </div>
            </div>
        @endif

        <!-- Aviso Importante -->
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg p-4 mb-6">
            <div class="flex items-start">
                <i class="fas fa-info-circle text-blue-600 dark:text-blue-400 mr-3 mt-0.5"></i>
                <div>
                    <h3 class="text-sm font-medium text-blue-800 dark:text-blue-300 mb-1">
                        Informações Importantes
                    </h3>
                    <div class="text-sm text-blue-700 dark:text-blue-200 space-y-1">
                        <p>• Ao confirmar, sua reserva será processada imediatamente</p>
                        <p>• Guarde o código de confirmação para apresentar no check-in</p>
                        <p>• Verifique se todos os dados estão corretos antes de confirmar</p>
                        @if(isset($booking->is_refundable) && $booking->is_refundable)
                            <p>• Esta reserva é <strong>reembolsável</strong> conforme políticas do hotel</p>
                        @else
                            <p>• Esta reserva é <strong>não reembolsável</strong></p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Ações -->
        <div class="text-center space-y-4">
            <!-- Botão de Teste (temporário para debug) -->
            <div class="mb-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <p class="text-sm text-yellow-800 mb-2">Teste de Debug:</p>
                <button 
                    type="button"
                    wire:click="testLivewire"
                    class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors"
                >
                    Testar Livewire
                </button>
            </div>
            
            <button 
                type="button"
                wire:click="confirmBooking"
                wire:loading.attr="disabled"
                class="w-full md:w-auto px-8 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white font-medium rounded-lg hover:from-green-600 hover:to-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-200 transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed"
            >
                <span wire:loading.remove wire:target="confirmBooking">
                    <i class="fas fa-check mr-2"></i>
                    Confirmar Reserva
                </span>
                <span wire:loading wire:target="confirmBooking">
                    <i class="fas fa-spinner fa-spin mr-2"></i>
                    A confirmar...
                </span>
            </button>
            
            @if(isset($booking->is_refundable) && $booking->is_refundable)
                <button 
                    type="button"
                    wire:click="cancelBooking"
                    wire:loading.attr="disabled"
                    class="w-full md:w-auto px-8 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white font-medium rounded-lg hover:from-red-600 hover:to-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all duration-200 transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <span wire:loading.remove wire:target="cancelBooking">
                        <i class="fas fa-times mr-2"></i>
                        Cancelar Reserva
                    </span>
                    <span wire:loading wire:target="cancelBooking">
                        <i class="fas fa-spinner fa-spin mr-2"></i>
                        A cancelar...
                    </span>
                </button>
            @endif
        </div>

        <!-- Link para Voltar -->
        <div class="text-center mt-6">
            <a href="{{ route('home') }}" 
               class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium">
                <i class="fas fa-arrow-left mr-1"></i>
                Voltar ao Início
            </a>
        </div>
    </div>
</div>

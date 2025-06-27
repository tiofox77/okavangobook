<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Sucesso Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8 text-center">
            
            <!-- Ícone de Sucesso -->
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 dark:bg-green-900/20 mb-6">
                <i class="fas fa-check-circle text-3xl text-green-600 dark:text-green-400"></i>
            </div>
            
            <!-- Título -->
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                Reserva Confirmada!
            </h1>
            <p class="text-gray-600 dark:text-gray-400 mb-8">
                Sua reserva foi criada com sucesso. Você receberá um email de confirmação em breve.
            </p>
            
            <!-- Informações da Reserva -->
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 mb-6 text-left">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 text-center">
                    <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                    Detalhes da Reserva
                </h2>
                
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Código de Confirmação:</span>
                        <span class="font-mono font-bold text-blue-600 dark:text-blue-400">{{ $booking->confirmation_code }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Hotel:</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ $booking->hotel->name }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Tipo de Quarto:</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ $booking->roomType->name }}</span>
                    </div>
                    
                    @if($booking->room)
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Quarto:</span>
                            <span class="font-medium text-gray-900 dark:text-white">{{ $booking->room->room_number }}</span>
                        </div>
                    @endif
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Check-in:</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ $booking->check_in?->format('d/m/Y') }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Check-out:</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ $booking->check_out?->format('d/m/Y') }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Hóspedes:</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ $booking->guests }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Noites:</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ $booking->nights }}</span>
                    </div>
                    
                    <hr class="border-gray-200 dark:border-gray-600">
                    
                    <div class="flex justify-between">
                        <span class="text-lg font-semibold text-gray-900 dark:text-white">Total:</span>
                        <span class="text-lg font-bold text-green-600 dark:text-green-400">
                            {{ number_format($booking->total_price, 0, ',', '.') }} Kz
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Status -->
            <div class="flex items-center justify-center space-x-4 mb-6">
                <div class="flex items-center">
                    <span class="w-3 h-3 bg-yellow-400 rounded-full mr-2"></span>
                    <span class="text-sm text-gray-600 dark:text-gray-400">
                        Status: <span class="font-medium text-yellow-600">{{ ucfirst($booking->status) }}</span>
                    </span>
                </div>
                <div class="flex items-center">
                    <span class="w-3 h-3 bg-orange-400 rounded-full mr-2"></span>
                    <span class="text-sm text-gray-600 dark:text-gray-400">
                        Pagamento: <span class="font-medium text-orange-600">{{ ucfirst($booking->payment_status) }}</span>
                    </span>
                </div>
            </div>
            
            <!-- Informações Importantes -->
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg p-4 mb-6 text-left">
                <h3 class="font-semibold text-blue-900 dark:text-blue-100 mb-2">
                    <i class="fas fa-lightbulb mr-2"></i>
                    Informações Importantes
                </h3>
                <ul class="text-sm text-blue-800 dark:text-blue-200 space-y-1">
                    <li>• Apresente o código de confirmação no check-in</li>
                    <li>• Check-in a partir das 14:00</li>
                    <li>• Check-out até às 12:00</li>
                    <li>• Em caso de dúvidas, contacte o hotel directamente</li>
                </ul>
            </div>
            
            <!-- Ações -->
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('home') }}" 
                   class="inline-flex items-center justify-center px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors duration-200">
                    <i class="fas fa-home mr-2"></i>
                    Voltar ao Início
                </a>
                
                @auth
                    <a href="{{ route('my.bookings') }}" 
                       class="inline-flex items-center justify-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                        <i class="fas fa-list mr-2"></i>
                        Minhas Reservas
                    </a>
                @endauth
            </div>
        </div>
    </div>
</div>

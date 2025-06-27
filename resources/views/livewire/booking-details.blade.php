<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumb -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('my.bookings') }}" class="text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                        <i class="fas fa-calendar-check mr-1"></i>
                        Minhas Reservas
                    </a>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mr-2"></i>
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Reserva {{ $booking->confirmation_code }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                        Detalhes da Reserva
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">
                        Código: <span class="font-mono font-medium text-blue-600">{{ $booking->confirmation_code }}</span>
                    </p>
                </div>
                
                <!-- Status Badge -->
                @php
                    $statusConfig = [
                        'pending' => ['bg' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400', 'icon' => 'fas fa-clock'],
                        'confirmed' => ['bg' => 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400', 'icon' => 'fas fa-check-circle'],
                        'cancelled' => ['bg' => 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400', 'icon' => 'fas fa-times-circle'],
                        'completed' => ['bg' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400', 'icon' => 'fas fa-check-double'],
                    ];
                    $config = $statusConfig[$booking->status] ?? $statusConfig['pending'];
                @endphp
                
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $config['bg'] }}">
                    <i class="{{ $config['icon'] }} mr-2"></i>
                    {{ ucfirst($booking->status) }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Detalhes Principais -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Informações do Hotel -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        <i class="fas fa-hotel text-blue-600 mr-2"></i>
                        Informações do Hotel
                    </h2>
                    
                    <div class="flex items-start space-x-4">
                        <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-hotel text-white text-2xl"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $booking->hotel->name }}</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-2">
                                <i class="fas fa-map-marker-alt text-red-500 mr-1"></i>
                                {{ $booking->hotel->location->name ?? 'N/A' }}
                            </p>
                            @if($booking->hotel->description)
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ Str::limit($booking->hotel->description, 150) }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Detalhes da Acomodação -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        <i class="fas fa-bed text-indigo-600 mr-2"></i>
                        Detalhes da Acomodação
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Tipo de Quarto:</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $booking->roomType->name }}</span>
                            </div>
                            
                            @if($booking->room)
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Número do Quarto:</span>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $booking->room->room_number }}</span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Andar:</span>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $booking->room->floor }}º</span>
                                </div>
                            @endif
                            
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Capacidade:</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $booking->roomType->capacity }} pessoas</span>
                            </div>
                        </div>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Hóspedes:</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $booking->guests }}</span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Noites:</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $booking->nights }}</span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Preço/Noite:</span>
                                <span class="font-medium text-gray-900 dark:text-white">
                                    {{ number_format($booking->roomType->base_price, 0, ',', '.') }} Kz
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Pedidos Especiais -->
                @if($booking->special_requests)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                            <i class="fas fa-comment-alt text-yellow-600 mr-2"></i>
                            Pedidos Especiais
                        </h2>
                        <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded-lg p-4">
                            <p class="text-gray-800 dark:text-gray-200">{{ $booking->special_requests }}</p>
                        </div>
                    </div>
                @endif
                
                <!-- Políticas e Informações -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                        Políticas e Informações
                    </h2>
                    
                    <div class="space-y-3 text-sm">
                        <div class="flex items-start">
                            <i class="fas fa-check text-green-500 mr-2 mt-0.5"></i>
                            <span class="text-gray-700 dark:text-gray-300">Check-in: A partir das 14:00</span>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-check text-green-500 mr-2 mt-0.5"></i>
                            <span class="text-gray-700 dark:text-gray-300">Check-out: Até às 12:00</span>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-check text-green-500 mr-2 mt-0.5"></i>
                            <span class="text-gray-700 dark:text-gray-300">
                                Cancelamento: 
                                @if($booking->is_refundable)
                                    <span class="text-green-600 font-medium">Reembolsável</span>
                                @else
                                    <span class="text-red-600 font-medium">Não reembolsável</span>
                                @endif
                            </span>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-info text-blue-500 mr-2 mt-0.5"></i>
                            <span class="text-gray-700 dark:text-gray-300">Apresente o código de confirmação no check-in</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                
                <!-- Resumo Financeiro -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        <i class="fas fa-file-invoice-dollar text-green-600 mr-2"></i>
                        Resumo Financeiro
                    </h3>
                    
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Subtotal:</span>
                            <span class="font-medium text-gray-900 dark:text-white">
                                {{ number_format($booking->total_price, 0, ',', '.') }} Kz
                            </span>
                        </div>
                        
                        <hr class="border-gray-200 dark:border-gray-600">
                        
                        <div class="flex justify-between">
                            <span class="text-lg font-semibold text-gray-900 dark:text-white">Total:</span>
                            <span class="text-lg font-bold text-green-600 dark:text-green-400">
                                {{ number_format($booking->total_price, 0, ',', '.') }} Kz
                            </span>
                        </div>
                    </div>
                    
                    <!-- Status de Pagamento -->
                    <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-600">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Status do Pagamento:</span>
                            @php
                                $paymentStatusConfig = [
                                    'pending' => ['bg' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400', 'icon' => 'fas fa-clock'],
                                    'paid' => ['bg' => 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400', 'icon' => 'fas fa-check-circle'],
                                    'failed' => ['bg' => 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400', 'icon' => 'fas fa-times-circle'],
                                ];
                                $paymentConfig = $paymentStatusConfig[$booking->payment_status] ?? $paymentStatusConfig['pending'];
                            @endphp
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $paymentConfig['bg'] }}">
                                <i class="{{ $paymentConfig['icon'] }} mr-1"></i>
                                {{ ucfirst($booking->payment_status) }}
                            </span>
                        </div>
                        
                        <div class="flex items-center justify-between mt-2">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Método:</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">
                                @switch($booking->payment_method)
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
                                    @default
                                        {{ ucfirst($booking->payment_method) }}
                                @endswitch
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Datas -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        <i class="fas fa-calendar text-blue-600 mr-2"></i>
                        Período da Estadia
                    </h3>
                    
                    <div class="space-y-4">
                        <div class="text-center">
                            <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Check-in</div>
                            <div class="text-lg font-semibold text-green-600">
                                {{ $booking->check_in?->format('d/m/Y') }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $booking->check_in?->format('l') }}
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-center">
                            <div class="w-12 h-px bg-gray-300 dark:bg-gray-600"></div>
                            <div class="mx-2 text-xs text-gray-500">{{ $booking->nights }} noites</div>
                            <div class="w-12 h-px bg-gray-300 dark:bg-gray-600"></div>
                        </div>
                        
                        <div class="text-center">
                            <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Check-out</div>
                            <div class="text-lg font-semibold text-red-600">
                                {{ $booking->check_out?->format('d/m/Y') }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $booking->check_out?->format('l') }}
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Ações -->
                @if($booking->status === 'pending' || $booking->status === 'confirmed')
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                            <i class="fas fa-cogs text-gray-600 mr-2"></i>
                            Ações
                        </h3>
                        
                        <div class="space-y-3">
                            <button class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors duration-200 flex items-center justify-center">
                                <i class="fas fa-times mr-2"></i>
                                Cancelar Reserva
                            </button>
                            
                            <button class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200 flex items-center justify-center">
                                <i class="fas fa-edit mr-2"></i>
                                Solicitar Alteração
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

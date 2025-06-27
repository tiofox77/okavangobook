<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                        <i class="fas fa-calendar-check text-blue-600 mr-2"></i>
                        Minhas Reservas
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">
                        Gerencie e acompanhe todas as suas reservas
                    </p>
                </div>
                
                <!-- Filtros -->
                <div class="flex items-center space-x-2">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Filtrar:</label>
                    <select wire:model.live="statusFilter" 
                            class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm">
                        <option value="all">Todas</option>
                        <option value="pending">Pendentes</option>
                        <option value="confirmed">Confirmadas</option>
                        <option value="cancelled">Canceladas</option>
                        <option value="completed">Concluídas</option>
                    </select>
                </div>
            </div>
        </div>
        
        <!-- Lista de Reservas -->
        @if($bookings->count() > 0)
            <div class="space-y-4">
                @foreach($bookings as $booking)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="p-6">
                            <div class="flex items-start justify-between">
                                
                                <!-- Informações da Reserva -->
                                <div class="flex-1">
                                    <div class="flex items-center space-x-4 mb-3">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                            {{ $booking->hotel->name }}
                                        </h3>
                                        
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
                                        
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $config['bg'] }}">
                                            <i class="{{ $config['icon'] }} mr-1"></i>
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                        
                                        <span class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $booking->confirmation_code }}
                                        </span>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                        
                                        <!-- Datas -->
                                        <div>
                                            <div class="flex items-center text-gray-600 dark:text-gray-400 mb-1">
                                                <i class="fas fa-calendar text-green-500 mr-2"></i>
                                                Check-in
                                            </div>
                                            <div class="font-medium text-gray-900 dark:text-white">
                                                {{ $booking->check_in?->format('d/m/Y') }}
                                            </div>
                                        </div>
                                        
                                        <div>
                                            <div class="flex items-center text-gray-600 dark:text-gray-400 mb-1">
                                                <i class="fas fa-calendar text-red-500 mr-2"></i>
                                                Check-out
                                            </div>
                                            <div class="font-medium text-gray-900 dark:text-white">
                                                {{ $booking->check_out?->format('d/m/Y') }}
                                            </div>
                                        </div>
                                        
                                        <!-- Detalhes -->
                                        <div>
                                            <div class="flex items-center text-gray-600 dark:text-gray-400 mb-1">
                                                <i class="fas fa-bed text-indigo-500 mr-2"></i>
                                                Acomodação
                                            </div>
                                            <div class="font-medium text-gray-900 dark:text-white">
                                                {{ $booking->roomType->name }}
                                                @if($booking->room)
                                                    <span class="text-xs text-gray-500">({{ $booking->room->room_number }})</span>
                                                @endif
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $booking->guests }} {{ $booking->guests === 1 ? 'hóspede' : 'hóspedes' }} • {{ $booking->nights }} {{ $booking->nights === 1 ? 'noite' : 'noites' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Preço e Ações -->
                                <div class="text-right ml-6">
                                    <div class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                                        {{ number_format($booking->total_price, 0, ',', '.') }} Kz
                                    </div>
                                    
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mb-3">
                                        Pagamento: 
                                        <span class="font-medium">
                                            @switch($booking->payment_status)
                                                @case('pending')
                                                    <span class="text-yellow-600">Pendente</span>
                                                    @break
                                                @case('paid')
                                                    <span class="text-green-600">Pago</span>
                                                    @break
                                                @case('failed')
                                                    <span class="text-red-600">Falhado</span>
                                                    @break
                                                @default
                                                    {{ ucfirst($booking->payment_status) }}
                                            @endswitch
                                        </span>
                                    </div>
                                    
                                    <!-- Ações -->
                                    <div class="flex flex-col space-y-2">
                                        <a href="{{ route('booking.details', $booking->id) }}" 
                                           class="inline-flex items-center justify-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded transition-colors duration-200">
                                            <i class="fas fa-eye mr-1"></i>
                                            Ver Detalhes
                                        </a>
                                        
                                        @if($booking->status === 'pending' || $booking->status === 'confirmed')
                                            <button class="inline-flex items-center justify-center px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded transition-colors duration-200">
                                                <i class="fas fa-times mr-1"></i>
                                                Cancelar
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Pedidos Especiais -->
                            @if($booking->special_requests)
                                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-600">
                                    <div class="flex items-start">
                                        <i class="fas fa-comment-alt text-yellow-500 mr-2 mt-0.5"></i>
                                        <div>
                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Pedidos Especiais:</span>
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $booking->special_requests }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Paginação -->
            <div class="mt-6">
                {{ $bookings->links() }}
            </div>
            
        @else
            <!-- Estado Vazio -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-12 text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-gray-100 dark:bg-gray-700 mb-4">
                    <i class="fas fa-calendar-times text-2xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
                    Nenhuma reserva encontrada
                </h3>
                <p class="text-gray-500 dark:text-gray-400 mb-6">
                    @if($statusFilter === 'all')
                        Você ainda não fez nenhuma reserva.
                    @else
                        Nenhuma reserva encontrada com o status selecionado.
                    @endif
                </p>
                <a href="{{ route('home') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                    <i class="fas fa-search mr-2"></i>
                    Procurar Hotéis
                </a>
            </div>
        @endif
    </div>
</div>

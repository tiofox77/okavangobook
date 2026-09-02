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
                
                {{-- Pesquisa por código ou alojamento --}}
                <div class="relative w-full sm:w-72">
                    <i class="fas fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm" aria-hidden="true"></i>
                    <input wire:model.live.debounce.300ms="search" type="text"
                           placeholder="{{ __('Código ou alojamento…') }}"
                           aria-label="{{ __('Procurar reservas') }}"
                           class="w-full pl-9">
                    <div wire:loading wire:target="search" class="absolute right-3 top-1/2 -translate-y-1/2">
                        <i class="fas fa-circle-notch fa-spin text-gray-400 text-sm" aria-hidden="true"></i>
                    </div>
                </div>
            </div>

            {{-- Período --}}
            <div class="flex flex-wrap items-center gap-2 mt-5">
                @foreach ([
                    'todas' => ['Todas', null],
                    'proximas' => ['Próximas', $contagemProximas],
                    'passadas' => ['Passadas', null],
                ] as $chave => $info)
                    <button type="button" wire:click="$set('periodo', '{{ $chave }}')"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-medium transition-colors {{ $periodo === $chave ? 'bg-primary text-white shadow-sm' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                        {{ __($info[0]) }}
                        @if($info[1])
                            <span class="px-1.5 py-0.5 rounded-full text-xs {{ $periodo === $chave ? 'bg-white/25' : 'bg-gray-100 dark:bg-gray-700' }}">{{ $info[1] }}</span>
                        @endif
                    </button>
                @endforeach

                <span class="hidden sm:block h-6 w-px bg-gray-200 dark:bg-gray-700 mx-1"></span>

                {{-- Estado --}}
                @foreach ([
                    'all' => 'Todos os estados',
                    'pending' => 'Pendentes',
                    'confirmed' => 'Confirmadas',
                    'completed' => 'Concluídas',
                    'cancelled' => 'Canceladas',
                ] as $chave => $rotulo)
                    @if($chave === 'all' || ($contagens[$chave] ?? 0) > 0)
                        <button type="button" wire:click="$set('statusFilter', '{{ $chave }}')"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm transition-colors {{ $statusFilter === $chave ? 'bg-gray-900 dark:bg-gray-100 text-white dark:text-gray-900 font-medium' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            {{ __($rotulo) }}
                            <span class="text-xs opacity-70">{{ $contagens[$chave] ?? 0 }}</span>
                        </button>
                    @endif
                @endforeach

                @if($statusFilter !== 'all' || $periodo !== 'todas' || $search !== '')
                    <button type="button" wire:click="limparFiltros"
                            class="ml-auto text-sm text-gray-500 hover:text-primary inline-flex items-center gap-1.5">
                        <i class="fas fa-times text-xs" aria-hidden="true"></i>{{ __('Limpar') }}
                    </button>
                @endif
            </div>

            @if($bookings->total() > 0)
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-4">
                    {{ trans_choice(':count reserva|:count reservas', $bookings->total(), ['count' => $bookings->total()]) }}
                </p>
            @endif
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

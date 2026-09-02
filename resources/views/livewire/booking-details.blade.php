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
                    {{ ['pending' => 'Pendente', 'confirmed' => 'Confirmada', 'cancelled' => 'Cancelada', 'completed' => 'Concluída', 'checked_in' => 'Em estadia', 'checked_out' => 'Terminada', 'no_show' => 'Não compareceu'][$booking->status] ?? ucfirst($booking->status) }}
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
                                    {{ number_format($this->noites > 0 ? $booking->total_price / $this->noites : ($booking->roomType->base_price ?? 0), 0, ',', '.') }} Kz
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
                                {{ ['pending' => 'Pendente', 'paid' => 'Pago', 'refunded' => 'Reembolsado', 'failed' => 'Falhou'][$booking->payment_status] ?? ucfirst($booking->payment_status) }}
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
                            <div class="mx-2 text-xs text-gray-500">{{ trans_choice(':count noite|:count noites', $this->noites, ['count' => $this->noites]) }}</div>
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
                
                {{-- Ações da reserva --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 print:hidden">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        <i class="fas fa-bolt text-primary mr-2" aria-hidden="true"></i>{{ __('Ações') }}
                    </h3>

                    <div class="space-y-2.5">
                        @if($this->podeCancelar)
                            <button type="button" wire:click="abrirAlteracao"
                                    class="w-full px-4 py-2.5 bg-primary hover:bg-blue-800 text-white font-medium rounded-lg transition-colors flex items-center justify-center gap-2">
                                <i class="fas fa-pen-to-square text-sm" aria-hidden="true"></i>{{ __('Solicitar alteração') }}
                            </button>
                        @endif

                        <button type="button" onclick="window.print()"
                                class="w-full px-4 py-2.5 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-medium rounded-lg transition-colors flex items-center justify-center gap-2">
                            <i class="fas fa-print text-sm" aria-hidden="true"></i>{{ __('Imprimir / PDF') }}
                        </button>

                        @if($this->whatsapp)
                            <a href="{{ $this->whatsapp }}" target="_blank" rel="noopener noreferrer"
                               class="w-full px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg transition-colors flex items-center justify-center gap-2">
                                <i class="fab fa-whatsapp" aria-hidden="true"></i>{{ __('Falar com o alojamento') }}
                            </a>
                        @endif

                        @if($booking->hotel)
                            <a href="{{ route('hotel.details', $booking->hotel->slug) }}"
                               class="w-full px-4 py-2.5 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-medium rounded-lg transition-colors flex items-center justify-center gap-2">
                                <i class="fas fa-hotel text-sm" aria-hidden="true"></i>{{ __('Ver alojamento') }}
                            </a>
                        @endif

                        @if($this->podeCancelar)
                            <button type="button" wire:click="abrirCancelamento"
                                    class="w-full px-4 py-2.5 border border-red-200 dark:border-red-800 text-red-600 dark:text-red-400 font-medium rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors flex items-center justify-center gap-2">
                                <i class="fas fa-circle-xmark text-sm" aria-hidden="true"></i>{{ __('Cancelar reserva') }}
                            </button>
                        @endif
                    </div>

                    @if($booking->status === 'cancelled')
                        <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700 text-sm text-gray-500 dark:text-gray-400">
                            <p><i class="fas fa-circle-info mr-1.5" aria-hidden="true"></i>{{ __('Cancelada em') }} {{ $booking->cancelled_at?->format('d/m/Y') ?? '—' }}</p>
                            @if($booking->cancellation_reason)
                                <p class="mt-2 border-l-4 border-gray-200 dark:border-gray-600 pl-3">{{ $booking->cancellation_reason }}</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Modal: pedido de alteração --}}
        @if($mostrarAlteracao)
            <div class="fixed inset-0 z-50 bg-gray-900/60 flex items-center justify-center p-4 print:hidden" wire:key="alterar-reserva">
                <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-lg">
                    <div class="p-6">
                        <div class="flex items-start gap-4 mb-5">
                            <span class="h-12 w-12 flex-shrink-0 rounded-full bg-primary/10 text-primary flex items-center justify-center">
                                <i class="fas fa-pen-to-square text-xl" aria-hidden="true"></i>
                            </span>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ __('Solicitar alteração') }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    {{ __('O pedido segue para o alojamento, que confirma a disponibilidade. As datas só mudam depois dessa confirmação.') }}
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="nova-entrada" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('Nova entrada') }}</label>
                                <input id="nova-entrada" type="date" wire:model="novaEntrada" min="{{ now()->toDateString() }}" class="w-full">
                                @error('novaEntrada') <span class="block mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="nova-saida" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('Nova saída') }}</label>
                                <input id="nova-saida" type="date" wire:model="novaSaida" min="{{ now()->addDay()->toDateString() }}" class="w-full">
                                @error('novaSaida') <span class="block mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror
                            </div>
                            <div class="sm:col-span-2">
                                <label for="novos-hospedes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('Hóspedes') }}</label>
                                <input id="novos-hospedes" type="number" min="1" max="30" wire:model="novosHospedes" class="w-full">
                                @error('novosHospedes') <span class="block mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror
                            </div>
                            <div class="sm:col-span-2">
                                <label for="nota-alteracao" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('Nota para o alojamento (opcional)') }}</label>
                                <textarea id="nota-alteracao" rows="3" wire:model="notaAlteracao"
                                          placeholder="{{ __('Ex.: chegada mais tarde, quarto no piso térreo…') }}" class="w-full"></textarea>
                                @error('notaAlteracao') <span class="block mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 px-6 py-4 bg-gray-50 dark:bg-gray-900/40 rounded-b-2xl">
                        <button type="button" wire:click="fecharAlteracao"
                                class="px-4 py-2.5 rounded-lg bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 font-medium">
                            {{ __('Cancelar') }}
                        </button>
                        <button type="button" wire:click="enviarPedidoAlteracao" wire:loading.attr="disabled" wire:target="enviarPedidoAlteracao"
                                class="px-4 py-2.5 rounded-lg bg-primary hover:bg-blue-800 disabled:bg-blue-400 text-white font-semibold">
                            <span wire:loading.remove wire:target="enviarPedidoAlteracao">{{ __('Enviar pedido') }}</span>
                            <span wire:loading wire:target="enviarPedidoAlteracao">
                                <i class="fas fa-circle-notch fa-spin mr-1" aria-hidden="true"></i>{{ __('A enviar…') }}
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        @endif
        {{-- Modal de cancelamento --}}
        @if($mostrarCancelamento)
            <div class="fixed inset-0 z-50 bg-gray-900/60 flex items-center justify-center p-4 print:hidden" wire:key="cancelar-reserva">
                <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-lg">
                    <div class="p-6">
                        <div class="flex items-start gap-4 mb-5">
                            <span class="h-12 w-12 flex-shrink-0 rounded-full bg-red-100 dark:bg-red-900/40 text-red-600 dark:text-red-400 flex items-center justify-center">
                                <i class="fas fa-triangle-exclamation text-xl" aria-hidden="true"></i>
                            </span>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ __('Cancelar esta reserva?') }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    {{ $booking->hotel?->name }} ·
                                    {{ $booking->check_in?->format('d/m/Y') }} — {{ $booking->check_out?->format('d/m/Y') }}
                                </p>
                                @if($booking->is_refundable)
                                    <p class="text-sm text-emerald-600 dark:text-emerald-400 mt-2">
                                        <i class="fas fa-circle-check mr-1" aria-hidden="true"></i>{{ __('Esta reserva é reembolsável.') }}
                                    </p>
                                @else
                                    <p class="text-sm text-amber-600 dark:text-amber-400 mt-2">
                                        <i class="fas fa-circle-exclamation mr-1" aria-hidden="true"></i>{{ __('Esta reserva não é reembolsável.') }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        <label for="motivo-cancelamento" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                            {{ __('Motivo do cancelamento') }} *
                        </label>
                        <textarea id="motivo-cancelamento" wire:model="motivoCancelamento" rows="3"
                                  placeholder="{{ __('Ex.: mudança de planos, datas erradas…') }}"
                                  class="w-full"></textarea>
                        @error('motivoCancelamento')
                            <span class="block mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 px-6 py-4 bg-gray-50 dark:bg-gray-900/40 rounded-b-2xl">
                        <button type="button" wire:click="fecharCancelamento"
                                class="px-4 py-2.5 rounded-lg bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 font-medium">
                            {{ __('Manter reserva') }}
                        </button>
                        <button type="button" wire:click="confirmarCancelamento" wire:loading.attr="disabled" wire:target="confirmarCancelamento"
                                class="px-4 py-2.5 rounded-lg bg-red-600 hover:bg-red-700 disabled:bg-red-400 text-white font-semibold">
                            <span wire:loading.remove wire:target="confirmarCancelamento">{{ __('Sim, cancelar') }}</span>
                            <span wire:loading wire:target="confirmarCancelamento">
                                <i class="fas fa-circle-notch fa-spin mr-1" aria-hidden="true"></i>{{ __('A cancelar…') }}
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
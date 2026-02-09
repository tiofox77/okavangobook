<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Relatórios de Reservas</h1>
        <button wire:click="export" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg">
            <i class="fas fa-download mr-2"></i> Exportar CSV
        </button>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm mb-1">Data Início</label>
                <input wire:model.live="startDate" type="date" class="w-full px-3 py-2 border rounded-lg">
            </div>
            <div>
                <label class="block text-sm mb-1">Data Fim</label>
                <input wire:model.live="endDate" type="date" class="w-full px-3 py-2 border rounded-lg">
            </div>
            <div>
                <label class="block text-sm mb-1">Status Reserva</label>
                <select wire:model.live="status" class="w-full px-3 py-2 border rounded-lg">
                    <option value="all">Todos</option>
                    <option value="pending">Pendente</option>
                    <option value="confirmed">Confirmada</option>
                    <option value="cancelled">Cancelada</option>
                </select>
            </div>
            <div>
                <label class="block text-sm mb-1">Método Pagamento</label>
                <select wire:model.live="paymentMethod" class="w-full px-3 py-2 border rounded-lg">
                    <option value="all">Todos</option>
                    <option value="cash">Dinheiro</option>
                    <option value="transfer">Transferência</option>
                    <option value="tpa_onsite">TPA no Local</option>
                </select>
            </div>
            <div>
                <label class="block text-sm mb-1">Status Pagamento</label>
                <select wire:model.live="paymentStatus" class="w-full px-3 py-2 border rounded-lg">
                    <option value="all">Todos</option>
                    <option value="pending">Pendente</option>
                    <option value="paid">Pago</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Estatísticas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-md p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Total Reservas</p>
                    <p class="text-3xl font-bold">{{ $stats['total'] }}</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-lg">
                    <i class="fas fa-calendar-alt text-blue-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Confirmadas</p>
                    <p class="text-3xl font-bold text-green-600">{{ $stats['confirmed'] }}</p>
                    <p class="text-xs text-gray-500">Pendentes: {{ $stats['pending'] }}</p>
                </div>
                <div class="bg-green-100 p-3 rounded-lg">
                    <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Canceladas</p>
                    <p class="text-3xl font-bold text-red-600">{{ $stats['cancelled'] }}</p>
                </div>
                <div class="bg-red-100 p-3 rounded-lg">
                    <i class="fas fa-times-circle text-red-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Receita Total</p>
                    <p class="text-2xl font-bold text-green-600">{{ number_format($stats['total_revenue'], 0) }} Kz</p>
                    <p class="text-xs text-gray-500">Pendente: {{ number_format($stats['pending_revenue'], 0) }} Kz</p>
                </div>
                <div class="bg-yellow-100 p-3 rounded-lg">
                    <i class="fas fa-money-bill-wave text-yellow-600 text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Métodos de Pagamento -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-md p-4">
            <h3 class="font-bold mb-3">Métodos de Pagamento</h3>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="text-gray-600">💵 Dinheiro:</span>
                    <span class="font-bold">{{ $stats['by_method']['cash'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">🏦 Transferência:</span>
                    <span class="font-bold">{{ $stats['by_method']['transfer'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">💳 TPA no Local:</span>
                    <span class="font-bold">{{ $stats['by_method']['tpa_onsite'] }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-4">
            <h3 class="font-bold mb-3">Status de Pagamento</h3>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="text-gray-600">✅ Pagos:</span>
                    <span class="font-bold text-green-600">{{ $stats['paid'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">⏳ Pendentes:</span>
                    <span class="font-bold text-yellow-600">{{ $stats['payment_pending'] }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-4">
            <h3 class="font-bold mb-3">Taxa de Conversão</h3>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="text-gray-600">Confirmadas:</span>
                    <span class="font-bold">{{ $stats['total'] > 0 ? round(($stats['confirmed'] / $stats['total']) * 100) : 0 }}%</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Canceladas:</span>
                    <span class="font-bold text-red-600">{{ $stats['total'] > 0 ? round(($stats['cancelled'] / $stats['total']) * 100) : 0 }}%</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabela de Reservas -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hotel</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cliente</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Datas</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Método</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pagamento</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Valor</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($reservations as $reservation)
                    <tr>
                        <td class="px-4 py-3 text-sm">#{{ $reservation->id }}</td>
                        <td class="px-4 py-3 text-sm">{{ $reservation->hotel->name ?? '' }}</td>
                        <td class="px-4 py-3 text-sm">{{ $reservation->user->name ?? $reservation->guest_name }}</td>
                        <td class="px-4 py-3 text-sm">
                            {{ $reservation->check_in->format('d/m') }} - {{ $reservation->check_out->format('d/m') }}
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 text-xs rounded-full 
                                {{ $reservation->status === 'confirmed' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $reservation->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $reservation->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ ucfirst($reservation->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            @if($reservation->payment_method === 'cash')
                                💵 Dinheiro
                            @elseif($reservation->payment_method === 'transfer')
                                🏦 Transferência
                            @elseif($reservation->payment_method === 'tpa_onsite')
                                💳 TPA Local
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 text-xs rounded-full 
                                {{ $reservation->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $reservation->payment_status === 'paid' ? 'Pago' : 'Pendente' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm font-bold">{{ number_format($reservation->total_price, 0) }} Kz</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-6 text-center text-gray-500">Nenhuma reserva encontrada</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

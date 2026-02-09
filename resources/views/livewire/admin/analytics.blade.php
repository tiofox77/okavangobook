<div class="p-6">
    {{-- To attain knowledge, add things every day; To attain wisdom, subtract things every day. --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Analytics & Relatórios</h1>
        <select wire:model.live="period" class="px-4 py-2 border rounded-lg">
            <option value="7">Últimos 7 dias</option>
            <option value="30">Últimos 30 dias</option>
            <option value="90">Últimos 90 dias</option>
            <option value="365">Último ano</option>
        </select>
    </div>

    <!-- Cards de Estatísticas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Total Usuários</p>
                    <p class="text-2xl font-bold">{{ number_format($stats['total_users']) }}</p>
                    <p class="text-xs text-green-600">+{{ $stats['new_users'] }} novos</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-lg">
                    <i class="fas fa-users text-blue-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Reservas</p>
                    <p class="text-2xl font-bold">{{ number_format($stats['total_reservations']) }}</p>
                    <p class="text-xs text-yellow-600">{{ $stats['pending_reservations'] }} pendentes</p>
                </div>
                <div class="bg-purple-100 p-3 rounded-lg">
                    <i class="fas fa-calendar-check text-purple-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Receita Total</p>
                    <p class="text-2xl font-bold">{{ number_format($stats['total_revenue'], 0) }} Kz</p>
                    <p class="text-xs text-green-600">{{ number_format($stats['period_revenue'], 0) }} no período</p>
                </div>
                <div class="bg-green-100 p-3 rounded-lg">
                    <i class="fas fa-dollar-sign text-green-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Ticket Médio</p>
                    <p class="text-2xl font-bold">{{ number_format($stats['avg_reservation_value'], 0) }} Kz</p>
                    <p class="text-xs text-gray-600">por reserva</p>
                </div>
                <div class="bg-orange-100 p-3 rounded-lg">
                    <i class="fas fa-chart-line text-orange-600 text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Estatísticas Adicionais -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="font-bold mb-2">Newsletter</h3>
            <p class="text-3xl font-bold text-blue-600">{{ number_format($stats['newsletter_subscribers']) }}</p>
            <p class="text-sm text-gray-600">Assinantes ativos</p>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="font-bold mb-2">Cupons</h3>
            <p class="text-3xl font-bold text-green-600">{{ number_format($stats['active_coupons']) }}</p>
            <p class="text-sm text-gray-600">Cupons ativos</p>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="font-bold mb-2">Buscas</h3>
            <p class="text-3xl font-bold text-purple-600">{{ number_format($stats['total_searches']) }}</p>
            <p class="text-sm text-gray-600">{{ $stats['recent_searches'] }} recentes</p>
        </div>
    </div>

    <!-- Top Hotéis -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="font-bold text-lg mb-4">Top 5 Hotéis Mais Reservados</h3>
            @if($charts['top_hotels']->count() > 0)
                <div class="space-y-3">
                    @foreach($charts['top_hotels'] as $index => $hotel)
                        <div class="flex items-center">
                            <span class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold text-sm mr-3">
                                {{ $index + 1 }}
                            </span>
                            <div class="flex-1">
                                <p class="font-medium">{{ $hotel['name'] }}</p>
                                <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ ($hotel['count'] / $charts['top_hotels']->max('count')) * 100 }}%"></div>
                                </div>
                            </div>
                            <span class="ml-3 font-bold text-blue-600">{{ $hotel['count'] }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-4">Sem dados no período</p>
            @endif
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="font-bold text-lg mb-4">Buscas Mais Populares</h3>
            @if($charts['popular_searches']->count() > 0)
                <div class="space-y-3">
                    @foreach($charts['popular_searches'] as $search)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-map-marker-alt text-gray-400 mr-2"></i>
                                <span class="font-medium">{{ $search->location }}</span>
                            </div>
                            <span class="bg-purple-100 text-purple-600 px-3 py-1 rounded-full text-sm font-bold">
                                {{ $search->count }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-4">Sem dados no período</p>
            @endif
        </div>
    </div>
</div>

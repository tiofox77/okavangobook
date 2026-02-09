<div>
    <!-- Header do Dashboard -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Dashboard</h1>
                <p class="text-gray-500 mt-1">Visão geral do sistema - {{ now()->translatedFormat('d M Y, H:i') }}</p>
            </div>
            <div class="mt-4 md:mt-0 flex space-x-3">
                <a href="{{ route('admin.reservations') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition shadow-sm">
                    <i class="fas fa-calendar-plus mr-2"></i> Nova Reserva
                </a>
                <a href="{{ route('admin.hotels') }}" class="inline-flex items-center px-4 py-2 bg-white text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition shadow-sm border border-gray-300">
                    <i class="fas fa-hotel mr-2"></i> Propriedades
                </a>
            </div>
        </div>
    </div>

    <!-- Row 1: Main KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Receita do Mês -->
        <div class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-2xl shadow-lg p-6 text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-10 translate-x-10"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-dollar-sign text-xl"></i>
                    </div>
                    @if($statistics['crescimento_receita'] != 0)
                        <span class="text-sm font-medium px-2 py-1 rounded-full {{ $statistics['crescimento_receita'] >= 0 ? 'bg-green-400/30 text-green-100' : 'bg-red-400/30 text-red-100' }}">
                            <i class="fas fa-arrow-{{ $statistics['crescimento_receita'] >= 0 ? 'up' : 'down' }} mr-1"></i>{{ abs($statistics['crescimento_receita']) }}%
                        </span>
                    @endif
                </div>
                <p class="text-blue-100 text-sm font-medium">Receita do Mês</p>
                <p class="text-3xl font-bold mt-1">{{ number_format($statistics['receita_mes'], 0, ',', '.') }} <span class="text-lg font-normal text-blue-200">KZ</span></p>
                <p class="text-blue-200 text-xs mt-2">Total: {{ number_format($statistics['receita_total'], 0, ',', '.') }} KZ</p>
            </div>
        </div>

        <!-- Reservas -->
        <div class="rounded-2xl shadow-lg p-6 text-white relative overflow-hidden" style="background: linear-gradient(to bottom right, #10b981, #059669);">
            <div class="absolute top-0 right-0 w-32 h-32 rounded-full" style="background: rgba(255,255,255,0.1); transform: translate(40px, -40px);"></div>
            <div class="relative" style="z-index: 10;">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: rgba(255,255,255,0.2);">
                        <i class="fas fa-calendar-check text-xl"></i>
                    </div>
                    @if($statistics['crescimento_reservas'] != 0)
                        <span class="text-sm font-medium px-2 py-1 rounded-full" style="background: {{ $statistics['crescimento_reservas'] >= 0 ? 'rgba(74,222,128,0.3)' : 'rgba(248,113,113,0.3)' }};">
                            <i class="fas fa-arrow-{{ $statistics['crescimento_reservas'] >= 0 ? 'up' : 'down' }} mr-1"></i>{{ abs($statistics['crescimento_reservas']) }}%
                        </span>
                    @endif
                </div>
                <p class="text-sm font-medium" style="color: rgba(255,255,255,0.8);">Reservas do Mês</p>
                <p class="text-3xl font-bold mt-1">{{ $statistics['reservas_mes'] }}</p>
                <p class="text-xs mt-2" style="color: rgba(255,255,255,0.7);">Total: {{ $statistics['reservas_total'] }} reservas</p>
            </div>
        </div>

        <!-- Propriedades -->
        <div class="rounded-2xl shadow-lg p-6 text-white relative overflow-hidden" style="background: linear-gradient(to bottom right, #8b5cf6, #7c3aed);">
            <div class="absolute top-0 right-0 w-32 h-32 rounded-full" style="background: rgba(255,255,255,0.1); transform: translate(40px, -40px);"></div>
            <div class="relative" style="z-index: 10;">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: rgba(255,255,255,0.2);">
                        <i class="fas fa-hotel text-xl"></i>
                    </div>
                    <span class="text-sm font-medium px-2 py-1 rounded-full" style="background: rgba(255,255,255,0.2);">
                        {{ $statistics['hoteis_ativos'] }} activos
                    </span>
                </div>
                <p class="text-sm font-medium" style="color: rgba(255,255,255,0.8);">Propriedades</p>
                <p class="text-3xl font-bold mt-1">{{ $statistics['hoteis'] }}</p>
                <p class="text-xs mt-2" style="color: rgba(255,255,255,0.7);">{{ $statistics['quartos'] }} tipos de quarto</p>
            </div>
        </div>

        <!-- Utilizadores -->
        <div class="rounded-2xl shadow-lg p-6 text-white relative overflow-hidden" style="background: linear-gradient(to bottom right, #f59e0b, #f97316);">
            <div class="absolute top-0 right-0 w-32 h-32 rounded-full" style="background: rgba(255,255,255,0.1); transform: translate(40px, -40px);"></div>
            <div class="relative" style="z-index: 10;">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: rgba(255,255,255,0.2);">
                        <i class="fas fa-users text-xl"></i>
                    </div>
                    @if($statistics['novos_utilizadores'] > 0)
                        <span class="text-sm font-medium px-2 py-1 rounded-full" style="background: rgba(74,222,128,0.3);">
                            +{{ $statistics['novos_utilizadores'] }} novos
                        </span>
                    @endif
                </div>
                <p class="text-sm font-medium" style="color: rgba(255,255,255,0.8);">Utilizadores</p>
                <p class="text-3xl font-bold mt-1">{{ $statistics['utilizadores'] }}</p>
                <p class="text-xs mt-2" style="color: rgba(255,255,255,0.7);">{{ $statistics['localizacoes'] }} destinos | {{ $statistics['newsletter'] }} newsletter</p>
            </div>
        </div>
    </div>

    <!-- Row 2: Secondary Stats -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100 hover:shadow-md transition">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-yellow-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800">{{ $statistics['reservas_pendentes'] }}</p>
                    <p class="text-xs text-gray-500">Pendentes</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100 hover:shadow-md transition">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800">{{ $statistics['reservas_confirmadas'] }}</p>
                    <p class="text-xs text-gray-500">Confirmadas</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100 hover:shadow-md transition">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-times-circle text-red-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800">{{ $statistics['reservas_canceladas'] }}</p>
                    <p class="text-xs text-gray-500">Canceladas</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100 hover:shadow-md transition">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-star text-blue-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800">{{ $statistics['media_avaliacao'] }}</p>
                    <p class="text-xs text-gray-500">Avaliação</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100 hover:shadow-md transition">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-comment text-purple-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800">{{ $statistics['avaliacoes'] }}</p>
                    <p class="text-xs text-gray-500">Avaliações</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100 hover:shadow-md transition">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-map-marker-alt text-indigo-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800">{{ $statistics['localizacoes'] }}</p>
                    <p class="text-xs text-gray-500">Destinos</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Row 3: Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Reservations Chart -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Reservas & Receita</h3>
                    <p class="text-sm text-gray-500">Últimos 6 meses</p>
                </div>
                <div class="flex space-x-4 text-sm">
                    <span class="flex items-center"><span class="w-3 h-3 bg-blue-500 rounded-full mr-1.5"></span>Reservas</span>
                    <span class="flex items-center"><span class="w-3 h-3 bg-emerald-500 rounded-full mr-1.5"></span>Receita</span>
                </div>
            </div>
            <div style="height: 300px;">
                <canvas id="reservationsChart"></canvas>
            </div>
        </div>

        <!-- Status Distribution Donut -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <div class="mb-6">
                <h3 class="text-lg font-bold text-gray-800">Estado das Reservas</h3>
                <p class="text-sm text-gray-500">Distribuição actual</p>
            </div>
            <div style="height: 220px;" class="flex items-center justify-center">
                <canvas id="statusChart"></canvas>
            </div>
            <div class="mt-4 space-y-2">
                @php
                    $statusColors = ['pending' => 'yellow', 'confirmed' => 'green', 'checked_in' => 'blue', 'checked_out' => 'gray', 'cancelled' => 'red'];
                    $statusLabels = ['pending' => 'Pendente', 'confirmed' => 'Confirmada', 'checked_in' => 'Check-in', 'checked_out' => 'Check-out', 'cancelled' => 'Cancelada'];
                @endphp
                @foreach($statusDistribution as $status => $count)
                    @if($count > 0)
                    <div class="flex items-center justify-between text-sm">
                        <span class="flex items-center">
                            <span class="w-2.5 h-2.5 bg-{{ $statusColors[$status] ?? 'gray' }}-500 rounded-full mr-2"></span>
                            {{ $statusLabels[$status] ?? ucfirst($status) }}
                        </span>
                        <span class="font-semibold text-gray-700">{{ $count }}</span>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    <!-- Row 4: Hotels by Type + Top Hotels -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Hotels by Type -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Propriedades por Tipo</h3>
            <div style="height: 220px;" class="flex items-center justify-center">
                <canvas id="hotelTypesChart"></canvas>
            </div>
            <div class="mt-4 space-y-2">
                @foreach($hotelsByType as $type => $count)
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600 capitalize">{{ $type ?: 'Outros' }}</span>
                    <span class="font-semibold text-gray-800 bg-gray-100 px-2 py-0.5 rounded-full">{{ $count }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Top Hotels by Reservations -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Top Propriedades</h3>
            <div class="space-y-4">
                @forelse($topHotels as $index => $hotel)
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center font-bold text-sm
                        {{ $index === 0 ? 'bg-yellow-100 text-yellow-700' : ($index === 1 ? 'bg-gray-100 text-gray-600' : ($index === 2 ? 'bg-orange-100 text-orange-700' : 'bg-blue-50 text-blue-600')) }}">
                        {{ $index + 1 }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-800 truncate">{{ $hotel->name }}</p>
                        <p class="text-xs text-gray-500">{{ $hotel->reservations_count }} reservas</p>
                    </div>
                    <div class="flex items-center text-xs text-gray-500">
                        @if($hotel->rating)
                            <i class="fas fa-star text-yellow-400 mr-1"></i>{{ number_format($hotel->rating, 1) }}
                        @endif
                    </div>
                </div>
                @empty
                <p class="text-sm text-gray-400 text-center py-4">Sem dados ainda</p>
                @endforelse
            </div>
        </div>

        <!-- Top Locations -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Top Destinos</h3>
            <div class="space-y-4">
                @forelse($topLocations as $index => $location)
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center font-bold text-sm
                        {{ $index === 0 ? 'bg-emerald-100 text-emerald-700' : ($index === 1 ? 'bg-blue-100 text-blue-600' : 'bg-purple-50 text-purple-600') }}">
                        {{ $index + 1 }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-800 truncate">{{ $location->name }}</p>
                        <p class="text-xs text-gray-500">{{ $location->hotels_count }} propriedades</p>
                    </div>
                </div>
                @empty
                <p class="text-sm text-gray-400 text-center py-4">Sem dados ainda</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Row 5: Recent Reservations Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-8">
        <div class="p-6 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Reservas Recentes</h3>
                    <p class="text-sm text-gray-500">Últimas 8 reservas</p>
                </div>
                <a href="{{ route('admin.reservations') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                    Ver todas <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Hóspede</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Propriedade</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Check-in</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Check-out</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Valor</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Estado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($recentReservations as $reservation)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white text-xs font-bold">
                                    {{ strtoupper(substr($reservation->user->name ?? '?', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-800">{{ Str::limit($reservation->user->name ?? 'N/A', 20) }}</p>
                                    <p class="text-xs text-gray-400">{{ $reservation->confirmation_code ?? '' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ Str::limit($reservation->hotel->name ?? 'N/A', 25) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $reservation->check_in ? $reservation->check_in->format('d/m/Y') : '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $reservation->check_out ? $reservation->check_out->format('d/m/Y') : '-' }}</td>
                        <td class="px-6 py-4 text-sm font-semibold text-gray-800">{{ number_format($reservation->total_price, 0, ',', '.') }} KZ</td>
                        <td class="px-6 py-4">
                            @php
                                $badgeClasses = [
                                    'pending' => 'bg-yellow-100 text-yellow-700',
                                    'confirmed' => 'bg-green-100 text-green-700',
                                    'checked_in' => 'bg-blue-100 text-blue-700',
                                    'checked_out' => 'bg-gray-100 text-gray-700',
                                    'cancelled' => 'bg-red-100 text-red-700',
                                    'no_show' => 'bg-orange-100 text-orange-700',
                                ];
                                $badgeTexts = [
                                    'pending' => 'Pendente',
                                    'confirmed' => 'Confirmada',
                                    'checked_in' => 'Check-in',
                                    'checked_out' => 'Check-out',
                                    'cancelled' => 'Cancelada',
                                    'no_show' => 'No Show',
                                ];
                            @endphp
                            <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium {{ $badgeClasses[$reservation->status] ?? 'bg-gray-100 text-gray-600' }}">
                                {{ $badgeTexts[$reservation->status] ?? ucfirst($reservation->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-400">
                            <i class="fas fa-calendar-times text-3xl mb-2"></i>
                            <p>Nenhuma reserva encontrada</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Row 6: Recent Users + Recent Reviews -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Recent Users -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-800">Utilizadores Recentes</h3>
                <a href="{{ route('admin.users') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">Ver todos</a>
            </div>
            <div class="space-y-3">
                @forelse($recentUsers as $user)
                <div class="flex items-center justify-between p-3 rounded-xl hover:bg-gray-50 transition">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-violet-400 to-purple-600 flex items-center justify-center text-white font-bold text-sm">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-800">{{ $user->name }}</p>
                            <p class="text-xs text-gray-400">{{ $user->email }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-400">{{ $user->created_at->diffForHumans() }}</p>
                        @if($user->hasRole('Admin'))
                            <span class="text-xs bg-red-100 text-red-600 px-2 py-0.5 rounded-full font-medium">Admin</span>
                        @endif
                    </div>
                </div>
                @empty
                <p class="text-sm text-gray-400 text-center py-4">Nenhum utilizador</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Reviews -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-800">Últimas Avaliações</h3>
                <span class="text-sm text-gray-500">{{ $statistics['avaliacoes'] }} total</span>
            </div>
            <div class="space-y-4">
                @forelse($recentReviews as $review)
                <div class="p-3 rounded-xl hover:bg-gray-50 transition border border-gray-50">
                    <div class="flex items-center justify-between mb-1">
                        <p class="text-sm font-medium text-gray-800">{{ Str::limit($review->user->name ?? 'Anónimo', 20) }}</p>
                        <div class="flex items-center">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star text-xs {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-200' }}"></i>
                            @endfor
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mb-1">{{ Str::limit($review->hotel->name ?? '', 30) }}</p>
                    @if($review->comment)
                        <p class="text-xs text-gray-600 line-clamp-2">{{ Str::limit($review->comment, 80) }}</p>
                    @endif
                    <p class="text-xs text-gray-400 mt-1">{{ $review->created_at->diffForHumans() }}</p>
                </div>
                @empty
                <p class="text-sm text-gray-400 text-center py-4">Nenhuma avaliação ainda</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Charts Script -->
    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Reservations & Revenue Chart
        const ctx1 = document.getElementById('reservationsChart');
        if (ctx1) {
            new Chart(ctx1, {
                type: 'bar',
                data: {
                    labels: @json($monthLabels),
                    datasets: [
                        {
                            label: 'Reservas',
                            data: @json($reservationsByMonth),
                            backgroundColor: 'rgba(59, 130, 246, 0.8)',
                            borderRadius: 8,
                            borderSkipped: false,
                            yAxisID: 'y',
                            barPercentage: 0.6,
                        },
                        {
                            label: 'Receita (KZ)',
                            data: @json($revenueByMonth),
                            type: 'line',
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            borderWidth: 3,
                            pointRadius: 5,
                            pointBackgroundColor: '#10b981',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            fill: true,
                            tension: 0.4,
                            yAxisID: 'y1',
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1f2937',
                            titleColor: '#f9fafb',
                            bodyColor: '#d1d5db',
                            borderColor: '#374151',
                            borderWidth: 1,
                            cornerRadius: 8,
                            padding: 12,
                        }
                    },
                    scales: {
                        x: { grid: { display: false }, ticks: { font: { size: 11 } } },
                        y: {
                            position: 'left',
                            grid: { color: '#f3f4f6' },
                            ticks: { font: { size: 11 }, stepSize: 1 },
                            title: { display: true, text: 'Reservas', font: { size: 12 } }
                        },
                        y1: {
                            position: 'right',
                            grid: { drawOnChartArea: false },
                            ticks: {
                                font: { size: 11 },
                                callback: function(value) { return (value/1000).toFixed(0) + 'k KZ'; }
                            },
                            title: { display: true, text: 'Receita', font: { size: 12 } }
                        }
                    }
                }
            });
        }

        // Status Distribution Donut
        const ctx2 = document.getElementById('statusChart');
        if (ctx2) {
            const statusData = @json(array_values($statusDistribution));
            const hasData = statusData.some(v => v > 0);
            new Chart(ctx2, {
                type: 'doughnut',
                data: {
                    labels: ['Pendente', 'Confirmada', 'Check-in', 'Check-out', 'Cancelada'],
                    datasets: [{
                        data: hasData ? statusData : [1],
                        backgroundColor: hasData
                            ? ['#eab308', '#22c55e', '#3b82f6', '#6b7280', '#ef4444']
                            : ['#e5e7eb'],
                        borderWidth: 0,
                        spacing: 2,
                        borderRadius: 4,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1f2937',
                            cornerRadius: 8,
                            padding: 10,
                        }
                    }
                }
            });
        }

        // Hotel Types Chart
        const ctx3 = document.getElementById('hotelTypesChart');
        if (ctx3) {
            const typeData = @json($hotelsByType);
            const typeLabels = Object.keys(typeData).map(k => k ? k.charAt(0).toUpperCase() + k.slice(1) : 'Outros');
            const typeValues = Object.values(typeData);
            const typeColors = ['#8b5cf6', '#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#ec4899', '#6366f1'];
            const hasTypeData = typeValues.some(v => v > 0);

            new Chart(ctx3, {
                type: 'doughnut',
                data: {
                    labels: hasTypeData ? typeLabels : ['Sem dados'],
                    datasets: [{
                        data: hasTypeData ? typeValues : [1],
                        backgroundColor: hasTypeData ? typeColors.slice(0, typeLabels.length) : ['#e5e7eb'],
                        borderWidth: 0,
                        spacing: 2,
                        borderRadius: 4,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '65%',
                    plugins: {
                        legend: { display: false },
                        tooltip: { backgroundColor: '#1f2937', cornerRadius: 8, padding: 10 }
                    }
                }
            });
        }
    });
    </script>
    @endpush
</div>

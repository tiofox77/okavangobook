<div class="p-4 sm:p-6" x-data>

    {{-- Cabeçalho + filtros --}}
    <div class="flex flex-col gap-4 mb-6 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                <i class="fas fa-chart-line text-indigo-500 mr-2"></i>Visitas &amp; Tráfego
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Rastreio e controlo do site em tempo quase-real</p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <select wire:model.live="range" class="px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 dark:text-white">
                <option value="7">Últimos 7 dias</option>
                <option value="30">Últimos 30 dias</option>
                <option value="90">Últimos 90 dias</option>
                <option value="365">Último ano</option>
                <option value="custom">Personalizado…</option>
            </select>

            <template x-if="$wire.range === 'custom'">
                <div class="flex items-center gap-2">
                    <input type="date" wire:model.live="from" class="px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 dark:text-white">
                    <span class="text-gray-400">→</span>
                    <input type="date" wire:model.live="to" class="px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 dark:text-white">
                </div>
            </template>

            <div class="inline-flex rounded-lg border border-gray-300 dark:border-gray-600 overflow-hidden">
                @foreach(['day' => 'Dia', 'week' => 'Semana', 'month' => 'Mês'] as $val => $lbl)
                    <button wire:click="$set('groupBy', '{{ $val }}')"
                            class="px-3 py-2 text-sm {{ $groupBy === $val ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600' }}">
                        {{ $lbl }}
                    </button>
                @endforeach
            </div>

            <label class="inline-flex items-center gap-2 px-3 py-2 text-sm bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer text-gray-600 dark:text-gray-300">
                <input type="checkbox" wire:model.live="includeBots" class="rounded text-indigo-600">
                Incluir bots
            </label>
        </div>
    </div>

    {{-- Indicador de carregamento --}}
    <div wire:loading.flex class="fixed top-4 right-4 z-50 items-center gap-2 bg-indigo-600 text-white px-4 py-2 rounded-lg shadow-lg text-sm">
        <i class="fas fa-circle-notch fa-spin"></i> A actualizar…
    </div>

    {{-- KPIs principais --}}
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 mb-6">
        @php
            $kpiCards = [
                ['Visualizações', number_format($kpis['pageviews']), 'fa-eye', 'indigo', 'no período'],
                ['Visitantes únicos', number_format($kpis['visitors']), 'fa-users', 'blue', number_format($kpis['pages_per_visit'], 1) . ' pág/visita'],
                ['Hoje', number_format($kpis['today']), 'fa-calendar-day', 'green', 'visualizações'],
                ['Esta semana', number_format($kpis['week']), 'fa-calendar-week', 'teal', 'visualizações'],
                ['Este mês', number_format($kpis['month']), 'fa-calendar-alt', 'cyan', 'visualizações'],
                ['Média/dia', number_format($kpis['avg_per_day'], 1), 'fa-chart-bar', 'purple', 'no período'],
            ];
        @endphp
        @foreach($kpiCards as [$label, $value, $icon, $color, $sub])
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ $label }}</span>
                    <i class="fas {{ $icon }} text-{{ $color }}-500"></i>
                </div>
                <p class="text-2xl font-bold text-gray-800 dark:text-white leading-tight">{{ $value }}</p>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">{{ $sub }}</p>
            </div>
        @endforeach
    </div>

    {{-- Métricas secundárias --}}
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 mb-6">
        @php
            $mini = [
                ['Inscrições newsletter', number_format($kpis['newsletter']), 'fa-envelope-open-text', 'green'],
                ['Total assinantes', number_format($kpis['newsletter_total']), 'fa-envelope', 'emerald'],
                ['Novos utilizadores', number_format($kpis['new_users']), 'fa-user-plus', 'blue'],
                ['Cliques em hotéis', number_format($kpis['hotel_clicks']), 'fa-hotel', 'amber'],
                ['Pesquisas', number_format($kpis['searches']), 'fa-search', 'purple'],
                ['Reservas', number_format($kpis['reservations']), 'fa-calendar-check', 'rose'],
            ];
        @endphp
        @foreach($mini as [$label, $value, $icon, $color])
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border border-gray-100 dark:border-gray-700 flex items-center gap-3">
                <div class="bg-{{ $color }}-100 dark:bg-{{ $color }}-900/30 p-2.5 rounded-lg">
                    <i class="fas {{ $icon }} text-{{ $color }}-600 dark:text-{{ $color }}-400"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-lg font-bold text-gray-800 dark:text-white leading-tight">{{ $value }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $label }}</p>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Gráfico de visitas + dispositivos --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 border border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-gray-800 dark:text-white">Evolução das visitas</h3>
                <span class="text-xs text-gray-400">
                    {{ $groupBy === 'day' ? 'por dia' : ($groupBy === 'week' ? 'por semana' : 'por mês') }}
                </span>
            </div>
            <div wire:ignore class="relative" style="height: 300px;">
                <canvas id="va-visits-chart"></canvas>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 border border-gray-100 dark:border-gray-700">
            <h3 class="font-bold text-gray-800 dark:text-white mb-4">Dispositivos</h3>
            @if(count($devices) > 0)
                <div wire:ignore class="relative mx-auto" style="height: 200px;">
                    <canvas id="va-devices-chart"></canvas>
                </div>
                <div class="mt-4 space-y-2">
                    @php $devTotal = array_sum(array_column($devices, 'total')); @endphp
                    @foreach($devices as $d)
                        @php
                            $devIcons = ['desktop' => 'fa-desktop', 'mobile' => 'fa-mobile-alt', 'tablet' => 'fa-tablet-alt', 'bot' => 'fa-robot'];
                            $devLabels = ['desktop' => 'Computador', 'mobile' => 'Telemóvel', 'tablet' => 'Tablet', 'bot' => 'Bots'];
                        @endphp
                        <div class="flex items-center justify-between text-sm">
                            <span class="flex items-center gap-2 text-gray-600 dark:text-gray-300">
                                <i class="fas {{ $devIcons[$d['label']] ?? 'fa-question' }} text-gray-400 w-4"></i>
                                {{ $devLabels[$d['label']] ?? ucfirst($d['label']) }}
                            </span>
                            <span class="font-semibold text-gray-800 dark:text-white">
                                {{ number_format($d['total']) }}
                                <span class="text-gray-400 text-xs">({{ $devTotal ? round($d['total'] / $devTotal * 100) : 0 }}%)</span>
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-400 text-center py-12 text-sm">Sem dados no período</p>
            @endif
        </div>
    </div>

    {{-- Browsers + Sistemas + Localização --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        {{-- Localização --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 border border-gray-100 dark:border-gray-700">
            <h3 class="font-bold text-gray-800 dark:text-white mb-4"><i class="fas fa-globe-africa text-indigo-500 mr-2"></i>Localização</h3>
            @forelse($countries as $c)
                @php $cMax = max(1, $countries[0]['total']); @endphp
                <div class="mb-3">
                    <div class="flex items-center justify-between text-sm mb-1">
                        <span class="flex items-center gap-2 text-gray-700 dark:text-gray-200">
                            <span class="text-base">{{ $c['code'] ? \App\Support\Flag::emoji($c['code']) : '🌍' }}</span>
                            {{ $c['label'] }}
                        </span>
                        <span class="font-semibold text-gray-800 dark:text-white">{{ number_format($c['total']) }}</span>
                    </div>
                    <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-1.5">
                        <div class="bg-indigo-500 h-1.5 rounded-full" style="width: {{ round($c['total'] / $cMax * 100) }}%"></div>
                    </div>
                </div>
            @empty
                <p class="text-gray-400 text-center py-8 text-sm">Sem dados de localização.<br><span class="text-xs">Ative a geolocalização ou aguarde tráfego real.</span></p>
            @endforelse

            @if(count($cities) > 0)
                <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                    <p class="text-xs font-medium text-gray-400 uppercase mb-2">Cidades</p>
                    <div class="flex flex-wrap gap-1.5">
                        @foreach($cities as $city)
                            <span class="text-xs bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 px-2 py-1 rounded-full">
                                {{ $city['label'] }} <span class="text-gray-400">{{ $city['total'] }}</span>
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- Browsers --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 border border-gray-100 dark:border-gray-700">
            <h3 class="font-bold text-gray-800 dark:text-white mb-4"><i class="fas fa-window-maximize text-blue-500 mr-2"></i>Navegadores</h3>
            @forelse($browsers as $b)
                @php $bMax = max(1, $browsers[0]['total']); @endphp
                <div class="mb-3">
                    <div class="flex items-center justify-between text-sm mb-1">
                        <span class="text-gray-700 dark:text-gray-200">{{ $b['label'] }}</span>
                        <span class="font-semibold text-gray-800 dark:text-white">{{ number_format($b['total']) }}</span>
                    </div>
                    <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-1.5">
                        <div class="bg-blue-500 h-1.5 rounded-full" style="width: {{ round($b['total'] / $bMax * 100) }}%"></div>
                    </div>
                </div>
            @empty
                <p class="text-gray-400 text-center py-8 text-sm">Sem dados no período</p>
            @endforelse
        </div>

        {{-- Sistemas --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 border border-gray-100 dark:border-gray-700">
            <h3 class="font-bold text-gray-800 dark:text-white mb-4"><i class="fas fa-microchip text-purple-500 mr-2"></i>Sistemas</h3>
            @forelse($platforms as $p)
                @php $pMax = max(1, $platforms[0]['total']); @endphp
                <div class="mb-3">
                    <div class="flex items-center justify-between text-sm mb-1">
                        <span class="text-gray-700 dark:text-gray-200">{{ $p['label'] }}</span>
                        <span class="font-semibold text-gray-800 dark:text-white">{{ number_format($p['total']) }}</span>
                    </div>
                    <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-1.5">
                        <div class="bg-purple-500 h-1.5 rounded-full" style="width: {{ round($p['total'] / $pMax * 100) }}%"></div>
                    </div>
                </div>
            @empty
                <p class="text-gray-400 text-center py-8 text-sm">Sem dados no período</p>
            @endforelse
        </div>
    </div>

    {{-- Páginas + Referrers --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 border border-gray-100 dark:border-gray-700">
            <h3 class="font-bold text-gray-800 dark:text-white mb-4"><i class="fas fa-file-alt text-teal-500 mr-2"></i>Páginas mais visitadas</h3>
            @forelse($topPages as $page)
                <div class="flex items-center justify-between py-2 border-b border-gray-50 dark:border-gray-700/50 last:border-0">
                    <span class="text-sm text-gray-700 dark:text-gray-200 truncate mr-3" title="{{ $page['path'] }}">{{ $page['path'] }}</span>
                    <span class="flex items-center gap-3 flex-shrink-0 text-xs">
                        <span class="text-gray-500 dark:text-gray-400"><i class="fas fa-eye mr-1"></i>{{ number_format($page['views']) }}</span>
                        <span class="text-gray-400"><i class="fas fa-user mr-1"></i>{{ number_format($page['visitors']) }}</span>
                    </span>
                </div>
            @empty
                <p class="text-gray-400 text-center py-8 text-sm">Sem dados no período</p>
            @endforelse
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 border border-gray-100 dark:border-gray-700">
            <h3 class="font-bold text-gray-800 dark:text-white mb-4"><i class="fas fa-external-link-alt text-orange-500 mr-2"></i>Origens de tráfego</h3>
            @forelse($referrers as $ref)
                @php $rMax = max(1, $referrers[0]['total']); @endphp
                <div class="mb-3">
                    <div class="flex items-center justify-between text-sm mb-1">
                        <span class="text-gray-700 dark:text-gray-200 truncate mr-3">{{ $ref['label'] }}</span>
                        <span class="font-semibold text-gray-800 dark:text-white flex-shrink-0">{{ number_format($ref['total']) }}</span>
                    </div>
                    <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-1.5">
                        <div class="bg-orange-500 h-1.5 rounded-full" style="width: {{ round($ref['total'] / $rMax * 100) }}%"></div>
                    </div>
                </div>
            @empty
                <p class="text-gray-400 text-center py-8 text-sm">Tráfego maioritariamente direto (sem referências externas).</p>
            @endforelse
        </div>
    </div>

    {{-- Visitas recentes --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 border border-gray-100 dark:border-gray-700">
        <h3 class="font-bold text-gray-800 dark:text-white mb-4"><i class="fas fa-stream text-rose-500 mr-2"></i>Visitas recentes</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs text-gray-400 uppercase border-b border-gray-100 dark:border-gray-700">
                        <th class="pb-2 pr-4">Página</th>
                        <th class="pb-2 pr-4">Dispositivo</th>
                        <th class="pb-2 pr-4">Navegador</th>
                        <th class="pb-2 pr-4">Localização</th>
                        <th class="pb-2 text-right">Quando</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recent as $v)
                        <tr class="border-b border-gray-50 dark:border-gray-700/40">
                            <td class="py-2 pr-4 text-gray-700 dark:text-gray-200 truncate max-w-[200px]" title="{{ $v['path'] }}">
                                {{ $v['path'] }}
                                @if($v['is_bot'])<span class="ml-1 text-xs bg-gray-100 dark:bg-gray-700 text-gray-500 px-1.5 rounded">bot</span>@endif
                            </td>
                            <td class="py-2 pr-4 text-gray-500 dark:text-gray-400 capitalize">{{ $v['device'] }}</td>
                            <td class="py-2 pr-4 text-gray-500 dark:text-gray-400">{{ $v['browser'] }}</td>
                            <td class="py-2 pr-4 text-gray-500 dark:text-gray-400">
                                {{ $v['city'] ? $v['city'] . ', ' : '' }}{{ $v['country'] ?? '—' }}
                            </td>
                            <td class="py-2 text-right text-gray-400 text-xs whitespace-nowrap">{{ $v['when'] }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-gray-400 text-center py-8">Ainda não há visitas registadas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Charts (Chart.js v3, carregado localmente no layout) --}}
    @script
    <script>
        const grid = 'rgba(148,163,184,0.15)';
        const tick = '#94a3b8';
        let visitsChart = null;
        let devicesChart = null;

        const deviceColors = {
            desktop: '#6366f1', mobile: '#10b981', tablet: '#f59e0b', bot: '#94a3b8', outro: '#a855f7',
        };

        function drawVisits(data) {
            const el = document.getElementById('va-visits-chart');
            if (!el || typeof Chart === 'undefined') return;
            if (visitsChart) visitsChart.destroy();
            visitsChart = new Chart(el, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [
                        {
                            label: 'Visualizações', data: data.views,
                            borderColor: '#6366f1', backgroundColor: 'rgba(99,102,241,0.12)',
                            fill: true, tension: 0.35, borderWidth: 2, pointRadius: 2, pointHoverRadius: 5,
                        },
                        {
                            label: 'Visitantes únicos', data: data.visitors,
                            borderColor: '#10b981', backgroundColor: 'rgba(16,185,129,0.08)',
                            fill: true, tension: 0.35, borderWidth: 2, pointRadius: 2, pointHoverRadius: 5,
                        },
                    ],
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: { legend: { labels: { color: tick, usePointStyle: true, boxWidth: 8 } } },
                    scales: {
                        x: { grid: { color: grid }, ticks: { color: tick, maxRotation: 0, autoSkip: true, maxTicksLimit: 12 } },
                        y: { grid: { color: grid }, ticks: { color: tick, precision: 0 }, beginAtZero: true },
                    },
                },
            });
        }

        function drawDevices(rows) {
            const el = document.getElementById('va-devices-chart');
            if (!el || typeof Chart === 'undefined' || !rows || !rows.length) return;
            if (devicesChart) devicesChart.destroy();
            devicesChart = new Chart(el, {
                type: 'doughnut',
                data: {
                    labels: rows.map(r => r.label),
                    datasets: [{
                        data: rows.map(r => r.total),
                        backgroundColor: rows.map(r => deviceColors[r.label] || '#cbd5e1'),
                        borderWidth: 0,
                    }],
                },
                options: {
                    responsive: true, maintainAspectRatio: false, cutout: '65%',
                    plugins: { legend: { display: false } },
                },
            });
        }

        // Redesenha a cada actualização do componente (mudança de filtro).
        $wire.on('va:charts', (payload) => {
            const data = Array.isArray(payload) ? payload[0] : payload;
            drawVisits(data.series);
            drawDevices(data.devices);
        });

        // Primeiro desenho no carregamento inicial.
        drawVisits(@js($series));
        drawDevices(@js($devices));
    </script>
    @endscript
</div>

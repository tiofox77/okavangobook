<?php

namespace App\Livewire\Admin;

use App\Models\NewsletterSubscriber;
use App\Models\PageVisit;
use App\Models\Reservation;
use App\Models\SearchHistory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Livewire\Component;

/**
 * Dashboard "Visitas & Tráfego" — rastreio e controlo do site.
 * Visitas diárias/semanais/mensais, intervalos personalizados, dispositivos,
 * localização, páginas mais vistas, origens de tráfego, inscrições e cliques.
 */
class VisitorAnalytics extends Component
{
    public string $range = '30';        // 7, 30, 90, 365 ou 'custom'
    public ?string $from = null;        // data inicial (modo custom)
    public ?string $to = null;          // data final (modo custom)
    public string $groupBy = 'day';     // day, week, month
    public bool $includeBots = false;   // incluir tráfego de bots/crawlers

    public function mount(): void
    {
        $this->to = $this->to ?: Carbon::now()->toDateString();
        $this->from = $this->from ?: Carbon::now()->subDays(29)->toDateString();
    }

    public function updatedRange(string $value): void
    {
        if ($value !== 'custom') {
            $this->from = Carbon::now()->subDays((int) $value - 1)->toDateString();
            $this->to = Carbon::now()->toDateString();
            // Ajusta automaticamente o agrupamento à dimensão do intervalo
            $this->groupBy = match (true) {
                (int) $value <= 31 => 'day',
                (int) $value <= 120 => 'week',
                default => 'month',
            };
        }
    }

    /** Intervalo [início, fim] com base no filtro atual. */
    private function period(): array
    {
        if ($this->range === 'custom') {
            $start = Carbon::parse($this->from)->startOfDay();
            $end = Carbon::parse($this->to)->endOfDay();
        } else {
            $start = Carbon::now()->subDays((int) $this->range - 1)->startOfDay();
            $end = Carbon::now()->endOfDay();
        }

        if ($end->lessThan($start)) {
            [$start, $end] = [$end->copy()->startOfDay(), $start->copy()->endOfDay()];
        }

        return [$start, $end];
    }

    /** Query base já filtrada por período e por humano/bot. */
    private function baseQuery(Carbon $start, Carbon $end)
    {
        $q = PageVisit::query()->betweenDates($start, $end);

        if (! $this->includeBots) {
            $q->humans();
        }

        return $q;
    }

    public function render()
    {
        // Se a migração ainda não correu, mostra um estado de "configuração"
        // em vez de rebentar com erro 500.
        if (! Schema::hasTable('page_visits')) {
            $blank = ['labels' => [], 'views' => [], 'visitors' => []];
            $this->dispatch('va:charts', ['series' => $blank, 'devices' => []]);

            return view('livewire.admin.visitor-analytics', [
                'tableReady' => false,
                'kpis' => array_fill_keys([
                    'pageviews', 'visitors', 'avg_per_day', 'pages_per_visit', 'today', 'week', 'month',
                    'registered', 'new_users', 'newsletter', 'newsletter_total', 'searches', 'reservations',
                    'hotel_clicks', 'bots',
                ], 0),
                'series' => $blank,
                'devices' => [], 'browsers' => [], 'platforms' => [], 'countries' => [],
                'cities' => [], 'topPages' => [], 'referrers' => [], 'recent' => [],
            ])->layout('layouts.admin');
        }

        [$start, $end] = $this->period();

        $series = $this->timeSeries($start, $end);
        $devices = $this->breakdown($start, $end, 'device_type');

        // Reenvia os dados dos gráficos para o JS redesenhar após cada filtro.
        $this->dispatch('va:charts', ['series' => $series, 'devices' => $devices]);

        return view('livewire.admin.visitor-analytics', [
            'tableReady' => true,
            'kpis' => $this->kpis($start, $end),
            'series' => $series,
            'devices' => $devices,
            'browsers' => $this->breakdown($start, $end, 'browser', 6),
            'platforms' => $this->breakdown($start, $end, 'platform', 6),
            'countries' => $this->locations($start, $end),
            'cities' => $this->breakdown($start, $end, 'city', 8, true),
            'topPages' => $this->topPages($start, $end),
            'referrers' => $this->breakdown($start, $end, 'referrer_host', 8, true),
            'recent' => $this->recentVisits(),
        ])->layout('layouts.admin');
    }

    private function kpis(Carbon $start, Carbon $end): array
    {
        $base = fn () => $this->baseQuery($start, $end);

        $pageviews = (clone $base())->count();
        $visitors = (clone $base())->distinct('session_id')->count('session_id');
        $days = max(1, $start->diffInDays($end) + 1);

        $today = Carbon::today();
        $weekStart = Carbon::now()->startOfWeek();
        $monthStart = Carbon::now()->startOfMonth();

        $humans = fn () => $this->includeBots ? PageVisit::query() : PageVisit::query()->humans();

        return [
            'pageviews' => $pageviews,
            'visitors' => $visitors,
            'avg_per_day' => round($pageviews / $days, 1),
            'pages_per_visit' => $visitors > 0 ? round($pageviews / $visitors, 1) : 0,
            'today' => (clone $humans())->whereDate('created_at', $today)->count(),
            'week' => (clone $humans())->where('created_at', '>=', $weekStart)->count(),
            'month' => (clone $humans())->where('created_at', '>=', $monthStart)->count(),
            'registered' => (clone $base())->whereNotNull('user_id')->distinct('user_id')->count('user_id'),
            'new_users' => User::whereBetween('created_at', [$start, $end])->count(),
            'newsletter' => NewsletterSubscriber::whereBetween('created_at', [$start, $end])->count(),
            'newsletter_total' => NewsletterSubscriber::where('is_active', true)->count(),
            'searches' => SearchHistory::whereBetween('created_at', [$start, $end])->count(),
            'reservations' => Reservation::whereBetween('created_at', [$start, $end])->count(),
            'hotel_clicks' => (clone $base())->where('path', 'like', 'hotel/%')->count(),
            'bots' => PageVisit::betweenDates($start, $end)->where('is_bot', true)->count(),
        ];
    }

    /** Série temporal de visitas + visitantes únicos, agrupada por dia/semana/mês. */
    private function timeSeries(Carbon $start, Carbon $end): array
    {
        $driver = DB::connection()->getDriverName();

        // Expressão do "bucket" por grupo (MySQL/MariaDB e SQLite para testes).
        $bucket = match ($this->groupBy) {
            'week' => $driver === 'sqlite'
                ? "strftime('%Y-%W', created_at)"
                : "DATE_FORMAT(created_at, '%x-%v')",
            'month' => $driver === 'sqlite'
                ? "strftime('%Y-%m', created_at)"
                : "DATE_FORMAT(created_at, '%Y-%m')",
            default => $driver === 'sqlite'
                ? "strftime('%Y-%m-%d', created_at)"
                : "DATE_FORMAT(created_at, '%Y-%m-%d')",
        };

        $rows = $this->baseQuery($start, $end)
            ->selectRaw("$bucket as bucket")
            ->selectRaw('COUNT(*) as views')
            ->selectRaw('COUNT(DISTINCT session_id) as visitors')
            ->groupBy('bucket')
            ->orderBy('bucket')
            ->get()
            ->keyBy('bucket');

        // Preenche buckets sem visitas para o gráfico não ter "buracos".
        $labels = [];
        $views = [];
        $visitors = [];

        // Alinha o cursor ao início do bucket (dia/semana/mês) para que
        // TODOS os buckets do intervalo sejam visitados exatamente uma vez.
        $cursor = match ($this->groupBy) {
            'week' => $start->copy()->startOfWeek(),
            'month' => $start->copy()->startOfMonth(),
            default => $start->copy()->startOfDay(),
        };
        $step = match ($this->groupBy) {
            'week' => 'addWeek',
            'month' => 'addMonth',
            default => 'addDay',
        };

        while ($cursor->lessThanOrEqualTo($end)) {
            $key = match ($this->groupBy) {
                'week' => $cursor->format('o-W'),
                'month' => $cursor->format('Y-m'),
                default => $cursor->format('Y-m-d'),
            };

            $label = match ($this->groupBy) {
                'week' => 'Sem ' . $cursor->format('W/o'),
                'month' => $cursor->translatedFormat('M Y'),
                default => $cursor->format('d/m'),
            };

            $labels[] = $label;
            $views[] = (int) ($rows[$key]->views ?? 0);
            $visitors[] = (int) ($rows[$key]->visitors ?? 0);

            $cursor->{$step}();
        }

        return ['labels' => $labels, 'views' => $views, 'visitors' => $visitors];
    }

    /** Distribuição por uma coluna (device/browser/platform/city/referrer). */
    private function breakdown(Carbon $start, Carbon $end, string $column, int $limit = 10, bool $skipNull = false): array
    {
        $q = $this->baseQuery($start, $end)
            ->selectRaw("$column as label")
            ->selectRaw('COUNT(*) as total')
            ->groupBy($column)
            ->orderByDesc('total')
            ->limit($limit);

        if ($skipNull) {
            $q->whereNotNull($column)->where($column, '!=', '');
        }

        return $q->get()->map(fn ($r) => [
            'label' => $r->label ?: 'Desconhecido',
            'total' => (int) $r->total,
        ])->toArray();
    }

    /** Top países com código para bandeira. */
    private function locations(Carbon $start, Carbon $end): array
    {
        return $this->baseQuery($start, $end)
            ->selectRaw('country, country_code')
            ->selectRaw('COUNT(*) as total')
            ->whereNotNull('country')
            ->groupBy('country', 'country_code')
            ->orderByDesc('total')
            ->limit(10)
            ->get()
            ->map(fn ($r) => [
                'label' => $r->country,
                'code' => $r->country_code,
                'total' => (int) $r->total,
            ])->toArray();
    }

    /** Páginas mais visitadas no período. */
    private function topPages(Carbon $start, Carbon $end): array
    {
        return $this->baseQuery($start, $end)
            ->selectRaw('path')
            ->selectRaw('COUNT(*) as views')
            ->selectRaw('COUNT(DISTINCT session_id) as visitors')
            ->groupBy('path')
            ->orderByDesc('views')
            ->limit(12)
            ->get()
            ->map(fn ($r) => [
                'path' => '/' . ltrim($r->path, '/'),
                'views' => (int) $r->views,
                'visitors' => (int) $r->visitors,
            ])->toArray();
    }

    /** Últimas visitas (fluxo em tempo quase-real). */
    private function recentVisits(): array
    {
        $q = PageVisit::query()->latest();
        if (! $this->includeBots) {
            $q->humans();
        }

        return $q->limit(15)->get()->map(fn ($v) => [
            'path' => '/' . ltrim($v->path, '/'),
            'device' => $v->device_type,
            'browser' => $v->browser,
            'country' => $v->country,
            'city' => $v->city,
            'is_bot' => $v->is_bot,
            'when' => $v->created_at?->diffForHumans(),
        ])->toArray();
    }
}

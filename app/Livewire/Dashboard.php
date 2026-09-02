<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Reservation;
use Illuminate\Support\Facades\Schema;
use Livewire\Component;

/**
 * Painel do cliente (/conta).
 *
 * Antes mostrava apenas as 5 reservas mais recentes por data de criação —
 * uma reserva cancelada do ano passado aparecia acima de uma viagem da
 * semana seguinte. Agora destaca a PRÓXIMA VIAGEM, resume a atividade da
 * conta e usa dados que já existiam mas nunca eram mostrados (favoritos,
 * alertas de preço, avaliações).
 */
class Dashboard extends Component
{
    public function mount()
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }
    }

    public function render()
    {
        $user = auth()->user();
        $hoje = now()->startOfDay();

        $base = fn () => $user->reservations()->with(['hotel.location', 'roomType']);

        // Próxima viagem: a estadia futura mais próxima que não foi cancelada
        $proximaViagem = $base()
            ->whereNotIn('status', ['cancelled'])
            ->whereDate('check_in', '>=', $hoje)
            ->orderBy('check_in')
            ->first();

        // Estadia a decorrer (check-in já passou, check-out ainda não)
        $viagemAtual = $base()
            ->whereNotIn('status', ['cancelled'])
            ->whereDate('check_in', '<=', $hoje)
            ->whereDate('check_out', '>=', $hoje)
            ->first();

        // Reservas para a lista: futuras primeiro, depois as mais recentes
        $reservas = $base()
            ->orderByRaw('CASE WHEN check_in >= ? THEN 0 ELSE 1 END', [$hoje])
            ->orderByRaw('CASE WHEN check_in >= ? THEN check_in END ASC', [$hoje])
            ->orderByDesc('check_in')
            ->take(5)
            ->get();

        // Resumo da conta
        $todas = $user->reservations();
        $confirmadas = (clone $todas)->whereIn('status', ['confirmed', 'completed']);

        $estatisticas = [
            'reservas' => (clone $todas)->count(),
            'proximas' => (clone $todas)->whereNotIn('status', ['cancelled'])->whereDate('check_in', '>=', $hoje)->count(),
            // 'nights' e um atributo calculado no modelo, nao uma coluna:
            // somar em SQL exige DATEDIFF sobre as datas.
            'noites' => (int) (clone $confirmadas)
                ->selectRaw('COALESCE(SUM(DATEDIFF(check_out, check_in)), 0) as total')
                ->value('total'),
            'gasto' => (float) (clone $confirmadas)->sum('total_price'),
        ];

        return view('livewire.dashboard', [
            'user' => $user,
            'bookings' => $reservas,
            'proximaViagem' => $viagemAtual ?: $proximaViagem,
            'emCurso' => (bool) $viagemAtual,
            'estatisticas' => $estatisticas,
            'favoritos' => $this->contar(fn () => $user->favorites()->count()),
            'alertas' => $this->contarAlertas($user->id),
            'avaliacoes' => $this->contar(fn () => $user->reviews()->count()),
            'perfilCompleto' => $this->percentagemPerfil($user),
        ])->layout('layouts.app');
    }

    /** Contagens acessórias nunca podem derrubar o painel. */
    private function contar(callable $consulta): int
    {
        try {
            return (int) $consulta();
        } catch (\Throwable $e) {
            return 0;
        }
    }

    private function contarAlertas(int $userId): int
    {
        return $this->contar(function () use ($userId) {
            if (! Schema::hasTable('price_alerts')) {
                return 0;
            }

            $q = \App\Models\PriceAlert::where('user_id', $userId);

            return Schema::hasColumn('price_alerts', 'is_active')
                ? $q->where('is_active', true)->count()
                : $q->count();
        });
    }

    /** Percentagem de preenchimento do perfil, para incentivar a completá-lo. */
    private function percentagemPerfil($user): int
    {
        $campos = ['name', 'email', 'phone', 'address', 'city', 'country', 'profile_photo'];
        $existentes = array_values(array_filter(
            $campos,
            fn ($c) => Schema::hasColumn('users', $c)
        ));

        if ($existentes === []) {
            return 100;
        }

        $preenchidos = count(array_filter(
            $existentes,
            fn ($c) => trim((string) ($user->{$c} ?? '')) !== ''
        ));

        return (int) round($preenchidos / count($existentes) * 100);
    }
}

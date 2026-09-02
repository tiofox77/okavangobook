<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Reservation;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Lista de reservas do cliente (/minhas-reservas).
 *
 * Antes ordenava por data de criação e não mostrava contagens: uma reserva
 * cancelada há meses aparecia antes da viagem da semana seguinte, e mudar
 * de filtro mantinha o número da página anterior (podendo cair numa lista
 * vazia). Agora filtra por período e por estado, com contagens.
 */
class MyBookings extends Component
{
    use WithPagination;

    public string $statusFilter = 'all';
    public string $periodo = 'todas';   // todas | proximas | passadas
    public string $search = '';

    public function updatedStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatedPeriodo(): void
    {
        $this->resetPage();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function limparFiltros(): void
    {
        $this->reset('statusFilter', 'periodo', 'search');
        $this->statusFilter = 'all';
        $this->periodo = 'todas';
        $this->resetPage();
    }

    private function base()
    {
        return Reservation::where('user_id', Auth::id());
    }

    public function render(): View
    {
        $hoje = now()->startOfDay();

        $bookings = $this->base()
            ->with(['hotel.location', 'roomType', 'room'])
            ->when($this->statusFilter !== 'all', fn ($q) => $q->where('status', $this->statusFilter))
            ->when($this->periodo === 'proximas', fn ($q) => $q->whereDate('check_in', '>=', $hoje))
            ->when($this->periodo === 'passadas', fn ($q) => $q->whereDate('check_out', '<', $hoje))
            ->when($this->search !== '', function ($q) {
                $termo = '%' . $this->search . '%';
                $q->where(fn ($w) => $w->where('confirmation_code', 'like', $termo)
                    ->orWhereHas('hotel', fn ($h) => $h->where('name', 'like', $termo)));
            })
            // Futuras primeiro, por data de chegada; depois as mais recentes.
            ->orderByRaw('CASE WHEN check_in >= ? THEN 0 ELSE 1 END', [$hoje])
            ->orderByRaw('CASE WHEN check_in >= ? THEN check_in END ASC', [$hoje])
            ->orderByDesc('check_in')
            ->paginate(10);

        // Contagens para os separadores (uma query agregada, não uma por estado)
        $porEstado = $this->base()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        return view('livewire.my-bookings', [
            'bookings' => $bookings,
            'contagens' => [
                'all' => array_sum($porEstado),
                'pending' => $porEstado['pending'] ?? 0,
                'confirmed' => $porEstado['confirmed'] ?? 0,
                'completed' => $porEstado['completed'] ?? 0,
                'cancelled' => $porEstado['cancelled'] ?? 0,
            ],
            'contagemProximas' => $this->base()
                ->whereNotIn('status', ['cancelled'])
                ->whereDate('check_in', '>=', $hoje)
                ->count(),
        ])->layout('layouts.app');
    }
}

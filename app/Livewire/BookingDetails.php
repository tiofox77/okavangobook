<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Reservation;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

/**
 * Detalhe de uma reserva do cliente (/reserva/{id}).
 *
 * O modelo já tinha cancel() — que liberta o quarto e regista o motivo —
 * mas a página nunca o expunha: o cliente não tinha forma de cancelar
 * sozinho. Passa a ter, com as salvaguardas devidas.
 */
class BookingDetails extends Component
{
    public Reservation $booking;

    // Cancelamento
    public bool $mostrarCancelamento = false;
    public string $motivoCancelamento = '';

    public function mount(Reservation|int $booking): void
    {
        if ($booking instanceof Reservation) {
            abort_unless($booking->user_id === Auth::id(), 403, 'Reserva não pertence a este utilizador');
            $this->booking = $booking->load(['hotel.location', 'roomType', 'room', 'user']);
        } else {
            $this->booking = Reservation::with(['hotel.location', 'roomType', 'room', 'user'])
                ->where('user_id', Auth::id())
                ->findOrFail($booking);
        }
    }

    /**
     * O cliente pode cancelar? Só reservas pendentes ou confirmadas cuja
     * estadia ainda não começou.
     */
    public function getPodeCancelarProperty(): bool
    {
        if (! in_array($this->booking->status, ['pending', 'confirmed'], true)) {
            return false;
        }

        return $this->booking->check_in === null
            || $this->booking->check_in->startOfDay()->gte(now()->startOfDay());
    }

    /** Noites da estadia (atributo calculado no modelo). */
    public function getNoitesProperty(): int
    {
        return (int) ($this->booking->nights ?? 0);
    }

    public function abrirCancelamento(): void
    {
        if (! $this->podeCancelar) {
            $this->dispatch('show-toast', type: 'error', message: 'Esta reserva já não pode ser cancelada.');
            return;
        }

        $this->motivoCancelamento = '';
        $this->resetValidation();
        $this->mostrarCancelamento = true;
    }

    public function fecharCancelamento(): void
    {
        $this->mostrarCancelamento = false;
    }

    public function confirmarCancelamento(): void
    {
        // Revalida a posse e o estado no momento da ação, não só ao abrir
        abort_unless($this->booking->user_id === Auth::id(), 403);

        if (! $this->podeCancelar) {
            $this->mostrarCancelamento = false;
            $this->dispatch('show-toast', type: 'error', message: 'Esta reserva já não pode ser cancelada.');
            return;
        }

        $this->validate(
            ['motivoCancelamento' => ['required', 'string', 'min:5', 'max:500']],
            [
                'motivoCancelamento.required' => 'Diga-nos o motivo do cancelamento.',
                'motivoCancelamento.min' => 'Escreva pelo menos 5 caracteres.',
            ]
        );

        $ok = $this->booking->cancel(
            $this->motivoCancelamento,
            (bool) $this->booking->is_refundable
        );

        if (! $ok) {
            $this->dispatch('show-toast', type: 'error', message: 'Não foi possível cancelar a reserva. Contacte o suporte.');
            return;
        }

        $this->booking->refresh();
        $this->mostrarCancelamento = false;
        $this->dispatch('show-toast', type: 'success', message: 'Reserva cancelada. Receberá a confirmação por email.');
    }

    /** Link de WhatsApp para falar com o alojamento sobre esta reserva. */
    public function getWhatsappProperty(): ?string
    {
        $telefone = preg_replace('/\D+/', '', (string) ($this->booking->hotel->phone ?? ''));
        if ($telefone === '' || strlen($telefone) < 9) {
            return null;
        }
        if (! str_starts_with($telefone, '244')) {
            $telefone = '244' . ltrim($telefone, '0');
        }

        $mensagem = sprintf(
            'Olá! Tenho a reserva %s no %s, de %s a %s. Gostaria de esclarecer uma questão. (via KiandaStay)',
            $this->booking->confirmation_code ?: '#' . $this->booking->id,
            $this->booking->hotel->name ?? '',
            $this->booking->check_in?->format('d/m/Y') ?? '—',
            $this->booking->check_out?->format('d/m/Y') ?? '—'
        );

        return 'https://wa.me/' . $telefone . '?text=' . rawurlencode($mensagem);
    }

    public function render(): View
    {
        return view('livewire.booking-details')->layout('layouts.app');
    }
}

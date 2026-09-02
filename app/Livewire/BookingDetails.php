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

    // ---- Pedido de alteração ----
    public bool $mostrarAlteracao = false;
    public string $novaEntrada = '';
    public string $novaSaida = '';
    public int $novosHospedes = 1;
    public string $notaAlteracao = '';

    /** Alterações só fazem sentido antes de a estadia começar. */
    public function getPodePedirAlteracaoProperty(): bool
    {
        return $this->podeCancelar;
    }

    public function abrirAlteracao(): void
    {
        if (! $this->podePedirAlteracao) {
            $this->dispatch('show-toast', type: 'error', message: 'Esta reserva já não pode ser alterada.');
            return;
        }

        $this->novaEntrada = $this->booking->check_in?->toDateString() ?? '';
        $this->novaSaida = $this->booking->check_out?->toDateString() ?? '';
        $this->novosHospedes = (int) $this->booking->guests;
        $this->notaAlteracao = '';
        $this->resetValidation();
        $this->mostrarAlteracao = true;
    }

    public function fecharAlteracao(): void
    {
        $this->mostrarAlteracao = false;
    }

    /**
     * Regista o pedido na reserva e avisa o alojamento por email.
     * Não altera as datas por si: a confirmação é do alojamento.
     */
    public function enviarPedidoAlteracao(): void
    {
        abort_unless($this->booking->user_id === \Illuminate\Support\Facades\Auth::id(), 403);

        if (! $this->podePedirAlteracao) {
            $this->mostrarAlteracao = false;
            $this->dispatch('show-toast', type: 'error', message: 'Esta reserva já não pode ser alterada.');
            return;
        }

        $this->validate([
            'novaEntrada' => ['required', 'date', 'after_or_equal:today'],
            'novaSaida' => ['required', 'date', 'after:novaEntrada'],
            'novosHospedes' => ['required', 'integer', 'min:1', 'max:30'],
            'notaAlteracao' => ['nullable', 'string', 'max:500'],
        ], [
            'novaEntrada.required' => 'Indique a nova data de entrada.',
            'novaEntrada.after_or_equal' => 'A entrada não pode ser no passado.',
            'novaSaida.after' => 'A saída tem de ser depois da entrada.',
            'novosHospedes.min' => 'Indique pelo menos um hóspede.',
        ]);

        $semMudanca = $this->novaEntrada === ($this->booking->check_in?->toDateString() ?? '')
            && $this->novaSaida === ($this->booking->check_out?->toDateString() ?? '')
            && $this->novosHospedes === (int) $this->booking->guests
            && trim($this->notaAlteracao) === '';

        if ($semMudanca) {
            $this->addError('notaAlteracao', 'Altere as datas ou os hóspedes, ou escreva uma nota com o pedido.');
            return;
        }

        $resumo = sprintf(
            "[%s] Pedido de alteração: %s → %s, %d hóspede(s).%s",
            now()->format('d/m/Y H:i'),
            $this->novaEntrada,
            $this->novaSaida,
            $this->novosHospedes,
            trim($this->notaAlteracao) !== '' ? ' Nota: ' . trim($this->notaAlteracao) : ''
        );

        // Fica registado na própria reserva, para o alojamento e o admin verem
        $this->booking->special_requests = trim(($this->booking->special_requests ?? '') . "\n" . $resumo);
        $this->booking->save();

        $this->notificarAlojamento($resumo);

        $this->booking->refresh();
        $this->mostrarAlteracao = false;
        $this->dispatch('show-toast', type: 'success', message: 'Pedido enviado. O alojamento entrará em contacto para confirmar.');
    }

    /** O email é um extra: falhar a notificação não pode perder o pedido. */
    private function notificarAlojamento(string $resumo): void
    {
        $destino = $this->booking->hotel->email
            ?? \App\Models\Setting::get('contact_email');

        if (! $destino || ! filter_var($destino, FILTER_VALIDATE_EMAIL)) {
            return;
        }

        try {
            \Illuminate\Support\Facades\Mail::send([], [], function ($m) use ($destino, $resumo) {
                $m->to($destino)
                  ->replyTo($this->booking->user->email)
                  ->subject('Pedido de alteração — reserva ' . ($this->booking->confirmation_code ?: '#' . $this->booking->id))
                  ->text(sprintf(
                      "Reserva: %s\nAlojamento: %s\nCliente: %s (%s)\nDatas atuais: %s a %s\n\n%s\n\n— KiandaStay",
                      $this->booking->confirmation_code ?: '#' . $this->booking->id,
                      $this->booking->hotel->name ?? '—',
                      $this->booking->user->name ?? '—',
                      $this->booking->user->email ?? '—',
                      $this->booking->check_in?->format('d/m/Y') ?? '—',
                      $this->booking->check_out?->format('d/m/Y') ?? '—',
                      $resumo
                  ));
            });
        } catch (\Throwable $e) {
            report($e);
        }
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

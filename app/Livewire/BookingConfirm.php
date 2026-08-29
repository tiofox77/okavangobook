<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Reservation;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class BookingConfirm extends Component
{
    public Reservation $booking;
    
    /**
     * Inicializar componente
     */
    public function mount(Reservation|int $booking): void
    {
        $bookingId = $booking instanceof Reservation ? $booking->getKey() : $booking;
        $this->booking = Reservation::with(['hotel', 'roomType', 'room', 'user'])
            ->findOrFail($bookingId);
    }
    
    /**
     * Confirmar a reserva
     */
    public function confirmBooking(): void
    {
        try {
            $this->booking->update([
                'status' => 'confirmed',
                'confirmed_at' => now(),
            ]);
            
            session()->flash('success', 'Reserva confirmada com sucesso!');
            
            $this->redirect(route('booking.success', $this->booking));
            
        } catch (\Exception $e) {
            \Log::error('Error confirming booking: ' . $e->getMessage());
            session()->flash('error', 'Erro ao confirmar reserva. Tente novamente.');
        }
    }
    
    /**
     * Cancelar a reserva
     */
    public function cancelBooking(): void
    {
        try {
            $this->booking->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
            ]);
            
            session()->flash('info', 'Reserva cancelada.');
            
            // Usar redirect sem route helper primeiro para testar
            $this->redirect('/');
            
        } catch (\Exception $e) {
            \Log::error('Error cancelling booking: ' . $e->getMessage());
            session()->flash('error', 'Erro ao cancelar reserva. Tente novamente.');
        }
    }
    
    /**
     * Render do componente
     */
    public function render(): View
    {
        return view('livewire.booking-confirm')
            ->layout('layouts.app');
    }
}

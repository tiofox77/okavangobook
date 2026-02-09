<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Reservation;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class BookingDetails extends Component
{
    public Reservation $booking;
    
    /**
     * Inicializar componente
     * 
     * @param Reservation|int $booking Objeto da reserva ou ID da reserva
     */
    public function mount(Reservation|int $booking): void
    {
        if ($booking instanceof Reservation) {
            // Verificar se a reserva pertence ao usuário atual
            if ($booking->user_id === Auth::id()) {
                $this->booking = $booking->load(['hotel', 'roomType', 'room', 'user']);
            } else {
                abort(403, 'Reserva não pertence a este utilizador');
            }
        } else {
            $this->booking = Reservation::with(['hotel', 'roomType', 'room', 'user'])
                ->where('user_id', Auth::id())
                ->findOrFail($booking);
        }
    }
    
    /**
     * Render do componente
     */
    public function render(): View
    {
        return view('livewire.booking-details')
            ->layout('layouts.app');
    }
}

<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Reservation;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class BookingSuccess extends Component
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
            $this->booking = $booking->load(['hotel', 'roomType', 'user']);
        } else {
            $this->booking = Reservation::with(['hotel', 'roomType', 'user'])
                ->findOrFail($booking);
        }
    }
    
    /**
     * Render do componente
     */
    public function render(): View
    {
        return view('livewire.booking-success')
            ->layout('layouts.app');
    }
}

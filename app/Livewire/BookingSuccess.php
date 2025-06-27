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
     */
    public function mount(int $booking): void
    {
        $this->booking = Reservation::with(['hotel', 'roomType', 'user'])
            ->findOrFail($booking);
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

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
     */
    public function mount(int $booking): void
    {
        $this->booking = Reservation::with(['hotel', 'roomType', 'room', 'user'])
            ->where('user_id', Auth::id())
            ->findOrFail($booking);
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

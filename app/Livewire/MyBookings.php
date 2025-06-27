<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Reservation;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class MyBookings extends Component
{
    use WithPagination;
    
    public string $statusFilter = 'all';
    
    /**
     * Render do componente
     */
    public function render(): View
    {
        $bookings = Reservation::where('user_id', Auth::id())
            ->with(['hotel', 'roomType', 'room'])
            ->when($this->statusFilter !== 'all', function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('livewire.my-bookings', [
            'bookings' => $bookings
        ])->layout('layouts.app');
    }
}

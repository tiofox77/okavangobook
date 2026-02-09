<?php

declare(strict_types=1);

namespace App\Livewire;

use Livewire\Component;
use App\Models\Hotel;
use App\Models\Reservation;

class Dashboard extends Component
{
    public function mount()
    {
        // Verificar se o utilizador está autenticado
        if (!auth()->check()) {
            return redirect()->route('login');
        }
    }
    
    public function render()
    {
        // Obter as reservas do utilizador
        $user = auth()->user();
        
        // Carregar reservas recentes com relacionamentos
        $bookings = $user->reservations()
                    ->with(['hotel', 'roomType', 'room'])
                    ->latest()
                    ->take(5)
                    ->get();
        
        return view('livewire.dashboard', [
            'user' => $user,
            'bookings' => $bookings,
        ])->layout('layouts.app');
    }
}

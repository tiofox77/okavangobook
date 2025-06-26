<?php

declare(strict_types=1);

namespace App\Livewire;

use Livewire\Component;
use App\Models\Hotel;

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
        // Obter as reservas do utilizador se houver
        $user = auth()->user();
        
        // Se existir um relacionamento de reservas entre Hotel e User
        // $bookings = $user->bookings()->with('hotel')->latest()->take(5)->get();
        
        return view('livewire.dashboard', [
            'user' => $user,
            // 'bookings' => $bookings,
        ])->layout('layouts.app');
    }
}

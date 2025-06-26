<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Hotel;
use App\Models\User;
use App\Models\Location;

class Dashboard extends Component
{
    public function mount()
    {
        // Verificar se o utilizador tem permissão para aceder ao dashboard
        if (!auth()->check() || !auth()->user()->hasRole('Admin')) {
            return redirect()->route('login');
        }
    }
    
    public function render()
    {
        $statistics = [
            'hoteis' => Hotel::count(),
            'utilizadores' => User::count(),
            'localizacoes' => Location::count(),
            // Adicione mais estatísticas conforme necessário
        ];
        
        return view('livewire.admin.dashboard', [
            'statistics' => $statistics
        ])->layout('layouts.admin');
    }
}

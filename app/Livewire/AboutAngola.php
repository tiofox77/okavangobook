<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Location;

class AboutAngola extends Component
{
    public function render()
    {
        // Buscar algumas localizações populares para exibir na página
        $popularLocations = Location::withCount('hotels')
            ->orderBy('hotels_count', 'desc')
            ->take(6)
            ->get();
            
        return view('livewire.about-angola', [
            'popularLocations' => $popularLocations
        ])
        ->layout('layouts.app', [
            'title' => 'Sobre Angola - Descubra as Maravilhas',
            'metaDescription' => 'Conheça as belezas naturais, cultura e destinos turísticos de Angola.'
        ]);
    }
}

<?php

namespace App\Livewire;

use App\Helpers\ImageHelper;
use App\Models\Location;
use Livewire\Component;

class Destinations extends Component
{
    public $locations;
    
    public function mount()
    {
        // Agrupar localizações por província, selecionando apenas uma localização por província
        // (geralmente a capital ou cidade principal)
        $groupedLocations = Location::orderBy('province')
            ->get()
            ->groupBy('province')
            ->map(function ($group) {
                // Retorna apenas o primeiro item de cada grupo (província)
                return $group->first();
            });
            
        $this->locations = $groupedLocations->values();
    }
    
    public function render()
    {
        // Passa o helper de imagem para a view
        return view('livewire.destinations', [
            'imageHelper' => new ImageHelper()
        ])
        ->layout('layouts.app', [
            'title' => 'Destinos - Províncias de Angola',
            'metaDescription' => 'Explore as províncias de Angola e encontre os melhores destinos para sua viagem.'
        ]);
    }
}

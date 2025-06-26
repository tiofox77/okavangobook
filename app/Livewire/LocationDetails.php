<?php

namespace App\Livewire;

use App\Helpers\ImageHelper;
use App\Models\Location;
use App\Models\Hotel;
use Livewire\Component;

class LocationDetails extends Component
{
    public $province;
    public $locations;
    public $hotels;
    
    public function mount($province)
    {
        $this->province = $province;
        
        // Carregar todas as localizações dentro da província selecionada
        $this->locations = Location::where('province', $province)
            ->orderBy('name')
            ->get();
            
        if ($this->locations->isEmpty()) {
            return redirect()->route('destinations');
        }
        
        // Carregar hotéis associados a essa província
        $locationIds = $this->locations->pluck('id');
        $this->hotels = Hotel::whereIn('location_id', $locationIds)
            ->with('location')
            ->take(6) // Limitar a 6 hotéis para exibição inicial
            ->get();
    }
    
    public function render()
    {
        $provinceName = ucfirst($this->province);
        
        return view('livewire.location-details', [
            'imageHelper' => new ImageHelper(),
            'provinceName' => $provinceName
        ])
        ->layout('layouts.app', [
            'title' => "$provinceName - Destinos em Angola",
            'metaDescription' => "Conheça $provinceName, suas cidades, atrações turísticas e os melhores hotéis para se hospedar."
        ]);
    }
}

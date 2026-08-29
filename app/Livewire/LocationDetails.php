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
    public bool $isSpecificLocation = false;
    
    public function mount($province)
    {
        $requested = strtolower((string) $province);
        $specificLocation = Location::where('slug', $requested)->first();

        if ($specificLocation && strtolower($specificLocation->province) !== $requested) {
            $this->isSpecificLocation = true;
            $this->province = $specificLocation->slug;
            $this->locations = collect([$specificLocation]);
        } else {
            $this->province = $requested;
            $this->locations = Location::whereRaw('LOWER(province) = ?', [$requested])
                ->orderBy('name')
                ->get();
        }
            
        if ($this->locations->isEmpty()) {
            return redirect()->route('destinations');
        }
        
        // Carregar hotéis associados a essa província
        $locationIds = $this->locations->pluck('id');
        $this->hotels = Hotel::whereIn('location_id', $locationIds)
            ->with('location')
            ->take(12)
            ->get();
    }
    
    public function render()
    {
        $provinceName = $this->isSpecificLocation
            ? $this->locations->first()->name
            : Location::provinceName($this->province);
        $description = $this->locations->first()->description
            ?: "Conheça {$provinceName}, os seus alojamentos e experiências turísticas em Angola.";
        
        return view('livewire.location-details', [
            'imageHelper' => new ImageHelper(),
            'provinceName' => $provinceName,
            'locationDescription' => $description,
        ])
        ->layout('layouts.app', [
            'title' => "$provinceName - Destinos em Angola",
            'metaDescription' => $description,
        ]);
    }
}

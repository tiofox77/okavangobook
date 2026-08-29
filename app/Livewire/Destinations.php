<?php

namespace App\Livewire;

use App\Helpers\ImageHelper;
use App\Models\Location;
use Livewire\Component;

class Destinations extends Component
{
    public $locations;
    public string $sortBy = 'popular';
    
    public function mount()
    {
        // Agrupar localizações por província, selecionando apenas uma localização por província
        // (geralmente a capital ou cidade principal)
        $groupedLocations = Location::withCount('hotels')
            ->orderBy('province')
            ->get()
            ->groupBy('province')
            ->map(function ($group) {
                $location = $group->first();
                $story = config('destination_stories.' . $location->province);
                $localImage = 'locations/commons/' . $location->province . '.jpg';

                if ($story) {
                    $location->description = $story;
                }
                if (\Storage::disk('public')->exists($localImage)) {
                    $location->image = $localImage;
                }

                return $location;
            });
            
        $this->locations = $groupedLocations->values();
        $this->sortLocations();
    }

    public function setSorting(string $sort): void
    {
        if (!in_array($sort, ['popular', 'alphabetical'], true)) {
            return;
        }

        $this->sortBy = $sort;
        $this->sortLocations();
    }

    private function sortLocations(): void
    {
        $this->locations = $this->locations
            ->when(
                $this->sortBy === 'popular',
                fn ($locations) => $locations->sortByDesc('hotels_count'),
                fn ($locations) => $locations->sortBy('province')
            )
            ->values();
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

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
        // Preço mínimo por província (uma query só) para mostrar "a partir de"
        $minPrices = \App\Models\Hotel::where('hotels.is_active', true)
            ->join('locations', 'hotels.location_id', '=', 'locations.id')
            ->where('hotels.min_price', '>', 0)
            ->selectRaw('locations.province, MIN(hotels.min_price) as min_price')
            ->groupBy('locations.province')
            ->pluck('min_price', 'province');

        $groupedLocations = Location::withCount(['hotels' => fn ($q) => $q->where('is_active', true)])
            ->orderBy('province')
            ->get()
            ->groupBy('province')
            ->map(function ($group) use ($minPrices) {
                // Representante da província: o local com mais alojamentos
                $location = $group->sortByDesc('hotels_count')->first();

                // BUG corrigido: a contagem mostrada era só a do primeiro local
                // do grupo; agora é a SOMA de todos os locais da província.
                $location->hotels_count = (int) $group->sum('hotels_count');
                $location->locations_count = $group->count();
                $location->min_price = $minPrices[$location->province] ?? null;

                $story = config('destination_stories.' . $location->province);
                $localImage = 'locations/commons/' . $location->province . '.jpg';

                // Descrição: história curada > primeira descrição não-vazia do grupo
                if ($story) {
                    $location->description = $story;
                } elseif (trim((string) $location->description) === '') {
                    $location->description = $group->pluck('description')
                        ->filter(fn ($d) => trim((string) $d) !== '')->first() ?: '';
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

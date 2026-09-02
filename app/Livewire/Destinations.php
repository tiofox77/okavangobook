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
        // Preço mínimo por província (uma query só) para mostrar "a partir de"
        $minPrices = \App\Models\Hotel::where('hotels.is_active', true)
            ->join('locations', 'hotels.location_id', '=', 'locations.id')
            ->where('hotels.min_price', '>', 0)
            ->selectRaw('locations.province, MIN(hotels.min_price) as min_price')
            ->groupBy('locations.province')
            ->pluck('min_price', 'province');

        // Uma entrada por província, representada pelo local com mais alojamentos
        $this->locations = Location::withCount(['hotels' => fn ($q) => $q->where('is_active', true)])
            ->orderBy('province')
            ->get()
            ->groupBy('province')
            ->map(function ($group) use ($minPrices) {
                $location = $group->sortByDesc('hotels_count')->first();

                // A contagem mostrada era só a do primeiro local do grupo;
                // agora é a SOMA de todos os locais da província.
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
            })
            ->values();
    }

    public function setSorting(string $sort): void
    {
        if (in_array($sort, ['popular', 'alphabetical'], true)) {
            $this->sortBy = $sort;
        }
    }

    /**
     * Lista completa das províncias para apresentação: as que têm registos na
     * base de dados mais as que ainda não têm (ex.: Cuando, Moxico Leste e
     * Ícolo e Bengo, criadas na divisão administrativa de 2025).
     *
     * Construída aqui e não em $this->locations porque o Livewire não
     * consegue serializar uma coleção que misture modelos gravados e não
     * gravados ("multiple model connections").
     */
    private function todasAsProvincias(): \Illuminate\Support\Collection
    {
        $comRegisto = collect($this->locations);
        $nomesPresentes = $comRegisto->map(fn ($l) => Location::provinceName($l->province))->all();

        $semRegisto = collect(Location::PROVINCE_NAMES)
            ->reject(fn ($nome) => in_array($nome, $nomesPresentes, true))
            ->unique()   // 'cuando-cubango' e 'cubango' partilham o nome
            ->map(fn ($nome, $slug) => (object) [
                'province' => $slug,
                'name' => Location::PROVINCE_CAPITALS[$slug] ?? $nome,
                'description' => (string) config('destination_stories.' . $slug),
                'image' => 'locations/commons/' . $slug . '.jpg',
                'hotels_count' => 0,
                'locations_count' => 0,
                'min_price' => null,
            ])
            ->values();

        return $comRegisto->concat($semRegisto)
            ->when(
                $this->sortBy === 'popular',
                fn ($c) => $c->sortByDesc('hotels_count'),
                fn ($c) => $c->sortBy(fn ($l) => Location::provinceName($l->province))
            )
            ->values();
    }

    public function render()
    {
        return view('livewire.destinations', [
            'imageHelper' => new ImageHelper(),
            // nome distinto: uma chave 'locations' seria sobreposta pela
            // propriedade pública do mesmo nome ao chegar à view
            'provincias' => $this->todasAsProvincias(),
        ])
        ->layout('layouts.app', [
            'title' => 'Destinos - Províncias de Angola',
            'metaDescription' => 'Explore as 21 províncias de Angola e encontre os melhores destinos para sua viagem.',
        ]);
    }
}

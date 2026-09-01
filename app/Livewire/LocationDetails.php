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
        
        // Carregar hotéis associados a essa província (melhores primeiro)
        $locationIds = $this->locations->pluck('id');
        $this->hotels = Hotel::whereIn('location_id', $locationIds)
            ->where('is_active', true)
            ->with('location')
            ->orderByDesc('is_featured')
            ->orderByDesc('rating')
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

        // Dados para SEO: total de alojamentos na província e preço mínimo por noite
        $locationIds = $this->locations->pluck('id');
        $hotelsCount = Hotel::whereIn('location_id', $locationIds)->where('is_active', true)->count();
        $minPrice = Hotel::whereIn('location_id', $locationIds)
            ->where('is_active', true)
            ->where('min_price', '>', 0)
            ->min('min_price');
        if (!$minPrice) {
            $minPrice = \App\Models\RoomType::whereHas('hotel', fn ($q) => $q->whereIn('location_id', $locationIds)->where('is_active', true))
                ->where('is_available', true)
                ->where('base_price', '>', 0)
                ->min('base_price');
        }

        // Galeria multimédia do destino (imagens + vídeos, de todas as
        // localizações da província, ordenadas)
        $galleryMedia = \App\Models\LocationMedia::whereIn('location_id', $locationIds)
            ->orderBy('position')->orderBy('id')
            ->get();

        // Meta description orientada à pesquisa "hotéis em {província}"
        $seoDescription = "Compare {$hotelsCount} hotéis, resorts e hospedarias em {$provinceName}"
            . ($minPrice ? ' desde AKZ ' . number_format((float) $minPrice, 0, ',', '.') . '/noite' : '')
            . '. Fotos, avaliações e reserva online com os melhores preços no KiandaStay.';

        return view('livewire.location-details', [
            'imageHelper' => new ImageHelper(),
            'provinceName' => $provinceName,
            'locationDescription' => $description,
            'hotelsCount' => $hotelsCount,
            'minPrice' => $minPrice,
            'seoDescription' => $seoDescription,
            'galleryMedia' => $galleryMedia,
        ])
        ->layout('layouts.app', [
            'title' => "Hotéis em $provinceName: compare preços e reserve",
            'metaDescription' => $seoDescription,
        ]);
    }
}

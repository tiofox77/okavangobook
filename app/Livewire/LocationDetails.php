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

        // Contagem REAL de hotéis por local (a coluna hotels_count estava
        // desatualizada e mostrava 0 nos cards e 158 no total).
        $this->locations->loadCount(['hotels' => fn ($q) => $q->where('is_active', true)]);
        // Locais com mais alojamentos primeiro (antes era alfabética e as zonas
        // vazias apareciam à frente das relevantes)
        $this->locations = $this->locations->sortByDesc('hotels_count')->values();

        // Carregar hotéis associados a essa província (melhores primeiro)
        $locationIds = $this->locations->pluck('id');
        $this->hotels = Hotel::whereIn('location_id', $locationIds)
            ->where('is_active', true)
            ->with('location')
            ->withMin(['roomTypes as cheapest_room' => fn ($q) => $q->where('is_available', true)->where('base_price', '>', 0)], 'base_price')
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

        // Descrição por ordem de qualidade: história curada da província
        // (a mesma que /destinos usa) > descrição do local homónimo >
        // primeira não-vazia > texto genérico. Antes usava sempre a do
        // primeiro local por ordem alfabética: vinha vazia ou descrevia
        // outra zona (ex.: "Sobre Luanda" mostrava o texto do Mussulo).
        $curatedStory = $this->isSpecificLocation ? null : config('destination_stories.' . $this->province);
        $sameNameDesc = optional($this->locations->first(
            fn ($l) => \Illuminate\Support\Str::slug($l->name) === $this->province
        ))->description;

        // Escolha por SUBSTÂNCIA: o texto editado no admin/Agent API ganha à
        // história pré-definida quando é realmente desenvolvido (>=180 car.);
        // se for só uma linha, a história curada dá melhor página. Assim o
        // enriquecimento do agente aparece sem degradar províncias onde ele
        // gravou apenas uma frase.
        $editado = trim((string) $sameNameDesc);
        $outraNaoVazia = (string) $this->locations->pluck('description')
            ->filter(fn ($d) => trim((string) $d) !== '')->first();

        $description = (mb_strlen($editado) >= 180 ? $editado : null)
            ?: $curatedStory
            ?: ($editado !== '' ? $editado : null)
            ?: (trim($outraNaoVazia) !== '' ? $outraNaoVazia : null)
            ?: "Conheça {$provinceName}, os seus alojamentos e experiências turísticas em Angola.";

        // Capital real: campo capital preenchido, senão o local homónimo da
        // província, senão o próprio nome da província (antes mostrava o
        // primeiro local por ordem alfabética — ex.: "Capital: Alvalade").
        $capital = $this->locations->pluck('capital')->filter(fn ($c) => trim((string) $c) !== '')->first()
            ?: (optional($this->locations->first(fn ($l) => \Illuminate\Support\Str::slug($l->name) === $this->province))->name
                ?: $provinceName);

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
        // Guarda: enquanto a migração de location_media não correr no servidor,
        // a página não pode rebentar (a tabela ainda não existe).
        $galleryMedia = collect();
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('location_media')) {
                $galleryMedia = \App\Models\LocationMedia::whereIn('location_id', $locationIds)
                    ->orderBy('position')->orderBy('id')
                    ->get();
            }
        } catch (\Throwable $e) {
            report($e); // galeria é opcional — nunca quebrar a página
        }

        // Distribuição por tipo de alojamento (alimenta os atalhos filtrados)
        $typeCounts = Hotel::whereIn('location_id', $locationIds)
            ->where('is_active', true)
            ->selectRaw('property_type, COUNT(*) as total')
            ->groupBy('property_type')
            ->pluck('total', 'property_type')
            ->toArray();

        // Avaliação média e nº de propriedades avaliadas
        $ratingStats = Hotel::whereIn('location_id', $locationIds)
            ->where('is_active', true)->where('rating', '>', 0)
            ->selectRaw('AVG(rating) as avg_rating, COUNT(*) as rated')
            ->first();

        // Artigos do blog sobre a província (conteúdo relacionado que existia
        // na BD mas nunca era mostrado nesta página)
        $relatedArticles = collect();
        try {
            $relatedArticles = \App\Models\Article::query()
                ->when(
                    \Illuminate\Support\Facades\Schema::hasColumn('articles', 'is_published'),
                    fn ($q) => $q->where('is_published', true)
                )
                ->where(fn ($q) => $q->where('title', 'like', "%{$provinceName}%")
                    ->orWhere('content', 'like', "%{$provinceName}%"))
                ->latest()->take(3)->get();
        } catch (\Throwable $e) {
            // conteúdo relacionado é opcional — nunca quebrar a página
        }

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
            'capital' => $capital,
            'typeCounts' => $typeCounts,
            'avgRating' => $ratingStats?->avg_rating ? round((float) $ratingStats->avg_rating, 1) : null,
            'ratedCount' => (int) ($ratingStats?->rated ?? 0),
            'relatedArticles' => $relatedArticles,
        ])
        ->layout('layouts.app', [
            'title' => "Hotéis em $provinceName: compare preços e reserve",
            'metaDescription' => $seoDescription,
        ]);
    }
}

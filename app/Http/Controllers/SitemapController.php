<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Hotel;
use App\Models\Location;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    /**
     * Gera o sitemap.xml dinamicamente (em cache 6h).
     */
    public function index()
    {
        $xml = Cache::remember('sitemap.xml', now()->addHours(6), function () {
            $urls = [];

            // Páginas estáticas
            $static = [
                ['home', 'daily', '1.0'],
                ['search.results', 'daily', '0.9'],
                ['destinations', 'weekly', '0.8'],
                ['about.angola', 'monthly', '0.6'],
                ['contact', 'monthly', '0.5'],
                ['pricing', 'monthly', '0.6'],
                ['articles', 'weekly', '0.7'],
                ['faq', 'monthly', '0.4'],
                ['privacy', 'yearly', '0.3'],
                ['terms', 'yearly', '0.3'],
            ];
            foreach ($static as [$route, $freq, $priority]) {
                $urls[] = $this->url(route($route), null, $freq, $priority);
            }

            // Hotéis
            Hotel::where('is_active', true)->whereNotNull('slug')
                ->select('slug', 'name', 'thumbnail', 'updated_at')->chunk(500, function ($hotels) use (&$urls) {
                    foreach ($hotels as $hotel) {
                        $urls[] = $this->url(
                            route('hotel.details', $hotel->slug),
                            $hotel->updated_at,
                            'weekly',
                            '0.8',
                            $hotel->thumbnail ? \App\Helpers\ImageHelper::getValidImage($hotel->thumbnail, 'hotel') : null,
                            $hotel->name
                        );
                    }
                });

            // Destinos por província
            Location::whereNotNull('province')->select('province', 'updated_at')
                ->get()->unique('province')->each(function ($loc) use (&$urls) {
                    $urls[] = $this->url(route('location.details', \Illuminate\Support\Str::slug($loc->province)), $loc->updated_at, 'weekly', '0.7');
                });

            // Destinos específicos (ex.: Mussulo), além das páginas provinciais.
            Location::whereNotNull('slug')->select('name', 'slug', 'province', 'image', 'updated_at')
                ->get()->each(function ($loc) use (&$urls) {
                    if ($loc->slug === \Illuminate\Support\Str::slug($loc->province)) {
                        return;
                    }
                    $urls[] = $this->url(
                        route('location.details', $loc->slug),
                        $loc->updated_at,
                        'weekly',
                        '0.75',
                        $loc->image ? \App\Helpers\ImageHelper::getValidImage($loc->image, 'location') : null,
                        $loc->name
                    );
                });

            // Artigos publicados
            Article::published()->whereNotNull('slug')->select('slug', 'updated_at')
                ->get()->each(function ($article) use (&$urls) {
                    $urls[] = $this->url(route('article.details', $article->slug), $article->updated_at, 'monthly', '0.6');
                });

            return view('sitemap', ['urls' => $urls])->render();
        });

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }

    private function url(
        string $loc,
        $lastmod,
        string $changefreq,
        string $priority,
        ?string $image = null,
        ?string $imageTitle = null
    ): array
    {
        return [
            'loc' => $loc,
            'lastmod' => $lastmod ? $lastmod->toAtomString() : now()->toAtomString(),
            'changefreq' => $changefreq,
            'priority' => $priority,
            'image' => $image,
            'image_title' => $imageTitle,
        ];
    }
}

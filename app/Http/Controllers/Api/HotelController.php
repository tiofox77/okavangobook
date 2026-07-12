<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\HotelResource;
use App\Models\Hotel;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    /**
     * GET /api/v1/hotels
     * Lista/pesquisa hotéis com filtros: province, property_type, stars,
     * min_price, max_price, q (texto), sort, per_page.
     */
    public function index(Request $request)
    {
        $query = Hotel::query()
            ->where('is_active', true)
            ->with('location');

        if ($request->filled('q')) {
            $q = $request->string('q');
            $query->where(fn ($sub) => $sub->where('name', 'like', "%{$q}%")
                ->orWhereHas('location', fn ($l) => $l->where('name', 'like', "%{$q}%")->orWhere('province', 'like', "%{$q}%")));
        }

        if ($request->filled('province')) {
            $query->whereHas('location', fn ($l) => $l->where('province', $request->string('province')));
        }

        if ($request->filled('property_type')) {
            $query->whereIn('property_type', (array) $request->input('property_type'));
        }

        if ($request->filled('stars')) {
            $query->whereIn('stars', (array) $request->input('stars'));
        }

        if ($request->filled('min_price')) {
            $query->where('min_price', '>=', (float) $request->input('min_price'));
        }
        if ($request->filled('max_price')) {
            $query->where('min_price', '<=', (float) $request->input('max_price'));
        }

        match ($request->input('sort')) {
            'price_asc' => $query->orderBy('min_price'),
            'price_desc' => $query->orderByDesc('min_price'),
            'rating' => $query->orderByDesc('rating'),
            'stars' => $query->orderByDesc('stars'),
            default => $query->orderByDesc('is_featured')->orderByDesc('rating'),
        };

        $perPage = min((int) $request->input('per_page', 20), 100);

        return HotelResource::collection($query->paginate($perPage));
    }

    /**
     * GET /api/v1/hotels/{slug}
     */
    public function show(string $slug)
    {
        $hotel = Hotel::where('slug', $slug)
            ->orWhere('id', $slug)
            ->with(['location', 'roomTypes'])
            ->firstOrFail();

        return new HotelResource($hotel);
    }
}

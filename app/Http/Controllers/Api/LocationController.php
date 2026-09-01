<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LocationResource;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * GET /api/v1/locations
     * Lista destinos/localizações activas.
     */
    public function index(Request $request)
    {
        $query = Location::query()->where('is_active', true);

        if ($request->filled('province')) {
            $query->where('province', $request->string('province'));
        }

        // Pesquisa por nome/província (usada pelo autocomplete do frontend)
        if ($request->filled('q')) {
            $q = $request->string('q');
            $query->where(fn ($w) => $w->where('name', 'like', "%$q%")
                ->orWhere('province', 'like', "%$q%"));
        }

        return LocationResource::collection(
            $query->orderBy('name')->limit((int) min($request->input('limit', 100), 100))->get()
        );
    }
}

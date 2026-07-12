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

        return LocationResource::collection($query->orderBy('name')->get());
    }
}

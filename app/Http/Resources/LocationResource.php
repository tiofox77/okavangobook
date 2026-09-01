<?php

namespace App\Http\Resources;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LocationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'province' => $this->province,
            'province_name' => Location::provinceName($this->province),
            'description' => $this->description,
            'image' => $this->image,
            'image_url' => \App\Helpers\ImageHelper::getValidImage($this->image, 'location'),
            'capital' => $this->capital,
            'population' => $this->population,
            'coordinates' => [
                'latitude' => $this->latitude !== null ? (float) $this->latitude : null,
                'longitude' => $this->longitude !== null ? (float) $this->longitude : null,
            ],
            'is_featured' => (bool) $this->is_featured,
            'is_active' => (bool) $this->is_active,
            'hotels_count' => $this->whenCounted('hotels'),
            'url' => $this->slug ? route('location.details', $this->slug) : null,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}

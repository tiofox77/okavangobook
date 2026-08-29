<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomTypeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'capacity' => $this->capacity,
            'adult_capacity' => $this->adult_capacity,
            'children_capacity' => $this->children_capacity,
            'beds' => $this->beds,
            'bed_type' => $this->bed_type,
            'size' => $this->size,
            'size_unit' => 'm2',
            'amenities' => $this->amenities ?? [],
            'images' => $this->images ?? [],
            'base_price' => $this->base_price !== null ? (float) $this->base_price : null,
            'currency' => 'AKZ',
            'rooms_count' => $this->rooms_count,
            'is_available' => (bool) $this->is_available,
            'is_featured' => (bool) $this->is_featured,
            'position' => $this->position,
            'source_url' => $this->source_url,
            'prices' => $this->whenLoaded('prices'),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}

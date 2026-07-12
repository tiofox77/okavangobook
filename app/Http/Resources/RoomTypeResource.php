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
            'capacity' => $this->capacity,
            'beds' => $this->beds,
            'size' => $this->size,
            'base_price' => $this->base_price !== null ? (float) $this->base_price : null,
            'currency' => 'AKZ',
            'is_available' => (bool) $this->is_available,
        ];
    }
}

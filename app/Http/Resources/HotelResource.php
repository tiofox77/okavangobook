<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HotelResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'property_type' => $this->property_type,
            'stars' => $this->stars,
            'rating' => $this->rating !== null ? (float) $this->rating : null, // escala 0-5
            'reviews_count' => $this->reviews_count,
            'description' => $this->when($request->routeIs('api.hotels.show') || $request->is('api/agent/*'), $this->description),
            'address' => $this->address,
            'phone' => $this->phone,
            'email' => $this->email,
            'website' => $this->website,
            'coordinates' => [
                'latitude' => $this->latitude !== null ? (float) $this->latitude : null,
                'longitude' => $this->longitude !== null ? (float) $this->longitude : null,
            ],
            'location' => $this->whenLoaded('location', fn () => [
                'name' => $this->location->name,
                'province' => $this->location->province,
            ]),
            'thumbnail' => \App\Helpers\ImageHelper::getValidImage($this->thumbnail, 'hotel'),
            'images' => collect($this->images ?? [])->filter(fn ($image) => is_string($image) && $image !== '')->values(),
            'media' => $this->whenLoaded('agentMedia', fn () => $this->agentMedia->map(fn ($media) => [
                'id' => $media->id,
                'url' => $media->url,
                'alt_text' => $media->alt_text,
                'title' => $media->title,
                'position' => $media->position,
                'is_cover' => $media->is_cover,
            ])),
            'min_price' => $this->min_price !== null ? (float) $this->min_price : null,
            'currency' => 'AKZ',
            'url' => $this->slug ? route('hotel.details', $this->slug) : null,
            'room_types' => RoomTypeResource::collection($this->whenLoaded('roomTypes')),
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'confirmation_code' => $this->confirmation_code,
            'status' => $this->status,
            'payment_status' => $this->payment_status,
            'hotel' => $this->whenLoaded('hotel', fn () => [
                'id' => $this->hotel->id,
                'name' => $this->hotel->name,
                'slug' => $this->hotel->slug,
            ]),
            'room_type_id' => $this->room_type_id,
            'check_in' => optional($this->check_in)->toDateString(),
            'check_out' => optional($this->check_out)->toDateString(),
            'guests' => $this->guests,
            'total_price' => $this->total_price !== null ? (float) $this->total_price : null,
            'currency' => 'AKZ',
            'special_requests' => $this->special_requests,
            'created_at' => optional($this->created_at)->toIso8601String(),
        ];
    }
}

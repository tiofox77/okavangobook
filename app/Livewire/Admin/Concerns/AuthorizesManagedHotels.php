<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Concerns;

use App\Models\Hotel;
use Illuminate\Auth\Access\AuthorizationException;

trait AuthorizesManagedHotels
{
    protected function authorizeManagedHotel(int $hotelId): Hotel
    {
        $hotel = Hotel::findOrFail($hotelId);
        $user = auth()->user();

        if (!$user || (!$user->hasRole('Admin') && (int) $hotel->user_id !== (int) $user->id)) {
            throw new AuthorizationException('Não tem permissão para gerir este hotel.');
        }

        return $hotel;
    }

    protected function authorizeHotelResource(object $resource): void
    {
        $this->authorizeManagedHotel((int) $resource->hotel_id);
    }
}

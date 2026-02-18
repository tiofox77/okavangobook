<?php

namespace App\Observers;

use App\Models\Hotel;
use App\Models\Notification;

class HotelObserver
{
    public function created(Hotel $hotel)
    {
        $hotel->loadMissing('location');
        $locationName = $hotel->location?->name ?? '';

        Notification::notifyAdmins(
            Notification::TYPE_HOTEL_NEW,
            'Nova propriedade adicionada',
            "{$hotel->name}" . ($locationName ? " em {$locationName}" : '') . " foi adicionada ao sistema",
            null,
            route('admin.hotels')
        );
    }
}

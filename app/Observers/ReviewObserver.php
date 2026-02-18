<?php

namespace App\Observers;

use App\Models\Notification;
use App\Models\Review;

class ReviewObserver
{
    public function created(Review $review)
    {
        $review->loadMissing(['user', 'hotel']);
        $hotelName = $review->hotel?->name ?? 'Hotel';
        $guestName = $review->user?->name ?? 'Utilizador';
        $rating = $review->rating ?? 0;
        $stars = str_repeat('★', $rating) . str_repeat('☆', 5 - $rating);

        Notification::notifyAdminsAndOwner(
            $review->hotel_id,
            Notification::TYPE_REVIEW_NEW,
            'Nova avaliação recebida',
            "{$guestName} avaliou {$hotelName} com {$stars} ({$rating}/5)",
            null,
            route('admin.hotels')
        );
    }
}

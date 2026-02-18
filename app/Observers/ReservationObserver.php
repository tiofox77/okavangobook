<?php

namespace App\Observers;

use App\Models\Notification;
use App\Models\Reservation;

class ReservationObserver
{
    public function created(Reservation $reservation)
    {
        $reservation->loadMissing(['user', 'hotel']);
        $hotelName = $reservation->hotel?->name ?? 'Hotel';
        $guestName = $reservation->user?->name ?? 'Hóspede';
        $total = number_format($reservation->total_price ?? 0, 2, ',', '.');

        Notification::notifyAdminsAndOwner(
            $reservation->hotel_id,
            Notification::TYPE_RESERVATION_NEW,
            'Nova reserva recebida',
            "{$guestName} fez uma reserva no {$hotelName} - {$total} AOA",
            null,
            route('admin.reservations')
        );
    }

    public function updated(Reservation $reservation)
    {
        if (!$reservation->wasChanged('status')) {
            // Se mudou payment_status para paid
            if ($reservation->wasChanged('payment_status') && $reservation->payment_status === 'paid') {
                $reservation->loadMissing(['user', 'hotel']);
                $hotelName = $reservation->hotel?->name ?? 'Hotel';
                $total = number_format($reservation->total_price ?? 0, 2, ',', '.');

                Notification::notifyAdminsAndOwner(
                    $reservation->hotel_id,
                    Notification::TYPE_PAYMENT_RECEIVED,
                    'Pagamento recebido',
                    "Pagamento de {$total} AOA confirmado para {$hotelName}",
                    null,
                    route('admin.reservations')
                );
            }
            return;
        }

        $reservation->loadMissing(['user', 'hotel']);
        $hotelName = $reservation->hotel?->name ?? 'Hotel';
        $guestName = $reservation->user?->name ?? 'Hóspede';

        match($reservation->status) {
            Reservation::STATUS_CONFIRMED => Notification::notifyAdminsAndOwner(
                $reservation->hotel_id,
                Notification::TYPE_RESERVATION_CONFIRMED,
                'Reserva confirmada',
                "Reserva de {$guestName} no {$hotelName} foi confirmada",
                null,
                route('admin.reservations')
            ),
            Reservation::STATUS_CANCELLED => Notification::notifyAdminsAndOwner(
                $reservation->hotel_id,
                Notification::TYPE_RESERVATION_CANCELLED,
                'Reserva cancelada',
                "Reserva de {$guestName} no {$hotelName} foi cancelada",
                null,
                route('admin.reservations')
            ),
            Reservation::STATUS_CHECKED_IN => Notification::notifyAdminsAndOwner(
                $reservation->hotel_id,
                Notification::TYPE_RESERVATION_CHECKIN,
                'Check-in realizado',
                "{$guestName} fez check-in no {$hotelName}",
                null,
                route('admin.reservations')
            ),
            Reservation::STATUS_CHECKED_OUT => Notification::notifyAdminsAndOwner(
                $reservation->hotel_id,
                Notification::TYPE_RESERVATION_CHECKOUT,
                'Check-out realizado',
                "{$guestName} fez check-out do {$hotelName}",
                null,
                route('admin.reservations')
            ),
            default => null,
        };
    }
}

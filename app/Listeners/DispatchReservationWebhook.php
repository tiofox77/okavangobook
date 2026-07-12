<?php

namespace App\Listeners;

use App\Events\ReservationCreated;
use App\Events\ReservationStatusChanged;
use App\Http\Resources\ReservationResource;
use App\Services\WebhookService;

class DispatchReservationWebhook
{
    /**
     * Reserva criada -> evento reservation.created
     */
    public function handleCreated(ReservationCreated $event): void
    {
        $event->reservation->loadMissing('hotel');
        WebhookService::dispatch(
            'reservation.created',
            (new ReservationResource($event->reservation))->resolve()
        );
    }

    /**
     * Estado alterado -> reservation.status_changed (e .cancelled quando aplicável)
     */
    public function handleStatusChanged(ReservationStatusChanged $event): void
    {
        $event->reservation->loadMissing('hotel');
        $payload = (new ReservationResource($event->reservation))->resolve();
        $payload['old_status'] = $event->oldStatus;
        $payload['new_status'] = $event->newStatus;

        WebhookService::dispatch('reservation.status_changed', $payload);

        if ($event->newStatus === 'cancelled') {
            WebhookService::dispatch('reservation.cancelled', $payload);
        }
    }
}

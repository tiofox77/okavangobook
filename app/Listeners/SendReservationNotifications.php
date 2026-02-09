<?php

namespace App\Listeners;

use App\Events\ReservationCreated;
use App\Events\ReservationStatusChanged;
use App\Mail\ReservationConfirmed;
use App\Mail\ReservationCancelled;
use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendReservationNotifications implements ShouldQueue
{
    public function __construct()
    {
        //
    }

    public function handle($event): void
    {
        if ($event instanceof ReservationCreated) {
            $this->handleReservationCreated($event);
        } elseif ($event instanceof ReservationStatusChanged) {
            $this->handleStatusChanged($event);
        }
    }

    private function handleReservationCreated(ReservationCreated $event)
    {
        $reservation = $event->reservation;
        
        Notification::createForUser(
            $reservation->user_id,
            'reservation_created',
            'Reserva Criada com Sucesso!',
            "Sua reserva no {$reservation->hotel->name} foi registrada. Aguardando confirmação.",
            'fas fa-check-circle',
            route('booking.details', $reservation->id)
        );

        if ($reservation->user && $reservation->user->email) {
            Mail::to($reservation->user->email)
                ->queue(new ReservationConfirmed($reservation));
        }
    }

    private function handleStatusChanged(ReservationStatusChanged $event)
    {
        $reservation = $event->reservation;
        
        if ($event->newStatus === 'confirmed') {
            Notification::createForUser(
                $reservation->user_id,
                'reservation_confirmed',
                'Reserva Confirmada!',
                "Sua reserva no {$reservation->hotel->name} foi confirmada!",
                'fas fa-calendar-check',
                route('booking.details', $reservation->id)
            );
        } elseif ($event->newStatus === 'cancelled') {
            Notification::createForUser(
                $reservation->user_id,
                'reservation_cancelled',
                'Reserva Cancelada',
                "Sua reserva no {$reservation->hotel->name} foi cancelada.",
                'fas fa-times-circle',
                route('booking.details', $reservation->id)
            );

            if ($reservation->user && $reservation->user->email) {
                Mail::to($reservation->user->email)
                    ->send(new ReservationCancelled($reservation));
            }
        }
    }
}

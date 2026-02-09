<?php

namespace App\Events;

use App\Models\Reservation;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ReservationStatusChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $reservation;
    public $oldStatus;
    public $newStatus;

    public function __construct(Reservation $reservation, $oldStatus, $newStatus)
    {
        $this->reservation = $reservation;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }
}

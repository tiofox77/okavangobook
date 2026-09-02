<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Concerns;

use App\Models\Hotel;
use App\Models\Reservation;
use Illuminate\Auth\Access\AuthorizationException;

/**
 * Guarda de propriedade para os painéis partilhados entre Admin e
 * Propriedade (/admin/hotels, /admin/rooms, /admin/reservations, …).
 *
 * As listagens já filtravam por dono, mas vários métodos aceitavam um id
 * vindo do browser e agiam sobre ele sem verificar nada — um utilizador
 * Propriedade conseguia apagar o hotel de outro, ou confirmar, cancelar e
 * fazer check-in de reservas alheias, bastando trocar o id no pedido.
 */
trait AuthorizesManagedHotels
{
    protected function isPlatformAdmin(): bool
    {
        return (bool) auth()->user()?->hasRole('Admin');
    }

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

    /** Hotéis que o utilizador atual pode gerir (Admin: todos). */
    protected function managedHotelIds(): array
    {
        if ($this->isPlatformAdmin()) {
            return Hotel::pluck('id')->map(fn ($id) => (int) $id)->all();
        }

        return auth()->user()?->managedHotels()->pluck('hotels.id')->map(fn ($id) => (int) $id)->all() ?? [];
    }

    /** A reserva tem de pertencer a um hotel do utilizador atual. */
    protected function authorizeManagedReservation(?int $reservationId): Reservation
    {
        $reserva = Reservation::findOrFail($reservationId);

        if ($this->isPlatformAdmin()) {
            return $reserva;
        }

        if (!in_array((int) $reserva->hotel_id, $this->managedHotelIds(), true)) {
            throw new AuthorizationException('Não tem permissão para gerir esta reserva.');
        }

        return $reserva;
    }

    /** Variante que aceita o id do hotel diretamente (pode ser nulo). */
    protected function authorizeManagedHotelId(?int $hotelId): void
    {
        if ($hotelId === null || $this->isPlatformAdmin()) {
            return;
        }

        $this->authorizeManagedHotel($hotelId);
    }
}

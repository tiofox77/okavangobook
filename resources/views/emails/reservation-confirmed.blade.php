<x-mail::message>
# Reserva Confirmada! 🎉

Olá {{ $reservation->user->name }},

Sua reserva foi **confirmada** com sucesso!

### Detalhes da Reserva

- **Hotel:** {{ $reservation->hotel->name }}
- **Check-in:** {{ $reservation->check_in->format('d/m/Y') }}
- **Check-out:** {{ $reservation->check_out->format('d/m/Y') }}
- **Hóspedes:** {{ $reservation->guests }}
- **Valor Total:** {{ number_format($reservation->total_price, 2) }} Kz

<x-mail::button :url="route('booking.details', $reservation->id)">
Ver Detalhes da Reserva
</x-mail::button>

Agradecemos a sua preferência!

Atenciosamente,<br>
{{ config('app.name') }}
</x-mail::message>

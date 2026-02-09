<x-mail::message>
# Reserva Cancelada

Olá {{ $reservation->user->name }},

Informamos que sua reserva foi **cancelada**.

### Detalhes da Reserva Cancelada

- **Hotel:** {{ $reservation->hotel->name }}
- **Check-in:** {{ $reservation->check_in->format('d/m/Y') }}
- **Check-out:** {{ $reservation->check_out->format('d/m/Y') }}
- **Valor:** {{ number_format($reservation->total_price, 2) }} Kz

Se o cancelamento foi feito por engano ou se tiver alguma dúvida, entre em contato conosco.

<x-mail::button :url="route('hotels.index')">
Fazer Nova Reserva
</x-mail::button>

Esperamos vê-lo em breve!

Atenciosamente,<br>
{{ config('app.name') }}
</x-mail::message>

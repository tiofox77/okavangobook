<x-mail::message>
# Alerta de Preço Atingido! 🏷️

Olá {{ $alert->user->name }},

Ótimas notícias! O preço do hotel que você estava acompanhando **baixou** e atingiu seu alerta!

### Detalhes do Alerta

- **Hotel:** {{ $alert->hotel->name }}
- **Preço Alvo:** {{ number_format($alert->target_price, 2) }} Kz
- **Preço Atual:** {{ number_format($alert->current_price, 2) }} Kz
- **Você economiza:** {{ number_format($alert->target_price - $alert->current_price, 2) }} Kz

@if($alert->roomType)
- **Tipo de Quarto:** {{ $alert->roomType->name }}
@endif

Não perca esta oportunidade! Reserve agora e aproveite o melhor preço.

<x-mail::button :url="route('hotel.details', $alert->hotel->slug ?? $alert->hotel_id)">
Ver Hotel e Reservar
</x-mail::button>

Boas viagens!

Atenciosamente,<br>
{{ config('app.name') }}
</x-mail::message>

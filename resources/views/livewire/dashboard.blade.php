@section('title', 'A minha conta')

@php
    $estados = [
        'pending' => ['Pendente', 'fa-clock', 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300'],
        'confirmed' => ['Confirmada', 'fa-circle-check', 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300'],
        'cancelled' => ['Cancelada', 'fa-circle-xmark', 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300'],
        'completed' => ['Concluída', 'fa-check-double', 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300'],
    ];
    $kz = fn ($v) => number_format((float) $v, 0, ',', '.') . ' Kz';
@endphp

<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    {{-- Saudação --}}
    <div class="bg-gradient-to-br from-primary via-blue-700 to-blue-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <p class="text-white/70 text-sm">{{ now()->translatedFormat('l, d \d\e F') }}</p>
                    <h1 class="text-3xl sm:text-4xl font-extrabold mt-1">
                        {{ __('Olá') }}, {{ Str::before($user->name, ' ') }}
                    </h1>
                    <p class="text-white/80 mt-1">{{ __('Aqui está o resumo da sua conta.') }}</p>
                </div>
                <a href="{{ route('search.results') }}"
                   class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-white text-primary font-semibold hover:bg-blue-50 transition-colors shadow-sm">
                    <i class="fas fa-magnifying-glass" aria-hidden="true"></i>{{ __('Nova pesquisa') }}
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-6 pb-12">
        {{-- Resumo --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-8">
            @foreach ([
                ['fa-suitcase-rolling', 'Reservas', $estatisticas['reservas'], null],
                ['fa-plane-departure', 'Próximas', $estatisticas['proximas'], null],
                ['fa-moon', 'Noites', $estatisticas['noites'], null],
                ['fa-wallet', 'Total gasto', $kz($estatisticas['gasto']), 'text-lg'],
            ] as [$icone, $rotulo, $valor, $tamanho])
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 sm:p-5 shadow-sm border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-2 text-gray-500 dark:text-gray-400 text-xs sm:text-sm mb-1.5">
                        <i class="fas {{ $icone }} text-primary" aria-hidden="true"></i>{{ __($rotulo) }}
                    </div>
                    <p class="{{ $tamanho ?? 'text-2xl' }} font-bold text-gray-900 dark:text-white">{{ $valor }}</p>
                </div>
            @endforeach
        </div>

        <div class="grid lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                {{-- Próxima viagem --}}
                @if($proximaViagem && $proximaViagem->hotel)
                    @php
                        $dias = (int) now()->startOfDay()->diffInDays($proximaViagem->check_in, false);
                    @endphp
                    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-gray-900 to-blue-900 text-white shadow-lg">
                        @if($proximaViagem->hotel->thumbnail)
                            <img src="{{ \App\Helpers\ImageHelper::getValidImage($proximaViagem->hotel->thumbnail, 'hotel') }}"
                                 alt="" class="absolute inset-0 w-full h-full object-cover opacity-25">
                        @endif
                        <div class="relative p-6 sm:p-7">
                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/20 backdrop-blur-sm text-xs font-semibold mb-4">
                                <i class="fas {{ $emCurso ? 'fa-bed' : 'fa-plane-departure' }}" aria-hidden="true"></i>
                                {{ $emCurso ? __('Estadia a decorrer') : __('Próxima viagem') }}
                            </span>

                            <h2 class="text-2xl sm:text-3xl font-bold mb-1">{{ $proximaViagem->hotel->name }}</h2>
                            @if($proximaViagem->hotel->location)
                                <p class="text-white/75 mb-4">
                                    <i class="fas fa-location-dot mr-1.5" aria-hidden="true"></i>{{ $proximaViagem->hotel->location->name }}
                                </p>
                            @endif

                            <div class="flex flex-wrap gap-x-6 gap-y-3 text-sm mb-5">
                                <div>
                                    <span class="block text-white/60 text-xs">{{ __('Check-in') }}</span>
                                    <span class="font-semibold">{{ $proximaViagem->check_in?->translatedFormat('d M Y') ?? '—' }}</span>
                                </div>
                                <div>
                                    <span class="block text-white/60 text-xs">{{ __('Check-out') }}</span>
                                    <span class="font-semibold">{{ $proximaViagem->check_out?->translatedFormat('d M Y') ?? '—' }}</span>
                                </div>
                                <div>
                                    <span class="block text-white/60 text-xs">{{ __('Hóspedes') }}</span>
                                    <span class="font-semibold">{{ $proximaViagem->guests }}</span>
                                </div>
                                @if(!$emCurso)
                                    <div>
                                        <span class="block text-white/60 text-xs">{{ __('Faltam') }}</span>
                                        <span class="font-semibold">
                                            {{ $dias === 0 ? __('é hoje!') : trans_choice(':count dia|:count dias', max($dias, 0), ['count' => max($dias, 0)]) }}
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <div class="flex flex-wrap gap-3">
                                <a href="{{ route('booking.details', $proximaViagem->id) }}"
                                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-white text-primary font-semibold hover:bg-blue-50 transition-colors">
                                    <i class="fas fa-file-lines text-sm" aria-hidden="true"></i>{{ __('Ver reserva') }}
                                </a>
                                <a href="{{ route('hotel.details', $proximaViagem->hotel->slug) }}"
                                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-white/15 hover:bg-white/25 backdrop-blur-sm font-medium transition-colors">
                                    <i class="fas fa-hotel text-sm" aria-hidden="true"></i>{{ __('Ver alojamento') }}
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Reservas --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">{{ __('As minhas reservas') }}</h2>
                        @if($bookings->isNotEmpty())
                            <a href="{{ route('my.bookings') }}" class="text-sm text-primary font-medium hover:underline">
                                {{ __('Ver todas') }} <i class="fas fa-arrow-right text-xs ml-1" aria-hidden="true"></i>
                            </a>
                        @endif
                    </div>

                    @if($bookings->isNotEmpty())
                        <ul class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach($bookings as $booking)
                                @php
                                    $e = $estados[$booking->status] ?? $estados['pending'];
                                    $futura = $booking->check_in && $booking->check_in->startOfDay()->gte(now()->startOfDay());
                                @endphp
                                <li class="p-4 sm:p-5 hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors">
                                    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                                        <div class="h-14 w-14 rounded-xl overflow-hidden bg-gray-100 dark:bg-gray-700 flex-shrink-0">
                                            @if($booking->hotel?->thumbnail)
                                                <img src="{{ \App\Helpers\ImageHelper::getValidImage($booking->hotel->thumbnail, 'hotel') }}"
                                                     alt="" loading="lazy" class="w-full h-full object-cover">
                                            @else
                                                <span class="w-full h-full flex items-center justify-center text-gray-400">
                                                    <i class="fas fa-hotel" aria-hidden="true"></i>
                                                </span>
                                            @endif
                                        </div>

                                        <div class="min-w-0 flex-1">
                                            <div class="flex flex-wrap items-center gap-2 mb-1">
                                                <h3 class="font-semibold text-gray-900 dark:text-white truncate">
                                                    {{ $booking->hotel?->name ?? __('Alojamento indisponível') }}
                                                </h3>
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium {{ $e[2] }}">
                                                    <i class="fas {{ $e[1] }}" aria-hidden="true"></i>{{ __($e[0]) }}
                                                </span>
                                                @if($futura && $booking->status !== 'cancelled')
                                                    <span class="text-xs text-primary font-medium">{{ __('futura') }}</span>
                                                @endif
                                            </div>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                <i class="far fa-calendar mr-1" aria-hidden="true"></i>
                                                @if($booking->check_in && $booking->check_out)
                                                    {{ $booking->check_in->format('d/m/Y') }} — {{ $booking->check_out->format('d/m/Y') }}
                                                @else
                                                    {{ __('Datas por definir') }}
                                                @endif
                                                <span class="mx-2">·</span>
                                                <i class="far fa-user mr-1" aria-hidden="true"></i>{{ trans_choice(':count hóspede|:count hóspedes', $booking->guests, ['count' => $booking->guests]) }}
                                            </p>
                                        </div>

                                        <div class="flex items-center justify-between sm:flex-col sm:items-end gap-2 flex-shrink-0">
                                            <span class="font-bold text-gray-900 dark:text-white">{{ $kz($booking->total_price) }}</span>
                                            <a href="{{ route('booking.details', $booking->id) }}"
                                               class="text-sm text-primary font-medium hover:underline whitespace-nowrap">
                                                {{ __('Detalhes') }} <i class="fas fa-chevron-right text-xs" aria-hidden="true"></i>
                                            </a>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-center py-14 px-6">
                            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center">
                                <i class="fas fa-suitcase-rolling text-primary text-2xl" aria-hidden="true"></i>
                            </div>
                            <h3 class="font-bold text-gray-900 dark:text-white mb-1">{{ __('Ainda não tem reservas') }}</h3>
                            <p class="text-gray-500 dark:text-gray-400 text-sm mb-5">{{ __('Encontre o alojamento certo para a sua próxima viagem.') }}</p>
                            <a href="{{ route('search.results') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-white rounded-lg font-medium hover:bg-blue-800 transition-colors">
                                <i class="fas fa-magnifying-glass text-sm" aria-hidden="true"></i>{{ __('Pesquisar alojamentos') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Coluna lateral --}}
            <div class="space-y-6">
                {{-- Perfil --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="h-12 w-12 rounded-full bg-primary/10 text-primary flex items-center justify-center text-lg font-bold">
                            {{ Str::upper(Str::substr($user->name, 0, 1)) }}
                        </span>
                        <div class="min-w-0">
                            <p class="font-semibold text-gray-900 dark:text-white truncate">{{ $user->name }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400 truncate">{{ $user->email }}</p>
                        </div>
                    </div>

                    @if($perfilCompleto < 100)
                        <div class="mb-4">
                            <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 mb-1.5">
                                <span>{{ __('Perfil completo') }}</span>
                                <span class="font-semibold text-gray-700 dark:text-gray-200">{{ $perfilCompleto }}%</span>
                            </div>
                            <div class="h-2 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden">
                                <div class="h-full rounded-full bg-primary transition-all" style="width: {{ $perfilCompleto }}%"></div>
                            </div>
                        </div>
                    @endif

                    <a href="{{ route('profile') }}"
                       class="block w-full text-center px-4 py-2.5 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-medium hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                        <i class="fas fa-user-pen mr-2 text-sm" aria-hidden="true"></i>{{ __('Editar perfil') }}
                    </a>
                </div>

                {{-- Atalhos --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <h2 class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 font-bold text-gray-900 dark:text-white">
                        {{ __('Atalhos') }}
                    </h2>
                    <ul class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach ([
                            [route('my.bookings'), 'fa-suitcase', 'As minhas reservas', $estatisticas['reservas']],
                            [route('price-alerts'), 'fa-bell', 'Alertas de preço', $alertas],
                            [route('hotels.compare'), 'fa-scale-balanced', 'Comparar alojamentos', null],
                            [route('destinations'), 'fa-map-location-dot', 'Explorar destinos', null],
                            [route('articles'), 'fa-book-open', 'Guias de viagem', null],
                        ] as [$url, $icone, $rotulo, $contagem])
                            <li>
                                <a href="{{ $url }}" class="flex items-center gap-3 px-6 py-3.5 hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors group">
                                    <span class="h-9 w-9 rounded-lg bg-primary/10 text-primary flex items-center justify-center flex-shrink-0">
                                        <i class="fas {{ $icone }} text-sm" aria-hidden="true"></i>
                                    </span>
                                    <span class="flex-1 text-gray-700 dark:text-gray-200 font-medium">{{ __($rotulo) }}</span>
                                    @if($contagem)
                                        <span class="px-2 py-0.5 rounded-full bg-gray-100 dark:bg-gray-700 text-xs font-semibold text-gray-600 dark:text-gray-300">{{ $contagem }}</span>
                                    @endif
                                    <i class="fas fa-chevron-right text-xs text-gray-300 group-hover:text-primary transition-colors" aria-hidden="true"></i>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Atividade --}}
                @if($favoritos || $avaliacoes)
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                        <h2 class="font-bold text-gray-900 dark:text-white mb-4">{{ __('A sua atividade') }}</h2>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="text-center p-3 rounded-xl bg-rose-50 dark:bg-rose-900/20">
                                <i class="fas fa-heart text-rose-500 mb-1" aria-hidden="true"></i>
                                <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $favoritos }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('favoritos') }}</p>
                            </div>
                            <div class="text-center p-3 rounded-xl bg-amber-50 dark:bg-amber-900/20">
                                <i class="fas fa-star text-amber-500 mb-1" aria-hidden="true"></i>
                                <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $avaliacoes }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('avaliações') }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

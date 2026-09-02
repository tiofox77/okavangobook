@section('title', 'Blog & Guias de Viagem em Angola')
@section('meta_description', 'Dicas, guias e roteiros para explorar Angola. Leia os nossos artigos sobre destinos, gastronomia e cultura angolana.')

@php
    // classes completas e literais: o Tailwind não deteta nomes montados por interpolação
    $categorias = [
        'destino' => ['Destinos', 'fa-map-location-dot', 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300'],
        'guia' => ['Guias de Viagem', 'fa-compass', 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300'],
        'dica' => ['Dicas', 'fa-lightbulb', 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300'],
    ];
    $destaque = $articles->currentPage() === 1 && $articles->isNotEmpty() ? $articles->first() : null;
    $restantes = $destaque ? $articles->slice(1) : $articles;
@endphp

<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    {{-- Cabeçalho --}}
    <div class="bg-gradient-to-br from-primary via-blue-700 to-blue-900 text-white">
        <div class="max-w-7xl mx-auto px-4 py-14 sm:py-20">
            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/15 backdrop-blur-sm text-xs font-medium mb-4">
                <i class="fas fa-book-open" aria-hidden="true"></i> Blog KiandaStay
            </span>
            <h1 class="text-4xl sm:text-5xl font-extrabold tracking-tight mb-3">
                {{ __('Guias de Viagem por Angola') }}
            </h1>
            <p class="text-lg text-white/85 max-w-2xl">
                {{ __('Roteiros, dicas práticas e histórias das 21 províncias — para planear a próxima viagem com quem conhece o país.') }}
            </p>

            {{-- Pesquisa --}}
            <div class="mt-8 max-w-xl relative">
                <i class="fas fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-white/60" aria-hidden="true"></i>
                <input wire:model.live.debounce.300ms="search" type="text"
                       placeholder="{{ __('Procurar artigo, destino ou tema…') }}"
                       aria-label="{{ __('Procurar artigos') }}"
                       class="w-full pl-11 pr-4 py-3 rounded-xl bg-white/15 backdrop-blur-sm border border-white/25 text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-white/50 focus:bg-white/20">
                <div wire:loading wire:target="search" class="absolute right-4 top-1/2 -translate-y-1/2">
                    <i class="fas fa-circle-notch fa-spin text-white/70" aria-hidden="true"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-10">
        {{-- Categorias --}}
        <div class="flex flex-wrap items-center gap-2 mb-8">
            <button wire:click="$set('category', 'all')"
                    class="px-4 py-2 rounded-full text-sm font-medium transition-colors {{ $category === 'all' ? 'bg-primary text-white shadow-sm' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 border border-gray-200 dark:border-gray-700' }}">
                {{ __('Tudo') }}
            </button>
            @foreach($categorias as $chave => $cat)
                <button wire:click="$set('category', '{{ $chave }}')"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-medium transition-colors {{ $category === $chave ? 'bg-primary text-white shadow-sm' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 border border-gray-200 dark:border-gray-700' }}">
                    <i class="fas {{ $cat[1] }} text-xs" aria-hidden="true"></i>{{ __($cat[0]) }}
                </button>
            @endforeach

            @if($search || $category !== 'all')
                <button wire:click="limparFiltros" class="ml-auto text-sm text-gray-500 hover:text-primary inline-flex items-center gap-1.5">
                    <i class="fas fa-times text-xs" aria-hidden="true"></i>{{ __('Limpar filtros') }}
                </button>
            @endif
        </div>

        @if($articles->isNotEmpty())
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                {{ trans_choice(':count artigo|:count artigos', $articles->total(), ['count' => $articles->total()]) }}
                @if($search) {{ __('para') }} <span class="font-medium text-gray-700 dark:text-gray-200">“{{ $search }}”</span> @endif
            </p>
        @endif

        {{-- Artigo em destaque --}}
        @if($destaque)
            <a href="{{ route('article.details', $destaque->slug) }}"
               class="group grid md:grid-cols-2 gap-0 mb-10 bg-white dark:bg-gray-800 rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-shadow border border-gray-100 dark:border-gray-700">
                <div class="relative h-60 md:h-full min-h-[16rem] overflow-hidden bg-gray-100 dark:bg-gray-700">
                    @if($destaque->featured_image)
                        <img src="{{ $destaque->featured_image }}" alt="{{ $destaque->title }}"
                             class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-primary to-blue-800 flex items-center justify-center">
                            <i class="fas fa-mountain-sun text-white/40 text-7xl" aria-hidden="true"></i>
                        </div>
                    @endif
                    <span class="absolute top-4 left-4 px-3 py-1 rounded-full bg-white/95 text-primary text-xs font-bold shadow">
                        <i class="fas fa-star mr-1" aria-hidden="true"></i>{{ __('Em destaque') }}
                    </span>
                </div>
                <div class="p-6 sm:p-8 flex flex-col justify-center">
                    @php $c = $categorias[$destaque->category] ?? null; @endphp
                    @if($c)
                        <span class="inline-flex items-center gap-1.5 self-start px-2.5 py-1 rounded-full {{ $c[2] }} text-xs font-semibold mb-3">
                            <i class="fas {{ $c[1] }}" aria-hidden="true"></i>{{ __($c[0]) }}
                        </span>
                    @endif
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mb-3 leading-tight group-hover:text-primary transition-colors">
                        {{ $destaque->title }}
                    </h2>
                    <p class="text-gray-600 dark:text-gray-300 mb-5 line-clamp-3">
                        {{ $destaque->excerpt ?? Str::limit(strip_tags($destaque->content), 200) }}
                    </p>
                    <div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-sm text-gray-500 dark:text-gray-400">
                        <span><i class="fas fa-user-pen mr-1.5" aria-hidden="true"></i>{{ $destaque->author->name ?? 'KiandaStay' }}</span>
                        @if($destaque->published_at)
                            <span><i class="far fa-calendar mr-1.5" aria-hidden="true"></i>{{ $destaque->published_at->translatedFormat('d M Y') }}</span>
                        @endif
                        @if($destaque->read_time)
                            <span><i class="far fa-clock mr-1.5" aria-hidden="true"></i>{{ $destaque->read_time }} min</span>
                        @endif
                    </div>
                    <span class="mt-5 inline-flex items-center gap-2 text-primary font-semibold group-hover:gap-3 transition-all">
                        {{ __('Ler artigo') }} <i class="fas fa-arrow-right text-sm" aria-hidden="true"></i>
                    </span>
                </div>
            </a>
        @endif

        {{-- Grelha --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($restantes as $article)
                @php $c = $categorias[$article->category] ?? null; @endphp
                <a href="{{ route('article.details', $article->slug) }}"
                   wire:key="artigo-{{ $article->id }}"
                   class="group flex flex-col bg-white dark:bg-gray-800 rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1 border border-gray-100 dark:border-gray-700">
                    <div class="relative h-44 overflow-hidden bg-gray-100 dark:bg-gray-700">
                        @if($article->featured_image)
                            <img src="{{ $article->featured_image }}" alt="{{ $article->title }}" loading="lazy"
                                 class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-primary/90 to-blue-800 flex items-center justify-center">
                                <i class="fas {{ $c[1] ?? 'fa-newspaper' }} text-white/40 text-5xl" aria-hidden="true"></i>
                            </div>
                        @endif
                        @if($c)
                            <span class="absolute top-3 left-3 inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full {{ $c[2] }} text-xs font-semibold shadow-sm">
                                <i class="fas {{ $c[1] }}" aria-hidden="true"></i>{{ __($c[0]) }}
                            </span>
                        @endif
                        @if($article->read_time)
                            <span class="absolute bottom-3 right-3 px-2 py-0.5 rounded-full bg-black/55 text-white text-xs backdrop-blur-sm">
                                {{ $article->read_time }} min
                            </span>
                        @endif
                    </div>

                    <div class="p-5 flex flex-col flex-1">
                        <h3 class="font-bold text-lg text-gray-900 dark:text-white mb-2 line-clamp-2 group-hover:text-primary transition-colors">
                            {{ $article->title }}
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm line-clamp-3 flex-1">
                            {{ $article->excerpt ?? Str::limit(strip_tags($article->content), 120) }}
                        </p>
                        <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-100 dark:border-gray-700 text-xs text-gray-500 dark:text-gray-400">
                            <span class="truncate">
                                <i class="fas fa-user-pen mr-1" aria-hidden="true"></i>{{ $article->author->name ?? 'KiandaStay' }}
                            </span>
                            @if($article->published_at)
                                <span class="flex-shrink-0">{{ $article->published_at->translatedFormat('d M Y') }}</span>
                            @endif
                        </div>
                    </div>
                </a>
            @empty
                @if(!$destaque)
                    {{-- Estado vazio --}}
                    <div class="col-span-full">
                        <div class="text-center py-16 px-6 bg-white dark:bg-gray-800 rounded-2xl border border-dashed border-gray-300 dark:border-gray-600">
                            <div class="w-20 h-20 mx-auto mb-5 rounded-full bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center">
                                <i class="fas {{ $search || $category !== 'all' ? 'fa-magnifying-glass' : 'fa-feather-pointed' }} text-primary text-3xl" aria-hidden="true"></i>
                            </div>
                            @if($search || $category !== 'all')
                                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ __('Sem resultados') }}</h2>
                                <p class="text-gray-600 dark:text-gray-400 mb-6">
                                    {{ __('Não encontrámos artigos para esta procura.') }}
                                </p>
                                <button wire:click="limparFiltros" class="px-5 py-2.5 bg-primary text-white rounded-lg font-medium hover:bg-blue-800 transition-colors">
                                    {{ __('Ver todos os artigos') }}
                                </button>
                            @else
                                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ __('Os primeiros guias estão a caminho') }}</h2>
                                <p class="text-gray-600 dark:text-gray-400 max-w-md mx-auto mb-6">
                                    {{ __('Estamos a preparar roteiros e dicas para as 21 províncias. Entretanto, explore os destinos e encontre onde ficar.') }}
                                </p>
                                <div class="flex flex-wrap justify-center gap-3">
                                    <a href="{{ route('destinations') }}" class="px-5 py-2.5 bg-primary text-white rounded-lg font-medium hover:bg-blue-800 transition-colors">
                                        <i class="fas fa-map-location-dot mr-2" aria-hidden="true"></i>{{ __('Explorar destinos') }}
                                    </a>
                                    <a href="{{ route('search.results') }}" class="px-5 py-2.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg font-medium hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                                        <i class="fas fa-hotel mr-2" aria-hidden="true"></i>{{ __('Ver alojamentos') }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            @endforelse
        </div>

        @if($articles->hasPages())
            <div class="mt-10">
                {{ $articles->links() }}
            </div>
        @endif
    </div>
</div>

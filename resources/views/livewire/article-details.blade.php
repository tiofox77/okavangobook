@section('title', $article->title)
@section('meta_description', Str::limit(strip_tags($article->excerpt ?: $article->content), 155))

@php
    $categorias = [
        'destino' => ['Destinos', 'fa-map-location-dot', 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300'],
        'guia' => ['Guias de Viagem', 'fa-compass', 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300'],
        'dica' => ['Dicas', 'fa-lightbulb', 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300'],
    ];
    $cat = $categorias[$article->category] ?? null;

    // O conteúdo pode vir em HTML (admin/Agent API) ou em texto simples.
    // Antes era sempre escapado com nl2br(e(...)) e os artigos em HTML
    // apareciam com as tags à vista. Agora: HTML é renderizado depois de
    // removidos script/iframe/style e atributos on*; texto simples leva nl2br.
    $bruto = (string) $article->content;
    $pareceHtml = $bruto !== strip_tags($bruto);
    if ($pareceHtml) {
        $limpo = preg_replace('#<\s*(script|iframe|style|object|embed)\b[^>]*>.*?<\s*/\s*\1\s*>#is', '', $bruto);
        $limpo = preg_replace('#\s+on[a-z]+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)#i', '', $limpo);
        $corpo = $limpo;
    } else {
        $corpo = nl2br(e($bruto));
    }
@endphp

<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    {{-- Cabeçalho com imagem --}}
    <div class="relative">
        @if($article->featured_image)
            <div class="h-72 sm:h-96 w-full overflow-hidden bg-gray-800">
                <img src="{{ $article->featured_image }}" alt="{{ $article->title }}"
                     class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-black/20"></div>
            </div>
        @else
            <div class="h-56 sm:h-72 w-full bg-gradient-to-br from-primary via-blue-700 to-blue-900"></div>
        @endif

        <div class="absolute inset-x-0 bottom-0">
            <div class="max-w-4xl mx-auto px-4 pb-8">
                <nav class="text-sm text-white/70 mb-3" aria-label="Navegação">
                    <a href="{{ route('home') }}" class="hover:text-white">{{ __('Início') }}</a>
                    <span class="mx-2">/</span>
                    <a href="{{ route('articles') }}" class="hover:text-white">{{ __('Blog') }}</a>
                </nav>
                @if($cat)
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full {{ $cat[2] }} text-xs font-semibold mb-3">
                        <i class="fas {{ $cat[1] }}" aria-hidden="true"></i>{{ __($cat[0]) }}
                    </span>
                @endif
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white leading-tight drop-shadow-lg">
                    {{ $article->title }}
                </h1>
            </div>
        </div>
    </div>

    <article class="max-w-4xl mx-auto px-4 -mt-2 pb-16">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 sm:p-10">
            {{-- Metadados --}}
            <div class="flex flex-wrap items-center gap-x-5 gap-y-2 text-sm text-gray-500 dark:text-gray-400 pb-6 mb-6 border-b border-gray-100 dark:border-gray-700">
                <span class="inline-flex items-center gap-2">
                    <span class="h-8 w-8 rounded-full bg-primary/10 text-primary flex items-center justify-center">
                        <i class="fas fa-user-pen text-xs" aria-hidden="true"></i>
                    </span>
                    <span class="font-medium text-gray-700 dark:text-gray-200">{{ $article->author->name ?? 'KiandaStay' }}</span>
                </span>
                @if($article->published_at)
                    <span><i class="far fa-calendar mr-1.5" aria-hidden="true"></i>{{ $article->published_at->translatedFormat('d \d\e F \d\e Y') }}</span>
                @endif
                @if($article->read_time)
                    <span><i class="far fa-clock mr-1.5" aria-hidden="true"></i>{{ $article->read_time }} {{ __('min de leitura') }}</span>
                @endif
                <span><i class="far fa-eye mr-1.5" aria-hidden="true"></i>{{ number_format($article->views, 0, ',', '.') }}</span>
            </div>

            {{-- Resumo --}}
            @if($article->excerpt)
                <p class="text-lg sm:text-xl text-gray-700 dark:text-gray-200 leading-relaxed border-l-4 border-primary pl-5 mb-8">
                    {{ $article->excerpt }}
                </p>
            @endif

            {{-- Corpo --}}
            <div class="prose prose-lg max-w-none dark:prose-invert
                        prose-headings:font-bold prose-headings:text-gray-900 dark:prose-headings:text-white
                        prose-h2:text-2xl prose-h2:mt-10 prose-h2:mb-3
                        prose-p:text-gray-700 dark:prose-p:text-gray-300 prose-p:leading-relaxed
                        prose-a:text-primary prose-a:no-underline hover:prose-a:underline
                        prose-strong:text-gray-900 dark:prose-strong:text-white
                        prose-li:text-gray-700 dark:prose-li:text-gray-300">
                {!! $corpo !!}
            </div>

            {{-- Tags --}}
            @if($article->tags && count($article->tags) > 0)
                <div class="mt-10 pt-6 border-t border-gray-100 dark:border-gray-700 flex flex-wrap items-center gap-2">
                    <span class="text-sm text-gray-500 dark:text-gray-400 mr-1">{{ __('Temas:') }}</span>
                    @foreach($article->tags as $tag)
                        <span class="px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-sm rounded-full">
                            #{{ $tag }}
                        </span>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Chamada para ação: reservar nos destinos do artigo --}}
        @php
            $destinos = collect($article->locations ?? [])->filter()->take(3);
        @endphp
        @if($destinos->isNotEmpty())
            <div class="mt-8 bg-gradient-to-br from-primary to-blue-800 rounded-2xl p-6 sm:p-8 text-white">
                <h2 class="text-xl sm:text-2xl font-bold mb-2">{{ __('Pronto para ir?') }}</h2>
                <p class="text-white/85 mb-5">{{ __('Encontre alojamento nos destinos deste artigo e compare preços.') }}</p>
                <div class="flex flex-wrap gap-2">
                    @foreach($destinos as $slugDestino)
                        <a href="{{ route('location.details', ['province' => $slugDestino]) }}"
                           class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-white/15 hover:bg-white/25 backdrop-blur-sm font-medium transition-colors">
                            <i class="fas fa-map-marker-alt text-sm" aria-hidden="true"></i>
                            {{ \App\Models\Location::provinceName($slugDestino) }}
                        </a>
                    @endforeach
                    <a href="{{ route('search.results') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-white text-primary font-semibold hover:bg-blue-50 transition-colors">
                        <i class="fas fa-hotel text-sm" aria-hidden="true"></i>{{ __('Ver todos os alojamentos') }}
                    </a>
                </div>
            </div>
        @endif

        {{-- Artigos relacionados --}}
        @if($relatedArticles->count() > 0)
            <div class="mt-12">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">{{ __('Continue a ler') }}</h2>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    @foreach($relatedArticles as $related)
                        @php $rc = $categorias[$related->category] ?? null; @endphp
                        <a href="{{ route('article.details', $related->slug) }}"
                           class="group flex flex-col bg-white dark:bg-gray-800 rounded-xl overflow-hidden shadow-sm hover:shadow-lg transition-all hover:-translate-y-1 border border-gray-100 dark:border-gray-700">
                            <div class="h-32 overflow-hidden bg-gray-100 dark:bg-gray-700">
                                @if($related->featured_image)
                                    <img src="{{ $related->featured_image }}" alt="{{ $related->title }}" loading="lazy"
                                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-primary/90 to-blue-800 flex items-center justify-center">
                                        <i class="fas {{ $rc[1] ?? 'fa-newspaper' }} text-white/40 text-3xl" aria-hidden="true"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="p-4 flex-1 flex flex-col">
                                <h3 class="font-bold text-gray-900 dark:text-white line-clamp-2 mb-1.5 group-hover:text-primary transition-colors">
                                    {{ $related->title }}
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2">
                                    {{ $related->excerpt }}
                                </p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="mt-10 text-center">
            <a href="{{ route('articles') }}" class="inline-flex items-center gap-2 text-primary font-medium hover:gap-3 transition-all">
                <i class="fas fa-arrow-left text-sm" aria-hidden="true"></i>{{ __('Voltar ao blog') }}
            </a>
        </div>
    </article>
</div>

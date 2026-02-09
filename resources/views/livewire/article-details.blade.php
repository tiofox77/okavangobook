<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <article class="max-w-4xl mx-auto px-4">
        @if($article->featured_image)
            <img src="{{ $article->featured_image }}" alt="{{ $article->title }}" class="w-full h-96 object-cover rounded-lg mb-6">
        @endif

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-8">
            <div class="mb-6">
                <span class="px-3 py-1 text-sm rounded-full 
                    {{ $article->category === 'destino' ? 'bg-blue-100 text-blue-800' : '' }}
                    {{ $article->category === 'guia' ? 'bg-green-100 text-green-800' : '' }}
                    {{ $article->category === 'dica' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                    {{ ucfirst($article->category) }}
                </span>
            </div>

            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">
                {{ $article->title }}
            </h1>

            <div class="flex items-center gap-6 text-sm text-gray-600 dark:text-gray-400 mb-6 pb-6 border-b">
                <span>
                    <i class="fas fa-user mr-2"></i>
                    {{ $article->author->name ?? 'Admin' }}
                </span>
                <span>
                    <i class="far fa-calendar mr-2"></i>
                    {{ $article->published_at->format('d/m/Y') }}
                </span>
                <span>
                    <i class="far fa-clock mr-2"></i>
                    {{ $article->read_time }} min de leitura
                </span>
                <span>
                    <i class="fas fa-eye mr-2"></i>
                    {{ $article->views }} visualizações
                </span>
            </div>

            @if($article->excerpt)
                <div class="text-xl text-gray-700 dark:text-gray-300 mb-6 italic border-l-4 border-blue-600 pl-4">
                    {{ $article->excerpt }}
                </div>
            @endif

            <div class="prose prose-lg max-w-none dark:prose-invert">
                {!! nl2br(e($article->content)) !!}
            </div>

            @if($article->tags && count($article->tags) > 0)
                <div class="mt-8 pt-6 border-t">
                    <h3 class="font-bold mb-3">Tags:</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($article->tags as $tag)
                            <span class="px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm rounded-full">
                                #{{ $tag }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        @if($relatedArticles->count() > 0)
            <div class="mt-12">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Artigos Relacionados</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach($relatedArticles as $related)
                        <a href="{{ route('article.details', $related->slug) }}" class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden hover:shadow-xl transition">
                            <div class="p-4">
                                <h3 class="font-bold mb-2 text-gray-900 dark:text-white line-clamp-2">
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
    </article>
</div>

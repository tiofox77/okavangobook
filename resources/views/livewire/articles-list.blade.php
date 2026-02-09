<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="max-w-7xl mx-auto px-4">
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">
                <i class="fas fa-book-open text-blue-600 mr-3"></i>
                Blog & Guias de Viagem
            </h1>
            <p class="text-gray-600 dark:text-gray-400">Descubra destinos, dicas e guias personalizados</p>
        </div>

        <div class="flex flex-col md:flex-row gap-4 mb-6">
            <input wire:model.live="search" type="text" placeholder="Buscar artigos..." class="flex-1 px-4 py-2 border rounded-lg">
            <select wire:model.live="category" class="px-4 py-2 border rounded-lg">
                <option value="all">Todas Categorias</option>
                <option value="destino">Destinos</option>
                <option value="guia">Guias de Viagem</option>
                <option value="dica">Dicas</option>
            </select>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($articles as $article)
                <a href="{{ route('article.details', $article->slug) }}" class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden hover:shadow-xl transition transform hover:-translate-y-1">
                    @if($article->featured_image)
                        <img src="{{ $article->featured_image }}" alt="{{ $article->title }}" class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                            <i class="fas fa-newspaper text-white text-6xl opacity-50"></i>
                        </div>
                    @endif
                    
                    <div class="p-4">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="px-2 py-1 text-xs rounded-full 
                                {{ $article->category === 'destino' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $article->category === 'guia' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $article->category === 'dica' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                {{ ucfirst($article->category) }}
                            </span>
                            @if($article->read_time)
                                <span class="text-xs text-gray-500">
                                    <i class="far fa-clock mr-1"></i>{{ $article->read_time }} min
                                </span>
                            @endif
                        </div>
                        
                        <h3 class="font-bold text-lg mb-2 text-gray-900 dark:text-white line-clamp-2">
                            {{ $article->title }}
                        </h3>
                        
                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-3 line-clamp-3">
                            {{ $article->excerpt ?? Str::limit(strip_tags($article->content), 100) }}
                        </p>
                        
                        <div class="flex items-center justify-between text-xs text-gray-500">
                            <span>
                                <i class="fas fa-user mr-1"></i>
                                {{ $article->author->name ?? 'Admin' }}
                            </span>
                            <span>
                                <i class="fas fa-eye mr-1"></i>
                                {{ $article->views }} visualizações
                            </span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-3 text-center py-12">
                    <i class="fas fa-newspaper text-gray-400 text-6xl mb-4"></i>
                    <p class="text-gray-600 dark:text-gray-400">Nenhum artigo encontrado</p>
                </div>
            @endforelse
        </div>

        @if($articles->hasPages())
            <div class="mt-8">
                {{ $articles->links() }}
            </div>
        @endif
    </div>
</div>

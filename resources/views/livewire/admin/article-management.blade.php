<div class="p-6">
    {{-- Do your work, then step back. --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Gestão de Artigos</h1>
        <button wire:click="create" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
            <i class="fas fa-plus mr-2"></i> Novo Artigo
        </button>
    </div>

    @if (session()->has('message'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('message') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-md p-4 mb-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <input wire:model.live="search" type="text" placeholder="Buscar artigos..." class="px-4 py-2 border rounded-lg">
            <select wire:model.live="filterCategory" class="px-4 py-2 border rounded-lg">
                <option value="all">Todas Categorias</option>
                <option value="destino">Destinos</option>
                <option value="guia">Guias de Viagem</option>
                <option value="dica">Dicas</option>
            </select>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Título</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Categoria</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Autor</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Visualizações</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ações</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($articles as $article)
                    <tr>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">{{ $article->title }}</div>
                            <div class="text-sm text-gray-500">{{ Str::limit($article->excerpt, 50) }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full 
                                {{ $article->category === 'destino' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $article->category === 'guia' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $article->category === 'dica' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                {{ ucfirst($article->category) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm">{{ $article->author->name ?? 'Admin' }}</td>
                        <td class="px-6 py-4 text-sm">{{ $article->views }}</td>
                        <td class="px-6 py-4">
                            <button wire:click="togglePublished({{ $article->id }})" 
                                class="px-2 py-1 text-xs rounded {{ $article->is_published ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $article->is_published ? 'Publicado' : 'Rascunho' }}
                            </button>
                        </td>
                        <td class="px-6 py-4">
                            <button wire:click="edit({{ $article->id }})" class="text-blue-600 hover:text-blue-800 mr-3">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button wire:click="delete({{ $article->id }})" onclick="return confirm('Remover artigo?')" class="text-red-600 hover:text-red-800">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">Nenhum artigo encontrado</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $articles->links() }}
    </div>

    @if($showModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" wire:click="closeModal">
            <div class="bg-white rounded-lg p-6 w-full max-w-4xl max-h-screen overflow-y-auto" wire:click.stop>
                <h2 class="text-xl font-bold mb-4">{{ $articleId ? 'Editar' : 'Novo' }} Artigo</h2>
                
                <div class="space-y-4">
                    <div>
                        <label class="block mb-2">Título *</label>
                        <input wire:model="title" type="text" class="w-full px-4 py-2 border rounded-lg">
                        @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block mb-2">Categoria *</label>
                        <select wire:model="category" class="w-full px-4 py-2 border rounded-lg">
                            <option value="">Selecione</option>
                            <option value="destino">Destino</option>
                            <option value="guia">Guia de Viagem</option>
                            <option value="dica">Dica</option>
                        </select>
                        @error('category') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block mb-2">Resumo</label>
                        <textarea wire:model="excerpt" rows="2" class="w-full px-4 py-2 border rounded-lg"></textarea>
                    </div>

                    <div>
                        <label class="block mb-2">Conteúdo *</label>
                        <textarea wire:model="content" rows="10" class="w-full px-4 py-2 border rounded-lg"></textarea>
                        @error('content') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="flex items-center">
                            <input wire:model="is_published" type="checkbox" class="mr-2">
                            Publicar imediatamente
                        </label>
                    </div>
                </div>

                <div class="flex justify-end gap-2 mt-6">
                    <button wire:click="closeModal" class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">Cancelar</button>
                    <button wire:click="save" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Salvar</button>
                </div>
            </div>
        </div>
    @endif
</div>

<div class="py-8">
    @if (session()->has('message'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition class="mb-6 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 px-4 py-3 rounded relative">
            {{ session('message') }}
            <button @click="show = false" class="absolute top-2 right-2 text-green-700 dark:text-green-300 hover:text-green-900">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <!-- Estatísticas de Avaliações -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
        <div class="flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="text-center md:text-left">
                <div class="flex items-center gap-2 mb-2">
                    <span class="text-5xl font-bold text-gray-900 dark:text-white">{{ $averageRating }}</span>
                    <div class="flex text-yellow-400">
                        @for ($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star {{ $i <= round($averageRating) ? '' : 'opacity-30' }}"></i>
                        @endfor
                    </div>
                </div>
                <p class="text-gray-600 dark:text-gray-400">{{ $totalReviews }} {{ $totalReviews == 1 ? 'avaliação' : 'avaliações' }}</p>
            </div>

            <div class="flex-1 max-w-md w-full">
                @foreach($ratingDistribution as $stars => $count)
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-sm w-8">{{ $stars }} <i class="fas fa-star text-yellow-400 text-xs"></i></span>
                        <div class="flex-1 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $totalReviews > 0 ? ($count / $totalReviews * 100) : 0 }}%"></div>
                        </div>
                        <span class="text-sm w-12 text-right text-gray-600 dark:text-gray-400">{{ $count }}</span>
                    </div>
                @endforeach
            </div>

            <div>
                <button wire:click="openModal" wire:loading.attr="disabled" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200 disabled:opacity-50">
                    <span wire:loading.remove wire:target="openModal">
                        <i class="fas fa-pen mr-2"></i>
                        {{ $existingReview ? 'Editar Avaliação' : 'Escrever Avaliação' }}
                    </span>
                    <span wire:loading wire:target="openModal">
                        <i class="fas fa-spinner fa-spin mr-2"></i> Carregando...
                    </span>
                </button>
            </div>
        </div>
    </div>

    <!-- Lista de Avaliações -->
    <div class="space-y-4">
        @forelse($reviews as $review)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold">
                            {{ strtoupper(substr($review->user->name, 0, 1)) }}
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 dark:text-white">{{ $review->user->name }}</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $review->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    <div class="flex text-yellow-400">
                        @for ($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star {{ $i <= $review->rating ? '' : 'opacity-30' }}"></i>
                        @endfor
                    </div>
                </div>

                @if($review->title)
                    <h5 class="font-semibold text-gray-900 dark:text-white mb-2">{{ $review->title }}</h5>
                @endif
                <p class="text-gray-700 dark:text-gray-300 mb-4">{{ $review->comment }}</p>

                @if($review->photos && count($review->photos) > 0)
                    <div class="flex gap-2 mb-4 flex-wrap">
                        @foreach($review->photos as $photo)
                            <img src="{{ asset('storage/' . $photo) }}" class="w-24 h-24 object-cover rounded-lg cursor-pointer hover:opacity-75 transition">
                        @endforeach
                    </div>
                @endif

                @if($review->is_verified)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300">
                        <i class="fas fa-check-circle mr-1"></i> Estadia Verificada
                    </span>
                @endif

                @if($review->response)
                    <div class="mt-4 bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border-l-4 border-blue-600">
                        <p class="text-sm font-semibold text-gray-900 dark:text-white mb-1">Resposta do hotel</p>
                        <p class="text-sm text-gray-700 dark:text-gray-300">{{ $review->response }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">{{ $review->responded_at->diffForHumans() }}</p>
                    </div>
                @endif
            </div>
        @empty
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-12 text-center">
                <i class="fas fa-comments text-gray-400 text-5xl mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Nenhuma avaliação ainda</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6">Seja o primeiro a avaliar este hotel!</p>
                <button wire:click="openModal" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg">
                    Escrever Avaliação
                </button>
            </div>
        @endforelse
    </div>

    <!-- Paginação -->
    @if($reviews->hasPages())
        <div class="mt-6">
            {{ $reviews->links() }}
        </div>
    @endif

    <!-- Modal de Avaliação -->
    @if($showModal)
        <div class="fixed inset-0 overflow-y-auto z-50" x-data x-show="$wire.showModal">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-gray-500 dark:bg-gray-900 opacity-75" wire:click="closeModal"></div>
                
                <div class="relative bg-white dark:bg-gray-800 rounded-lg max-w-2xl w-full p-6 shadow-xl">
                    <button wire:click="closeModal" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>

                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">
                        {{ $existingReview ? 'Editar Avaliação' : 'Escrever Avaliação' }}
                    </h3>

                    <form wire:submit.prevent="save">
                        <!-- Rating -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Classificação *</label>
                            <div class="flex gap-2">
                                @for ($i = 1; $i <= 5; $i++)
                                    <button type="button" wire:click="$set('rating', {{ $i }})" class="text-3xl {{ $rating >= $i ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600' }} hover:text-yellow-400 transition">
                                        <i class="fas fa-star"></i>
                                    </button>
                                @endfor
                            </div>
                            @error('rating') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Título -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Título da Avaliação</label>
                            <input type="text" wire:model="title" placeholder="Resuma sua experiência" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Comentário -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Comentário *</label>
                            <textarea wire:model="comment" rows="5" placeholder="Conte-nos sobre sua experiência... (mínimo 10 caracteres)" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"></textarea>
                            @error('comment') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Fotos -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Adicionar Fotos</label>
                            <input type="file" wire:model="photos" multiple accept="image/*" class="w-full text-sm">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Máximo 2MB por foto</p>
                            @error('photos.*') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex justify-end gap-3">
                            @if($existingReview)
                                <button type="button" wire:click="deleteReview" wire:loading.attr="disabled" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md disabled:opacity-50 disabled:cursor-not-allowed">
                                    <span wire:loading.remove wire:target="deleteReview">Remover</span>
                                    <span wire:loading wire:target="deleteReview" class="flex items-center">
                                        <i class="fas fa-spinner fa-spin mr-2"></i> Removendo...
                                    </span>
                                </button>
                            @endif
                            <button type="button" wire:click="closeModal" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700">
                                Cancelar
                            </button>
                            <button type="submit" wire:loading.attr="disabled" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md disabled:opacity-50 disabled:cursor-not-allowed">
                                <span wire:loading.remove wire:target="save">{{ $existingReview ? 'Atualizar' : 'Publicar' }}</span>
                                <span wire:loading wire:target="save" class="flex items-center">
                                    <i class="fas fa-spinner fa-spin mr-2"></i> {{ $existingReview ? 'Atualizando...' : 'Publicando...' }}
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>

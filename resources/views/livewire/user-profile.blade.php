<div class="min-h-screen bg-gray-100 dark:bg-gray-900 py-8">
    <div class="container mx-auto px-4">
        @if (session()->has('message'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition class="mb-6 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 px-4 py-3 rounded relative">
                {{ session('message') }}
                <button @click="show = false" class="absolute top-2 right-2 text-green-700 hover:text-green-900">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        <!-- Cabeçalho do Perfil -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
            <div class="flex items-center gap-4">
                <div class="w-20 h-20 bg-blue-600 rounded-full flex items-center justify-center text-white text-3xl font-bold">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h1>
                    <p class="text-gray-600 dark:text-gray-400">{{ $user->email }}</p>
                </div>
            </div>
        </div>

        <!-- Navegação por Tabs -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md mb-6">
            <div class="border-b border-gray-200 dark:border-gray-700">
                <nav class="flex -mb-px">
                    <button wire:click="changeTab('favorites')" class="py-4 px-6 font-medium transition-colors {{ $activeTab == 'favorites' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400' }}">
                        <i class="fas fa-heart mr-2"></i> Favoritos ({{ $favorites->total() }})
                    </button>
                    <button wire:click="changeTab('reservations')" class="py-4 px-6 font-medium transition-colors {{ $activeTab == 'reservations' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400' }}">
                        <i class="fas fa-calendar-check mr-2"></i> Reservas ({{ $reservations->total() }})
                    </button>
                    <button wire:click="changeTab('reviews')" class="py-4 px-6 font-medium transition-colors {{ $activeTab == 'reviews' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400' }}">
                        <i class="fas fa-star mr-2"></i> Minhas Avaliações ({{ $reviews->total() }})
                    </button>
                </nav>
            </div>
        </div>

        <!-- Conteúdo das Tabs -->
        @if($activeTab == 'favorites')
            <!-- Tab Favoritos -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($favorites as $hotel)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden hover:shadow-xl transition">
                        <div class="relative h-48">
                            <img src="{{ $hotel->featured_image ? asset('storage/' . $hotel->featured_image) : 'https://via.placeholder.com/400x300' }}" class="w-full h-full object-cover">
                            <button wire:click="removeFavorite({{ $hotel->id }})" wire:loading.attr="disabled" wire:target="removeFavorite({{ $hotel->id }})" class="absolute top-2 right-2 bg-white rounded-full p-2 hover:bg-red-50 transition disabled:opacity-50">
                                <i class="fas fa-heart text-red-600" wire:loading.remove wire:target="removeFavorite({{ $hotel->id }})"></i>
                                <i class="fas fa-spinner fa-spin text-gray-600" wire:loading wire:target="removeFavorite({{ $hotel->id }})"></i>
                            </button>
                        </div>
                        <div class="p-4">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ $hotel->name }}</h3>
                            <p class="text-gray-600 dark:text-gray-400 text-sm mb-2">
                                <i class="fas fa-map-marker-alt mr-1"></i> {{ $hotel->location->name }}
                            </p>
                            <div class="flex items-center justify-between">
                                <div class="flex text-yellow-400">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $hotel->stars ? '' : 'opacity-30' }}"></i>
                                    @endfor
                                </div>
                                <a href="{{ route('hotel.details', $hotel->id) }}" class="text-blue-600 hover:text-blue-700 font-medium">
                                    Ver Detalhes <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white dark:bg-gray-800 rounded-lg shadow-md p-12 text-center">
                        <i class="fas fa-heart-broken text-gray-400 text-5xl mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Nenhum favorito ainda</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-6">Comece a adicionar hotéis aos seus favoritos!</p>
                        <a href="{{ route('home') }}" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg inline-block">
                            Explorar Hotéis
                        </a>
                    </div>
                @endforelse
            </div>
            @if($favorites->hasPages())
                <div class="mt-6">{{ $favorites->links() }}</div>
            @endif

        @elseif($activeTab == 'reservations')
            <!-- Tab Reservas -->
            <div class="space-y-4">
                @forelse($reservations as $reservation)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                        <div class="flex flex-wrap items-start justify-between gap-4">
                            <div class="flex-1">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ $reservation->hotel->name }}</h3>
                                <p class="text-gray-600 dark:text-gray-400 mb-2">{{ $reservation->roomType->name }}</p>
                                <div class="flex flex-wrap gap-4 text-sm text-gray-600 dark:text-gray-400">
                                    <span><i class="fas fa-calendar mr-1"></i> {{ \Carbon\Carbon::parse($reservation->check_in)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($reservation->check_out)->format('d/m/Y') }}</span>
                                    <span><i class="fas fa-users mr-1"></i> {{ $reservation->guests }} hóspedes</span>
                                    <span><i class="fas fa-door-open mr-1"></i> {{ $reservation->rooms }} quarto(s)</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ number_format($reservation->total_price, 2, ',', '.') }} Kz</div>
                                <span class="inline-block px-3 py-1 rounded-full text-sm font-medium
                                    @if($reservation->status == 'confirmed') bg-green-100 text-green-800
                                    @elseif($reservation->status == 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($reservation->status == 'checked_in') bg-blue-100 text-blue-800
                                    @elseif($reservation->status == 'completed') bg-gray-100 text-gray-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ ucfirst($reservation->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-12 text-center">
                        <i class="fas fa-calendar-times text-gray-400 text-5xl mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Nenhuma reserva ainda</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-6">Faça sua primeira reserva agora!</p>
                        <a href="{{ route('home') }}" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg inline-block">
                            Buscar Hotéis
                        </a>
                    </div>
                @endforelse
            </div>
            @if($reservations->hasPages())
                <div class="mt-6">{{ $reservations->links() }}</div>
            @endif

        @elseif($activeTab == 'reviews')
            <!-- Tab Avaliações -->
            <div class="space-y-4">
                @forelse($reviews as $review)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-1">{{ $review->hotel->name }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $review->created_at->format('d/m/Y') }}</p>
                            </div>
                            <div class="flex text-yellow-400">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $review->rating ? '' : 'opacity-30' }}"></i>
                                @endfor
                            </div>
                        </div>
                        @if($review->title)
                            <h4 class="font-semibold text-gray-900 dark:text-white mb-2">{{ $review->title }}</h4>
                        @endif
                        <p class="text-gray-700 dark:text-gray-300">{{ $review->comment }}</p>
                        @if($review->is_verified)
                            <span class="inline-block mt-3 px-3 py-1 rounded-full text-xs bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i> Estadia Verificada
                            </span>
                        @endif
                    </div>
                @empty
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-12 text-center">
                        <i class="fas fa-comment-slash text-gray-400 text-5xl mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Nenhuma avaliação ainda</h3>
                        <p class="text-gray-600 dark:text-gray-400">Compartilhe suas experiências de viagem!</p>
                    </div>
                @endforelse
            </div>
            @if($reviews->hasPages())
                <div class="mt-6">{{ $reviews->links() }}</div>
            @endif
        @endif
    </div>
</div>

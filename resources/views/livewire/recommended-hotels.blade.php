<div class="mb-8">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
            <i class="fas fa-magic text-blue-600 mr-2"></i>
            Recomendado para Você
        </h2>
        @auth
            <span class="text-sm text-gray-600 dark:text-gray-400">
                <i class="fas fa-robot mr-1"></i> Personalizado
            </span>
        @endauth
    </div>

    @if($hotels->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($hotels as $hotel)
                <a href="{{ route('hotel.details', $hotel->id) }}" class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden hover:shadow-xl transition transform hover:-translate-y-1">
                    <div class="relative">
                        <img src="{{ $hotel->featured_image ?? $hotel->thumbnail ?? '/images/hotel-placeholder.jpg' }}" 
                             alt="{{ $hotel->name }}" 
                             class="w-full h-48 object-cover">
                        @if($hotel->stars)
                            <div class="absolute top-2 left-2 bg-yellow-500 text-white px-2 py-1 rounded-md text-sm font-bold">
                                @for($i = 0; $i < $hotel->stars; $i++)
                                    ★
                                @endfor
                            </div>
                        @endif
                    </div>
                    <div class="p-4">
                        <h3 class="font-bold text-lg mb-2 text-gray-900 dark:text-white">{{ $hotel->name }}</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-3">
                            <i class="fas fa-map-marker-alt mr-1"></i>
                            {{ $hotel->location->city ?? 'Angola' }}
                        </p>
                        @if($hotel->roomTypes->isNotEmpty())
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">A partir de</span>
                                <span class="text-xl font-bold text-blue-600">
                                    {{ number_format($hotel->roomTypes->min('base_price'), 0) }} Kz
                                </span>
                            </div>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
    @else
        <div class="text-center py-12 bg-gray-50 dark:bg-gray-800 rounded-lg">
            <i class="fas fa-hotel text-gray-400 text-5xl mb-4"></i>
            <p class="text-gray-600 dark:text-gray-400">Nenhuma recomendação disponível no momento</p>
        </div>
    @endif
</div>

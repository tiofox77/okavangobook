<div>
    {{-- Success is as dangerous as failure. --}}
    @if($similarHotels->count() > 0)
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">
                <i class="fas fa-th-large text-blue-600 mr-2"></i>
                Hotéis Semelhantes
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach($similarHotels as $hotel)
                    <a href="{{ route('hotel.details', $hotel->id) }}" class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden hover:shadow-xl transition">
                        <div class="relative">
                            <img src="{{ $hotel->featured_image ?? $hotel->thumbnail ?? '/images/hotel-placeholder.jpg' }}" 
                                 alt="{{ $hotel->name }}" 
                                 class="w-full h-40 object-cover">
                            @if($hotel->similarity_score > 20)
                                <div class="absolute top-2 right-2 bg-green-500 text-white px-2 py-1 rounded-md text-xs font-bold">
                                    <i class="fas fa-check"></i> Match
                                </div>
                            @endif
                        </div>
                        <div class="p-3">
                            <h3 class="font-bold text-sm mb-1 text-gray-900 dark:text-white truncate">{{ $hotel->name }}</h3>
                            <div class="flex items-center justify-between text-xs text-gray-600 dark:text-gray-400 mb-2">
                                <span>
                                    @if($hotel->stars)
                                        @for($i = 0; $i < $hotel->stars; $i++)
                                            ★
                                        @endfor
                                    @endif
                                </span>
                                <span>{{ $hotel->location->city ?? '' }}</span>
                            </div>
                            @if($hotel->roomTypes->isNotEmpty())
                                <div class="text-right">
                                    <span class="text-blue-600 font-bold">
                                        {{ number_format($hotel->roomTypes->min('base_price'), 0) }} Kz
                                    </span>
                                </div>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>

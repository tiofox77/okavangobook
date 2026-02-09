<div class="min-h-screen bg-gray-100 dark:bg-gray-900 py-8">
    <div class="container mx-auto px-4">
        <!-- Mensagens -->
        @if (session()->has('message'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                {{ session('message') }}
                <button @click="show = false" class="absolute top-2 right-2"><i class="fas fa-times"></i></button>
            </div>
        @endif

        @if (session()->has('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                {{ session('error') }}
                <button @click="show = false" class="absolute top-2 right-2"><i class="fas fa-times"></i></button>
            </div>
        @endif

        <!-- Cabeçalho -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Comparar Hotéis</h1>
                    <p class="text-gray-600 dark:text-gray-400">Compare até 4 hotéis lado a lado</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('home') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition">
                        <i class="fas fa-search mr-2"></i> Buscar Hotéis
                    </a>
                    @if(count($hotels) > 0)
                        <button wire:click="clearAll" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition">
                            <i class="fas fa-trash mr-2"></i> Limpar Tudo
                        </button>
                    @endif
                </div>
            </div>
        </div>

        @if(count($hotels) == 0)
            <!-- Estado vazio -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-12 text-center">
                <i class="fas fa-balance-scale text-gray-400 text-6xl mb-4"></i>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Nenhum hotel selecionado</h2>
                <p class="text-gray-600 dark:text-gray-400 mb-6">Adicione hotéis à comparação para ver as diferenças lado a lado</p>
                <a href="{{ route('home') }}" class="inline-block px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg">
                    Buscar Hotéis para Comparar
                </a>
            </div>
        @else
            <!-- Tabela de Comparação -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-gray-700">
                            <th class="p-4 text-left font-medium text-gray-700 dark:text-gray-300 w-48 sticky left-0 bg-white dark:bg-gray-800">Característica</th>
                            @foreach($hotels as $hotel)
                                <th class="p-4 text-center min-w-64">
                                    <div class="relative">
                                        <button wire:click="removeHotel({{ $hotel['id'] }})" class="absolute -top-2 -right-2 bg-red-600 text-white rounded-full w-6 h-6 hover:bg-red-700 transition">
                                            <i class="fas fa-times text-xs"></i>
                                        </button>
                                        @if(isset($hotel['featured_image']) && $hotel['featured_image'])
                                            <img src="{{ asset('storage/' . $hotel['featured_image']) }}" class="w-full h-32 object-cover rounded-lg mb-3">
                                        @elseif(isset($hotel['thumbnail']) && $hotel['thumbnail'])
                                            <img src="{{ $hotel['thumbnail'] }}" class="w-full h-32 object-cover rounded-lg mb-3">
                                        @endif
                                        <h3 class="font-bold text-gray-900 dark:text-white">{{ $hotel['name'] }}</h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $hotel['location']['name'] ?? '' }}</p>
                                    </div>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        <!-- Estrelas -->
                        <tr>
                            <td class="p-4 font-medium text-gray-700 dark:text-gray-300 sticky left-0 bg-white dark:bg-gray-800">
                                <i class="fas fa-star text-yellow-400 mr-2"></i> Classificação
                            </td>
                            @foreach($hotels as $hotel)
                                <td class="p-4 text-center">
                                    <div class="flex justify-center text-yellow-400">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $hotel['stars'] ? '' : 'opacity-30' }}"></i>
                                        @endfor
                                    </div>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">{{ $hotel['stars'] }} estrelas</span>
                                </td>
                            @endforeach
                        </tr>

                        <!-- Endereço -->
                        <tr>
                            <td class="p-4 font-medium text-gray-700 dark:text-gray-300 sticky left-0 bg-white dark:bg-gray-800">
                                <i class="fas fa-map-marker-alt text-blue-600 mr-2"></i> Endereço
                            </td>
                            @foreach($hotels as $hotel)
                                <td class="p-4 text-center text-sm text-gray-600 dark:text-gray-400">
                                    {{ $hotel['address'] }}
                                </td>
                            @endforeach
                        </tr>

                        <!-- Número de Quartos -->
                        <tr>
                            <td class="p-4 font-medium text-gray-700 dark:text-gray-300 sticky left-0 bg-white dark:bg-gray-800">
                                <i class="fas fa-bed text-purple-600 mr-2"></i> Tipos de Quartos
                            </td>
                            @foreach($hotels as $hotel)
                                <td class="p-4 text-center">
                                    <span class="text-2xl font-bold text-blue-600">{{ count($hotel['room_types'] ?? []) }}</span>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">opções disponíveis</p>
                                </td>
                            @endforeach
                        </tr>

                        <!-- Descrição -->
                        <tr>
                            <td class="p-4 font-medium text-gray-700 dark:text-gray-300 sticky left-0 bg-white dark:bg-gray-800">
                                <i class="fas fa-info-circle text-gray-600 mr-2"></i> Descrição
                            </td>
                            @foreach($hotels as $hotel)
                                <td class="p-4 text-sm text-gray-600 dark:text-gray-400">
                                    {{ Str::limit($hotel['description'] ?? 'Sem descrição', 100) }}
                                </td>
                            @endforeach
                        </tr>

                        <!-- Ações -->
                        <tr>
                            <td class="p-4 font-medium text-gray-700 dark:text-gray-300 sticky left-0 bg-white dark:bg-gray-800"></td>
                            @foreach($hotels as $hotel)
                                <td class="p-4 text-center">
                                    <a href="{{ route('hotel.details', $hotel['id']) }}" class="block w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition">
                                        Ver Detalhes
                                    </a>
                                </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

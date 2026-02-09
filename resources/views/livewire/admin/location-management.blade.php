<div class="py-6 px-4" x-data="{ confirmingDeletion: false, locationIdToDelete: null }">
    
    <style>
        .line-clamp-1 {
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .transition-all {
            transition-property: all;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 300ms;
        }
    </style>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                <span class="inline-flex items-center">
                    <i class="fas fa-map-marker-alt mr-2 text-blue-600"></i>
                    Gestão de Localizações
                </span>
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Gerencie as localizações disponíveis no sistema</p>
        </div>
        
        <div class="flex items-center space-x-4">
            <!-- Botões de visualização -->
            <div class="flex bg-gray-100 dark:bg-gray-800 p-1 rounded-lg">
                <button 
                    wire:click="toggleViewMode('list')" 
                    type="button" 
                    class="px-3 py-2 rounded-md text-sm font-medium {{ $viewMode === 'list' ? 'bg-white dark:bg-gray-700 text-blue-600 dark:text-blue-400 shadow-sm' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200' }} transition-colors duration-200"
                    title="Visualização em lista">
                    <i class="fas fa-list-ul"></i>
                </button>
                <button 
                    wire:click="toggleViewMode('grid')" 
                    type="button" 
                    class="px-3 py-2 rounded-md text-sm font-medium {{ $viewMode === 'grid' ? 'bg-white dark:bg-gray-700 text-blue-600 dark:text-blue-400 shadow-sm' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200' }} transition-colors duration-200"
                    title="Visualização em grade">
                    <i class="fas fa-th-large"></i>
                </button>
            </div>
            
            <button wire:click="openModal" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition-colors duration-200 flex items-center shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nova Localização
            </button>
        </div>
    </div>
    
    <!-- Mensagens de feedback -->
    @if (session()->has('message'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
            <p>{{ session('message') }}</p>
        </div>
    @endif
    
    @if (session()->has('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
            <p>{{ session('error') }}</p>
        </div>
    @endif
    
    <!-- Cartões de estatísticas -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3 mb-6">
        <!-- Total de Localizações -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-md rounded-lg border-l-4 border-blue-500">
            <div class="p-3 flex items-center">
                <div class="flex-shrink-0 rounded-full p-2 bg-blue-100 dark:bg-blue-900">
                    <i class="fas fa-map text-blue-600 dark:text-blue-400"></i>
                </div>
                <div class="ml-4">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Total Localizações</p>
                    <p class="text-xl font-semibold text-gray-800 dark:text-white">{{ $locations->total() ?? 0 }}</p>
                </div>
            </div>
        </div>
        
        <!-- Localizações em Destaque -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-md rounded-lg border-l-4 border-yellow-500">
            <div class="p-3 flex items-center">
                <div class="flex-shrink-0 rounded-full p-2 bg-yellow-100 dark:bg-yellow-900">
                    <i class="fas fa-star text-yellow-600 dark:text-yellow-400"></i>
                </div>
                <div class="ml-4">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Destacadas</p>
                    <p class="text-xl font-semibold text-gray-800 dark:text-white">{{ $locations->where('is_featured', true)->count() ?? 0 }}</p>
                </div>
            </div>
        </div>
        
        <!-- Total de Hotéis -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-md rounded-lg border-l-4 border-indigo-500">
            <div class="p-3 flex items-center">
                <div class="flex-shrink-0 rounded-full p-2 bg-indigo-100 dark:bg-indigo-900">
                    <i class="fas fa-hotel text-indigo-600 dark:text-indigo-400"></i>
                </div>
                <div class="ml-4">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Total Hotéis</p>
                    <p class="text-xl font-semibold text-gray-800 dark:text-white">{{ $locations->sum('hotels_count') ?? 0 }}</p>
                </div>
            </div>
        </div>
        
        <!-- Províncias -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-md rounded-lg border-l-4 border-green-500">
            <div class="p-3 flex items-center">
                <div class="flex-shrink-0 rounded-full p-2 bg-green-100 dark:bg-green-900">
                    <i class="fas fa-flag text-green-600 dark:text-green-400"></i>
                </div>
                <div class="ml-4">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Províncias</p>
                    <p class="text-xl font-semibold text-gray-800 dark:text-white">{{ $locations->pluck('province')->unique()->count() ?? 0 }}</p>
                </div>
            </div>
        </div>
        
        <!-- População Total -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-md rounded-lg border-l-4 border-indigo-500">
            <div class="p-3 flex items-center">
                <div class="flex-shrink-0 rounded-full p-2 bg-indigo-100 dark:bg-indigo-900">
                    <i class="fas fa-users text-indigo-600 dark:text-indigo-400"></i>
                </div>
                <div class="ml-4">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">População</p>
                    <p class="text-xl font-semibold text-gray-800 dark:text-white">{{ number_format($locations->sum('population'), 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Filtros e pesquisa -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            <!-- Pesquisa -->
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pesquisar</label>
                <div class="relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input wire:model.live.debounce.300ms="search" type="text" class="pl-10 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="Nome ou província...">
                </div>
            </div>
            
            <!-- Filtro por destaque -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                <div class="inline-flex rounded-md shadow-sm">
                    <button wire:click="$set('featuredFilter', '')" type="button" class="px-4 py-2.5 text-sm font-medium {{ $featuredFilter === '' ? 'bg-blue-600 hover:bg-blue-700 text-white' : 'bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600' }} border rounded-l-lg focus:z-10 focus:ring-2 focus:ring-blue-500 transition-colors">
                        <i class="fas fa-list-ul mr-1"></i> Todas
                    </button>
                    <button wire:click="$set('featuredFilter', '1')" type="button" class="px-4 py-2.5 text-sm font-medium {{ $featuredFilter === '1' ? 'bg-blue-600 hover:bg-blue-700 text-white' : 'bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600' }} border-t border-b border-r focus:z-10 focus:ring-2 focus:ring-blue-500 transition-colors">
                        <i class="fas fa-star mr-1"></i> Destaque
                    </button>
                    <button wire:click="$set('featuredFilter', '0')" type="button" class="px-4 py-2.5 text-sm font-medium {{ $featuredFilter === '0' ? 'bg-blue-600 hover:bg-blue-700 text-white' : 'bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600' }} border-t border-b border-r rounded-r-lg focus:z-10 focus:ring-2 focus:ring-blue-500 transition-colors">
                        <i class="fas fa-minus-circle mr-1"></i> Normal
                    </button>
                </div>
            </div>
            
            <!-- Filtro por província -->
            <div>
                <label for="province-filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Província</label>
                <select wire:model.live="provinceFilter" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <option value="">Todas as Províncias</option>
                    @foreach($locations->pluck('province')->unique()->filter() as $province)
                        <option value="{{ $province }}">{{ $province }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    
    <!-- Visualização em Lista -->
    @if($viewMode === 'list')
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-hashtag mr-2"></i>
                                ID
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-map-marker-alt mr-2"></i>
                                Localização
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-flag mr-2"></i>
                                Província
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-users mr-2"></i>
                                População
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-star mr-2"></i>
                                Status
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            <div class="flex items-center justify-end">
                                <i class="fas fa-cogs mr-2"></i>
                                Ações
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($locations as $location)
                        <tr wire:key="location-{{ $location->id }}" class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $location->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        @if($location->image)
                                            <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/'.$location->image) }}" alt="{{ $location->name }}">
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                                                <i class="fas fa-map text-gray-500 dark:text-gray-400"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $location->name }}</div>
                                        @if($location->capital)
                                            <div class="text-sm text-gray-500 dark:text-gray-400">Capital: {{ $location->capital }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    <i class="fas fa-flag mr-1"></i>
                                    {{ $location->province }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                @if($location->population)
                                    {{ number_format($location->population, 0, ',', '.') }}
                                @else
                                    <span class="text-gray-400">N/A</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($location->is_featured)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                        <i class="fas fa-star mr-1"></i>
                                        Destaque
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                        <i class="fas fa-circle mr-1"></i>
                                        Normal
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <button wire:click="view({{ $location->id }})" class="bg-green-100 hover:bg-green-200 text-green-800 px-3 py-1 rounded-md text-xs font-medium transition-colors duration-200" title="Visualizar">
                                        <i class="fas fa-eye mr-1"></i>
                                        Ver
                                    </button>
                                    <button wire:click="openModal({{ $location->id }})" class="bg-blue-100 hover:bg-blue-200 text-blue-800 px-3 py-1 rounded-md text-xs font-medium transition-colors duration-200" title="Editar">
                                        <i class="fas fa-edit mr-1"></i>
                                        Editar
                                    </button>
                                    <button wire:click="toggleFeatured({{ $location->id }})" class="bg-yellow-100 hover:bg-yellow-200 text-yellow-800 px-3 py-1 rounded-md text-xs font-medium transition-colors duration-200">
                                        <i class="fas fa-star mr-1"></i>
                                        {{ $location->is_featured ? 'Remover' : 'Destacar' }}
                                    </button>
                                    <button @click="confirmingDeletion = true; locationIdToDelete = {{ $location->id }}" class="bg-red-100 hover:bg-red-200 text-red-800 px-3 py-1 rounded-md text-xs font-medium transition-colors duration-200">
                                        <i class="fas fa-trash mr-1"></i>
                                        Eliminar
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-4.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                    </svg>
                                    <p class="text-lg font-medium text-gray-500 dark:text-gray-400">Nenhuma localização encontrada</p>
                                    <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Adicione uma nova localização para começar.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
            {{ $locations->links() }}
        </div>
    </div>
    @else
    <!-- Visualização em Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse ($locations as $location)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                <!-- Imagem da Localização -->
                <div class="relative h-48 bg-gradient-to-br from-blue-100 to-blue-200 dark:from-gray-700 dark:to-gray-600">
                    @if($location->image)
                        <img src="{{ asset('storage/'.$location->image) }}" 
                             alt="{{ $location->name }}" 
                             class="w-full h-full object-cover">
                    @else
                        <!-- Imagem padrão com gradiente e ícone -->
                        <div class="w-full h-full flex flex-col items-center justify-center text-blue-400 dark:text-blue-300">
                            <div class="bg-white dark:bg-gray-800 rounded-full p-4 shadow-lg mb-2">
                                <i class="fas fa-map-marker-alt text-3xl text-blue-500 dark:text-blue-400"></i>
                            </div>
                            <span class="text-sm font-medium text-blue-600 dark:text-blue-300">{{ $location->name }}</span>
                        </div>
                    @endif
                    
                    <!-- Badge de Destaque -->
                    @if($location->is_featured)
                        <div class="absolute top-3 right-3 bg-gradient-to-r from-yellow-400 to-yellow-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg flex items-center">
                            <i class="fas fa-star mr-1"></i>
                            Destaque
                        </div>
                    @endif
                    
                    <!-- ID Badge -->
                    <div class="absolute top-3 left-3 bg-black bg-opacity-50 text-white text-xs font-medium px-2 py-1 rounded-md">
                        #{{ $location->id }}
                    </div>
                </div>
                
                <!-- Conteúdo do Card -->
                <div class="p-5">
                    <div class="mb-3">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2 line-clamp-1">
                            {{ $location->name }}
                        </h3>
                        
                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400 mb-2">
                            <i class="fas fa-flag mr-2 text-blue-500"></i>
                            <span class="font-medium">{{ $location->province }}</span>
                        </div>
                        
                        @if($location->capital)
                            <div class="flex items-center text-sm text-gray-600 dark:text-gray-400 mb-2">
                                <i class="fas fa-building mr-2 text-green-500"></i>
                                <span>Capital: {{ $location->capital }}</span>
                            </div>
                        @endif
                        
                        @if($location->population)
                            <div class="flex items-center text-sm text-gray-600 dark:text-gray-400 mb-2">
                                <i class="fas fa-users mr-2 text-purple-500"></i>
                                <span>{{ number_format($location->population, 0, ',', '.') }} habitantes</span>
                            </div>
                        @endif
                        
                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400 mb-3">
                            <i class="fas fa-hotel mr-2 text-indigo-500"></i>
                            <span>{{ $location->hotels_count }} {{ $location->hotels_count === 1 ? 'hotel' : 'hotéis' }}</span>
                        </div>
                    </div>
                    
                    <!-- Descrição Curta -->
                    @if($location->description)
                        <div class="mb-4">
                            <p class="text-sm text-gray-600 dark:text-gray-300 line-clamp-3 leading-relaxed">
                                {{ Str::limit($location->description, 120) }}
                            </p>
                        </div>
                    @else
                        <div class="mb-4">
                            <p class="text-sm text-gray-400 dark:text-gray-500 italic">
                                Sem descrição disponível para esta localização.
                            </p>
                        </div>
                    @endif
                    
                    <!-- Status e Ações -->
                    <div class="flex items-center justify-between pt-4 border-t border-gray-100 dark:border-gray-700">
                        <div class="flex space-x-2">
                            <button 
                                wire:click="view({{ $location->id }})" 
                                class="flex items-center justify-center w-8 h-8 bg-green-100 hover:bg-green-200 dark:bg-green-900 dark:hover:bg-green-800 text-green-600 dark:text-green-400 rounded-full transition-colors duration-200"
                                title="Visualizar localização">
                                <i class="fas fa-eye text-sm"></i>
                            </button>
                            
                            <button 
                                wire:click="openModal({{ $location->id }})" 
                                class="flex items-center justify-center w-8 h-8 bg-blue-100 hover:bg-blue-200 dark:bg-blue-900 dark:hover:bg-blue-800 text-blue-600 dark:text-blue-400 rounded-full transition-colors duration-200"
                                title="Editar localização">
                                <i class="fas fa-edit text-sm"></i>
                            </button>
                            
                            <button 
                                wire:click="toggleFeatured({{ $location->id }})" 
                                class="flex items-center justify-center w-8 h-8 {{ $location->is_featured ? 'bg-yellow-100 hover:bg-yellow-200 dark:bg-yellow-900 dark:hover:bg-yellow-800 text-yellow-600 dark:text-yellow-400' : 'bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-600 dark:text-gray-400' }} rounded-full transition-colors duration-200"
                                title="{{ $location->is_featured ? 'Remover do destaque' : 'Destacar localização' }}">
                                <i class="fas fa-star text-sm"></i>
                            </button>
                            
                            <button 
                                @click="confirmingDeletion = true; locationIdToDelete = {{ $location->id }}" 
                                class="flex items-center justify-center w-8 h-8 bg-red-100 hover:bg-red-200 dark:bg-red-900 dark:hover:bg-red-800 text-red-600 dark:text-red-400 rounded-full transition-colors duration-200"
                                title="Eliminar localização">
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                        </div>
                        
                        <!-- Status Badge -->
                        <div class="flex">
                            @if($location->is_featured)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                    <i class="fas fa-star mr-1"></i>
                                    Destaque
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                    <i class="fas fa-circle mr-1"></i>
                                    Normal
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="text-center py-16">
                    <div class="flex flex-col items-center justify-center">
                        <div class="bg-gray-100 dark:bg-gray-800 rounded-full p-6 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Nenhuma localização encontrada</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-6 max-w-sm">
                            Não foram encontradas localizações que correspondam aos seus critérios de pesquisa.
                        </p>
                        <button 
                            wire:click="openModal" 
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                            <i class="fas fa-plus mr-2"></i>
                            Adicionar Primeira Localização
                        </button>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
    
    <!-- Paginação para visualização em grid -->
    @if($locations->hasPages())
        <div class="mt-6">
            {{ $locations->links() }}
        </div>
    @endif
    @endif
    
    <!-- Modal de criação/edição (usando partial) -->
    @include('livewire.admin.partials.location-form-modal')
    
    <!-- Modal de visualização -->
    @include('livewire.admin.partials.location-view-modal')
    
    <!-- Modal de confirmação de eliminação (usando partial) -->
    @include('livewire.admin.partials.location-delete-modal')
</div>

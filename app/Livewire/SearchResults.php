<?php

namespace App\Livewire;

use App\Models\Hotel;
use App\Models\Location;
use App\Models\Price;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class SearchResults extends Component
{
    use WithPagination;
    
    // Parâmetros de busca
    public $location;
    public $locationId;
    public $province; // Parâmetro para província
    public $checkIn;
    public $checkOut;
    public $guests;
    public $rooms;
    public $allHotels = false; // Indica se deve mostrar todos os hotéis sem filtro
    public $perPage = 10; // Quantidade de hotéis por página
    public $viewMode = 'grid'; // Modo de visualização: list ou grid
    public $minPrice = 0;
    public $maxPrice = 1000000;
    public $stars = [];
    public $ratings = []; // Filtro por avaliação dos hóspedes
    public $amenities = [];
    public $propertyTypes = [];
    public $selectedProvinces = [];
    public $sortBy = 'recommended';
    public $popularDestinations = [];
    public $provinces = [];
    public $provinceCounts = [];
    
    protected $queryString = [
        'location' => ['except' => ''],
        'locationId' => ['except' => '', 'as' => 'location_id'],
        'province' => ['except' => ''], // Adicionado o parâmetro de província
        'selectedProvinces' => ['except' => [], 'as' => 'provinces'],
        'checkIn' => ['except' => '', 'as' => 'check_in'],
        'checkOut' => ['except' => '', 'as' => 'check_out'],
        'guests' => ['except' => 1],
        'rooms' => ['except' => 1],
        'minPrice' => ['except' => 0, 'as' => 'min_price'],
        'maxPrice' => ['except' => 1000000, 'as' => 'max_price'],
        'stars' => ['except' => []],
        'ratings' => ['except' => []],
        'amenities' => ['except' => []],
        'propertyTypes' => ['except' => [], 'as' => 'property_types'],
        'sortBy' => ['except' => 'recommended', 'as' => 'sort'],
        'allHotels' => ['except' => false, 'as' => 'all_hotels'],
        'perPage' => ['except' => 10, 'as' => 'per_page'],
        'viewMode' => ['except' => 'grid', 'as' => 'view'],
    ];
    
    public function mount($location = null, $location_id = null, $province = null, $provinces = [], $check_in = null, $check_out = null, $guests = 2, $rooms = 1, $all_hotels = false, $per_page = 10)
    {
        $this->location = $location;
        $this->locationId = $location_id;
        $this->province = $province;
        $this->selectedProvinces = $provinces;
        $this->checkIn = $check_in ?? Carbon::now()->format('Y-m-d');
        $this->checkOut = $check_out ?? Carbon::now()->addDay()->format('Y-m-d');
        $this->guests = $guests;
        $this->rooms = $rooms;
        $this->allHotels = $all_hotels;
        $this->perPage = $per_page;
        
        // Se temos apenas o slug da localização, buscar o ID
        if ($this->location && !$this->locationId) {
            $foundLocation = Location::where('slug', $this->location)->first();
            if ($foundLocation) {
                $this->locationId = $foundLocation->id;
            }
        }
        
        // Carregar as províncias disponíveis
        $this->loadProvinces();
        
        // Carregar destinos populares
        $this->loadPopularDestinations();
    }
    
    // Carregar províncias disponíveis
    protected function loadProvinces()
    {
        // Buscar todas as províncias que possuem hotéis
        $this->provinces = Location::select('province')->distinct()->whereNotNull('province')->orderBy('province')->get();
        
        // Contar hotéis por província
        $provinceCounts = Hotel::join('locations', 'hotels.location_id', '=', 'locations.id')
            ->selectRaw('locations.province, count(*) as total')
            ->whereNotNull('locations.province')
            ->groupBy('locations.province')
            ->get()
            ->pluck('total', 'province')
            ->toArray();
            
        $this->provinceCounts = $provinceCounts;
    }
    
    // Carregar destinos populares
    protected function loadPopularDestinations()
    {
        // Buscar destinos com mais hotéis
        $this->popularDestinations = Location::withCount('hotels')
            ->orderBy('hotels_count', 'desc')
            ->take(5)
            ->get();
    }
    
    // Método para formatar preços
    public function formatPrice($price)
    {
        return number_format($price, 0, ',', '.');
    }
    
    // Método para limpar os filtros
    public function clearFilters()
    {
        $this->minPrice = 0;
        $this->maxPrice = 1000000;
        $this->stars = [];
        $this->ratings = [];
        $this->amenities = [];
        $this->propertyTypes = [];
        $this->selectedProvinces = [];
        $this->resetPage();
    }
    
    // Método para aplicar filtro de preço
    public function applyPriceFilter()
    {
        // Validação dos valores de preço
        if ($this->minPrice < 0) {
            $this->minPrice = 0;
        }
        
        if ($this->maxPrice < $this->minPrice) {
            $this->maxPrice = $this->minPrice + 1000;
        }
        
        if ($this->maxPrice > 1000000) {
            $this->maxPrice = 1000000;
        }
        
        $this->resetPage();
    }
    
    // Método para definir a ordenação com atualização instantânea
    public function setSorting($value)
    {
        $this->sortBy = $value;
        $this->resetPage();
    }
    
    // Método para aplicar o filtro de províncias (evita distorção do layout)
    public function applyProvinceFilter()
    {
        // Como estamos usando wire:model.defer para selectedProvinces,
        // os valores já estão definidos, só precisamos resetar a paginação
        $this->resetPage();
    }
    
    // Método para alternar a seleção de uma província individual
    public function toggleProvinceFilter($province)
    {
        if (in_array($province, $this->selectedProvinces)) {
            $this->selectedProvinces = array_diff($this->selectedProvinces, [$province]);
        } else {
            $this->selectedProvinces[] = $province;
        }
        
        // Recarregar as províncias para garantir que a lista continue visível
        if (empty($this->provinces) || count($this->provinces) === 0) {
            $this->loadProvinces();
        }
        
        $this->resetPage();
    }
    
    // Método para limpar todas as províncias selecionadas
    public function clearProvinceFilters()
    {
        $this->selectedProvinces = [];
        $this->resetPage();
    }
    
    // Método para processar o formulário de busca no topo da página
    public function search()
    {
        // Verifica se temos apenas o slug da localização, sem o ID
        if ($this->location && !$this->locationId) {
            $foundLocation = Location::where('slug', $this->location)
                ->orWhere('name', 'like', "%{$this->location}%")
                ->first();
                
            if ($foundLocation) {
                $this->locationId = $foundLocation->id;
            }
        }
        
        // Resetar a página para mostrar os novos resultados do início
        $this->resetPage();
    }
    
    // Método para selecionar um destino popular
    public function selectDestination($id, $name)
    {
        $this->locationId = $id;
        $this->location = $name;
        $this->resetPage();
    }
    
    // Método para aplicar filtro de avaliação dos hóspedes
    public function toggleRatingFilter($rating)
    {
        if (in_array($rating, $this->ratings)) {
            $this->ratings = array_diff($this->ratings, [$rating]);
        } else {
            $this->ratings[] = $rating;
        }
        $this->resetPage();
    }
    
    // Método para aplicar filtro de tipo de propriedade (desativado - coluna não existe)
    // public function togglePropertyTypeFilter($type)
    // {
    //     if (in_array($type, $this->propertyTypes)) {
    //         $this->propertyTypes = array_diff($this->propertyTypes, [$type]);
    //     } else {
    //         $this->propertyTypes[] = $type;
    //     }
    //     $this->resetPage();
    // }
    
    // Método para aplicar filtro de estrelas
    public function toggleStarFilter($star)
    {
        if (in_array($star, $this->stars)) {
            $this->stars = array_diff($this->stars, [$star]);
        } else {
            $this->stars[] = $star;
        }
        $this->resetPage();
    }
    
    // Método para aplicar filtro de amenidades
    public function toggleAmenityFilter($amenity)
    {
        if (in_array($amenity, $this->amenities)) {
            $this->amenities = array_diff($this->amenities, [$amenity]);
        } else {
            $this->amenities[] = $amenity;
        }
        $this->resetPage();
    }
    
    // O método clearFilters() já existe acima
    
    // Método para calcular o total de noites
    public function calculateNights()
    {
        $checkIn = Carbon::parse($this->checkIn);
        $checkOut = Carbon::parse($this->checkOut);
        return $checkOut->diffInDays($checkIn);
    }
    
    // Método para obter os resultados da busca com filtros reais dinâmicos
    public function getSearchResults()
    {
        // Iniciar a query
        $query = Hotel::query();
        
        // Carregar relacionamentos para melhorar performance
        $query->with(['location', 'roomTypes.prices']);
        
        // Para ordenação por preço, vamos usar outra abordagem mais adiante
        
        // ====== FILTROS DE LOCALIZAÇÃO ======
        // Se não mostrar todos os hotéis, aplicar filtros de localização
        if (!$this->allHotels) {
            // 1. Prioridade: Filtrar por ID de localização
            if ($this->locationId) {
                $query->where('location_id', $this->locationId);
            }
            // 2. Prioridade: Filtrar por província específica
            elseif ($this->province) {
                $query->whereHas('location', function ($q) {
                    $q->where('province', $this->province);
                });
            }
            // 3. Prioridade: Filtrar por termo de busca (nome da localização ou província)
            elseif ($this->location) {
                $locationTerm = '%' . $this->location . '%';
                $query->where(function($mainQuery) use ($locationTerm) {
                    // Buscar em hotel name
                    $mainQuery->where('name', 'like', $locationTerm)
                        // Ou em location
                        ->orWhereHas('location', function($q) use ($locationTerm) {
                            $q->where('name', 'like', $locationTerm)
                              ->orWhere('province', 'like', $locationTerm);
                        });
                });
            }
        }
        
        // ====== FILTRO DE PROVÍNCIAS SELECIONADAS ======
        if (!empty($this->selectedProvinces)) {
            $query->whereHas('location', function ($q) {
                $q->whereIn('province', $this->selectedProvinces);
            });
        }
        
        // ====== FILTROS DE ESTRELAS ======
        if (!empty($this->stars)) {
            $query->whereIn('stars', $this->stars);
        }
        
        // ====== FILTROS DE AVALIAÇÃO DOS HÓSPEDES ======
        if (!empty($this->ratings)) {
            $query->where(function($q) {
                foreach ($this->ratings as $rating) {
                    if ($rating == 9) {
                        // Excelente: 9.0+
                        $q->orWhere('rating', '>=', 9.0);
                    } elseif ($rating == 8) {
                        // Muito Bom: 8.0-8.9
                        $q->orWhereBetween('rating', [8.0, 8.9]);
                    } elseif ($rating == 7) {
                        // Bom: 7.0-7.9
                        $q->orWhereBetween('rating', [7.0, 7.9]);
                    } elseif ($rating == 6) {
                        // Regular: 6.0-6.9
                        $q->orWhereBetween('rating', [6.0, 6.9]);
                    } elseif ($rating == 0) {
                        // Ruim: < 6.0
                        $q->orWhere('rating', '<', 6.0);
                    }
                }
            });
        }
        
        // ====== FILTROS DE COMODIDADES (AMENITIES) ======
        if (!empty($this->amenities)) {
            $query->where(function($q) {
                foreach ($this->amenities as $amenity) {
                    // Verifica tanto para o caso do campo ser uma string JSON ou uma string simples
                    $q->orWhere(function($subQuery) use ($amenity) {
                        // Tenta como JSON
                        $subQuery->orWhereRaw("JSON_CONTAINS(amenities, '\"$amenity\"')");
                        // Tenta como string simples
                        $subQuery->orWhere('amenities', 'like', "%$amenity%");
                    });
                }
            });
        }
        
        // ====== FILTROS DE TIPO DE PROPRIEDADE ======
        // Desativado - coluna não existe
        // if (!empty($this->propertyTypes)) {
        //     $query->whereIn('property_type', $this->propertyTypes);
        // }
        
        // ====== FILTROS DE PREÇO ======
        // Usamos apenas where para permitir hotéis que tenham pelo menos um tipo de quarto que atenda ao critério
        if ($this->minPrice > 0 || $this->maxPrice < 1000000) {
            $query->whereHas('roomTypes.prices', function($q) {
                $q->whereBetween('price', [$this->minPrice, $this->maxPrice]);
                
                // Apenas filtrar por datas se ambas estiverem definidas
                if ($this->checkIn && $this->checkOut) {
                    $q->where(function($dateQuery) {
                        $dateQuery->whereDate('check_in', '<=', $this->checkIn)
                                 ->whereDate('check_out', '>=', $this->checkOut);
                    });
                }
            });
        }
        
        // ====== ORDENAÇÃO ======
        // Obtemos os IDs dos hotéis conforme os filtros atuais
        $filteredHotelIds = $query->pluck('id')->toArray();
        
        // Agora aplicamos a ordenação separadamente, sem afetar a query principal
        switch ($this->sortBy) {
            case 'price_asc':
                // Abordagem simplificada para evitar o erro de coluna duplicada
                if (!empty($filteredHotelIds)) {
                    // Primeiro obtemos os preços mínimos para cada hotel filtrado
                    $pricesSubQuery = \DB::table('hotels')
                        ->join('room_types', 'hotels.id', '=', 'room_types.hotel_id')
                        ->join('prices', 'room_types.id', '=', 'prices.room_type_id')
                        ->whereIn('hotels.id', $filteredHotelIds)
                        ->groupBy('hotels.id')
                        ->select('hotels.id', \DB::raw('MIN(prices.price) as min_price_value'))
                        ->orderBy('min_price_value', 'asc')
                        ->get();
                    
                    // Extrair os IDs na ordem correta
                    $orderedIds = $pricesSubQuery->pluck('id')->toArray();
                    
                    // Se não tivermos todos os hotéis na ordenação (alguns podem não ter preços),
                    // adicionamos os restantes ao final
                    $remainingIds = array_diff($filteredHotelIds, $orderedIds);
                    $sortedIds = array_merge($orderedIds, $remainingIds);
                    
                    // Criamos uma nova query que respeita a ordem que obtivemos
                    $query = Hotel::whereIn('id', $sortedIds)
                                  ->with(['location', 'roomTypes.prices']);
                    
                    // Se temos IDs para ordenar, usamos FIELD para preservar a ordem exata
                    if (!empty($sortedIds)) {
                        $orderClause = implode(',', $sortedIds);
                        $query->orderByRaw("FIELD(id, $orderClause)");
                    }
                }
                break;
                
            case 'price_desc':
                // Abordagem simplificada para evitar o erro de coluna duplicada
                if (!empty($filteredHotelIds)) {
                    // Primeiro obtemos os preços máximos para cada hotel filtrado
                    $pricesSubQuery = \DB::table('hotels')
                        ->join('room_types', 'hotels.id', '=', 'room_types.hotel_id')
                        ->join('prices', 'room_types.id', '=', 'prices.room_type_id')
                        ->whereIn('hotels.id', $filteredHotelIds)
                        ->groupBy('hotels.id')
                        ->select('hotels.id', \DB::raw('MAX(prices.price) as max_price_value'))
                        ->orderBy('max_price_value', 'desc')
                        ->get();
                    
                    // Extrair os IDs na ordem correta
                    $orderedIds = $pricesSubQuery->pluck('id')->toArray();
                    
                    // Se não tivermos todos os hotéis na ordenação (alguns podem não ter preços),
                    // adicionamos os restantes ao final
                    $remainingIds = array_diff($filteredHotelIds, $orderedIds);
                    $sortedIds = array_merge($orderedIds, $remainingIds);
                    
                    // Criamos uma nova query que respeita a ordem que obtivemos
                    $query = Hotel::whereIn('id', $sortedIds)
                                  ->with(['location', 'roomTypes.prices']);
                    
                    // Se temos IDs para ordenar, usamos FIELD para preservar a ordem exata
                    if (!empty($sortedIds)) {
                        $orderClause = implode(',', $sortedIds);
                        $query->orderByRaw("FIELD(id, $orderClause)");
                    }
                }
                break;
                
            case 'stars_desc':
                // Ordenar pelo número de estrelas (decrescente)
                $query->orderBy('stars', 'desc');
                break;
                
            case 'rating_desc':
                // Ordenar pela maior avaliação dos hóspedes
                $query->orderBy('rating', 'desc');
                break;
                
            default:
                // Ordenar por recomendados (mistura de estrelas, avaliação e preço)
                $query->orderBy('stars', 'desc')
                      ->orderBy('rating', 'desc');
                break;
        }
        
        // Remover duplicatas que podem surgir dos joins
        $query->distinct();
        
        // Retornar os resultados paginados usando o valor perPage definido
        return $query->paginate($this->perPage);
    }
    
    public function render()
    {
        // Obter a localização pesquisada
        $searchedLocation = null;
        if ($this->locationId) {
            $searchedLocation = Location::find($this->locationId);
        } elseif ($this->location) {
            $searchedLocation = Location::where('slug', $this->location)
                ->orWhere('name', 'like', "%{$this->location}%")
                ->first();
        }
        
        // Calcular o número de noites
        $nights = $this->calculateNights();
        
        // Obter destinos populares para sugestões
        $popularDestinations = Location::withCount('hotels')
            ->orderBy('hotels_count', 'desc')
            ->limit(5)
            ->get();
            
        // Contar hotéis por estrela para mostrar nos filtros
        $starCounts = [];
        for ($i = 1; $i <= 5; $i++) {
            $starCounts[$i] = Hotel::where('stars', $i)->count();
        }
        
        // Contar hotéis por avaliação para mostrar nos filtros
        $ratingCounts = [
            9 => Hotel::where('rating', '>=', 9.0)->count(),
            8 => Hotel::whereBetween('rating', [8.0, 8.9])->count(),
            7 => Hotel::whereBetween('rating', [7.0, 7.9])->count(),
            6 => Hotel::whereBetween('rating', [6.0, 6.9])->count(),
            0 => Hotel::where('rating', '<', 6.0)->count(),
        ];
        
        // Contar hotéis por amenidade para mostrar nos filtros
        $amenityCounts = [];
        $amenities = ['wifi', 'pool', 'breakfast', 'parking', 'air_conditioning', 'gym', 'spa', 'restaurant'];
        foreach ($amenities as $amenity) {
            $amenityCounts[$amenity] = Hotel::whereJsonContains('amenities', $amenity)->count();
        }
        
        // Contar hotéis por tipo de propriedade - desativado (coluna não existe)
        $propertyTypeCounts = [];
        
        return view('livewire.search-results', [
            'searchResults' => $this->getSearchResults(),
            'searchedLocation' => $searchedLocation,
            'nights' => $nights,
            'popularDestinations' => $popularDestinations,
            'starCounts' => $starCounts,
            'ratingCounts' => $ratingCounts,
            'amenityCounts' => $amenityCounts,
        ])->layout('layouts.app', ['slot' => 'content']);
    }
}

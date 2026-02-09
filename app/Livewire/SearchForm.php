<?php

namespace App\Livewire;

use App\Models\Location;
use App\Models\Hotel;
use App\Models\Search;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

class SearchForm extends Component
{
    // Propriedades públicas para o formulário
    public $location = '';
    public $selectedProvince = ''; // Valor vazio significa "Todas as províncias"
    public $checkIn;
    public $checkOut;
    public $guests = 2;
    public $rooms = 1;
    public $locationId = null;
    
    // Variáveis para exibição
    public $guestLabel;
    public $roomLabel;
    
    // Sugestões de localização
    public $locationSuggestions = [];
    
    // Lista de províncias disponíveis
    public $provinces = [];
    
    // Montagem do componente - definir datas padrão
    public function mount()
    {
        // Definir datas padrão: hoje e amanhã
        $this->checkIn = Carbon::now()->format('Y-m-d');
        $this->checkOut = Carbon::now()->addDay()->format('Y-m-d');
        
        // Carregar províncias do banco de dados
        $this->loadProvinces();
        
        // Atualizar labels
        $this->updateLabels();
    }
    
    /**
     * Carrega as províncias disponíveis do banco de dados com contagem de hotéis
     */
    private function loadProvinces()
    {
        // Obter todas as províncias com contagem de hotéis ativos
        $provinces = Location::select('locations.province', DB::raw('COUNT(DISTINCT hotels.id) as hotels_count'))
                      ->join('hotels', 'locations.id', '=', 'hotels.location_id')
                      ->where('hotels.is_active', true)
                      ->groupBy('locations.province')
                      ->orderBy('locations.province')
                      ->get()
                      ->mapWithKeys(function($item) {
                          return [$item->province => $item->hotels_count];
                      })
                      ->toArray();
        
        $this->provinces = $provinces;
    }
    
    // Método para atualizar os labels
    private function updateLabels()
    {
        $this->guestLabel = $this->guests == 1 ? '1 hóspede' : $this->guests . ' hóspedes';
        $this->roomLabel = $this->rooms == 1 ? '1 quarto' : $this->rooms . ' quartos';
    }
    
    // Métodos para incrementar/decrementar o número de hóspedes
    public function incrementGuests()
    {
        if ($this->guests < 10) {
            $this->guests++;
            $this->updateLabels();
        }
    }
    
    public function decrementGuests()
    {
        if ($this->guests > 1) {
            $this->guests--;
            $this->updateLabels();
        }
    }
    
    // Método para atualizar diretamente o número de hóspedes
    public function updatedGuests()
    {
        // Garantir que esteja dentro dos limites
        $this->guests = max(1, min(10, $this->guests));
        $this->updateLabels();
    }
    
    // Métodos para incrementar/decrementar o número de quartos
    public function incrementRooms()
    {
        if ($this->rooms < 5) {
            $this->rooms++;
            $this->updateLabels();
        }
    }
    
    public function decrementRooms()
    {
        if ($this->rooms > 1) {
            $this->rooms--;
            $this->updateLabels();
        }
    }
    
    // Método para atualizar diretamente o número de quartos
    public function updatedRooms()
    {
        // Garantir que esteja dentro dos limites
        $this->rooms = max(1, min(5, $this->rooms));
        $this->updateLabels();
    }
    
    // Método para atualizar localização e seu ID
    public function selectLocation($locationName, $locationId)
    {
        $this->location = $locationName;
        $this->locationId = $locationId;
        $this->locationSuggestions = [];
    }
    
    // Método para buscar sugestões de localização e hotéis baseadas no input
    public function updatedLocation()
    {
        if (empty($this->location)) {
            $this->locationSuggestions = [];
            return;
        }
        
        $suggestions = [];
        $searchTerm = $this->location;
        
        // 1. Buscar localizações (cidades/municípios)
        $locationsQuery = Location::query();
        
        if (!empty($this->selectedProvince)) {
            $locationsQuery->where('province', $this->selectedProvince);
        }
        
        $locationsQuery->where(function($q) use ($searchTerm) {
            $q->where('name', 'like', "%{$searchTerm}%")
              ->orWhere('province', 'like', "%{$searchTerm}%");
        });
        
        $locations = $locationsQuery->take(3)
            ->get()
            ->map(function($location) {
                return [
                    'id' => $location->id,
                    'name' => $location->name,
                    'province' => $location->province,
                    'type' => 'location',
                    'icon' => 'fa-map-marker-alt',
                ];
            });
        
        $suggestions = array_merge($suggestions, $locations->toArray());
        
        // 2. Buscar hotéis específicos
        $hotelsQuery = Hotel::with('location')
            ->where('is_active', true)
            ->where('name', 'like', "%{$searchTerm}%");
        
        if (!empty($this->selectedProvince)) {
            $hotelsQuery->whereHas('location', function($q) {
                $q->where('province', $this->selectedProvince);
            });
        }
        
        $hotels = $hotelsQuery->take(3)
            ->get()
            ->map(function($hotel) {
                return [
                    'id' => $hotel->location_id,
                    'hotel_id' => $hotel->id,
                    'name' => $hotel->name,
                    'province' => $hotel->location ? $hotel->location->province : '',
                    'location_name' => $hotel->location ? $hotel->location->name : '',
                    'type' => 'hotel',
                    'icon' => 'fa-hotel',
                ];
            });
        
        $suggestions = array_merge($suggestions, $hotels->toArray());
        
        // Limitar a 6 sugestões no total
        $this->locationSuggestions = array_slice($suggestions, 0, 6);
    }
    
    // Quando a província selecionada for alterada
    public function updatedSelectedProvince()
    {
        // Limpar o campo de localização e sugestões se a província for alterada
        $this->locationId = null;
        $this->locationSuggestions = [];
    }
    
    // Salvar a busca no histórico
    protected function saveSearch()
    {
        try {
            // Criar um novo registro de busca
            Search::create([
                'user_id' => Auth::id(), // Se o usuário estiver logado
                'location' => $this->location,
                'location_id' => $this->locationId,
                'check_in' => $this->checkIn,
                'check_out' => $this->checkOut,
                'guests' => $this->guests,
                'rooms' => $this->rooms,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        } catch (\Exception $e) {
            // Falha ao salvar busca, mas não interromper o fluxo
        }
    }
    
    // Método para lidar com o envio do formulário
    public function search()
    {
        // Atualizar labels antes de prosseguir
        $this->updateLabels();
        
        // Nota: Não precisamos validar se selectedProvince está vazio, já que isso significa "Todas as províncias"
        // A validação a seguir só é para garantir que as datas sejam válidas
        
        if (empty($this->checkIn) || empty($this->checkOut)) {
            session()->flash('error', 'As datas de check-in e check-out são obrigatórias.');
            return;
        }
        
        // Verificar se a data de check-out é após a data de check-in
        $checkInDate = Carbon::parse($this->checkIn);
        $checkOutDate = Carbon::parse($this->checkOut);
        
        if ($checkOutDate->lessThanOrEqualTo($checkInDate)) {
            session()->flash('error', 'A data de check-out deve ser posterior à data de check-in.');
            return;
        }
        
        // Buscar o ID da localização, se necessário
        $locationId = $this->locationId;
        $locationName = $this->location;
        $provinceName = $this->selectedProvince;
        
        // Se não tiver ID mas tiver localização ou província, buscar
        if (!$locationId && (!empty($locationName) || !empty($provinceName))) {
            $query = Location::query();
            
            if (!empty($provinceName)) {
                $query->where('province', 'like', "%{$provinceName}%");
            }
            
            if (!empty($locationName)) {
                $query->where(function($q) use ($locationName) {
                    $q->where('name', 'like', "%{$locationName}%")
                      ->orWhere('province', 'like', "%{$locationName}%");
                });
            }
            
            $matchingLocation = $query->first();
            
            if ($matchingLocation) {
                $locationId = $matchingLocation->id;
            }
        }
        
        // Construir os parâmetros da URL
        $searchParams = [
            'check_in' => $this->checkIn,
            'check_out' => $this->checkOut,
            'guests' => $this->guests,
            'rooms' => $this->rooms,
        ];
        
        // Adicionar localização/província aos parâmetros
        if (!empty($locationName)) {
            $searchParams['location'] = Str::slug($locationName);
        }
        
        if (!empty($provinceName)) {
            $searchParams['province'] = $provinceName;
        }
        
        if ($locationId) {
            $searchParams['location_id'] = $locationId;
        }
        
        // Tentar salvar a busca no histórico
        try {
            $this->saveSearch();
        } catch (\Exception $e) {
            // Apenas registrar o erro, sem interromper o fluxo
        }
        
        // Se não temos nenhum filtro de localização ou província, vamos buscar todos os hotéis
        // No entanto, para evitar sobrecarga, limitamos a 20 hotéis por página
        if (empty($locationName) && empty($provinceName) && empty($locationId)) {
            $searchParams['all_hotels'] = true;
            $searchParams['per_page'] = 20;
        }
        
        // Gerar a URL para os resultados da pesquisa
        $url = route('search.results', $searchParams);
        
        // Dispatch (Livewire v3) para redirecionar
        $this->dispatch('redirect', url: $url);
        
        return;
    }
    
    public function render()
    {
        return view('livewire.search-form');
    }
}

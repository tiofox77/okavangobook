<?php

namespace App\Livewire;

use App\Models\Hotel;
use App\Models\Price;
use Carbon\Carbon;
use Livewire\Component;

class HotelDetails extends Component
{
    public $hotelId;
    public $hotel;
    public $propertyType = 'hotel';
    public $checkIn;
    public $checkOut;
    public $guests;
    public $rooms;
    public $nights;
    public $roomTypes = [];
    public $selectedRoomId = null;
    public $activeTab = 'info'; // Tabs: info, rooms, restaurant, leisure, location, reviews
    public $isFavorited = false;
    
    protected $queryString = [
        'checkIn' => ['except' => '', 'as' => 'check_in'],
        'checkOut' => ['except' => '', 'as' => 'check_out'],
        'guests' => ['except' => 2],
        'rooms' => ['except' => 1],
    ];
    
    public function mount($id)
    {
        $this->hotelId = $id;
        $this->checkIn = request('check_in', Carbon::now()->format('Y-m-d'));
        $this->checkOut = request('check_out', Carbon::now()->addDay()->format('Y-m-d'));
        $this->guests = request('guests', 2);
        $this->rooms = request('rooms', 1);
        
        // Recuperar tab ativa da sessão
        $sessionKey = "hotel.{$id}.tab";
        $this->activeTab = session($sessionKey, 'info');
        
        $this->loadHotelData();
    }
    
    public function loadHotelData()
    {
        // Buscar o hotel com seus relacionamentos
        $this->hotel = Hotel::with([
            'location',
            'roomTypes' => function($query) {
                // Carregar todos os preços sem filtrar por datas
                $query->with('prices');
            },
            'restaurantItems' => function($query) {
                $query->where('is_available', true)->orderBy('category')->orderBy('display_order');
            },
            'leisureFacilities' => function($query) {
                $query->where('is_available', true)->orderBy('display_order');
            }
        ])->findOrFail($this->hotelId);
        
        // Carregar tipo de propriedade
        $this->propertyType = $this->hotel->property_type ?? 'hotel';
        
        // Verificar se o hotel está nos favoritos do usuário
        if (auth()->check()) {
            $this->isFavorited = auth()->user()->favoriteHotels()->where('hotel_id', $this->hotelId)->exists();
        }
        
        // Calcular o número de noites
        $checkInDate = Carbon::parse($this->checkIn);
        $checkOutDate = Carbon::parse($this->checkOut);
        $this->nights = $checkOutDate->diffInDays($checkInDate);
        
        // Preparar dados dos tipos de quarto e seus preços
        $this->prepareRoomTypesData();
    }
    
    protected function prepareRoomTypesData()
    {
        $this->roomTypes = [];
        
        foreach ($this->hotel->roomTypes as $roomType) {
            // Preços agrupados por provedor
            $pricesByProvider = [];
            $lowestPrice = null;
            $bestProvider = null;
            
            foreach ($roomType->prices as $price) {
                $totalPrice = $price->price * $this->nights;
                
                // Agrupar preços por provedor
                if (!isset($pricesByProvider[$price->provider])) {
                    $pricesByProvider[$price->provider] = [
                        'price' => $totalPrice,
                        'original_price' => $price->original_price ? ($price->original_price * $this->nights) : null,
                        'discount_percentage' => $price->discount_percentage,
                        'breakfast_included' => $price->breakfast_included,
                        'free_cancellation' => $price->free_cancellation,
                        'pay_at_hotel' => $price->pay_at_hotel,
                        'link' => $price->link
                    ];
                }
                
                // Rastrear o menor preço
                if ($lowestPrice === null || $totalPrice < $lowestPrice) {
                    $lowestPrice = $totalPrice;
                    $bestProvider = $price->provider;
                }
            }
            
            // Adicionar tipo de quarto à lista
            $this->roomTypes[] = [
                'id' => $roomType->id,
                'name' => $roomType->name,
                'description' => $roomType->description,
                'capacity' => $roomType->capacity,
                'beds' => $roomType->beds,
                'bed_type' => $roomType->bed_type,
                'size' => $roomType->size,
                'amenities' => is_string($roomType->amenities) ? json_decode($roomType->amenities, true) : $roomType->amenities,
                'images' => is_string($roomType->images) ? json_decode($roomType->images, true) : $roomType->images,
                'is_available' => $roomType->is_available,
                'base_price' => $roomType->base_price,
                'has_prices' => count($roomType->prices) > 0,
                'prices' => $pricesByProvider,
                'lowest_price' => $lowestPrice,
                'best_provider' => $bestProvider,
                'rooms_count' => $roomType->rooms_count
            ];
        }
        
        // Ordenar tipos de quarto por preço (do mais barato para o mais caro)
        usort($this->roomTypes, function($a, $b) {
            if (!$a['is_available']) return 1;
            if (!$b['is_available']) return -1;
            return $a['lowest_price'] <=> $b['lowest_price'];
        });
    }
    
    public function selectRoom($roomId)
    {
        $this->selectedRoomId = $roomId;
    }
    
    /**
     * Inicia processo de reserva através do sistema interno
     *
     * @param string $roomId ID do tipo de quarto a ser reservado
     * @return void
     */
    public function bookRoom($roomId)
    {
        // Encontrar o quarto na lista de quartos carregados
        $roomIndex = array_search($roomId, array_column($this->roomTypes, 'id'));
        
        if ($roomIndex !== false) {
            $room = $this->roomTypes[$roomIndex];
            
            // Passar preço por noite ao invés de total
            // Usar lowest_price se disponível, senão base_price
            $pricePerNight = isset($room['lowest_price']) && $room['lowest_price'] > 0
                ? $room['lowest_price'] / $this->nights  // lowest_price já é o total, dividir por noites
                : $room['base_price'];
            
            // Redirecionar para página de reserva com dados necessários
            return redirect()->route('booking.create', [
                'hotel_id' => $this->hotelId,
                'room_type_id' => $roomId,
                'check_in' => $this->checkIn,
                'check_out' => $this->checkOut,
                'guests' => $this->guests,
                'price_per_night' => $pricePerNight
            ]);
        }
    }
    
    public function changeTab($tab)
    {
        $this->activeTab = $tab;
        
        // Guardar a tab selecionada na sessão
        $sessionKey = "hotel.{$this->hotelId}.tab";
        session([$sessionKey => $tab]);
    }
    
    public function updateDates()
    {
        $this->loadHotelData();
    }
    
    public function formatPrice($price)
    {
        return number_format($price, 0, ',', '.');
    }
    
    public function toggleFavorite()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        
        $user = auth()->user();
        
        if ($this->isFavorited) {
            $user->favoriteHotels()->detach($this->hotelId);
            $this->isFavorited = false;
            session()->flash('message', 'Hotel removido dos favoritos!');
        } else {
            $user->favoriteHotels()->attach($this->hotelId);
            $this->isFavorited = true;
            session()->flash('message', 'Hotel adicionado aos favoritos!');
        }
    }
    
    public function addToCompare()
    {
        $compareList = session('compare_hotels', []);
        
        if (in_array($this->hotelId, $compareList)) {
            session()->flash('message', 'Este hotel já está na lista de comparação!');
            return;
        }
        
        if (count($compareList) >= 4) {
            session()->flash('error', 'Você pode comparar no máximo 4 hotéis. Remova alguns antes de adicionar mais.');
            return;
        }
        
        $compareList[] = $this->hotelId;
        session(['compare_hotels' => $compareList]);
        session()->flash('message', 'Hotel adicionado à comparação! (' . count($compareList) . '/4)');
    }
    
    public function render()
    {
        // Verificar se o hotel existe
        if (!$this->hotel) {
            return view('livewire.hotel-not-found')
                ->layout('layouts.app', ['slot' => 'content']);
        }
        
        // Mostrar os detalhes do hotel
        return view('livewire.hotel-details', [
            'hotel' => $this->hotel,
            'roomTypes' => $this->roomTypes,
            'nights' => $this->nights
        ])->layout('layouts.app', ['slot' => 'content']);
    }
}

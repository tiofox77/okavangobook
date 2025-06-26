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
    public $checkIn;
    public $checkOut;
    public $guests;
    public $rooms;
    public $nights;
    public $roomTypes = [];
    public $selectedRoomId = null;
    public $activeTab = 'info'; // Tabs: info, rooms, location, reviews
    
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
            }
        ])->findOrFail($this->hotelId);
        
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
                'amenities' => $roomType->amenities,
                'images' => $roomType->images,
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
     * Inicia processo de reserva direta pelo preço base do quarto
     *
     * @param string $roomId ID do quarto a ser reservado
     * @return void
     */
    public function bookRoomBasic($roomId)
    {
        // Encontrar o quarto na lista de quartos carregados
        $roomIndex = array_search($roomId, array_column($this->roomTypes, 'id'));
        
        if ($roomIndex !== false) {
            $room = $this->roomTypes[$roomIndex];
            $totalPrice = $room['base_price'] * $this->nights;
            
            // Redirecionar para página de reserva com dados necessários
            return redirect()->route('booking.create', [
                'hotel_id' => $this->hotelId,
                'room_id' => $roomId,
                'check_in' => $this->checkIn,
                'check_out' => $this->checkOut,
                'guests' => $this->guests,
                'nights' => $this->nights,
                'total' => $totalPrice
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

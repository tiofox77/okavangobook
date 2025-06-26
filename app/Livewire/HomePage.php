<?php

namespace App\Livewire;

use App\Models\Hotel;
use App\Models\Location;
use App\Models\Price;
use App\Models\RoomType;
use Carbon\Carbon;
use Livewire\Component;

class HomePage extends Component
{
    public $popularDestinations = [];
    public $specialOffers = [];
    public $featuredHotels = [];
    
    // Método para formatar preços em kwanzas angolanos
    public function formatPrice($price)
    {
        return number_format($price, 0, ',', '.');
    }
    
    // Método para calcular média de avaliações
    public function calculateAverageRating($hotelId)
    {
        // Na versão 1.0, vamos simplesmente retornar uma classificação aleatória entre 4 e 5
        // Em versões futuras, isso será calculado com base nas avaliações reais dos usuários
        return rand(40, 50) / 10;
    }
    
    // Método para calcular número de avaliações
    public function calculateReviewsCount($hotelId)
    {
        // Na versão 1.0, vamos simplesmente retornar um número aleatório entre 10 e 200
        // Em versões futuras, isso será calculado com base nas avaliações reais dos usuários
        return rand(10, 200);
    }
    
    // Buscar destinos populares do banco de dados
    public function loadPopularDestinations()
    {
        // Buscar localizações em destaque
        $locations = Location::where('is_featured', true)
            ->take(6)
            ->get();
            
        $this->popularDestinations = $locations->map(function($location) {
            // Verifica se a imagem está vazia ou é inválida
            $image = $location->image;
            if (empty($image) || !filter_var($image, FILTER_VALIDATE_URL)) {
                // Define uma imagem padrão baseada no nome da localização
                $locationName = strtolower($location->name);
                if (strpos($locationName, 'luanda') !== false) {
                    $image = 'https://images.unsplash.com/photo-1489392191049-fc10c97e64b6?ixlib=rb-4.0.3&auto=format&fit=crop&w=1500&q=80';
                } elseif (strpos($locationName, 'benguela') !== false) {
                    $image = 'https://images.unsplash.com/photo-1596306499317-8490982dfe4f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1500&q=80';
                } elseif (strpos($locationName, 'lubango') !== false) {
                    $image = 'https://images.unsplash.com/photo-1579005318686-5a86bbb3b2db?ixlib=rb-4.0.3&auto=format&fit=crop&w=1500&q=80';
                } elseif (strpos($locationName, 'namibe') !== false) {
                    $image = 'https://images.unsplash.com/photo-1508804185872-d7badad00f7d?ixlib=rb-4.0.3&auto=format&fit=crop&w=1500&q=80';
                } elseif (strpos($locationName, 'huambo') !== false) {
                    $image = 'https://images.unsplash.com/photo-1497271679421-ce9c3d6a31da?ixlib=rb-4.0.3&auto=format&fit=crop&w=1500&q=80';
                } else {
                    // Imagem padrão para outras localizações
                    $image = 'https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?ixlib=rb-4.0.3&auto=format&fit=crop&w=1500&q=80';
                }
            }
            
            return [
                'id' => $location->id,
                'name' => $location->name,
                'description' => $location->description,
                'hotels_count' => $location->hotels_count ?? rand(5, 20),
                'image' => $image,
                'slug' => $location->slug
            ];
        })->toArray();
    }
    
    // Buscar ofertas especiais do banco de dados
    public function loadSpecialOffers()
    {
        // Obter data atual
        $today = Carbon::today();
        $nextWeek = Carbon::today()->addDays(7);
        
        // Buscar hotéis com preços que têm descontos
        $hotels = Hotel::with(['roomTypes' => function($query) {
                $query->where('is_featured', true);
            }, 'location'])
            ->take(6)
            ->get();
        
        $offers = [];
        $count = 0;
        
        // Imagens padrão para hoteis
        $defaultHotelImages = [
            'https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&auto=format&fit=crop&w=1500&q=80',
            'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?ixlib=rb-4.0.3&auto=format&fit=crop&w=1500&q=80',
            'https://images.unsplash.com/photo-1564501049412-61c2a3083791?ixlib=rb-4.0.3&auto=format&fit=crop&w=1500&q=80',
            'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?ixlib=rb-4.0.3&auto=format&fit=crop&w=1500&q=80',
            'https://images.unsplash.com/photo-1571896349842-33c89424de2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=1500&q=80',
            'https://images.unsplash.com/photo-1455587734955-081b22074882?ixlib=rb-4.0.3&auto=format&fit=crop&w=1500&q=80'
        ];
        
        foreach ($hotels as $index => $hotel) {
            if ($hotel->roomTypes->isNotEmpty() && $count < 3) {
                $roomType = $hotel->roomTypes->first();
                
                // Buscar preços para o tipo de quarto
                $price = Price::where('hotel_id', $hotel->id)
                    ->where('room_type_id', $roomType->id)
                    ->where('discount_percentage', '>', 0)
                    ->whereDate('check_in', '>=', $today)
                    ->whereDate('check_in', '<=', $nextWeek)
                    ->first();
                
                if (!$price) {
                    // Se não encontrar preço com desconto, pegar o primeiro preço disponível
                    $price = Price::where('hotel_id', $hotel->id)
                        ->where('room_type_id', $roomType->id)
                        ->whereDate('check_in', '>=', $today)
                        ->whereDate('check_in', '<=', $nextWeek)
                        ->first();
                }
                
                // Se ainda não encontrar nenhum preço, criar preço temporário
                if (!$price) {
                    $basePrice = rand(15000, 30000);
                    $discountPercentage = rand(10, 30);
                    $discountPrice = $basePrice * (1 - ($discountPercentage / 100));
                    
                    $hotelLocation = $hotel->location ? $hotel->location->name . ', Angola' : 'Angola';
                    
                    // Verificar se a imagem é válida
                    $image = $hotel->featured_image;
                    if (empty($image) || !filter_var($image, FILTER_VALIDATE_URL)) {
                        $image = $defaultHotelImages[$index % count($defaultHotelImages)];
                    }
                    
                    $offers[] = [
                        'id' => $hotel->id,
                        'name' => $hotel->name,
                        'location' => $hotelLocation,
                        'rating' => $hotel->stars ?? rand(3, 5),
                        'reviews' => $this->calculateReviewsCount($hotel->id),
                        'original_price' => $basePrice,
                        'discount_price' => $discountPrice,
                        'discount_percentage' => $discountPercentage,
                        'image' => $image,
                        'slug' => $hotel->slug ?? Str::slug($hotel->name)
                    ];
                    
                    $count++;
                    continue;
                }
                
                // Verificar se a imagem é válida
                $image = $hotel->featured_image;
                if (empty($image) || !filter_var($image, FILTER_VALIDATE_URL)) {
                    $image = $defaultHotelImages[$index % count($defaultHotelImages)];
                }
                
                $hotelLocation = $hotel->location ? $hotel->location->name . ', Angola' : 'Angola';
                
                $offers[] = [
                    'id' => $hotel->id,
                    'name' => $hotel->name,
                    'location' => $hotelLocation,
                    'rating' => $hotel->stars ?? rand(3, 5),
                    'reviews' => $this->calculateReviewsCount($hotel->id),
                    'original_price' => $price->original_price ?? $price->price * 1.2,
                    'discount_price' => $price->price,
                    'discount_percentage' => $price->discount_percentage ?? 20,
                    'image' => $image,
                    'slug' => $hotel->slug ?? Str::slug($hotel->name)
                ];
                
                $count++;
            }
        }
        
        // Se não tivermos 3 ofertas, adicionar algumas fictícias
        while (count($offers) < 3) {
            $randomIndex = count($offers);
            $basePrice = rand(15000, 30000);
            $discountPercentage = rand(10, 30);
            $discountPrice = $basePrice * (1 - ($discountPercentage / 100));
            
            $hotelNames = ['Hotel Presidente', 'Hotel Terminus', 'Hotel Continental', 'Hotel Epic Sana', 'Hotel Trópico', 'Diamante Hotel'];
            $locations = ['Luanda', 'Benguela', 'Lubango', 'Namibe', 'Huambo'];
            
            $offers[] = [
                'id' => 1000 + $randomIndex,
                'name' => $hotelNames[$randomIndex % count($hotelNames)],
                'location' => $locations[$randomIndex % count($locations)] . ', Angola',
                'rating' => rand(3, 5),
                'reviews' => rand(10, 200),
                'original_price' => $basePrice,
                'discount_price' => $discountPrice,
                'discount_percentage' => $discountPercentage,
                'image' => $defaultHotelImages[$randomIndex % count($defaultHotelImages)],
                'slug' => Str::slug($hotelNames[$randomIndex % count($hotelNames)])
            ];
        }
        
        $this->specialOffers = $offers;
    }
    
    public function mount()
    {
        $this->loadPopularDestinations();
        $this->loadSpecialOffers();
    }
    
    public function render()
    {
        return view('livewire.home-page')
            ->layout('layouts.app', ['slot' => 'content']);
    }
}

<?php

namespace App\Livewire;

use App\Models\Hotel;
use App\Models\Location;
use App\Models\Price;
use App\Models\RoomType;
use App\Models\Setting;
use Carbon\Carbon;
use Livewire\Component;

class HomePage extends Component
{
    public $popularDestinations = [];
    public $specialOffers = [];
    public $featuredHotels = [];
    public $nearbyHotels = [];
    public $featuredResorts = [];
    public $featuredHospedarias = [];
    public $propertyTypeImages = [];
    public $heroBackground;
    public $offersBackground;
    public $userLatitude = null;
    public $userLongitude = null;
    public $locationPermissionDenied = false;
    
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
            return [
                'id' => $location->id,
                'name' => $location->name,
                'description' => $location->description,
                'hotels_count' => $location->hotels_count ?? rand(5, 20),
                'image' => $location->image,
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
    
    /**
     * Buscar hotéis próximos baseado em coordenadas do usuário
     * Usa fórmula de Haversine para calcular distância
     */
    public function loadNearbyHotels($latitude, $longitude, $radiusKm = 50)
    {
        $this->userLatitude = $latitude;
        $this->userLongitude = $longitude;
        
        // Fórmula de Haversine para calcular distância
        // Buscar hotéis ativos com coordenadas válidas
        $hotels = Hotel::where('is_active', true)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->with('location')
            ->get()
            ->map(function($hotel) use ($latitude, $longitude) {
                $distance = $this->calculateDistance(
                    $latitude, 
                    $longitude, 
                    (float) $hotel->latitude, 
                    (float) $hotel->longitude
                );
                
                $hotel->distance = $distance;
                return $hotel;
            })
            ->filter(function($hotel) use ($radiusKm) {
                return $hotel->distance <= $radiusKm;
            })
            ->sortBy('distance')
            ->take(6)
            ->map(function($hotel) {
                return [
                    'id' => $hotel->id,
                    'name' => $hotel->name,
                    'location' => $hotel->location ? $hotel->location->name : 'Angola',
                    'province' => $hotel->location ? $hotel->location->province : '',
                    'image' => $hotel->thumbnail ?? 'https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                    'rating' => $hotel->rating ?? rand(40, 50) / 10,
                    'reviews' => $hotel->reviews_count ?? rand(10, 100),
                    'price' => $hotel->min_price ?? rand(15000, 35000),
                    'distance' => round($hotel->distance, 1),
                    'slug' => $hotel->slug ?? \Illuminate\Support\Str::slug($hotel->name),
                ];
            })
            ->values()
            ->toArray();
        
        $this->nearbyHotels = $hotels;
    }
    
    /**
     * Calcular distância entre dois pontos usando fórmula de Haversine
     * Retorna distância em quilômetros
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // Raio da Terra em km
        
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        
        $a = sin($dLat/2) * sin($dLat/2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon/2) * sin($dLon/2);
        
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        $distance = $earthRadius * $c;
        
        return $distance;
    }
    
    /**
     * Método chamado pelo JavaScript quando obtém coordenadas
     */
    public function setUserLocation($latitude, $longitude)
    {
        $this->loadNearbyHotels($latitude, $longitude);
    }
    
    /**
     * Método chamado quando usuário nega permissão de localização
     */
    public function locationDenied()
    {
        $this->locationPermissionDenied = true;
        // Carregar hotéis em destaque como fallback
        $this->nearbyHotels = Hotel::where('is_active', true)
            ->where('is_featured', true)
            ->with('location')
            ->take(6)
            ->get()
            ->map(function($hotel) {
                return [
                    'id' => $hotel->id,
                    'name' => $hotel->name,
                    'location' => $hotel->location ? $hotel->location->name : 'Angola',
                    'province' => $hotel->location ? $hotel->location->province : '',
                    'image' => $hotel->thumbnail ?? 'https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                    'rating' => $hotel->rating ?? rand(40, 50) / 10,
                    'reviews' => $hotel->reviews_count ?? rand(10, 100),
                    'price' => $hotel->min_price ?? rand(15000, 35000),
                    'slug' => $hotel->slug ?? \Illuminate\Support\Str::slug($hotel->name),
                ];
            })
            ->toArray();
    }
    
    /**
     * Carregar Resorts em destaque
     */
    public function loadFeaturedResorts()
    {
        $defaultImage = 'https://images.unsplash.com/photo-1571896349842-33c89424de2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80';
        
        $this->featuredResorts = Hotel::where('property_type', 'resort')
            ->where('is_active', true)
            ->with('location')
            ->take(4)
            ->get()
            ->map(function($hotel) use ($defaultImage) {
                // Normalizar imagem
                $image = $hotel->thumbnail ?? $hotel->featured_image ?? null;
                if ($image) {
                    if (str_starts_with($image, 'http')) {
                        $imageUrl = $image;
                    } else {
                        $imageUrl = asset('storage/' . $image);
                    }
                } else {
                    $imageUrl = $defaultImage;
                }
                
                return [
                    'id' => $hotel->id,
                    'name' => $hotel->name,
                    'location' => $hotel->location ? $hotel->location->name : 'Angola',
                    'province' => $hotel->location ? $hotel->location->province : '',
                    'image' => $imageUrl,
                    'rating' => $hotel->rating ?? 4.5,
                    'stars' => $hotel->stars ?? 5,
                    'reviews' => $hotel->reviews_count ?? rand(20, 150),
                    'price' => $hotel->min_price ?? rand(25000, 50000),
                    'slug' => $hotel->slug ?? \Illuminate\Support\Str::slug($hotel->name),
                    'description' => \Illuminate\Support\Str::limit($hotel->description, 100),
                ];
            })
            ->toArray();
    }
    
    /**
     * Carregar Hospedarias em destaque
     */
    public function loadFeaturedHospedarias()
    {
        $defaultImage = 'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80';
        
        $this->featuredHospedarias = Hotel::where('property_type', 'hospedaria')
            ->where('is_active', true)
            ->with('location')
            ->take(4)
            ->get()
            ->map(function($hotel) use ($defaultImage) {
                // Normalizar imagem
                $image = $hotel->thumbnail ?? $hotel->featured_image ?? null;
                if ($image) {
                    if (str_starts_with($image, 'http')) {
                        $imageUrl = $image;
                    } else {
                        $imageUrl = asset('storage/' . $image);
                    }
                } else {
                    $imageUrl = $defaultImage;
                }
                
                return [
                    'id' => $hotel->id,
                    'name' => $hotel->name,
                    'location' => $hotel->location ? $hotel->location->name : 'Angola',
                    'province' => $hotel->location ? $hotel->location->province : '',
                    'image' => $imageUrl,
                    'rating' => $hotel->rating ?? 4.2,
                    'stars' => $hotel->stars ?? 3,
                    'reviews' => $hotel->reviews_count ?? rand(10, 80),
                    'price' => $hotel->min_price ?? rand(8000, 20000),
                    'slug' => $hotel->slug ?? \Illuminate\Support\Str::slug($hotel->name),
                    'description' => \Illuminate\Support\Str::limit($hotel->description, 100),
                ];
            })
            ->toArray();
    }
    
    /**
     * Carregar imagens representativas para cada tipo de propriedade
     */
    public function loadPropertyTypeImages()
    {
        $types = ['hotel', 'resort', 'hospedaria'];
        
        foreach ($types as $type) {
            $hotel = Hotel::where('property_type', $type)
                ->where('is_active', true)
                ->whereNotNull('thumbnail')
                ->first();
            
            if ($hotel && $hotel->thumbnail) {
                $this->propertyTypeImages[$type] = $hotel->thumbnail;
            } else {
                // Fallback - tentar pegar de featured_image
                $hotel = Hotel::where('property_type', $type)
                    ->where('is_active', true)
                    ->whereNotNull('featured_image')
                    ->first();
                
                $this->propertyTypeImages[$type] = $hotel ? $hotel->featured_image : null;
            }
        }
    }
    
    public function mount()
    {
        $this->loadPopularDestinations();
        $this->loadSpecialOffers();
        $this->loadFeaturedResorts();
        $this->loadFeaturedHospedarias();
        $this->loadPropertyTypeImages();
        
        // Carregar imagens de fundo das configurações
        $this->heroBackground = Setting::get('hero_background');
        $this->offersBackground = Setting::get('offers_background');
    }
    
    public function render()
    {
        return view('livewire.home-page')
            ->layout('layouts.app', ['slot' => 'content']);
    }
}

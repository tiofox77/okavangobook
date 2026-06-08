<?php

namespace Database\Seeders\Angola;

use App\Models\Location;
use App\Models\User;

class HotelsCabindaSeeder
{
    use HotelSeederTrait;

    public function run(User $admin): void
    {
        $loc = Location::where('name', 'Cabinda')->first();
        if (!$loc) return;

        $hotels = [
            [
                'name' => 'Hotel Maiombe Cabinda',
                'property_type' => 'hotel',
                'description' => 'O Hotel Maiombe é o principal estabelecimento de Cabinda, nomeado em homenagem à floresta tropical que envolve este enclave angolano. Quartos confortáveis com ar condicionado, restaurante com especialidades locais e peixe fresco, piscina e serviços de qualidade. A sua localização central facilita o acesso à vida económica e cultural de Cabinda.',
                'address' => 'Avenida do 1º de Agosto, Cabinda',
                'stars' => 4, 'rating' => 4.3, 'reviews_count' => 167, 'min_price' => 65000, 'is_featured' => true,
                'latitude' => -5.5500, 'longitude' => 12.1900,
                'phone' => '+244 231 220 500', 'email' => 'maiombe@hotelmaiombe.ao',
                'thumbnail' => 'https://images.unsplash.com/photo-1596386461350-326ccb383e9f?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1596386461350-326ccb383e9f?w=1200&q=80',
                    'https://images.unsplash.com/photo-1618773928121-c32242e63f39?w=1200&q=80',
                    'https://images.unsplash.com/photo-1578683010236-d716f9a3f461?w=1200&q=80',
                    'https://images.unsplash.com/photo-1566665797739-1674de7a421a?w=1200&q=80',
                ],
                'amenities' => ['WiFi gratuito', 'Piscina', 'Restaurante', 'Bar', 'Business center', 'Ginásio', 'Estacionamento', 'Serviço de quarto 24h'],
            ],
            [
                'name' => 'Floresta do Maiombe Lodge',
                'property_type' => 'resort',
                'description' => 'Lodge ecológico situado no coração da Floresta do Maiombe, uma das florestas tropicais mais biodiversas de África. Bangalôs integrados na floresta, restaurante com produtos locais, trilhas guiadas, observação de primatas raros e experiências culturais com comunidades locais. Um destino único para ecoturistas.',
                'address' => 'Floresta do Maiombe, Cabinda',
                'stars' => 4, 'rating' => 4.9, 'reviews_count' => 78, 'min_price' => 120000, 'is_featured' => true,
                'latitude' => -4.9500, 'longitude' => 12.4000,
                'phone' => '+244 924 231 456', 'email' => 'floresta@maiombelodge.ao',
                'thumbnail' => 'https://images.unsplash.com/photo-1523741543316-beb7fc7023d8?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1523741543316-beb7fc7023d8?w=1200&q=80',
                    'https://images.unsplash.com/photo-1565348271942-ab9e44c98aad?w=1200&q=80',
                    'https://images.unsplash.com/photo-1541123437800-1bb1317badc2?w=1200&q=80',
                    'https://images.unsplash.com/photo-1549180030-48bf079fb38a?w=1200&q=80',
                ],
                'amenities' => ['Bangalôs na floresta', 'Refeições incluídas', 'Trilhas guiadas', 'Observação de primatas', 'Experiências culturais', 'WiFi nas áreas comuns'],
            ],
            [
                'name' => 'Hotel Cabinda Palace',
                'property_type' => 'hotel',
                'description' => 'Hotel moderno no centro de Cabinda, muito frequentado por executivos da indústria petrolífera. Quartos executivos bem equipados, restaurante internacional, bar lounge, sala de fitness e espaços de coworking. A sua eficiência e localização privilegiada tornam-no a escolha preferida dos profissionais de negócios.',
                'address' => 'Rua Alfredo Trony, Cabinda',
                'stars' => 4, 'rating' => 4.2, 'reviews_count' => 212, 'min_price' => 70000, 'is_featured' => false,
                'latitude' => -5.5600, 'longitude' => 12.1950,
                'phone' => '+244 231 222 000', 'email' => 'palace@cabindapalace.ao',
                'thumbnail' => 'https://images.unsplash.com/photo-1564501049412-61c2a3083791?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1564501049412-61c2a3083791?w=1200&q=80',
                    'https://images.unsplash.com/photo-1611892440504-42a792e24d32?w=1200&q=80',
                    'https://images.unsplash.com/photo-1583037189850-1921ae7c6c22?w=1200&q=80',
                ],
                'amenities' => ['WiFi gratuito', 'Restaurante internacional', 'Bar lounge', 'Sala de fitness', 'Coworking', 'Estacionamento', 'Transfer aeroporto'],
            ],
        ];

        foreach ($hotels as $data) {
            $this->createHotel($data, $admin, $loc);
        }
    }
}

<?php

namespace Database\Seeders\Angola;

use App\Models\Location;
use App\Models\User;

/**
 * Cobre: Soyo (Zaire), Uíge, N'dalatando (Cuanza Norte), Caxito (Bengo)
 */
class HotelsNorteSeeder
{
    use HotelSeederTrait;

    public function run(User $admin): void
    {
        $this->soyo($admin);
        $this->uige($admin);
        $this->ndalatando($admin);
        $this->caxito($admin);
    }

    private function soyo(User $admin): void
    {
        $loc = Location::where('name', 'Soyo')->first();
        if (!$loc) return;

        $hotels = [
            [
                'name' => 'Hotel Soyo Palace',
                'property_type' => 'hotel',
                'description' => 'O Hotel Soyo Palace é o principal estabelecimento desta importante cidade portuária e petrolífera, na foz do Rio Congo. Muito frequentado por profissionais da indústria do petróleo e por executivos internacionais. Quartos executivos com vista para o rio, restaurante internacional, bar e serviços completos de apoio a negócios.',
                'address' => 'Avenida da Independência, Soyo',
                'stars' => 4, 'rating' => 4.2, 'reviews_count' => 143, 'min_price' => 68000, 'is_featured' => true,
                'latitude' => -6.1333, 'longitude' => 12.3667,
                'phone' => '+244 236 220 500', 'email' => 'palace@soyopalace.ao',
                'thumbnail' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=1200&q=80',
                    'https://images.unsplash.com/photo-1618773928121-c32242e63f39?w=1200&q=80',
                    'https://images.unsplash.com/photo-1566665797739-1674de7a421a?w=1200&q=80',
                    'https://images.unsplash.com/photo-1583037189850-1921ae7c6c22?w=1200&q=80',
                ],
                'amenities' => ['WiFi gratuito', 'Restaurante internacional', 'Bar', 'Business center', 'Ginásio', 'Piscina', 'Transfer aeroporto', 'Estacionamento'],
            ],
            [
                'name' => 'Rio Congo Lodge Soyo',
                'property_type' => 'resort',
                'description' => 'Lodge à beira do Rio Congo, o segundo maior rio de África. Uma experiência única de natureza tropical com vegetação exuberante, pesca no rio, observação de aves e refeições preparadas com peixe fresco. Bangalôs com varanda sobre o rio e atmosfera de aventura e tranquilidade.',
                'address' => 'Margem do Rio Congo, Soyo',
                'stars' => 3, 'rating' => 4.6, 'reviews_count' => 67, 'min_price' => 55000, 'is_featured' => false,
                'latitude' => -6.1000, 'longitude' => 12.3300,
                'phone' => '+244 924 360 789', 'email' => 'riocongo@lodge.ao',
                'thumbnail' => 'https://images.unsplash.com/photo-1549180030-48bf079fb38a?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1549180030-48bf079fb38a?w=1200&q=80',
                    'https://images.unsplash.com/photo-1523741543316-beb7fc7023d8?w=1200&q=80',
                    'https://images.unsplash.com/photo-1541123437800-1bb1317badc2?w=1200&q=80',
                ],
                'amenities' => ['Bangalôs sobre o rio', 'Pesca desportiva', 'Canoagem', 'Observação de aves', 'Refeições incluídas', 'WiFi nas áreas comuns'],
            ],
        ];
        foreach ($hotels as $data) { $this->createHotel($data, $admin, $loc); }
    }

    private function uige(User $admin): void
    {
        $loc = Location::where('name', 'Uíge')->first();
        if (!$loc) return;

        $hotels = [
            [
                'name' => 'Hotel Uíge',
                'property_type' => 'hotel',
                'description' => 'O Hotel Uíge é o estabelecimento de referência da capital da província homónima, no norte de Angola. Rodeada de plantações de café e floresta tropical, a cidade de Uíge tem um clima agradável. O hotel oferece quartos confortáveis, restaurante com pratos regionais e serviços de apoio ao viajante de negócios e turista.',
                'address' => 'Avenida Principal, Uíge',
                'stars' => 3, 'rating' => 4.1, 'reviews_count' => 98, 'min_price' => 35000, 'is_featured' => false,
                'latitude' => -7.6087, 'longitude' => 15.0613,
                'phone' => '+244 233 220 300', 'email' => 'hotel@hoteluige.ao',
                'thumbnail' => 'https://images.unsplash.com/photo-1564501049412-61c2a3083791?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1564501049412-61c2a3083791?w=1200&q=80',
                    'https://images.unsplash.com/photo-1590490360182-c33d57733427?w=1200&q=80',
                    'https://images.unsplash.com/photo-1583037189850-1921ae7c6c22?w=1200&q=80',
                ],
                'amenities' => ['WiFi gratuito', 'Restaurante regional', 'Bar', 'Estacionamento', 'Serviço de quarto'],
            ],
            [
                'name' => 'Quinta do Café Uíge',
                'property_type' => 'hospedaria',
                'description' => 'Hospedaria boutique numa quinta cafeicola histórica, rodeada pelas famosas plantações de café do Uíge. Uma experiência imersiva no mundo do café angolano: do grão à chávena. Quartos em bungalows de madeira, passeios pelas plantações, provas de café e refeições com produtos da quinta.',
                'address' => 'Fazenda do Café, Maquela do Zombo, Uíge',
                'stars' => 3, 'rating' => 4.8, 'reviews_count' => 54, 'min_price' => 30000, 'is_featured' => true,
                'latitude' => -6.0700, 'longitude' => 14.6800,
                'phone' => '+244 912 233 456', 'email' => 'quintacafe@gmail.com',
                'thumbnail' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=1200&q=80',
                    'https://images.unsplash.com/photo-1541123437800-1bb1317badc2?w=1200&q=80',
                    'https://images.unsplash.com/photo-1568084680786-a84f91d1153c?w=1200&q=80',
                ],
                'amenities' => ['Bungalows na plantação', 'Provas de café', 'Passeios pelas plantações', 'Refeições incluídas', 'WiFi nas áreas comuns', 'Estacionamento gratuito'],
            ],
        ];
        foreach ($hotels as $data) { $this->createHotel($data, $admin, $loc); }
    }

    private function ndalatando(User $admin): void
    {
        $loc = Location::where('name', "N'dalatando")->first();
        if (!$loc) return;

        $hotels = [
            [
                'name' => "Hotel N'dalatando",
                'property_type' => 'hotel',
                'description' => "Capital da província do Cuanza Norte, N'dalatando é uma cidade serrana com clima fresco e paisagens verdejantes. O Hotel N'dalatando é o principal estabelecimento da cidade, com quartos confortáveis, restaurante com especialidades regionais e serviços adequados para viajantes de negócios e turistas.",
                'address' => "Avenida Central, N'dalatando",
                'stars' => 3, 'rating' => 4.0, 'reviews_count' => 87, 'min_price' => 32000, 'is_featured' => false,
                'latitude' => -9.2971, 'longitude' => 14.9125,
                'phone' => '+244 235 220 100', 'email' => 'hotel@ndalatando.ao',
                'thumbnail' => 'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?w=1200&q=80',
                    'https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?w=1200&q=80',
                    'https://images.unsplash.com/photo-1566665797739-1674de7a421a?w=1200&q=80',
                ],
                'amenities' => ['WiFi gratuito', 'Restaurante', 'Bar', 'Estacionamento', 'Serviço de quarto'],
            ],
            [
                'name' => 'Pousada do Kwanza Norte',
                'property_type' => 'hospedaria',
                'description' => 'Pousada familiar às margens do Rio Kwanza, ideal para turismo de natureza. Quartos simples e acolhedores, refeições caseiras com produtos locais, pesca no rio, observação de aves e passeios de canoa. Uma experiência autêntica no coração do Cuanza Norte.',
                'address' => 'Margem do Rio Kwanza, Cuanza Norte',
                'stars' => 2, 'rating' => 4.4, 'reviews_count' => 43, 'min_price' => 22000, 'is_featured' => false,
                'latitude' => -9.1500, 'longitude' => 14.8000,
                'phone' => '+244 912 235 678', 'email' => 'kwanzanorte@pousada.ao',
                'thumbnail' => 'https://images.unsplash.com/photo-1549180030-48bf079fb38a?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1549180030-48bf079fb38a?w=1200&q=80',
                    'https://images.unsplash.com/photo-1523741543316-beb7fc7023d8?w=1200&q=80',
                ],
                'amenities' => ['WiFi nas áreas comuns', 'Refeições caseiras', 'Pesca no rio', 'Canoa', 'Observação de aves', 'Estacionamento gratuito'],
            ],
        ];
        foreach ($hotels as $data) { $this->createHotel($data, $admin, $loc); }
    }

    private function caxito(User $admin): void
    {
        $loc = Location::where('name', 'Caxito')->first();
        if (!$loc) return;

        $hotels = [
            [
                'name' => 'Hotel Bengo Caxito',
                'property_type' => 'hotel',
                'description' => 'A apenas 60km de Luanda, o Hotel Bengo em Caxito é uma opção popular para fim de semana. Rodeado de natureza fluvial e fazendas produtivas, oferece quartos confortáveis, restaurante com peixe fresco do Rio Bengo, piscina e espaços de lazer ao ar livre. Um refúgio tranquilo próximo da capital.',
                'address' => 'Avenida Central, Caxito, Bengo',
                'stars' => 3, 'rating' => 4.3, 'reviews_count' => 134, 'min_price' => 38000, 'is_featured' => false,
                'latitude' => -8.5783, 'longitude' => 13.6644,
                'phone' => '+244 232 220 200', 'email' => 'bengo@hotelbengo.ao',
                'thumbnail' => 'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?w=1200&q=80',
                    'https://images.unsplash.com/photo-1578683010236-d716f9a3f461?w=1200&q=80',
                    'https://images.unsplash.com/photo-1566665797739-1674de7a421a?w=1200&q=80',
                ],
                'amenities' => ['WiFi gratuito', 'Piscina', 'Restaurante', 'Bar', 'Estacionamento amplo', 'Jardim'],
            ],
            [
                'name' => 'Fazenda Eco Lodge Bengo',
                'property_type' => 'resort',
                'description' => 'Eco lodge numa fazenda produtiva às margens do Rio Dande, a 50km de Luanda. Bangalôs com vista para o rio, passeios de barco, pesca, visitas às fazendas de produção de hortícolas e frutas, refeições com produtos frescos e ambiente de descanso total. O escape perfeito do ritmo acelerado de Luanda.',
                'address' => 'Margem do Rio Dande, Bengo',
                'stars' => 3, 'rating' => 4.7, 'reviews_count' => 89, 'min_price' => 55000, 'is_featured' => true,
                'latitude' => -8.4000, 'longitude' => 13.5500,
                'phone' => '+244 924 232 567', 'email' => 'ecolodge@fazendabengo.ao',
                'thumbnail' => 'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?w=1200&q=80',
                    'https://images.unsplash.com/photo-1541123437800-1bb1317badc2?w=1200&q=80',
                    'https://images.unsplash.com/photo-1549180030-48bf079fb38a?w=1200&q=80',
                ],
                'amenities' => ['Bangalôs rio', 'Passeios de barco', 'Pesca', 'Visitas à fazenda', 'Refeições incluídas', 'WiFi nas áreas comuns', 'Estacionamento'],
            ],
        ];
        foreach ($hotels as $data) { $this->createHotel($data, $admin, $loc); }
    }
}

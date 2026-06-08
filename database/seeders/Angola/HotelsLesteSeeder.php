<?php

namespace Database\Seeders\Angola;

use App\Models\Location;
use App\Models\User;

/**
 * Cobre: Dundo (Lunda Norte), Saurimo (Lunda Sul), Kuito (Bié), Luena (Moxico), Sumbe (Cuanza Sul)
 */
class HotelsLesteSeeder
{
    use HotelSeederTrait;

    public function run(User $admin): void
    {
        $this->dundo($admin);
        $this->saurimo($admin);
        $this->kuito($admin);
        $this->luena($admin);
        $this->sumbe($admin);
    }

    private function dundo(User $admin): void
    {
        $loc = Location::where('name', 'Dundo')->first();
        if (!$loc) return;

        $hotels = [
            [
                'name' => 'Hotel Diamante Dundo',
                'property_type' => 'hotel',
                'description' => 'Nomeado em homenagem à riqueza que define a Lunda Norte, o Hotel Diamante é o principal estabelecimento de Dundo. Com quartos confortáveis, restaurante, bar e serviços orientados para executivos da indústria mineira e de diamantes. A cidade de Dundo tem uma atmosfera única marcada pela história colonial da Diamang.',
                'address' => 'Avenida Principal, Dundo, Lunda Norte',
                'stars' => 4, 'rating' => 4.1, 'reviews_count' => 112, 'min_price' => 58000, 'is_featured' => false,
                'latitude' => -7.3833, 'longitude' => 20.8333,
                'phone' => '+244 255 220 300', 'email' => 'diamante@hoteldiamante.ao',
                'thumbnail' => 'https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=1200&q=80',
                    'https://images.unsplash.com/photo-1618773928121-c32242e63f39?w=1200&q=80',
                    'https://images.unsplash.com/photo-1566665797739-1674de7a421a?w=1200&q=80',
                ],
                'amenities' => ['WiFi gratuito', 'Restaurante', 'Bar', 'Business center', 'Estacionamento', 'Serviço de quarto', 'Transfer'],
            ],
            [
                'name' => 'Pousada Lunda Norte',
                'property_type' => 'hospedaria',
                'description' => 'Pousada acolhedora em Dundo que serve de base para explorar a região das Lundas. Quartos simples e funcionais, refeições caseiras, e proprietários com vasto conhecimento das tradições Quioco e dos principais atractivos da região. Próxima do Museu Regional do Dundo, um dos mais ricos de Angola.',
                'address' => 'Rua do Museu, Dundo, Lunda Norte',
                'stars' => 2, 'rating' => 4.3, 'reviews_count' => 54, 'min_price' => 25000, 'is_featured' => false,
                'latitude' => -7.3900, 'longitude' => 20.8400,
                'phone' => '+244 912 255 678', 'email' => 'lundanorte@pousada.ao',
                'thumbnail' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=1200&q=80',
                    'https://images.unsplash.com/photo-1616594039964-ae9021a400a0?w=1200&q=80',
                ],
                'amenities' => ['WiFi nas áreas comuns', 'Refeições caseiras', 'Tours culturais Quioco', 'Visita ao Museu do Dundo', 'Estacionamento gratuito'],
            ],
        ];
        foreach ($hotels as $data) { $this->createHotel($data, $admin, $loc); }
    }

    private function saurimo(User $admin): void
    {
        $loc = Location::where('name', 'Saurimo')->first();
        if (!$loc) return;

        $hotels = [
            [
                'name' => 'Hotel Saurimo',
                'property_type' => 'hotel',
                'description' => 'O Hotel Saurimo é o principal estabelecimento da capital da Lunda Sul, uma região rica em diamantes e em belezas naturais ainda pouco exploradas. Quartos confortáveis com ar condicionado, restaurante, bar e serviços básicos de apoio a viajantes de negócios e técnicos da indústria mineira.',
                'address' => 'Avenida Central, Saurimo, Lunda Sul',
                'stars' => 3, 'rating' => 4.0, 'reviews_count' => 78, 'min_price' => 45000, 'is_featured' => false,
                'latitude' => -9.6608, 'longitude' => 20.3934,
                'phone' => '+244 256 220 200', 'email' => 'saurimo@hotelsaurimo.ao',
                'thumbnail' => 'https://images.unsplash.com/photo-1564501049412-61c2a3083791?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1564501049412-61c2a3083791?w=1200&q=80',
                    'https://images.unsplash.com/photo-1590490360182-c33d57733427?w=1200&q=80',
                    'https://images.unsplash.com/photo-1574362848149-11496d93a7c7?w=1200&q=80',
                ],
                'amenities' => ['WiFi gratuito', 'Restaurante', 'Bar', 'Estacionamento', 'Transfer aeroporto'],
            ],
            [
                'name' => 'Rio Chicapa Lodge',
                'property_type' => 'resort',
                'description' => 'Lodge na margem do Rio Chicapa, rodeado de natureza intocada na Lunda Sul. Uma experiência de ecoturismo autêntica com pesca, passeios de canoa, observação da fauna local e imersão na cultura tradicional das comunidades Quioco. Refeições preparadas com produtos frescos da região.',
                'address' => 'Margem do Rio Chicapa, Lunda Sul',
                'stars' => 3, 'rating' => 4.7, 'reviews_count' => 43, 'min_price' => 65000, 'is_featured' => true,
                'latitude' => -9.5000, 'longitude' => 20.2000,
                'phone' => '+244 924 256 789', 'email' => 'chicapa@riochicapalodge.ao',
                'thumbnail' => 'https://images.unsplash.com/photo-1549180030-48bf079fb38a?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1549180030-48bf079fb38a?w=1200&q=80',
                    'https://images.unsplash.com/photo-1523741543316-beb7fc7023d8?w=1200&q=80',
                    'https://images.unsplash.com/photo-1565348271942-ab9e44c98aad?w=1200&q=80',
                ],
                'amenities' => ['Bangalôs na natureza', 'Pesca no rio', 'Canoa', 'Cultura Quioco', 'Refeições incluídas', 'WiFi nas áreas comuns'],
            ],
        ];
        foreach ($hotels as $data) { $this->createHotel($data, $admin, $loc); }
    }

    private function kuito(User $admin): void
    {
        $loc = Location::where('name', 'Kuito')->first();
        if (!$loc) return;

        $hotels = [
            [
                'name' => 'Hotel Kuito',
                'property_type' => 'hotel',
                'description' => 'O Hotel Kuito é o principal estabelecimento da capital do Bié, uma das províncias mais férteis do planalto central. A cidade tem uma história marcante da guerra civil, e o hotel é um símbolo de reconstrução e resiliência. Quartos modernos, restaurante com cozinha regional e serviços de qualidade.',
                'address' => 'Avenida Principal, Kuito, Bié',
                'stars' => 3, 'rating' => 4.2, 'reviews_count' => 134, 'min_price' => 38000, 'is_featured' => false,
                'latitude' => -12.3793, 'longitude' => 16.9376,
                'phone' => '+244 248 220 400', 'email' => 'hotel@hotelkuito.ao',
                'thumbnail' => 'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?w=1200&q=80',
                    'https://images.unsplash.com/photo-1631049035182-249067d7618e?w=1200&q=80',
                    'https://images.unsplash.com/photo-1583037189850-1921ae7c6c22?w=1200&q=80',
                ],
                'amenities' => ['WiFi gratuito', 'Restaurante regional', 'Bar', 'Estacionamento', 'Serviço de quarto'],
            ],
            [
                'name' => 'Pousada do Planalto Bié',
                'property_type' => 'hospedaria',
                'description' => 'Pousada rural no coração do Bié, com vistas sobre as colinas verdejantes do planalto. Quartos acolhedores, pequeno-almoço com produtos locais e proprietários que partilham histórias sobre a história e cultura da região. Um destino autêntico para quem quer conhecer a Angola profunda.',
                'address' => 'Estrada do Planalto, Bié',
                'stars' => 2, 'rating' => 4.5, 'reviews_count' => 67, 'min_price' => 20000, 'is_featured' => false,
                'latitude' => -12.4000, 'longitude' => 16.9000,
                'phone' => '+244 912 248 456', 'email' => 'planaltobic@pousada.ao',
                'thumbnail' => 'https://images.unsplash.com/photo-1600047509807-ba8f99d2cdde?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1600047509807-ba8f99d2cdde?w=1200&q=80',
                    'https://images.unsplash.com/photo-1568084680786-a84f91d1153c?w=1200&q=80',
                ],
                'amenities' => ['WiFi nas áreas comuns', 'Pequeno-almoço incluído', 'Jardim', 'Tours culturais', 'Estacionamento gratuito'],
            ],
        ];
        foreach ($hotels as $data) { $this->createHotel($data, $admin, $loc); }
    }

    private function luena(User $admin): void
    {
        $loc = Location::where('name', 'Luena')->first();
        if (!$loc) return;

        $hotels = [
            [
                'name' => 'Hotel Moxico Luena',
                'property_type' => 'hotel',
                'description' => 'O Hotel Moxico é o principal estabelecimento de Luena, capital da maior província de Angola em extensão. Situado numa região rica em rios e savanas, o hotel oferece quartos confortáveis, restaurante com cozinha local, bar e serviços para viajantes que exploram esta vasta região ainda pouco turística.',
                'address' => 'Avenida Principal, Luena, Moxico',
                'stars' => 3, 'rating' => 4.1, 'reviews_count' => 89, 'min_price' => 40000, 'is_featured' => false,
                'latitude' => -11.7833, 'longitude' => 19.9167,
                'phone' => '+244 254 220 300', 'email' => 'moxico@hotelmoxico.ao',
                'thumbnail' => 'https://images.unsplash.com/photo-1564501049412-61c2a3083791?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1564501049412-61c2a3083791?w=1200&q=80',
                    'https://images.unsplash.com/photo-1590490360182-c33d57733427?w=1200&q=80',
                    'https://images.unsplash.com/photo-1574362848149-11496d93a7c7?w=1200&q=80',
                ],
                'amenities' => ['WiFi gratuito', 'Restaurante', 'Bar', 'Estacionamento', 'Transfer'],
            ],
            [
                'name' => 'Zambeze Safari Lodge',
                'property_type' => 'resort',
                'description' => 'Lodge de safari à beira do Rio Zambeze, onde Angola, Zâmbia e Namíbia se encontram. Uma experiência de natureza selvagem única com leões, elefantes, hipopótomos e uma biodiversidade extraordinária. Tendas de luxo, refeições gourmet ao ar livre e guias experientes. O destino mais exclusivo do leste angolano.',
                'address' => 'Rio Zambeze, Parque Nacional do Luengue-Luiana, Moxico',
                'stars' => 5, 'rating' => 4.9, 'reviews_count' => 56, 'min_price' => 250000, 'is_featured' => true,
                'latitude' => -13.5000, 'longitude' => 22.5000,
                'phone' => '+244 924 254 890', 'email' => 'zambeze@safariodge.ao',
                'thumbnail' => 'https://images.unsplash.com/photo-1523741543316-beb7fc7023d8?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1523741543316-beb7fc7023d8?w=1200&q=80',
                    'https://images.unsplash.com/photo-1565348271942-ab9e44c98aad?w=1200&q=80',
                    'https://images.unsplash.com/photo-1549180030-48bf079fb38a?w=1200&q=80',
                    'https://images.unsplash.com/photo-1509316785289-025f5b846b35?w=1200&q=80',
                ],
                'amenities' => ['Tendas de luxo', 'Refeições gourmet incluídas', 'Safaris diurnos e nocturnos', 'Passeios de barco no Zambeze', 'Guias especializados', 'WiFi nas áreas comuns', 'Transfer privado'],
            ],
        ];
        foreach ($hotels as $data) { $this->createHotel($data, $admin, $loc); }
    }

    private function sumbe(User $admin): void
    {
        $loc = Location::where('name', 'Sumbe')->first();
        if (!$loc) return;

        $hotels = [
            [
                'name' => 'Hotel Sumbe',
                'property_type' => 'hotel',
                'description' => 'O Hotel Sumbe é o principal estabelecimento desta cidade costeira do Cuanza Sul, conhecida pelas suas praias tranquilas e pela produção de sal. Quartos com vista para o oceano, restaurante com especialidades de peixe e marisco, bar e piscina. Uma alternativa menos agitada às praias do Benguela.',
                'address' => 'Avenida Marginal, Sumbe, Cuanza Sul',
                'stars' => 3, 'rating' => 4.2, 'reviews_count' => 98, 'min_price' => 42000, 'is_featured' => false,
                'latitude' => -11.2061, 'longitude' => 13.8428,
                'phone' => '+244 237 220 100', 'email' => 'sumbe@hotelsumbe.ao',
                'thumbnail' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=1200&q=80',
                    'https://images.unsplash.com/photo-1590490360182-c33d57733427?w=1200&q=80',
                    'https://images.unsplash.com/photo-1566665797739-1674de7a421a?w=1200&q=80',
                ],
                'amenities' => ['WiFi gratuito', 'Piscina', 'Restaurante frutos do mar', 'Bar', 'Vista para o oceano', 'Estacionamento'],
            ],
            [
                'name' => 'Praia das Salinas Resort Sumbe',
                'property_type' => 'resort',
                'description' => 'Resort tranquilo junto às famosas salinas de Sumbe, numa praia selvagem e praticamente deserta. Bangalôs de madeira à beira-mar, piscina de água salgada, restaurante com peixe do dia, e actividades aquáticas. Ideal para quem procura praias virgens longe do turismo de massas.',
                'address' => 'Praia das Salinas, Sumbe, Cuanza Sul',
                'stars' => 3, 'rating' => 4.6, 'reviews_count' => 67, 'min_price' => 65000, 'is_featured' => true,
                'latitude' => -11.1800, 'longitude' => 13.8200,
                'phone' => '+244 924 237 456', 'email' => 'salinas@praiadasalinas.ao',
                'thumbnail' => 'https://images.unsplash.com/photo-1499793983690-e29da59ef1c2?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1499793983690-e29da59ef1c2?w=1200&q=80',
                    'https://images.unsplash.com/photo-1602391833977-358a52198938?w=1200&q=80',
                    'https://images.unsplash.com/photo-1540541338287-41700207dee6?w=1200&q=80',
                ],
                'amenities' => ['Bangalôs à beira-mar', 'Praia privativa', 'Piscina salgada', 'Restaurante', 'Desportos aquáticos', 'WiFi nas áreas comuns', 'Estacionamento'],
            ],
        ];
        foreach ($hotels as $data) { $this->createHotel($data, $admin, $loc); }
    }
}

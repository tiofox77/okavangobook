<?php

namespace Database\Seeders\Angola;

use App\Models\Location;
use App\Models\User;

/**
 * Hotéis REAIS de Cabinda, Soyo (Zaire), Malanje e Kalandula
 * Fonte: hoteisangola.com — sem duplicar os já criados nos seeders anteriores
 */
class HotelsRealCabindaMalanjeSeeder
{
    use HotelSeederTrait;

    public function run(User $admin): void
    {
        $this->cabinda($admin);
        $this->soyo($admin);
        $this->malanje($admin);
    }

    private function cabinda(User $admin): void
    {
        $loc = Location::where('name', 'Cabinda')->first();
        if (!$loc) return;

        $hotels = [
            [
                'name' => 'HAL Hotel Infotur Cabinda',
                'property_type' => 'hotel',
                'description' => 'O HAL Hotel Infotur é o hotel oficial do governo angolano em Cabinda, gerido pelo Instituto de Fomento Turístico. Com uma localização central e instalações completas — quartos confortáveis, restaurante, bar e salas de conferência — é muito utilizado por delegações governamentais, empresariais e missões internacionais que visitam o enclave.',
                'address' => 'Avenida do 1º de Agosto, Cabinda',
                'stars' => 4, 'rating' => 4.2, 'reviews_count' => 134, 'min_price' => 72000, 'is_featured' => false,
                'latitude' => -5.5480, 'longitude' => 12.1880,
                'phone' => '+244 231 221 000', 'email' => 'infotur@halinfotur.ao',
                'thumbnail' => 'https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=1200&q=80',
                    'https://images.unsplash.com/photo-1596386461350-326ccb383e9f?w=1200&q=80',
                    'https://images.unsplash.com/photo-1578683010236-d716f9a3f461?w=1200&q=80',
                    'https://images.unsplash.com/photo-1583037189850-1921ae7c6c22?w=1200&q=80',
                ],
                'amenities' => ['WiFi gratuito', 'Restaurante', 'Bar', 'Salas de conferência', 'Business center', 'Ginásio', 'Estacionamento', 'Transfer'],
            ],
            [
                'name' => 'Hotel Apolonia Complexus Cabinda',
                'property_type' => 'hotel',
                'description' => 'Um dos mais modernos e completos hotéis de Cabinda, o Apolonia Complexus destaca-se pela amplitude das suas instalações. Oferece desde quartos standard a suítes presidenciais, restaurante internacional, piscina exterior, spa, ginásio e o maior espaço de eventos do enclave. Muito frequentado por executivos da indústria petrolífera.',
                'address' => 'Bairro Lombo, Cabinda',
                'stars' => 5, 'rating' => 4.5, 'reviews_count' => 187, 'min_price' => 110000, 'is_featured' => true,
                'latitude' => -5.5460, 'longitude' => 12.1920,
                'phone' => '+244 231 223 000', 'email' => 'apolonia@apoloniahotel.ao',
                'thumbnail' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=1200&q=80',
                    'https://images.unsplash.com/photo-1618773928121-c32242e63f39?w=1200&q=80',
                    'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=1200&q=80',
                    'https://images.unsplash.com/photo-1519690889869-e705e59f72e1?w=1200&q=80',
                    'https://images.unsplash.com/photo-1566665797739-1674de7a421a?w=1200&q=80',
                ],
                'amenities' => ['WiFi gratuito', 'Piscina exterior', 'Spa & wellness', 'Restaurante internacional', 'Bar', 'Ginásio', 'Espaço de eventos', 'Business center', 'Transfer aeroporto', 'Estacionamento coberto'],
            ],
            [
                'name' => 'Gerlu Hotel Cabinda',
                'property_type' => 'hotel',
                'description' => 'Hotel moderno e bem posicionado no centro de Cabinda, com quartos elegantes desde standard a suítes executivas. O Gerlu distingue-se pelo serviço personalizado, restaurante com cozinha variada e os seus duplex — apartamentos de dois andares ideais para famílias. Uma escolha de excelência para quem visita o enclave.',
                'address' => 'Rua da Independência, Cabinda',
                'stars' => 4, 'rating' => 4.4, 'reviews_count' => 156, 'min_price' => 80000, 'is_featured' => true,
                'latitude' => -5.5510, 'longitude' => 12.1910,
                'phone' => '+244 231 224 500', 'email' => 'gerlu@gerluhotel.ao',
                'thumbnail' => 'https://images.unsplash.com/photo-1564501049412-61c2a3083791?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1564501049412-61c2a3083791?w=1200&q=80',
                    'https://images.unsplash.com/photo-1590490360182-c33d57733427?w=1200&q=80',
                    'https://images.unsplash.com/photo-1611892440504-42a792e24d32?w=1200&q=80',
                    'https://images.unsplash.com/photo-1574362848149-11496d93a7c7?w=1200&q=80',
                ],
                'amenities' => ['WiFi gratuito', 'Restaurante', 'Bar', 'Ginásio', 'Apartamentos duplex', 'Business center', 'Estacionamento', 'Transfer'],
            ],
            [
                'name' => 'Futila Beach Resort Cabinda',
                'property_type' => 'resort',
                'description' => 'Resort de praia em Futila, uma das mais belas praias do enclave de Cabinda, com areia branca e palmeiras tropicais. Bangalôs à beira-mar rodeados de vegetação tropical, piscina, restaurante com frutos do mar frescos e actividades aquáticas. O escape perfeito da agitação petrolífera de Cabinda cidade.',
                'address' => 'Praia de Futila, Cabinda',
                'stars' => 4, 'rating' => 4.6, 'reviews_count' => 98, 'min_price' => 95000, 'is_featured' => true,
                'latitude' => -5.4500, 'longitude' => 12.2400,
                'phone' => '+244 924 231 789', 'email' => 'futila@futilaresort.ao',
                'thumbnail' => 'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?w=1200&q=80',
                    'https://images.unsplash.com/photo-1499793983690-e29da59ef1c2?w=1200&q=80',
                    'https://images.unsplash.com/photo-1540541338287-41700207dee6?w=1200&q=80',
                    'https://images.unsplash.com/photo-1596436889106-be35e843f974?w=1200&q=80',
                ],
                'amenities' => ['Bangalôs à beira-mar', 'Piscina tropical', 'Restaurante frutos do mar', 'Desportos aquáticos', 'Snorkeling', 'WiFi nas áreas comuns', 'Estacionamento'],
            ],
        ];
        foreach ($hotels as $data) { $this->createHotel($data, $admin, $loc); }
    }

    private function soyo(User $admin): void
    {
        $loc = Location::where('name', 'Soyo')->first();
        if (!$loc) return;

        $hotels = [
            [
                'name' => 'Hotel Nempanzu Soyo',
                'property_type' => 'hotel',
                'description' => 'O Hotel Nempanzu é um dos principais estabelecimentos de Soyo, nomeado em homenagem a um tradicional rei da região do Zaire. Com quartos confortáveis e bem equipados, restaurante com cozinha local e internacional, bar e serviços de apoio a executivos da indústria petrolífera e gasífera que dominam a actividade económica desta cidade portuária.',
                'address' => 'Avenida da Foz do Congo, Soyo, Zaire',
                'stars' => 4, 'rating' => 4.3, 'reviews_count' => 112, 'min_price' => 72000, 'is_featured' => false,
                'latitude' => -6.1310, 'longitude' => 12.3620,
                'phone' => '+244 236 221 500', 'email' => 'nempanzu@hotelnempanzu.ao',
                'thumbnail' => 'https://images.unsplash.com/photo-1596386461350-326ccb383e9f?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1596386461350-326ccb383e9f?w=1200&q=80',
                    'https://images.unsplash.com/photo-1618773928121-c32242e63f39?w=1200&q=80',
                    'https://images.unsplash.com/photo-1566665797739-1674de7a421a?w=1200&q=80',
                    'https://images.unsplash.com/photo-1574362848149-11496d93a7c7?w=1200&q=80',
                ],
                'amenities' => ['WiFi gratuito', 'Restaurante', 'Bar', 'Business center', 'Ginásio', 'Transfer aeroporto', 'Estacionamento'],
            ],
            [
                'name' => 'Kinwica Resort Hotel Soyo',
                'property_type' => 'resort',
                'description' => 'O Kinwica Resort é o mais completo complexo turístico de Soyo, com instalações hoteleiras e de resort numa localização privilegiada junto ao Rio Congo e ao Oceano Atlântico. Quartos e suítes com vista para o rio, piscina exterior, restaurante premium, bar e amplas áreas de lazer. O destino preferido de famílias e grupos corporativos em Soyo.',
                'address' => 'Margem do Rio Congo, Soyo, Zaire',
                'stars' => 4, 'rating' => 4.5, 'reviews_count' => 134, 'min_price' => 90000, 'is_featured' => true,
                'latitude' => -6.1280, 'longitude' => 12.3590,
                'phone' => '+244 236 223 000', 'email' => 'kinwica@kinwicaresort.ao',
                'thumbnail' => 'https://images.unsplash.com/photo-1549180030-48bf079fb38a?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1549180030-48bf079fb38a?w=1200&q=80',
                    'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?w=1200&q=80',
                    'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=1200&q=80',
                    'https://images.unsplash.com/photo-1519690889869-e705e59f72e1?w=1200&q=80',
                    'https://images.unsplash.com/photo-1541123437800-1bb1317badc2?w=1200&q=80',
                ],
                'amenities' => ['WiFi gratuito', 'Piscina exterior', 'Restaurante premium', 'Bar', 'Vista para o rio', 'Ginásio', 'Espaço de eventos', 'Transfer', 'Estacionamento'],
            ],
        ];
        foreach ($hotels as $data) { $this->createHotel($data, $admin, $loc); }
    }

    private function malanje(User $admin): void
    {
        $loc = Location::where('name', 'Malanje')->first();
        if (!$loc) return;

        $hotels = [
            [
                'name' => 'Pousada Quedas Duque de Bragança',
                'property_type' => 'hospedaria',
                'description' => 'A única pousada situada mesmo nas imediações das famosas Quedas de Kalandula (antigas Quedas do Duque de Bragança), a maior cascata de África em volume de água. Com apenas 12 quartos e uma localização privilegiada, oferece o acesso mais directo e exclusivo às quedas. O som permanente da água e a névoa matinal criam uma atmosfera mágica. Refeições caseiras incluídas e guias locais disponíveis.',
                'address' => 'Quedas de Kalandula, Kalandula, Malanje',
                'stars' => 3, 'rating' => 4.8, 'reviews_count' => 156, 'min_price' => 38000, 'is_featured' => true,
                'latitude' => -9.0450, 'longitude' => 16.0200,
                'phone' => '+244 912 251 456', 'email' => 'quedas@pousadaduque.ao',
                'thumbnail' => 'https://images.unsplash.com/photo-1523741543316-beb7fc7023d8?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1523741543316-beb7fc7023d8?w=1200&q=80',
                    'https://images.unsplash.com/photo-1565348271942-ab9e44c98aad?w=1200&q=80',
                    'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=1200&q=80',
                    'https://images.unsplash.com/photo-1541123437800-1bb1317badc2?w=1200&q=80',
                ],
                'amenities' => ['WiFi nas áreas comuns', 'Refeições incluídas', 'Acesso directo às quedas', 'Guias locais', 'Trilhas guiadas', 'Estacionamento', 'Miradouro privativo'],
            ],
            [
                'name' => 'Hotel Luxo Malanje',
                'property_type' => 'hotel',
                'description' => 'Hotel moderno e bem equipado no centro de Malanje, a capital de uma das províncias mais ricas em recursos naturais e atracções turísticas de Angola. Com quartos confortáveis, restaurante com cozinha regional premiada, bar e salas de reunião, é o ponto de partida ideal para explorar as Quedas de Kalandula e as Pedras Negras de Pungo Andongo.',
                'address' => 'Avenida Comandante Bula, Malanje',
                'stars' => 4, 'rating' => 4.3, 'reviews_count' => 123, 'min_price' => 52000, 'is_featured' => false,
                'latitude' => -9.5380, 'longitude' => 16.3510,
                'phone' => '+244 251 223 000', 'email' => 'luxo@hotelluxo.ao',
                'thumbnail' => 'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?w=1200&q=80',
                    'https://images.unsplash.com/photo-1590490360182-c33d57733427?w=1200&q=80',
                    'https://images.unsplash.com/photo-1566665797739-1674de7a421a?w=1200&q=80',
                ],
                'amenities' => ['WiFi gratuito', 'Restaurante regional', 'Bar', 'Salas de reunião', 'Tours Kalandula', 'Tours Pungo Andongo', 'Estacionamento'],
            ],
            [
                'name' => 'Cacuso River Lodge Malanje',
                'property_type' => 'resort',
                'description' => 'Lodge de natureza na margem do Rio Lucala em Cacuso, a poucos quilómetros de Malanje. O Rio Lucala alimenta as Quedas de Kalandula e tem uma beleza cênica extraordinária. Bangalôs à beira do rio, pesca, canoagem, e expedições guiadas às quedas. Uma base alternativa e mais íntima para explorar as maravilhas da região.',
                'address' => 'Margem do Rio Lucala, Cacuso, Malanje',
                'stars' => 3, 'rating' => 4.7, 'reviews_count' => 67, 'min_price' => 58000, 'is_featured' => true,
                'latitude' => -9.2000, 'longitude' => 15.7500,
                'phone' => '+244 924 252 345', 'email' => 'cacuso@riolvlodge.ao',
                'thumbnail' => 'https://images.unsplash.com/photo-1549180030-48bf079fb38a?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1549180030-48bf079fb38a?w=1200&q=80',
                    'https://images.unsplash.com/photo-1523741543316-beb7fc7023d8?w=1200&q=80',
                    'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=1200&q=80',
                ],
                'amenities' => ['Bangalôs no rio', 'Pesca', 'Canoagem', 'Excursões Quedas Kalandula', 'Refeições incluídas', 'WiFi nas áreas comuns', 'Estacionamento'],
            ],
        ];
        foreach ($hotels as $data) { $this->createHotel($data, $admin, $loc); }
    }
}

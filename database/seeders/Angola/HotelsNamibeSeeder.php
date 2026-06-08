<?php

namespace Database\Seeders\Angola;

use App\Models\Location;
use App\Models\User;

class HotelsNamibeSeeder
{
    use HotelSeederTrait;

    public function run(User $admin): void
    {
        $loc = Location::where('name', 'Namibe')->first();
        if (!$loc) return;

        $hotels = [
            [
                'name' => 'Hotel Namibe',
                'property_type' => 'hotel',
                'description' => 'O Hotel Namibe é o principal estabelecimento hoteleiro da cidade, situado no centro com vista para o Oceano Atlântico. Com quartos confortáveis e espaçosos, restaurante especializado em lagosta e peixe fresco, bar e piscina exterior, é a escolha natural para quem visita esta cidade única onde o deserto encontra o mar.',
                'address' => 'Avenida do Mar, Namibe',
                'stars' => 4, 'rating' => 4.3, 'reviews_count' => 243, 'min_price' => 55000, 'is_featured' => true,
                'latitude' => -15.1961, 'longitude' => 12.1522,
                'phone' => '+244 264 260 200', 'email' => 'hotel@hotelnamibe.ao',
                'thumbnail' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=1200&q=80',
                    'https://images.unsplash.com/photo-1590490360182-c33d57733427?w=1200&q=80',
                    'https://images.unsplash.com/photo-1578683010236-d716f9a3f461?w=1200&q=80',
                    'https://images.unsplash.com/photo-1566665797739-1674de7a421a?w=1200&q=80',
                ],
                'amenities' => ['WiFi gratuito', 'Piscina', 'Restaurante lagosta & peixe', 'Bar', 'Vista para o mar', 'Estacionamento', 'Tours ao deserto'],
            ],
            [
                'name' => 'Desert Star Lodge Namibe',
                'property_type' => 'resort',
                'description' => 'Lodge de luxo único no mundo, situado na orla do Deserto do Namibe — o deserto mais antigo do mundo. Bangalôs com vista 360º para as dunas e o oceano, piscina de água salgada, restaurante com ingredientes do deserto e do mar, e excursões privativas guiadas ao deserto. Uma experiência absolutamente inesquecível.',
                'address' => 'Deserto do Namibe, 40km a norte de Namibe',
                'stars' => 5, 'rating' => 4.9, 'reviews_count' => 178, 'min_price' => 180000, 'is_featured' => true,
                'latitude' => -14.9500, 'longitude' => 12.0500,
                'phone' => '+244 924 787 890', 'email' => 'reservas@desertstarlodge.ao',
                'thumbnail' => 'https://images.unsplash.com/photo-1509316785289-025f5b846b35?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1509316785289-025f5b846b35?w=1200&q=80',
                    'https://images.unsplash.com/photo-1509099381441-ea3c0cf98b94?w=1200&q=80',
                    'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=1200&q=80',
                    'https://images.unsplash.com/photo-1519690889869-e705e59f72e1?w=1200&q=80',
                    'https://images.unsplash.com/photo-1523741543316-beb7fc7023d8?w=1200&q=80',
                ],
                'amenities' => ['Bangalôs privados vista deserto', 'Piscina de água salgada', 'Restaurante gourmet', 'Excursões ao deserto', 'Observação de estrelas', 'Quadriciclos', 'WiFi nas áreas comuns', 'Transfer privado'],
            ],
            [
                'name' => 'Casa do Deserto Namibe',
                'property_type' => 'hospedaria',
                'description' => 'No coração do Namibe, a Casa do Deserto é uma hospedaria boutique que celebra a cultura e tradições locais. Com apenas 8 quartos decorados com artesanato angolano, oferece uma experiência íntima e acolhedora. Terraço com vista para o deserto, refeições caseiras com ingredientes locais e hospitalidade genuína.',
                'address' => 'Rua da Liberdade, Centro Histórico, Namibe',
                'stars' => 3, 'rating' => 4.6, 'reviews_count' => 89, 'min_price' => 25000, 'is_featured' => false,
                'latitude' => -15.1944, 'longitude' => 12.1556,
                'phone' => '+244 264 123 456', 'email' => 'contato@casadodeserto.ao',
                'thumbnail' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=1200&q=80',
                    'https://images.unsplash.com/photo-1616594039964-ae9021a400a0?w=1200&q=80',
                    'https://images.unsplash.com/photo-1541123437800-1bb1317badc2?w=1200&q=80',
                ],
                'amenities' => ['WiFi gratuito', 'Pequeno-almoço incluído', 'Terraço panorâmico', 'Cozinha partilhada', 'Tours locais', 'Artesanato à venda', 'Estacionamento'],
            ],
            [
                'name' => 'Hotel Flamingo Namibe',
                'property_type' => 'hotel',
                'description' => 'Hotel familiar em frente à Praia do Namibe, com vista directa para o oceano. Quartos com varanda virada ao mar, restaurante com peixe grelhado e marisco, bar e pequena piscina. A natureza selvagem da região é o principal atractivo, com colónias de flamingos visíveis a poucas distâncias.',
                'address' => 'Praia do Namibe, Namibe',
                'stars' => 3, 'rating' => 4.2, 'reviews_count' => 134, 'min_price' => 35000, 'is_featured' => false,
                'latitude' => -15.2000, 'longitude' => 12.1400,
                'phone' => '+244 264 261 500', 'email' => 'flamingo@hotelflamingo.ao',
                'thumbnail' => 'https://images.unsplash.com/photo-1602391833977-358a52198938?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1602391833977-358a52198938?w=1200&q=80',
                    'https://images.unsplash.com/photo-1591088398332-8a7791972843?w=1200&q=80',
                    'https://images.unsplash.com/photo-1583037189850-1921ae7c6c22?w=1200&q=80',
                ],
                'amenities' => ['WiFi gratuito', 'Vista para o oceano', 'Restaurante', 'Bar', 'Piscina', 'Tours flamingos', 'Estacionamento'],
            ],
            [
                'name' => 'Arco Iris Lodge Namibe',
                'property_type' => 'resort',
                'description' => 'Lodge ecológico no Parque Nacional do Iona, na fronteira com a Namíbia. Tendas de luxo com camas reais, refeições preparadas com produtos locais e guias especializados em fauna e flora do deserto do Namibe. Uma das experiências de ecoturismo mais exclusivas de Angola.',
                'address' => 'Parque Nacional do Iona, Namibe',
                'stars' => 4, 'rating' => 4.8, 'reviews_count' => 67, 'min_price' => 150000, 'is_featured' => true,
                'latitude' => -16.7000, 'longitude' => 12.3500,
                'phone' => '+244 924 890 123', 'email' => 'iona@arcoirislodge.ao',
                'thumbnail' => 'https://images.unsplash.com/photo-1523741543316-beb7fc7023d8?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1523741543316-beb7fc7023d8?w=1200&q=80',
                    'https://images.unsplash.com/photo-1565348271942-ab9e44c98aad?w=1200&q=80',
                    'https://images.unsplash.com/photo-1509316785289-025f5b846b35?w=1200&q=80',
                    'https://images.unsplash.com/photo-1541123437800-1bb1317badc2?w=1200&q=80',
                ],
                'amenities' => ['Tendas de luxo', 'Refeições incluídas', 'Guias fauna & flora', 'Safari fotográfico', 'Observação de estrelas', 'WiFi nas áreas comuns'],
            ],
        ];

        foreach ($hotels as $data) {
            $this->createHotel($data, $admin, $loc);
        }
    }
}

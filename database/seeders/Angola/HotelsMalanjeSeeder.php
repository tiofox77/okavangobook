<?php

namespace Database\Seeders\Angola;

use App\Models\Location;
use App\Models\User;

class HotelsMalanjeSeeder
{
    use HotelSeederTrait;

    public function run(User $admin): void
    {
        $loc = Location::where('name', 'Malanje')->first();
        if (!$loc) return;

        $hotels = [
            [
                'name' => 'Hotel Quedas de Kalandula',
                'property_type' => 'hotel',
                'description' => 'Situado a poucos quilómetros das magníficas Quedas de Kalandula — as maiores cascatas de África em volume de água — este hotel é o ponto de partida perfeito para explorar uma das maravilhas naturais de Angola. Quartos com vista para a floresta, restaurante com cozinha regional e serviços de guia turístico especializados.',
                'address' => 'Via Kalandula, Malanje',
                'stars' => 3, 'rating' => 4.5, 'reviews_count' => 198, 'min_price' => 40000, 'is_featured' => true,
                'latitude' => -9.0500, 'longitude' => 16.0300,
                'phone' => '+244 251 220 100', 'email' => 'kalandula@hotelkalandula.ao',
                'thumbnail' => 'https://images.unsplash.com/photo-1523741543316-beb7fc7023d8?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1523741543316-beb7fc7023d8?w=1200&q=80',
                    'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=1200&q=80',
                    'https://images.unsplash.com/photo-1541123437800-1bb1317badc2?w=1200&q=80',
                    'https://images.unsplash.com/photo-1590490360182-c33d57733427?w=1200&q=80',
                ],
                'amenities' => ['WiFi gratuito', 'Restaurante regional', 'Bar', 'Tours Quedas Kalandula', 'Guias especializados', 'Estacionamento', 'Serviço de quarto'],
            ],
            [
                'name' => 'Pousada Pedras Negras Malanje',
                'property_type' => 'hospedaria',
                'description' => 'Pousada rústica e autêntica nas imediações das Pedras Negras de Pungo Andongo — uma das formações rochosas mais espectaculares de Angola. Quartos simples com decoração local, refeições caseiras e proprietários que conhecem cada pedra da região. Um destino imprescindível para os amantes de geologia e paisagens únicas.',
                'address' => 'Pungo Andongo, Malanje',
                'stars' => 2, 'rating' => 4.6, 'reviews_count' => 87, 'min_price' => 20000, 'is_featured' => false,
                'latitude' => -9.6800, 'longitude' => 15.7700,
                'phone' => '+244 912 987 654', 'email' => 'pedrasnegras@pousada.ao',
                'thumbnail' => 'https://images.unsplash.com/photo-1565348271942-ab9e44c98aad?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1565348271942-ab9e44c98aad?w=1200&q=80',
                    'https://images.unsplash.com/photo-1509316785289-025f5b846b35?w=1200&q=80',
                    'https://images.unsplash.com/photo-1616594039964-ae9021a400a0?w=1200&q=80',
                ],
                'amenities' => ['WiFi nas áreas comuns', 'Refeições caseiras incluídas', 'Tours Pedras Negras', 'Camping organizado', 'Estacionamento gratuito'],
            ],
            [
                'name' => 'Hotel Malanje',
                'property_type' => 'hotel',
                'description' => 'O Hotel Malanje é o principal estabelecimento da cidade, com quartos modernos, restaurante com cozinha angolana e internacional, bar e salas de reunião. Localização central com acesso fácil aos serviços da cidade. Base ideal para excursões às Quedas de Kalandula e às Pedras Negras de Pungo Andongo.',
                'address' => 'Avenida Comandante Dangereux, Malanje',
                'stars' => 4, 'rating' => 4.2, 'reviews_count' => 156, 'min_price' => 48000, 'is_featured' => false,
                'latitude' => -9.5402, 'longitude' => 16.3534,
                'phone' => '+244 251 221 500', 'email' => 'malanje@hotelmalanje.ao',
                'thumbnail' => 'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?w=1200&q=80',
                    'https://images.unsplash.com/photo-1618773928121-c32242e63f39?w=1200&q=80',
                    'https://images.unsplash.com/photo-1566665797739-1674de7a421a?w=1200&q=80',
                ],
                'amenities' => ['WiFi gratuito', 'Restaurante', 'Bar', 'Salas de reunião', 'Estacionamento', 'Transfer excursões'],
            ],
            [
                'name' => 'Rio Kwanza Lodge Malanje',
                'property_type' => 'resort',
                'description' => 'Lodge à beira do majestoso Rio Kwanza, o maior rio inteiramente angolano. Bangalôs sobre pilotis com vista para o rio, pesca desportiva, canoagem, observação de aves e refeições preparadas com peixe fresco do rio. Uma experiência de natureza pura no coração de Angola.',
                'address' => 'Margem do Rio Kwanza, Malanje',
                'stars' => 4, 'rating' => 4.8, 'reviews_count' => 93, 'min_price' => 90000, 'is_featured' => true,
                'latitude' => -9.4000, 'longitude' => 16.2000,
                'phone' => '+244 924 123 456', 'email' => 'kwanza@riokwanzalodge.ao',
                'thumbnail' => 'https://images.unsplash.com/photo-1549180030-48bf079fb38a?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1549180030-48bf079fb38a?w=1200&q=80',
                    'https://images.unsplash.com/photo-1523741543316-beb7fc7023d8?w=1200&q=80',
                    'https://images.unsplash.com/photo-1541123437800-1bb1317badc2?w=1200&q=80',
                    'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=1200&q=80',
                ],
                'amenities' => ['Bangalôs sobre o rio', 'Pesca desportiva', 'Canoagem', 'Observação de aves', 'Refeições incluídas', 'WiFi nas áreas comuns', 'Transfer'],
            ],
        ];

        foreach ($hotels as $data) {
            $this->createHotel($data, $admin, $loc);
        }
    }
}

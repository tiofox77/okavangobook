<?php

namespace Database\Seeders\Angola;

use App\Models\Location;
use App\Models\User;

/**
 * Cobre: Ondjiva (Cunene), Menongue (Cuando Cubango)
 */
class HotelsSulSeeder
{
    use HotelSeederTrait;

    public function run(User $admin): void
    {
        $this->ondjiva($admin);
        $this->menongue($admin);
    }

    private function ondjiva(User $admin): void
    {
        $loc = Location::where('name', 'Ondjiva')->first();
        if (!$loc) return;

        $hotels = [
            [
                'name' => 'Hotel Ondjiva',
                'property_type' => 'hotel',
                'description' => 'O Hotel Ondjiva é o principal estabelecimento da capital do Cunene, no extremo sul de Angola, junto à fronteira com a Namíbia. A região é habitada pelo povo Cuanhama e tem tradições culturais únicas. O hotel oferece quartos confortáveis com ar condicionado, restaurante com cozinha regional e serviços de apoio ao viajante.',
                'address' => 'Avenida Principal, Ondjiva, Cunene',
                'stars' => 3, 'rating' => 4.0, 'reviews_count' => 87, 'min_price' => 38000, 'is_featured' => false,
                'latitude' => -17.0667, 'longitude' => 15.7333,
                'phone' => '+244 265 220 100', 'email' => 'ondjiva@hotelondjiva.ao',
                'thumbnail' => 'https://images.unsplash.com/photo-1564501049412-61c2a3083791?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1564501049412-61c2a3083791?w=1200&q=80',
                    'https://images.unsplash.com/photo-1590490360182-c33d57733427?w=1200&q=80',
                    'https://images.unsplash.com/photo-1566665797739-1674de7a421a?w=1200&q=80',
                ],
                'amenities' => ['WiFi gratuito', 'Restaurante regional', 'Bar', 'Estacionamento', 'Transfer fronteira Namíbia'],
            ],
            [
                'name' => 'Cuanhama Cultural Lodge',
                'property_type' => 'resort',
                'description' => 'Lodge imersivo na cultura Cuanhama, o povo mais numeroso do Cunene. Bangalôs tradicionais modernizados, refeições tradicionais, danças e cerimónias culturais, artesanato local, e excursões guiadas pela paisagem árida e semidesértica do sul de Angola e pelo Parque Nacional do Iona.',
                'address' => 'Aldeia Cuanhama, Cunene',
                'stars' => 3, 'rating' => 4.8, 'reviews_count' => 54, 'min_price' => 75000, 'is_featured' => true,
                'latitude' => -17.1500, 'longitude' => 15.6500,
                'phone' => '+244 924 265 456', 'email' => 'cuanhama@culturallodge.ao',
                'thumbnail' => 'https://images.unsplash.com/photo-1565348271942-ab9e44c98aad?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1565348271942-ab9e44c98aad?w=1200&q=80',
                    'https://images.unsplash.com/photo-1523741543316-beb7fc7023d8?w=1200&q=80',
                    'https://images.unsplash.com/photo-1509316785289-025f5b846b35?w=1200&q=80',
                    'https://images.unsplash.com/photo-1541123437800-1bb1317badc2?w=1200&q=80',
                ],
                'amenities' => ['Bangalôs tradicionais', 'Refeições incluídas', 'Experiências culturais Cuanhama', 'Artesanato local', 'Tours Parque Iona', 'WiFi nas áreas comuns'],
            ],
            [
                'name' => 'Pousada Fronteira Sul',
                'property_type' => 'hospedaria',
                'description' => 'Pousada simples e funcional em Ondjiva, muito utilizada por comerciantes e viajantes que cruzam a fronteira Angola-Namíbia. Quartos limpos, refeições caseiras e ambiente familiar. Os proprietários são especialistas em logística transfronteiriça e podem ajudar com documentação e transporte.',
                'address' => 'Rua da Fronteira, Ondjiva, Cunene',
                'stars' => 2, 'rating' => 3.9, 'reviews_count' => 76, 'min_price' => 20000, 'is_featured' => false,
                'latitude' => -17.0700, 'longitude' => 15.7400,
                'phone' => '+244 912 265 789', 'email' => 'fronteira@pousadafronteira.ao',
                'thumbnail' => 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=1200&q=80',
                    'https://images.unsplash.com/photo-1616594039964-ae9021a400a0?w=1200&q=80',
                ],
                'amenities' => ['WiFi nas áreas comuns', 'Refeições caseiras', 'Apoio logístico fronteira', 'Estacionamento gratuito', 'Lavandaria'],
            ],
        ];
        foreach ($hotels as $data) { $this->createHotel($data, $admin, $loc); }
    }

    private function menongue(User $admin): void
    {
        $loc = Location::where('name', 'Menongue')->first();
        if (!$loc) return;

        $hotels = [
            [
                'name' => 'Hotel Cubango Menongue',
                'property_type' => 'hotel',
                'description' => 'O Hotel Cubango é o principal estabelecimento de Menongue, capital do Cuando Cubango — conhecida como as "Terras do Fim do Mundo". Ponto de partida para explorar o sistema fluvial Cubango-Okavango, um dos mais importantes de África. Quartos confortáveis, restaurante regional e serviços de organização de expedições.',
                'address' => 'Avenida Principal, Menongue, Cuando Cubango',
                'stars' => 3, 'rating' => 4.2, 'reviews_count' => 76, 'min_price' => 42000, 'is_featured' => false,
                'latitude' => -14.6576, 'longitude' => 17.6818,
                'phone' => '+244 266 220 200', 'email' => 'cubango@hotelcubango.ao',
                'thumbnail' => 'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?w=1200&q=80',
                    'https://images.unsplash.com/photo-1631049035182-249067d7618e?w=1200&q=80',
                    'https://images.unsplash.com/photo-1566665797739-1674de7a421a?w=1200&q=80',
                ],
                'amenities' => ['WiFi gratuito', 'Restaurante regional', 'Bar', 'Organização de expedições', 'Estacionamento', 'Transfer'],
            ],
            [
                'name' => 'Okavango Source Lodge',
                'property_type' => 'resort',
                'description' => 'Lodge exclusivo nas nascentes do Rio Okavango, antes de a água seguir o seu longo percurso até ao Delta do Okavango no Botswana. Uma experiência de natureza selvagem com elefantes, hipopótomos, crocodilos e uma avifauna extraordinária. Tendas de luxo, refeições gourmet e safaris fluviais únicos.',
                'address' => 'Nascentes do Rio Okavango, Cuando Cubango',
                'stars' => 5, 'rating' => 4.9, 'reviews_count' => 43, 'min_price' => 280000, 'is_featured' => true,
                'latitude' => -14.0000, 'longitude' => 18.5000,
                'phone' => '+244 924 266 890', 'email' => 'okavango@sourcelodge.ao',
                'thumbnail' => 'https://images.unsplash.com/photo-1523741543316-beb7fc7023d8?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1523741543316-beb7fc7023d8?w=1200&q=80',
                    'https://images.unsplash.com/photo-1565348271942-ab9e44c98aad?w=1200&q=80',
                    'https://images.unsplash.com/photo-1549180030-48bf079fb38a?w=1200&q=80',
                    'https://images.unsplash.com/photo-1509316785289-025f5b846b35?w=1200&q=80',
                    'https://images.unsplash.com/photo-1541123437800-1bb1317badc2?w=1200&q=80',
                ],
                'amenities' => ['Tendas de luxo com vista rio', 'Todas as refeições incluídas', 'Safaris fluviais', 'Game drives', 'Passeios de canoa', 'Observação de aves', 'Guias natureza certificados', 'Transfer privado de avioneta'],
            ],
            [
                'name' => 'Terras do Fim do Mundo Lodge',
                'property_type' => 'resort',
                'description' => 'Lodge de aventura no coração do Cuando Cubango, numa zona de savana virgem com fauna selvagem abundante. Nomeado pela expressão popular que descreve esta região remota, oferece uma experiência autêntica de bush africano com todos os confortos modernos. Safaris pedestres, 4x4 e fluviais incluídos.',
                'address' => 'Savana do Cuando, Cuando Cubango',
                'stars' => 4, 'rating' => 4.8, 'reviews_count' => 61, 'min_price' => 180000, 'is_featured' => true,
                'latitude' => -14.3000, 'longitude' => 18.0000,
                'phone' => '+244 924 267 123', 'email' => 'terras@fimdomundalodge.ao',
                'thumbnail' => 'https://images.unsplash.com/photo-1549180030-48bf079fb38a?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1549180030-48bf079fb38a?w=1200&q=80',
                    'https://images.unsplash.com/photo-1523741543316-beb7fc7023d8?w=1200&q=80',
                    'https://images.unsplash.com/photo-1565348271942-ab9e44c98aad?w=1200&q=80',
                    'https://images.unsplash.com/photo-1509316785289-025f5b846b35?w=1200&q=80',
                ],
                'amenities' => ['Bangalôs de bush', 'Refeições incluídas', 'Safaris pedestres', 'Game drives 4x4', 'Safari fluvial', 'Observação de estrelas', 'WiFi nas áreas comuns', 'Transfer avioneta'],
            ],
        ];
        foreach ($hotels as $data) { $this->createHotel($data, $admin, $loc); }
    }
}

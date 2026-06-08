<?php

namespace Database\Seeders\Angola;

use App\Models\Location;
use App\Models\User;

class HotelsHuamboSeeder
{
    use HotelSeederTrait;

    public function run(User $admin): void
    {
        $loc = Location::where('name', 'Huambo')->first();
        if (!$loc) return;

        $hotels = [
            [
                'name' => 'Ekuikui Hotel Huambo',
                'property_type' => 'hotel',
                'description' => 'O Ekuikui é o hotel de referência do Huambo, situado no centro desta histórica cidade do planalto central. Com clima ameno durante todo o ano, quartos espaçosos, restaurante com cozinha regional do planalto, piscina aquecida e ginásio. Excelente base para explorar a cidade e o seu património ferroviário.',
                'address' => 'Avenida da República, Huambo',
                'stars' => 4, 'rating' => 4.4, 'reviews_count' => 287, 'min_price' => 52000, 'is_featured' => true,
                'latitude' => -12.7667, 'longitude' => 15.7333,
                'phone' => '+244 241 220 500', 'email' => 'ekuikui@ekuikui.ao',
                'thumbnail' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=1200&q=80',
                    'https://images.unsplash.com/photo-1618773928121-c32242e63f39?w=1200&q=80',
                    'https://images.unsplash.com/photo-1578683010236-d716f9a3f461?w=1200&q=80',
                    'https://images.unsplash.com/photo-1566665797739-1674de7a421a?w=1200&q=80',
                ],
                'amenities' => ['WiFi gratuito', 'Piscina aquecida', 'Restaurante regional', 'Bar', 'Ginásio', 'Business center', 'Estacionamento', 'Serviço de quarto'],
            ],
            [
                'name' => 'Hotel Imperial Huambo',
                'property_type' => 'hotel',
                'description' => 'Hotel de charme no centro histórico do Huambo, com arquitectura colonial preservada e decoração que remete à época áurea da Antiga Nova Lisboa. Restaurante com pratos tradicionais do planalto, bar com ambiente acolhedor e quartos confortáveis com vista para a cidade. Referência histórica e cultural do Huambo.',
                'address' => 'Rua Dr. António Agostinho Neto, Huambo',
                'stars' => 3, 'rating' => 4.2, 'reviews_count' => 198, 'min_price' => 38000, 'is_featured' => false,
                'latitude' => -12.7700, 'longitude' => 15.7380,
                'phone' => '+244 241 221 000', 'email' => 'imperial@hotelimperial.ao',
                'thumbnail' => 'https://images.unsplash.com/photo-1564501049412-61c2a3083791?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1564501049412-61c2a3083791?w=1200&q=80',
                    'https://images.unsplash.com/photo-1590490360182-c33d57733427?w=1200&q=80',
                    'https://images.unsplash.com/photo-1583037189850-1921ae7c6c22?w=1200&q=80',
                ],
                'amenities' => ['WiFi gratuito', 'Restaurante tradicional', 'Bar', 'Estacionamento', 'Lavandaria'],
            ],
            [
                'name' => 'Quinta das Acácias Huambo',
                'property_type' => 'hospedaria',
                'description' => 'Situada numa quinta centenária no planalto do Huambo, rodeada por jardins de acácias floridos e campos de cultivo orgânico. Experiência rural autêntica com colheita de legumes, refeições da quinta e passeios a cavalo. Refúgio perfeito para quem busca paz e contacto com a natureza angolana.',
                'address' => 'Fazenda do Planalto, Km 15 Via Huambo-Bailundo',
                'stars' => 3, 'rating' => 4.7, 'reviews_count' => 62, 'min_price' => 22000, 'is_featured' => false,
                'latitude' => -12.7762, 'longitude' => 15.7389,
                'phone' => '+244 241 987 654', 'email' => 'quintaacacias@gmail.com',
                'thumbnail' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=1200&q=80',
                    'https://images.unsplash.com/photo-1600047509807-ba8f99d2cdde?w=1200&q=80',
                    'https://images.unsplash.com/photo-1541123437800-1bb1317badc2?w=1200&q=80',
                ],
                'amenities' => ['WiFi nas áreas comuns', 'Pequeno-almoço caseiro incluído', 'Jardim orgânico', 'Passeios a cavalo', 'Ciclismo', 'Piscina natural', 'Churrasqueira', 'Estacionamento amplo'],
            ],
            [
                'name' => 'Pousada do Planalto Huambo',
                'property_type' => 'hospedaria',
                'description' => 'Pousada familiar com vista panorâmica para o Planalto Central. Quartos simples e limpos, refeições caseiras com produtos do planalto, jardim com flores silvestres e ambiente tranquilo. Ideal para quem quer conhecer o Huambo sem grandes gastos. O proprietário organiza excursões ao Bailundo e arredores.',
                'address' => 'Avenida Norton de Matos, Huambo',
                'stars' => 2, 'rating' => 4.3, 'reviews_count' => 87, 'min_price' => 18000, 'is_featured' => false,
                'latitude' => -12.7800, 'longitude' => 15.7300,
                'phone' => '+244 912 456 789', 'email' => 'planalto@pousadaplanalto.ao',
                'thumbnail' => 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=1200&q=80',
                    'https://images.unsplash.com/photo-1568084680786-a84f91d1153c?w=1200&q=80',
                ],
                'amenities' => ['WiFi gratuito', 'Pequeno-almoço incluído', 'Jardim', 'Excursões organizadas', 'Estacionamento gratuito'],
            ],
            [
                'name' => 'Hotel Central Huambo',
                'property_type' => 'hotel',
                'description' => 'Hotel bem localizado no centro do Huambo, junto à estação ferroviária histórica. Quartos modernos com ar condicionado, restaurante com menu variado, bar e serviços de apoio a viajantes de negócios. A estação do Caminho de Ferro de Benguela a poucos metros torna-o conveniente para quem utiliza o comboio.',
                'address' => 'Largo da Estação, Huambo',
                'stars' => 3, 'rating' => 4.0, 'reviews_count' => 143, 'min_price' => 32000, 'is_featured' => false,
                'latitude' => -12.7650, 'longitude' => 15.7350,
                'phone' => '+244 241 223 000', 'email' => 'central@hotelcentral.ao',
                'thumbnail' => 'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?w=1200&q=80',
                    'https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?w=1200&q=80',
                    'https://images.unsplash.com/photo-1574362848149-11496d93a7c7?w=1200&q=80',
                ],
                'amenities' => ['WiFi gratuito', 'Restaurante', 'Bar', 'Estacionamento', 'Lavandaria', 'Serviço de quarto'],
            ],
        ];

        foreach ($hotels as $data) {
            $this->createHotel($data, $admin, $loc);
        }
    }
}

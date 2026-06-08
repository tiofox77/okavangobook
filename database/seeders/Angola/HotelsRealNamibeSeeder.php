<?php

namespace Database\Seeders\Angola;

use App\Models\Location;
use App\Models\User;

/**
 * Hotéis REAIS do Namibe (Moçâmedes)
 * Fonte: hoteisangola.com — sem duplicar os já criados no HotelsNamibeSeeder
 */
class HotelsRealNamibeSeeder
{
    use HotelSeederTrait;

    public function run(User $admin): void
    {
        $loc = Location::where('name', 'Namibe')->first();
        if (!$loc) return;

        $hotels = [
            [
                'name' => 'Hotel Chik Chik Namibe',
                'property_type' => 'hotel',
                'description' => 'O Hotel Chik Chik Namibe é parte da renomada rede hoteleira angolana Chik Chik, conhecida pela qualidade consistente em todo o país. Situado no centro de Moçâmedes/Namibe, oferece quartos modernos com ar condicionado, restaurante com cozinha angolana e frutos do mar, bar e excelente serviço ao cliente. Ponto de partida ideal para excursões ao deserto e à costa.',
                'address' => 'Avenida da República, Moçâmedes, Namibe',
                'stars' => 4, 'rating' => 4.4, 'reviews_count' => 198, 'min_price' => 65000, 'is_featured' => true,
                'latitude' => -15.1950, 'longitude' => 12.1540,
                'phone' => '+244 264 261 000', 'email' => 'namibe@chikchikhotels.ao',
                'thumbnail' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=1200&q=80',
                    'https://images.unsplash.com/photo-1618773928121-c32242e63f39?w=1200&q=80',
                    'https://images.unsplash.com/photo-1578683010236-d716f9a3f461?w=1200&q=80',
                    'https://images.unsplash.com/photo-1566665797739-1674de7a421a?w=1200&q=80',
                ],
                'amenities' => ['WiFi gratuito', 'Restaurante frutos do mar', 'Bar', 'Ginásio', 'Business center', 'Tours ao deserto', 'Transfer aeroporto', 'Estacionamento'],
            ],
            [
                'name' => 'Viva Executive Hotel Namibe',
                'property_type' => 'hotel',
                'description' => 'Hotel executivo moderno no coração de Moçâmedes, muito frequentado por profissionais ligados à indústria da pesca e ao porto. Quartos executivos espaçosos, restaurante com ementa internacional, sala de conferências e ginásio. A sua localização central e serviço eficiente tornam-no a escolha de eleição para viajantes de negócios.',
                'address' => 'Rua do Porto, Moçâmedes, Namibe',
                'stars' => 4, 'rating' => 4.3, 'reviews_count' => 145, 'min_price' => 70000, 'is_featured' => false,
                'latitude' => -15.1970, 'longitude' => 12.1560,
                'phone' => '+244 264 262 500', 'email' => 'namibe@vivaexecutive.ao',
                'thumbnail' => 'https://images.unsplash.com/photo-1596386461350-326ccb383e9f?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1596386461350-326ccb383e9f?w=1200&q=80',
                    'https://images.unsplash.com/photo-1564501049412-61c2a3083791?w=1200&q=80',
                    'https://images.unsplash.com/photo-1611892440504-42a792e24d32?w=1200&q=80',
                    'https://images.unsplash.com/photo-1583037189850-1921ae7c6c22?w=1200&q=80',
                ],
                'amenities' => ['WiFi gratuito', 'Restaurante internacional', 'Bar', 'Sala de conferências', 'Ginásio', 'Business center', 'Estacionamento', 'Transfer'],
            ],
            [
                'name' => 'Baía das Pipas Lodge Namibe',
                'property_type' => 'resort',
                'description' => 'Lodge exclusivo na Baía das Pipas, uma das praias mais selvagens e belas do Namibe, famosa pelo avistamento sazonal de baleias e golfinhos. Bangalôs à beira-mar, restaurante com peixe do dia pescado localmente, e excursões de barco para observação de cetáceos. Um dos destinos de ecoturismo mais exclusivos de Angola.',
                'address' => 'Baía das Pipas, Namibe',
                'stars' => 4, 'rating' => 4.8, 'reviews_count' => 87, 'min_price' => 140000, 'is_featured' => true,
                'latitude' => -15.0500, 'longitude' => 12.0800,
                'phone' => '+244 924 264 890', 'email' => 'pipas@baiapipas.ao',
                'thumbnail' => 'https://images.unsplash.com/photo-1499793983690-e29da59ef1c2?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1499793983690-e29da59ef1c2?w=1200&q=80',
                    'https://images.unsplash.com/photo-1540541338287-41700207dee6?w=1200&q=80',
                    'https://images.unsplash.com/photo-1602391833977-358a52198938?w=1200&q=80',
                    'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=1200&q=80',
                    'https://images.unsplash.com/photo-1596436889106-be35e843f974?w=1200&q=80',
                ],
                'amenities' => ['Bangalôs à beira-mar', 'Praia privativa', 'Restaurante peixe fresco', 'Excursões baleias & golfinhos', 'Mergulho', 'Kayak', 'WiFi nas áreas comuns', 'Transfer'],
            ],
            [
                'name' => 'Mariquita Beach Resort Namibe',
                'property_type' => 'resort',
                'description' => 'Resort encantador na Praia da Mariquita, uma praia tranquila e pouco conhecida a 30km de Moçâmedes. Bangalôs de madeira e pedra em frente ao mar, piscina de água salgada, restaurante com lagosta e camarão frescos, e actividades de snorkeling e pesca. Um refúgio de paz onde o deserto se encontra com o oceano.',
                'address' => 'Praia da Mariquita, Namibe',
                'stars' => 4, 'rating' => 4.7, 'reviews_count' => 112, 'min_price' => 95000, 'is_featured' => true,
                'latitude' => -15.0200, 'longitude' => 12.0600,
                'phone' => '+244 924 265 123', 'email' => 'mariquita@mariquita.ao',
                'thumbnail' => 'https://images.unsplash.com/photo-1602391833977-358a52198938?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1602391833977-358a52198938?w=1200&q=80',
                    'https://images.unsplash.com/photo-1591088398332-8a7791972843?w=1200&q=80',
                    'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?w=1200&q=80',
                    'https://images.unsplash.com/photo-1541123437800-1bb1317badc2?w=1200&q=80',
                ],
                'amenities' => ['Bangalôs frente ao mar', 'Piscina salgada', 'Restaurante lagosta & camarão', 'Snorkeling', 'Pesca desportiva', 'WiFi nas áreas comuns', 'Transfer Moçâmedes'],
            ],
            [
                'name' => 'Praia do Soba Eco Lodge Namibe',
                'property_type' => 'resort',
                'description' => 'Eco lodge na isolada Praia do Soba, uma praia virgem onde as dunas do deserto descem directamente para o Atlântico. Tendas de luxo com camas reais e casa de banho privativa, refeições sob as estrelas, passeios pelas dunas de 4x4 e a magia de adormecer ao som das ondas com o deserto à volta. Uma experiência absolutamente inesquecível.',
                'address' => 'Praia do Soba, Namibe',
                'stars' => 4, 'rating' => 4.9, 'reviews_count' => 56, 'min_price' => 160000, 'is_featured' => true,
                'latitude' => -15.3500, 'longitude' => 12.0200,
                'phone' => '+244 924 266 456', 'email' => 'soba@praiasobaecolodge.ao',
                'thumbnail' => 'https://images.unsplash.com/photo-1509316785289-025f5b846b35?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1509316785289-025f5b846b35?w=1200&q=80',
                    'https://images.unsplash.com/photo-1509099381441-ea3c0cf98b94?w=1200&q=80',
                    'https://images.unsplash.com/photo-1523741543316-beb7fc7023d8?w=1200&q=80',
                    'https://images.unsplash.com/photo-1519690889869-e705e59f72e1?w=1200&q=80',
                ],
                'amenities' => ['Tendas de luxo', 'Refeições incluídas', 'Passeios 4x4 dunas', 'Observação de estrelas', 'Surf', 'Praia privativa', 'WiFi nas áreas comuns'],
            ],
        ];

        foreach ($hotels as $data) {
            $this->createHotel($data, $admin, $loc);
        }
    }
}

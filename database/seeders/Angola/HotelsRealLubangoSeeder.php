<?php

namespace Database\Seeders\Angola;

use App\Models\Location;
use App\Models\User;

/**
 * Hotéis REAIS de Lubango (Huíla)
 * Fonte: hoteisangola.com — sem duplicar os já criados no HotelsLubangoSeeder
 */
class HotelsRealLubangoSeeder
{
    use HotelSeederTrait;

    public function run(User $admin): void
    {
        $loc = Location::where('name', 'Lubango')->first();
        if (!$loc) return;

        $hotels = [
            [
                'name' => 'Pululukwa Resort Lubango',
                'property_type' => 'resort',
                'description' => 'O Pululukwa Resort é o mais icónico resort de Lubango, situado numa encosta verdejante com vista deslumbrante sobre a cidade e o vale. Bungalows independentes rodeados de jardins exuberantes, piscina exterior aquecida, restaurante premiado com cozinha angolana de autor e spa com tratamentos inspirados na flora da Huíla. Uma experiência única a 1.800m de altitude.',
                'address' => 'Estrada do Cristo Rei, Lubango, Huíla',
                'stars' => 5, 'rating' => 4.8, 'reviews_count' => 389, 'min_price' => 110000, 'is_featured' => true,
                'latitude' => -14.9100, 'longitude' => 13.4900,
                'phone' => '+244 261 230 500', 'email' => 'reservas@pululukwa.ao', 'website' => 'https://www.pululukwa.ao',
                'thumbnail' => 'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?w=1200&q=80',
                    'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1200&q=80',
                    'https://images.unsplash.com/photo-1519904981063-b0cf448d479e?w=1200&q=80',
                    'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=1200&q=80',
                    'https://images.unsplash.com/photo-1519690889869-e705e59f72e1?w=1200&q=80',
                ],
                'amenities' => ['WiFi gratuito', 'Bungalows privados', 'Piscina aquecida', 'Spa & sauna', 'Restaurante premiado', 'Bar', 'Trilhas guiadas', 'Transfer Serra da Leba', 'Estacionamento', 'Vista panorâmica'],
            ],
            [
                'name' => 'Hotel Chik Chik Lubango',
                'property_type' => 'hotel',
                'description' => 'O Hotel Chik Chik Lubango pertence à reconhecida rede hoteleira angolana Chik Chik, sinónimo de qualidade e conforto. Situado no centro da cidade, oferece quartos elegantes, restaurante com ementa diversificada, bar animado e uma equipa de serviço altamente treinada. Uma das opções mais populares entre viajantes de negócios e turismo.',
                'address' => 'Avenida Principal, Centro, Lubango',
                'stars' => 4, 'rating' => 4.5, 'reviews_count' => 312, 'min_price' => 75000, 'is_featured' => true,
                'latitude' => -14.9170, 'longitude' => 13.5010,
                'phone' => '+244 261 222 500', 'email' => 'lubango@chikchikhotels.ao',
                'thumbnail' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=1200&q=80',
                    'https://images.unsplash.com/photo-1618773928121-c32242e63f39?w=1200&q=80',
                    'https://images.unsplash.com/photo-1578683010236-d716f9a3f461?w=1200&q=80',
                    'https://images.unsplash.com/photo-1566665797739-1674de7a421a?w=1200&q=80',
                ],
                'amenities' => ['WiFi gratuito', 'Restaurante', 'Bar', 'Ginásio', 'Business center', 'Estacionamento', 'Serviço de quarto 24h', 'Transfer'],
            ],
            [
                'name' => 'Hotel Serra da Chela Lubango',
                'property_type' => 'hotel',
                'description' => 'Nomeado em homenagem à magnética Serra da Chela — com o seu Tundavala, um dos mais espectaculares pontos de vista de Angola — este hotel é o ponto de partida perfeito para explorar as maravilhas naturais da Huíla. Quartos com vista para as serras, restaurante regional, bar e serviços de guia turístico especializados.',
                'address' => 'Bairro Shangai, Lubango, Huíla',
                'stars' => 4, 'rating' => 4.6, 'reviews_count' => 267, 'min_price' => 68000, 'is_featured' => true,
                'latitude' => -14.9140, 'longitude' => 13.4960,
                'phone' => '+244 261 228 000', 'email' => 'serradachela@hotel.ao',
                'thumbnail' => 'https://images.unsplash.com/photo-1551632811-561732d1e306?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1551632811-561732d1e306?w=1200&q=80',
                    'https://images.unsplash.com/photo-1519904981063-b0cf448d479e?w=1200&q=80',
                    'https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=1200&q=80',
                    'https://images.unsplash.com/photo-1590490360182-c33d57733427?w=1200&q=80',
                ],
                'amenities' => ['WiFi gratuito', 'Restaurante regional', 'Bar', 'Tours Tundavala', 'Tours Serra da Chela', 'Estacionamento', 'Serviço de quarto'],
            ],
            [
                'name' => 'Kimbo do Soba Lubango',
                'property_type' => 'resort',
                'description' => 'O Kimbo do Soba é um complexo turístico único que recria a arquitectura tradicional de um kimbo angolano (aldeia tradicional) com bungalows de palhota modernizados, rodeados por jardins e na margem de um pequeno lago. Restaurante com cozinha tradicional angolana, fogueira nocturna, música ao vivo e experiências culturais. Um destino imperdível para quem quer mergulhar na cultura da Huíla.',
                'address' => 'Estrada do Humpata, Lubango, Huíla',
                'stars' => 4, 'rating' => 4.7, 'reviews_count' => 198, 'min_price' => 60000, 'is_featured' => true,
                'latitude' => -14.9300, 'longitude' => 13.4800,
                'phone' => '+244 261 235 000', 'email' => 'kimbodobola@kimbo.ao',
                'thumbnail' => 'https://images.unsplash.com/photo-1523741543316-beb7fc7023d8?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1523741543316-beb7fc7023d8?w=1200&q=80',
                    'https://images.unsplash.com/photo-1565348271942-ab9e44c98aad?w=1200&q=80',
                    'https://images.unsplash.com/photo-1541123437800-1bb1317badc2?w=1200&q=80',
                    'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=1200&q=80',
                ],
                'amenities' => ['WiFi gratuito', 'Bungalows tradicionais', 'Restaurante angolano', 'Fogueira nocturna', 'Música ao vivo', 'Lago artificial', 'Tours culturais', 'Estacionamento'],
            ],
            [
                'name' => 'Hotel VIP Huíla Lubango',
                'property_type' => 'hotel',
                'description' => 'O Hotel VIP Huíla faz parte da conceituada rede VIP Hotels, que garante padrões elevados de qualidade em toda Angola. No coração de Lubango, oferece quartos e suítes espaçosas, restaurante internacional, bar, spa e centro de negócios. Uma escolha segura para executivos e viajantes exigentes.',
                'address' => 'Avenida da Namíbia, Lubango, Huíla',
                'stars' => 4, 'rating' => 4.4, 'reviews_count' => 234, 'min_price' => 80000, 'is_featured' => false,
                'latitude' => -14.9160, 'longitude' => 13.5020,
                'phone' => '+244 261 240 000', 'email' => 'huila@viphotels.ao',
                'thumbnail' => 'https://images.unsplash.com/photo-1596386461350-326ccb383e9f?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1596386461350-326ccb383e9f?w=1200&q=80',
                    'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=1200&q=80',
                    'https://images.unsplash.com/photo-1574362848149-11496d93a7c7?w=1200&q=80',
                    'https://images.unsplash.com/photo-1583037189850-1921ae7c6c22?w=1200&q=80',
                ],
                'amenities' => ['WiFi gratuito', 'Restaurante internacional', 'Bar', 'Spa', 'Business center', 'Ginásio', 'Estacionamento', 'Serviço de quarto 24h'],
            ],
            [
                'name' => 'Mask Hotel Lubango',
                'property_type' => 'hotel',
                'description' => 'Hotel de design contemporâneo em Lubango, com decoração inspirada nas máscaras tradicionais do sul de Angola — um rico heritage cultural dos povos Nyaneka-Humbi. Quartos modernos e bem equipados, restaurante com fusão de cozinha angolana e internacional, e um bar com ambiente único decorado com arte local.',
                'address' => 'Rua Dr. António Agostinho Neto, Lubango',
                'stars' => 3, 'rating' => 4.3, 'reviews_count' => 145, 'min_price' => 48000, 'is_featured' => false,
                'latitude' => -14.9190, 'longitude' => 13.5030,
                'phone' => '+244 261 226 000', 'email' => 'mask@maskhotel.ao',
                'thumbnail' => 'https://images.unsplash.com/photo-1564501049412-61c2a3083791?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1564501049412-61c2a3083791?w=1200&q=80',
                    'https://images.unsplash.com/photo-1631049035182-249067d7618e?w=1200&q=80',
                    'https://images.unsplash.com/photo-1568084680786-a84f91d1153c?w=1200&q=80',
                ],
                'amenities' => ['WiFi gratuito', 'Restaurante', 'Bar arte local', 'Estacionamento', 'Serviço de quarto'],
            ],
            [
                'name' => 'Hospedaria S&I Freitas Lubango',
                'property_type' => 'hospedaria',
                'description' => 'Hospedaria familiar com décadas de história em Lubango, muito querida pelos luanguenses e pelos viajantes que a descobrem. Ambiente simples e autêntico, quartos limpos e confortáveis, refeições caseiras preparadas com produtos locais do planalto. Uma das melhores relações qualidade-preço da cidade.',
                'address' => 'Bairro Popular, Lubango, Huíla',
                'stars' => 2, 'rating' => 4.2, 'reviews_count' => 98, 'min_price' => 20000, 'is_featured' => false,
                'latitude' => -14.9210, 'longitude' => 13.5040,
                'phone' => '+244 912 261 456', 'email' => 'freitas@hospedaria.ao',
                'thumbnail' => 'https://images.unsplash.com/photo-1600047509807-ba8f99d2cdde?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1600047509807-ba8f99d2cdde?w=1200&q=80',
                    'https://images.unsplash.com/photo-1616594039964-ae9021a400a0?w=1200&q=80',
                ],
                'amenities' => ['WiFi gratuito', 'Refeições caseiras', 'Estacionamento gratuito', 'Ambiente familiar'],
            ],
            [
                'name' => 'Vila Mara Flats Lubango',
                'property_type' => 'hotel',
                'description' => 'Complexo de apartamentos turísticos totalmente equipados no bairro residencial Mara, em Lubango. Apartamentos de 1 a 3 quartos com cozinha, sala e varanda. Piscina, jardim e área de lazer. Ideal para estadias prolongadas de famílias e profissionais. A apenas 10 minutos do centro e dos pontos turísticos principais.',
                'address' => 'Bairro Mara, Lubango, Huíla',
                'stars' => 3, 'rating' => 4.4, 'reviews_count' => 123, 'min_price' => 52000, 'is_featured' => false,
                'latitude' => -14.9220, 'longitude' => 13.5060,
                'phone' => '+244 261 242 000', 'email' => 'vilamara@vilamaraflats.ao',
                'thumbnail' => 'https://images.unsplash.com/photo-1568084680786-a84f91d1153c?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1568084680786-a84f91d1153c?w=1200&q=80',
                    'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=1200&q=80',
                    'https://images.unsplash.com/photo-1540541338287-41700207dee6?w=1200&q=80',
                ],
                'amenities' => ['WiFi gratuito', 'Apartamentos equipados', 'Piscina', 'Jardim', 'Estacionamento', 'Lavandaria'],
            ],
        ];

        foreach ($hotels as $data) {
            $this->createHotel($data, $admin, $loc);
        }
    }
}
